<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model{
    use HasFactory;

    public function customerInfo(){
        return $this->belongsTo(User::class,'customer_id','id');

    }

    public function orderNumber(){
        return $this->belongsTo(OrderNumber::class,'order_number_id','id');
    }

    public function foodInfo(){
        return $this->belongsTo(FoodItem::class,'food_id','id');
    }


}

