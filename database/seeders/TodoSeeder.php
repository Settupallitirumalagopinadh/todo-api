<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Todo;
use Illuminate\Support\Facades\Hash;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        // Create a demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Create some todos for that user
        Todo::factory()->count(8)->create([
            'user_id' => $user->id,
        ]);

        // Optionally create more random users + todos
        \App\Models\User::factory(3)->create()->each(function ($user) {
            Todo::factory()->count(4)->create(['user_id' => $user->id]);
        });
    }
}
