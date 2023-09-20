<?php

namespace Database\Factories\Exam;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
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
            'name' => $this->faker->domainName(),
            'description' => $this->faker->words(10,true),
            'random_question' => $this->faker->boolean(),
            'random_answer' => $this->faker->boolean(),
            'show_result' => $this->faker->boolean(),
        ];
    }
}
