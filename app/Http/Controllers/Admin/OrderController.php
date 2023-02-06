<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orders(){
        $orders = Order::with('orders_products')->orderBy('id', 'Desc')->get()->toArray();
        // dd($orders); die; 
        return view('admin.orders.orders')->with(compact('orders'));
    }
}
