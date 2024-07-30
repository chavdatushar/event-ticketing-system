<?php

namespace App\Interfaces;

interface UserRepositoryInterfaces{
    public function create(array $details);
    public function update($id, array $newDetails);
}