<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'fname' => 'Admin',
            'lname' => 'User',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create staff user
        User::create([
            'fname' => 'Cashier',
            'lname' => 'User',
            'username' => 'cashier',
            'password' => Hash::make('password'),
            'role' => 'cashier',
        ]);
    }
}
