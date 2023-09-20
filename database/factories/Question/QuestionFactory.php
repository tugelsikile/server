<?php

namespace Database\Factories\Question;

use App\Models\Course\CourseTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
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
            'number' => $this->faker->numberBetween(1,100),
            'course_topic' => $this->faker->randomElement(CourseTopic::all()->map(function ($data){ return $data->id; })->toArray()),
            'content' => $this->faker->paragraph($this->faker->numberBetween(5,15), $this->faker->boolean()),
            'type' => $this->faker->randomElement(['multi_choice','string','lines','absolute']),
            'max_score' => $this->faker->numberBetween(0,1),
            'min_score' => $this->faker->numberBetween(0,1),
        ];
    }
}
