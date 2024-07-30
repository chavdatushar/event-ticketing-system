<?php

namespace App\Repositories;
use App\Interfaces\UserRepositoryInterfaces;
use App\Models\User;

class UserRepository implements UserRepositoryInterfaces{
    public function create(array $details){
        return User::create($details);
    }
    
    public function update($id, array $newDetails){
        return User::where('id', $id)->update($newDetails);
    }
}
