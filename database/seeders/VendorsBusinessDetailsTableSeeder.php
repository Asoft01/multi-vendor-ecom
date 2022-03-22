<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VendorsBusinessDetail;

class VendorsBusinessDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendorRecords =  [
            ['id' => 1, 'vendor_id' => 1, 'shop_name' => 'John Electronics Store', 'shop_address' => '1234-SCF', 'shop_city' => 'New Delhi', 'shop_state' => 'Delhi', 'shop_country' => 'India', 'shop_pincode' => '110001', 'shop_mobile' => '012345678', 'shop_website' => 'sitemaker.site', 'shop_email' => 'john@gmail.com', 'address_proof' => 'Passport', 'address_proof_image' => 'test.jpg', 'business_license_number' => '133455278', 'gst_number' => '44444323132', 'pan_number' => '2429320029'],
        ];

        VendorsBusinessDetail::insert($vendorRecords);
    }
}
