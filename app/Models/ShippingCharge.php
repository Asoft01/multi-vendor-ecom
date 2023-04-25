<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    use HasFactory;

    public static function getShippingCharges($total_weight, $country){
        $shippingDetails = ShippingCharge::where('country', $country)->first()->toArray();
        if($total_weight > 0){
            if($total_weight > 0 && $total_weight <= 500){
                $rate = $shippingDetails['0_500g'];
            }else if($total_weight > 500 && $total_weight <= 1000){
                $rate = $shippingDetails['0_500g'];
            }else if($total_weight > 1000 && $total_weight <= 2000){
                $rate = $shippingDetails['0_500g'];
            }else if($total_weight > 2000 && $total_weight <= 5000){
                $rate = $shippingDetails['0_500g'];
            }else if($total_weight > 5000){
                $rate = $shippingDetails['0_500g'];
            }else {
                $rate = 0;
            }
        }else{
            $rate = 0;
        }
        // return $getShippingCharges->rate; 
        return $rate;
    }
}

