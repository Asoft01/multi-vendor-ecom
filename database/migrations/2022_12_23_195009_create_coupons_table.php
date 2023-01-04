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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->nullable();
            $table->string('coupon_option')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('categories')->nullable();
            $table->text('users')->nullable();
            $table->string('coupon_type')->nullable();
            $table->string('amount_type')->nullable();
            $table->float('amount')->nullable();
            $table->date('expiry_date')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('coupons');
    }
};
