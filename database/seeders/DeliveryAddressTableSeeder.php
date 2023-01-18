<?php

namespace Database\Seeders;

use App\Models\DeliveryAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deliveryRecords = [
            ['id' => 1, 'user_id' => 1, 'name' => 'Amit Gupta', 'address' => '123-a', 'city' => 'New Delhi', 'state' => 'Delhi', 'country' => 'India', 'pincode' => 12345, 'mobile'=> 9000000000, 'status' => 1], 
            ['id' => 2, 'user_id' => 1, 'name' => 'Amit Gupta', 'address' => '12345-a', 'city' => 'Ludiana', 'state' => 'Punjab', 'country' => 'India', 'pincode' => 12345, 'mobile'=> 9000000000, 'status' => 1], 
        ]; 
        DeliveryAddress::insert($deliveryRecords); 
    }
}
