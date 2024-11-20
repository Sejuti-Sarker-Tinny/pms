<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FoodCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'food_categories_name'=>$this->faker->text('15'),
            'food_categories_slug'=>$this->faker->unique()->slug,
        ];
    }
}
