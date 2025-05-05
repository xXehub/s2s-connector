<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            ['name' => 'Dailam', 'email' => 'dailam@example.com'],
            ['name' => 'Angela', 'email' => 'angela@example.com'],
            ['name' => 'Sihub', 'email' => 'sihub@example.com'],
        ]);

        User::factory()->count(10)->create();
    }
}
