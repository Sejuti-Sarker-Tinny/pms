<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleInfo extends Model
{
    use HasFactory;
    
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    // protected static function boot()
    // {
    //     // parent:boot();

    //     static::creating(function ($sales) {
    //         if(!$sales->exists) {
    //             $latestInvoiceNumber = static::max('invoice_number');
                
    //             $number = (int) substr($latestInvoiceNumber, 3);

    //             $newNumber = $number + 1;

    //             $newInvoice = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    //             $sales->invoice_number = $newInvoice;
                
    //         }
    //     });
    // }
}
