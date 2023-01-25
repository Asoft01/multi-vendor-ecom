<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;


class AddressController extends Controller
{
    public function getDeliveryAddress(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $deliveryAddress = DeliveryAddress::where('id', $data['addressid'])->first()->toArray();
            return response()->json(['address' => $deliveryAddress]);
        }
    }

    public function saveDeliveryAddress(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if (!empty($data['delivery_id'])) {
                $address = array();
                $address['user_id'] = Auth::user()->id;
                $address['name'] = $data['delivery_name'];
                $address['address'] = $data['delivery_address'];
                $address['city'] = $data['delivery_city'];
                $address['state'] = $data['delivery_state'];
                $address['country'] = $data['delivery_country'];
                $address['pincode'] = $data['delivery_pincode'];
                $address['mobile'] = $data['delivery_mobile'];
                // Edit Delivery Address
                DeliveryAddress::where('id', $data['delivery_id'])->update($address);
            } else {
                // Add Delivery Address 
                // $address = DeliveryAddress::where('id', $data['addressid'])->first()->toArray(); 
            }
            $deliveryAddresses = DeliveryAddress::deliveryAddresses();
            $countries = Country::where('status', 1)->get()->toArray();

            return response()->json([
                'view' => (string)View::make('front.products.delivery_addresses')->with(compact('deliveryAddresses', 'countries'))
            ]);
        }
    }
}
