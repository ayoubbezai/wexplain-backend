<?php

namespace Database\Seeders;
use App\Models\Role;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where("name", "super_admin")->first();

        if($role){
            User::factory()->create([
                'id' => (string) Str::uuid(),
                'first_name' => "wexplain",
                'last_name' => "admin",
                "email" => "wexplain@gmail.com",
                "password"=>"12345678",
                "role_id" => $role->id
            ]);
        }
    }
}
