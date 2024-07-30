<?php

namespace App\Interfaces;

interface AttendeeRepositoryInterfaces{
    public function create(array $details);

    public function getById(int $id);
    public function getDatatable($eventId);
}