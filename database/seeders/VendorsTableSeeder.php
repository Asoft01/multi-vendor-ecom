<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendorRecords = [
            ['id' => 1, 'name' => 'John', 'address' => 'CP-112', 'city' => 'New Delhi', 'state' => 'Delhi', 'country' => 'India', 'pincode' => '110011', 'mobile' => '012345678', 'email' => 'john@gmail.com', 'status' => 0],
        ];

        Vendor::insert($vendorRecords);
    }
}
