<?php

namespace App\Http\Controllers;

use App\Enums\TicketType;
use App\Helpers\UrlHelper;
use App\Http\Requests\EventRequest;
use App\Models\Ticket;
use App\Repositories\AttendeeRepository;
use App\Repositories\EventRepository;
use App\Repositories\TicketRepository;
use Auth;
use Illuminate\Http\Request;
use App\Exports\EventsExport;
use Maatwebsite\Excel\Facades\Excel;


class EventController extends Controller
{
    private $eventRepository;
    private $ticketRepository;
    private $attendeeRepository;

    public function __construct(EventRepository $eventRepository,TicketRepository $ticketRepository,AttendeeRepository $attendeeRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->ticketRepository = $ticketRepository;
        $this->attendeeRepository = $attendeeRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->eventRepository->getDatatable();
        }
        return view('event.index');
    }

    public function create(Request $request)
    {
        $ticketTypes = TicketType::cases();
        return view('event.create',compact('ticketTypes'));
    }
    public function store(EventRequest $request)
    {
        try {
            
            $event = $this->eventRepository->create($request->validated());
            foreach ($request->tickets as $type => $value) {
                $this->ticketRepository->create([
                    'event_id' => $event->id,
                    'type' => $type,
                    'availability' => $value,
                    'price' => $request->ticket_price[$type],
                ]);
            }
            return response()->json([
                'data' => ['event' => $event],
                'success' => true,
                'message' => 'Event Created Successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function attendees(Request $request,$id){
        $eventId = UrlHelper::decrypt($id);
        $event= $this->eventRepository->getById($eventId);
        $this->authorize('view', $event);
        if ($request->ajax()) {
            return $this->attendeeRepository->getDatatable($eventId);
        }

        $viewContent['title'] = "Edit Events";
        $viewContent['event'] =$event;
        // $viewContent['attendees'] = $event->attendee->load('user');
        return view("event.attendees")->with($viewContent);
    }
    public function details(Request $request,$id)
    {
        $eventId = UrlHelper::decrypt($id);
        $event= $this->eventRepository->getById($eventId);
        $viewContent['event'] =$event;
        $viewContent['title'] = "Edit Events";
        $viewFile = "event.edit";
        
        if(Auth::user()->roles()->first()->name == 'attendee'){
            $viewFile = "event.detail";
            $viewContent['title'] = "Event Details";
            $viewContent['tickets'] = Ticket::where('event_id',$eventId)->get();
        }
        return view($viewFile)->with($viewContent);
    }

    public function update(EventRequest $request,$id)
    {
        try {
            $eventId = UrlHelper::decrypt($id);
            $event = $this->eventRepository->getById($eventId);

            $this->authorize('update', $event);

            $dataToUpdate = $request->only(['title', 'description', 'date', 'location', 'user_id','is_cancelled']);
            $dataToUpdate['is_cancelled'] = $request->get('is_cancelled',0);

            $this->eventRepository->update($eventId,$dataToUpdate);
            foreach ($request->tickets as $type => $value) {
                $this->ticketRepository->update($request->ticket_id[$type],[
                    'event_id' => $event->id,
                    'type' => $type,
                    'availability' => $value,
                    'price' => $request->ticket_price[$type],
                ]);
            }
            return response()->json(['success' => true,'message' => 'Event updated successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function destroy($id)
    {
        
        try {
            $eventId = UrlHelper::decrypt($id);
            $event = $this->eventRepository->getById($eventId);
            $this->authorize('delete', $event);
            $this->eventRepository->delete($eventId);
            return response()->json(['success' => true,'message' => 'URL deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
       
    }

    public function export()
    {
        return Excel::download(new EventsExport, 'events.xlsx');
    }
}
