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
        $user = User::create([
            'name' => 'gopi',
            'email' => 'gopi1234@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        Todo::factory()->count(8)->create([
            'user_id' => $user->id,
        ]);
        \App\Models\User::factory(3)->create()->each(function ($user) {
            Todo::factory()->count(4)->create(['user_id' => $user->id]);
        });
    }
}
