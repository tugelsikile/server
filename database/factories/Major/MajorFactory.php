<?php

namespace Database\Factories\Major;

use Illuminate\Database\Eloquent\Factories\Factory;

class MajorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->words($this->faker->numberBetween(3,6),true);
        return [
            'id' => $this->faker->unique()->uuid(),
            'code' => generateMajorCode($name),
            'name' => $name,
        ];
    }
}
