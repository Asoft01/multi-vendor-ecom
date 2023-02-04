<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Auth;

class OrderController extends Controller
{
    public function orders($id = null)
    {
        if (empty($id)) {
            $orders = Order::with('orders_products')->where('user_id', Auth::user()->id)->orderBy('id', 'Desc')->get()->toArray();
            // dd($orders); die; 
            return view('front.orders.orders')->with(compact('orders'));
        }else{ 
            // echo "order details"; die;
            $orderDetails = Order::with('orders_products')->where('id', $id)->first()->toArray();
            // dd($orderDetails); die; 
            return view('front.orders.order_details')->with(compact('orderDetails'));
        }
    }
}
