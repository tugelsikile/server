<?php

namespace Database\Factories\Course;

use Illuminate\Database\Eloquent\Factories\Factory;

class CourseTopicFactory extends Factory
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
            'code' => generateCourseTopicCode(),
            'name' => $this->faker->words($this->faker->numberBetween(2,5),true),
        ];
    }
}
