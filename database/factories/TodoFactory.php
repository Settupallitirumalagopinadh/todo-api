<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Todo;
use App\Models\User;
class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), 
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->sentence(),
            'is_completed' => $this->faker->boolean(20),
        ];
    }
}
