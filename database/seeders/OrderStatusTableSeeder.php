<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderStatusRecords = [
            ['id' => 1, 'name' => 'New', 'status' => 1], 
            ['id' => 2, 'name' => 'Pending', 'status' => 1], 
            ['id' => 3, 'name' => 'Cancelled', 'status' => 1], 
            ['id' => 4, 'name' => 'In Process', 'status' => 1], 
            ['id' => 5, 'name' => 'Shipped', 'status' => 1], 
            ['id' => 6, 'name' => 'Partially Shipped', 'status' => 1], 
            ['id' => 7, 'name' => 'Delivered', 'status' => 1], 
            ['id' => 8, 'name' => 'Partially Delivered', 'status' => 1], 
            ['id' => 9, 'name' => 'Paid', 'status' => 1], 
        ];
        OrderStatus::insert($orderStatusRecords); 
    }
}
