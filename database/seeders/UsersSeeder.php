<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        User::create([
            'name' => 'Admin',
            'email' => 'sessigmasoft.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }
}
