<?php
namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\User;
use App\Repositories\AttendeeRepository;
use App\Repositories\EventRepository;
use App\Repositories\TicketRepository;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\TicketPurchased;

class PaymentController extends Controller
{

    
    protected $ticketRepository;
    protected $attendeeRepository;
    protected $eventRepository;

    public function __construct(TicketRepository $ticketRepository, AttendeeRepository $attendeeRepository,EventRepository $eventRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->attendeeRepository = $attendeeRepository;
        $this->eventRepository = $eventRepository;
    }
    
    public function createPaymentRequest(PaymentRequest $request)
    {
        try{

            $ticket = $this->ticketRepository->getById($request->ticket_id);
            $event = $this->eventRepository->getById($ticket->event_id);
            if ($event->is_cancelled == 1) {
                return response()->json(['message' => 'This event has been cancelled', 'success' => false, 'data' => null], 400);
            }
            if ($ticket->availability < $request->quantity) {
                return response()->json(['message' => 'Not enough tickets available', 'success' => false, 'data' => null], 400);
            }

            $amount = $ticket->price * $request->quantity * 100; // Stripe uses cents

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'inr',
                'metadata' => [
                    'ticket_id' => $ticket->id,
                    'event_id' => $ticket->event_id,
                    'quantity' => $request->quantity,
                    'user_id' => Auth::id(),
                ],
            ]);

            return response()->json(['message' => 'Secret has been generated', 'success' => true, 'data' => ['clientSecret' => $paymentIntent->client_secret]]);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['message' => 'Invalid payload', 'success' => false, 'data' => null], 400);
        }
    }

    public function handleWebhook(Request $request)
    {
        
        // $payload = @file_get_contents('php://input');
        
        // $sig_header = $request->header('Stripe-Signature');
        // $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Event::constructFrom(
                $request->all()
            );
            // $event = Webhook::constructEvent(
            //     $payload, $sig_header, $endpoint_secret
            // );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['message' => 'Invalid payload', 'success' => false, 'data' => null], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['message' => 'Invalid signature', 'success' => false, 'data' => null], 400);
        }
        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handleSuccessfulPayment($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handleFailedPayment($paymentIntent);
                break;

            default:
                return response()->json(['message' => 'Unhandled event type', 'success' => false, 'data' => null], 400);
        }

        return response()->json(['message' => 'Event handled', 'success' => true, 'data' => null], 200);
    }


    protected function handleSuccessfulPayment($paymentIntent)
    {
        $metadata = $paymentIntent->metadata;

        DB::transaction(function () use ($metadata) {
            $purchase = $this->attendeeRepository->create([
                'user_id' => $metadata->user_id,
                'event_id' => $metadata->event_id,
                'ticket_id' => $metadata->ticket_id,
                'quantity' => $metadata->quantity,
            ]);


            $ticket = $this->ticketRepository->getById($metadata->ticket_id);
            $this->ticketRepository->update($metadata->ticket_id,[
                "availability"=>abs($ticket->availability-$metadata->quantity)
            ]);

            $user = User::find($metadata->user_id);
            $user->notify(new TicketPurchased($purchase));
        });
    }

    protected function handleFailedPayment($paymentIntent)
    {
        Log::error('Payment failed for payment intent: ' . $paymentIntent->id);
        // Log or handle the failed payment as needed
    }
}
