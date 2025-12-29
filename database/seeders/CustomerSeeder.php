<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $rows = Excel::toArray([], storage_path('app/Visit customer data.xlsx'))[0];

        // Remove header row
        unset($rows[0]);

        foreach ($rows as $row) {

            /**
             * CITY NORMALIZATION (NO DUMMY)
             */
            $cityId = null;
            $cityRaw = trim($row[3] ?? '');

            if ($cityRaw !== '') {
                $normalizedCity = Str::title(strtolower($cityRaw));

                $cityId = DB::table('cities')
                    ->whereRaw('LOWER(name) = ?', [strtolower($normalizedCity)])
                    ->value('id');
                // If city not found → remains NULL
            }

            DB::table('customers')->insert([
                'name'            => trim($row[0]) ?: 'Unknown Customer',
                'contact_person'  => $row[1] ?? null,
                'address'         => trim($row[2] ?? null),
                'phone1'          => trim($row[4] ?? null),

                'phone2'          => null,
                'email'           => null,
                'city_id'         => $cityId,
                'industry_id'     => null,
                'category_id'     => null,
                'image'           => null,
                'salesman_id'     => null,

                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}
