<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_infos', function (Blueprint $table) {
            $table->id('sale_info_id');
            $table->string('invoice_number')->unique();
            $table->string('sale_date');
            $table->string('sale_type');
            $table->string('product_id');
            $table->integer('product_quantity');
            $table->double('product_price_per_unit');
            $table->double('product_discount_in_amount')->default(0);
            $table->double('product_discount_in_percentage')->default(0);
            $table->double('product_total_price');
            $table->double('product_total_price_after_discount');
            $table->string('payment_status');
            $table->string('product_return_status')->default('not_returned');
            $table->double('returned_product_quantity')->default(0);
            $table->text('sale_remarks')->nullable();
            $table->string('sale_info_photo')->nullable();
            $table->string('sale_info_slug');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_infos');
    }
}
