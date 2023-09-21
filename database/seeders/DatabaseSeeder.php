<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            'Masyarakat Umum',
            'Processor',
            'Owner Coffee Shop',
            'Inti Team Beantrack'
        ];
        foreach($roles as $role){
            Role::create([
                'name' => $role,
            ]);
        }

        User::create([
            'role_id' => 4,
            'name' => 'Bagas',
            'email' => 'bagasrnfull@gmail.com',
            'password' => Hash::make('Password234#')
        ]);
    }
}
