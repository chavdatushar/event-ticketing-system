<?php

namespace App\Repositories;
use App\Interfaces\TicketRepositoryInterfaces;
use App\Models\Ticket;

class TicketRepository implements TicketRepositoryInterfaces{
    public function create(array $details){
        return Ticket::create($details);
    }
    
    public function update($id, array $newDetails){
        return Ticket::where('id', $id)->update($newDetails);
    }

    public function getAll(){
        return Ticket::all();
    }
    public function getById($id){
        return Ticket::find($id);
    }
    public function delete($id){
        Ticket::destroy($id);
    }
}
