<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'admin',
                'description' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'petugas',
                'description' => 'Petugas Posyandu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'kader',
                'description' => 'Kader Posyandu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
