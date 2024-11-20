<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInfo extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    public function supplier(){
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }
}
