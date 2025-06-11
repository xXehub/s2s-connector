<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultUsers = [
            ['name' => 'Dailam', 'email' => 'dailam@example.com'],
            ['name' => 'Angela', 'email' => 'angela@example.com'],
            ['name' => 'Sihub', 'email' => 'sihub@example.com'],
        ];

        foreach ($defaultUsers as $user) {
            User::updateOrCreate(
                ['email' => $user['email']], 
                ['name' => $user['name']]    
            );
        }
        User::factory()
            ->count(10)
            ->sequence(fn ($sequence) => ['email' => "user{$sequence->index}@example.com"])
            ->create();
    }
}
