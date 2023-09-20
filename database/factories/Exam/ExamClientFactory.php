<?php

namespace Database\Factories\Exam;

use App\Models\Exam\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamClientFactory extends Factory
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
            'exam' => $this->faker->randomElement(Exam::all()->map(function ($data){ return $data->id; })->toArray()),
            'code' => generateExamClientCode(),
            'name' => $this->faker->word(),
            'token' => generateExamClientToken(),
        ];
    }
}
