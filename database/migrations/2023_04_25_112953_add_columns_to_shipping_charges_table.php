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
        Schema::table('shipping_charges', function (Blueprint $table) {
            $table->float('0_500g')->after('country')->nullable();
            $table->float('501_1000g')->after('0_500g')->nullable();
            $table->float('1001_2000g')->after('501_1000g')->nullable();
            $table->float('2001_5000g')->after('1001_2000g')->nullable();
            $table->float('above_5000g')->after('2001_5000g')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_charges', function (Blueprint $table) {
            //
        });
    }
};
