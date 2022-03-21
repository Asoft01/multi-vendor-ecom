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
            ['id' =>2, 'name' => 'John', 'type' => 'vendor', 'vendor_id' => 1, 'mobile' => '012345678', 'email' => 'john@gmail.com', 'password' => '$2a$12$x7UBffqxY98H7sN5nywAb.SQ1DZhQm7GhCfcLD84owtwFXUTP7vY.', 'image' => '', 'status' => 0]
        ];
        Admin::insert($adminRecords);
    }
}
