<?php

use App\Models\FoodItem;

Class Helper
{
    public static function minPrice()
    {
        return floor(FoodItem::min('price'));
    }
    public static function maxPrice()
    {
        return floor(FoodItem::max('price'));
    }

}
