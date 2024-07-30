<?php

namespace App\Repositories;
use App\Interfaces\RoleRepositoryInterfaces;
use App\Models\Role;

class RoleRepository implements RoleRepositoryInterfaces{

    public function getDDArray($value, $key){
        return Role::pluck($value,$key);
    }
}
