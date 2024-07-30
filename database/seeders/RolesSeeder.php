<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $rolesArr = ["attendee","organizer"];
        if(!empty($rolesArr)){
            foreach ($rolesArr as $key => $value) {
                $checkRoleExist = Role::whereName($value)->first();
                if(!$checkRoleExist){
                    Role::create([
                        "name" => $value,
                    ]);
                }
            }
        }
    }
}
