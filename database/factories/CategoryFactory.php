<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'colour' => $this->faker->hexColor,
            'name' => $this->faker->word(1),
            'description' => $this->faker->paragraph(1),
        ];
    }
}
