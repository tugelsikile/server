<?php

namespace Database\Factories\Question;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->uuid(),
            'number' => $this->faker->numberBetween(1,5),
            'content' => $this->faker->words($this->faker->numberBetween(1,10),true),
            'score' => $this->faker->numberBetween(0,1),
        ];
    }
}
