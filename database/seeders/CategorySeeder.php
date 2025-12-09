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
            'A', 'B', 'C', 'D', 'Retail', 'Wholesale'
        ];

        foreach ($items as $i) {
            Category::create(['name' => $i]);
        }
    }
}
