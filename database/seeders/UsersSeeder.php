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
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Irfan',
            'email' => 'techby78@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'salesman'
        ]);
User::create([
            'name' => 'Irfan2',
            'email' => 'techby79@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'salesman'
        ]);


    }
}
