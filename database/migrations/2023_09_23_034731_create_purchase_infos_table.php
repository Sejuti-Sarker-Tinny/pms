<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_infos', function (Blueprint $table) {
            $table->id('purchase_info_id');
            $table->string('challan_number');
            $table->string('bill_number');
            $table->string('purchase_date');
            $table->string('purchase_type');
            $table->string('supplier_id');
            $table->string('product_id');
            $table->integer('carton_number');
            $table->integer('box_per_carton');
            $table->integer('pata_per_box');
            $table->integer('product_unit_per_pata');
            $table->integer('product_quantity');
            $table->double('product_price_per_unit');
            $table->double('product_total_price');
            $table->text('purchase_remarks')->nullable();
            $table->string('purchase_info_photo')->nullable();
            $table->string('purchase_info_slug');
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
        Schema::dropIfExists('purchase_infos');
    }
}
