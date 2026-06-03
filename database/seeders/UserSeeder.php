<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'role_id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
            'phone' => '081234567890',
            'address' => 'Posyandu',
            'isactive' => true,
        ]);
    }
}