<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Industry;

class IndustrySeeder extends Seeder
{
    public function run()
    {
        $items = [
            'Pharmaceutical',
            'Automotive',
            'Manufacturing',
            'Construction',
            'Chemical',
            'IT Services'
        ];

        foreach ($items as $i) {
            Industry::create(['name' => $i]);
        }
    }
}
