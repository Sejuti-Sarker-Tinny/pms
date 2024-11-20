<?php

namespace Database\Factories;

use App\Models\FoodCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id'=>$this->faker->randomElement(FoodCategory::pluck('food_categories_id')->toArray()),
            'sub_cat_name'=>$this->faker->text('20'),
            'sub_cat_slug'=>$this->faker->unique()->slug,
        ];
    }
}
