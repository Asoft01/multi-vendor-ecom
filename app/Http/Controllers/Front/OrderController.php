<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Auth;

class OrderController extends Controller
{
    public function orders(){
        $orders = Order::with('orders_products')->where('user_id', Auth::user()->id)->orderBy('id', 'Desc')->get()->toArray();
        // dd($orders); die; 
        return view('front.orders.orders')->with(compact('orders'));
    }
}
