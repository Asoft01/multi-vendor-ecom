<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class DeliveryAddress extends Model
{
    use HasFactory;

    public static function DeliveryAddresses(){
        $deliveryAddresses = DeliveryAddress::where('user_id', Auth::user()->id)->get()->toArray(); 
        return $deliveryAddresses; 
    }
}
