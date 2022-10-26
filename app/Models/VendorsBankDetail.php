<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorsBankDetail extends Model
{
    use HasFactory;

    public function vendorbankdetail(){
        return $this->belongsTo(Vendor::class, 'vendor_id'); 
    }
}
