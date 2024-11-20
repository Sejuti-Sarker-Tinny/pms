<?php

namespace Database\Factories;

use App\Models\FoodCategory;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodItemFactory extends Factory
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
            'sub_category_id'=>$this->faker->randomElement(SubCategory::pluck('id')->toArray()),
            'food_item_name'=>$this->faker->unique()->text('20'),
            'food_item_slug'=>$this->faker->unique()->slug,
            'food_item_details'=>$this->faker->sentence('20'),
            'food_item_img'=>$this->faker->imageUrl('400','400'),
            'price'=>$this->faker->numberBetween(100,1000),
            'spice_level'=>$this->faker->randomElement(['Normal','High', 'Medium']),
            'sugar_level'=>$this->faker->randomElement(['Yes','No', 'None']),
            'is_non_allergic'=>$this->faker->randomElement(['Yes','No', 'None']),
        ];
    }
}
