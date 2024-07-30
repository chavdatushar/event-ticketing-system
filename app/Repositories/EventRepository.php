<?php

namespace App\Repositories;
use App\Interfaces\EventRepositoryInterfaces;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class EventRepository implements EventRepositoryInterfaces{
    public function create(array $details){
        $details['user_id'] =Auth::id();
        return Event::create($details);
    }
    
    public function update($id, array $newDetails){
        return Event::where('id', $id)->update($newDetails);
    }

    public function getAll(){
        return Event::all();
    }
    public function getDatatable()
    {
        $query = Event::select(['title','date','location','id']);
        
        if(Auth::user()->roles()->first()->name == 'organizer'){
            $query->where('user_id', Auth::id());
        }else{
            $query->where('date', '>', now());
            $query->where('is_cancelled', 0);
            $query->orderBy('date', 'asc');
        }

        return DataTables::eloquent($query)
        ->addColumn('actions', function ($row) {
            return view('event.partials.actions', compact('row'))->render();
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
    public function getById($id){
        return Event::with('tickets','comments.user')->find($id);
    }
    public function delete($id){
        Event::destroy($id);
    }
}
