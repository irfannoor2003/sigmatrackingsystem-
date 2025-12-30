<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
       $items = [
    'Important', 'Routine', 'Average', 'Others'
];


        foreach ($items as $i) {
            Category::create(['name' => $i]);
        }
    }
}
