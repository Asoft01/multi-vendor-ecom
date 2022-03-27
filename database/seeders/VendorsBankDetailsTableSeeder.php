<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VendorsBankDetail;

class VendorsBankDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vendors Records
        $vendorRecords =  [
            ['id' => 1, 'vendor_id' => 1, 'account_holder_name' => 'John Cena', 'bank_name' => 'ICICI', 'account_number' => '123456930299', 'bank_ifsc_code' => '1234532'],
        ];

        VendorsBankDetail::insert($vendorRecords);
    }
}
