<?php

namespace Database\Factories\User;

use App\Models\Major\Major;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $email = $this->faker->unique()->safeEmail();
        return [
            'id' => $this->faker->unique()->uuid(),
            'name' => $this->faker->name(),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($email), // password
            'remember_token' => Str::random(10),
            'level' => 'participant',
            'class_level' => $this->faker->numberBetween(10,12),
            'major' => $this->faker->randomElement(Major::all()->map(function ($data){ return $data->id; })->toArray()),
        ];
    }
}
