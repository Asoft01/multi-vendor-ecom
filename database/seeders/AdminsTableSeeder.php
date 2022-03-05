<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRecords = [
            ['id' =>1, 'name' => 'Super Admin', 'type' => 'superadmin', 'vendor_id' => 0, 'mobile' => '123456789', 'email' => 'lekhad19@gmail.com', 'password' => '$2a$12$nc3TtPpgaIGjwvudfme/pesMfuQ29T09y4iP6BWyFDWIOQkmuvltG', 'image' => '', 'status' => 1]
        ];
        Admin::insert($adminRecords);
    }
}
