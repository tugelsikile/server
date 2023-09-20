<?php

namespace Database\Factories\Course;

use App\Models\Major\Major;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->words($this->faker->numberBetween(1,5),true);
        return [
            'id' => $this->faker->unique()->uuid(),
            'name' => $name,
            'major' => $this->faker->randomElement(Major::all()->map(function ($data){ return $data->id; })->toArray()),
            'code' => generateCourseCode($name),
            'level' => $this->faker->numberBetween(10,12),
        ];
    }
}
