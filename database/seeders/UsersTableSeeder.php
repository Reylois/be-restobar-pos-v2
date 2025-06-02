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
            'fname' => 'Reylois',
            'lname' => 'Baer',
            'mname' => 'Guinita',
            'username' => 'reylois',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create staff user
        User::create([
            'fname' => 'Cashier',
            'lname' => 'User',
            'mname' => 'cash',
            'username' => 'cashier',
            'password' => Hash::make('password'),
            'role' => 'cashier',
        ]);
    }
}
