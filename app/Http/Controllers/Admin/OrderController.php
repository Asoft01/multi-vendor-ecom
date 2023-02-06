<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Session;
use Auth;
class OrderController extends Controller
{
    public function orders(){
        Session::put('page', 'orders');
        $vendor_id = Auth::guard('admin')->user()->vendor_id;
        // dd($vendor_id); die;
        // dd(Auth::guard('admin')->user()->id);
        $adminType = Auth::guard('admin')->user()->type;
        if ($adminType == "vendor") {
            $vendorStatus = Auth::guard('admin')->user()->status;
            if ($vendorStatus == "0") {
                return redirect("admin/update-vendor-details/personal")->with('error_message', 'Your Vendor Account is not approved yet. Please make sure you fill your valid personal, business and bank details');
            }
        }
        if($adminType == "vendor"){
            $orders = Order::with(['orders_products' => function($query) use($vendor_id){
                $query->where('vendor_id', $vendor_id);  
            }])->orderBy('id', 'Desc')->get()->toArray();
        }else{
            $orders = Order::with('orders_products')->orderBy('id', 'Desc')->get()->toArray();
        }
        // dd($orders); die; 
        return view('admin.orders.orders')->with(compact('orders'));
    }
}
