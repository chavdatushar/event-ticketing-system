<?php

namespace App\Notifications;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketPurchased extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(Attendee $attendee)
    {
        $this->attendee = $attendee;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $ticket = $this->attendee->ticket;
        $event = $ticket->event;
        $user = $this->attendee->user;

        return (new MailMessage)
            ->subject('Ticket Purchase Confirmation')
            ->greeting('Hello ' . $user->name . '!')
            ->line('Thank you for purchasing tickets for the event: ' . $event->name)
            ->line('Ticket Type: ' . $ticket->type->label())
            ->line('Quantity: ' . $this->attendee->quantity)
            ->line('We look forward to seeing you at the event!')
            ->line('Event Date: ' . $event->date)
            ->line('Event Description: ' . $event->description)
            ->line('Thank you for your purchase!');
    }
}
