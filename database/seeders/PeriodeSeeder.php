<?php

namespace Database\Seeders;

use App\Models\Periode;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Periode::insert([
            [
                'name' => '2023 Pertama',
                'startdate' => '2023-01-01',
                'enddate' => '2023-03-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2023 Kedua',
                'startdate' => '2023-05-01',
                'enddate' => '2023-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2023 Ketiga',
                'startdate' => '2023-11-01',
                'enddate' => '2023-10-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2024 Pertama',
                'startdate' => '2024-02-01',
                'enddate' => '2024-03-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2024 Kedua',
                'startdate' => '2024-06-01',
                'enddate' => '2024-07-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2024 Ketiga',
                'startdate' => '2024-11-01',
                'enddate' => '2024-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2025 Pertama',
                'startdate' => '2025-05-01',
                'enddate' => '2025-07-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2026 Pertama',
                'startdate' => '2026-04-01',
                'enddate' => '2026-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
