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
            'name' => 'Hunzla Malik',
            // 'email' => 'digitalsales@sigmagroup.com.pk',
            'email' => 'admin@gmail.com',
            // 'password' => Hash::make('Hunzla!Admin#2026'),
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Hurrera Malik',
            'email' => 'hurrera@sigmagroup.com.pk2',
            'password' => Hash::make('Hurrera@Admin$2026'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Umar Arshad',
            'email' => 'sales@sigmagroup.com.pk2',
            'password' => Hash::make('Umar%Admin^2026'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Awais',
            'email' => 'awais@sigmagroup.com.pk2',
            // 'password' => Hash::make('Awais!Sales#2025'),
            'password' => Hash::make('password'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Sajjad',
            'email' => 'sajjad@sigmagroup.com.pk2',
            // 'password' => Hash::make('Sajjad@Sales$2025'),
            'password' => Hash::make('password'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Irfan Ashraf',
            'email' => 'irfan@sigmagroup.com.pk2',
            // 'password' => Hash::make('Irfan%Sales^2025'),
            'password' => Hash::make('password'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Ahmed',
            'email' => 'ahmad@sigmagroup.com.pk2',
            // 'password' => Hash::make('Ahmed&Sales*2025'),
            'password' => Hash::make('password'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Farhan',
            'email' => 'farhan@sigmagroup.com.pk2',
            // 'password' => Hash::make('Farhan(Sales)2025'),
            'password' => Hash::make('password'),
            'role' => 'salesman'
        ]);
         User::create([
            'name' => 'Moeen',
            'email' => 'store@sigmagroup.com.pk2',
            // 'password' => Hash::make('Moeen_Sales+2025'),
            'password' => Hash::make('password'),
            'role' => 'salesman'
        ]);
    }
}
