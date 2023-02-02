<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable(); 
            $table->integer('user_id')->nullable(); 
            $table->integer('vendor_id')->nullable(); 
            $table->integer('admin_id')->nullable(); 
            $table->integer('product_id')->nullable(); 
            $table->string('product_code')->nullable(); 
            $table->string('product_name')->nullable(); 
            $table->string('product_color')->nullable(); 
            $table->string('product_size')->nullable(); 
            $table->float('product_price')->nullable(); 
            $table->integer('product_qty')->nullable(); 
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
        Schema::dropIfExists('orders_products');
    }
};
