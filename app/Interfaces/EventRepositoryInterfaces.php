<?php

namespace App\Interfaces;

interface EventRepositoryInterfaces{
    public function create(array $details);
    public function update($id, array $newDetails);
    public function getDatatable();
    public function getById($id);
    public function delete($id);
}