<?php

namespace App\Repositories;
use App\Enums\TicketType;
use App\Interfaces\AttendeeRepositoryInterfaces;
use App\Models\Attendee;
use Yajra\DataTables\Facades\DataTables;

class AttendeeRepository implements AttendeeRepositoryInterfaces{

    public function create(array $data): Attendee
    {
        return Attendee::create($data);
    }


    public function getById(int $id)
    {
        return Attendee::find($id);
    }

    public function getDatatable($eventId)
    {
        $query = Attendee::with(['user'=>function($q){
            $q->select(['name','email','id']);
        },'ticket'=>function($q){
            $q->select(['type','id']);
        }])->select(['user_id','ticket_id','quantity','id']);
        $query->where('event_id', $eventId);

        return DataTables::eloquent($query)
        ->addColumn('attendee_name', function ($row) {
            return $row->user->name;
        })
        ->addColumn('attendee_email', function ($row) {
            return $row->user->email;
        })
        ->addColumn('ticket_type', function ($row) {
            return $row->ticket->type;//TicketType::from()->label();
        })
        ->make(true);
    }
}
