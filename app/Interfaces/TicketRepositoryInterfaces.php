<?php

namespace App\Interfaces;

interface TicketRepositoryInterfaces{
    public function create(array $details);
    public function update($id, array $newDetails);
    // public function getDatatable();
    public function getById($id);
    public function delete($id);
}
