<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Session; 

class ShippingController extends Controller
{
    public function shippingCharges(){
        Session::put('page', 'shipping'); 
        $shippinCharges = ShippingCharge::get()->toArray(); 
    }
}
