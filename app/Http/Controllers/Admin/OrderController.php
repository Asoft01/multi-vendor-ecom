<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItemStatus;
use App\Models\OrdersLog;
use App\Models\OrdersProduct;
use App\Models\OrderStatus;
use App\Models\Sms;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function orders()
    {
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
        if ($adminType == "vendor") {
            $orders = Order::with(['orders_products' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            }])->orderBy('id', 'Desc')->get()->toArray();
        } else {
            $orders = Order::with('orders_products')->orderBy('id', 'Desc')->get()->toArray();
        }
        // dd($orders); die; 
        return view('admin.orders.orders')->with(compact('orders'));
    }

    public function orderDetails($id)
    {
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

        if ($adminType == "vendor") {
            $orderDetails = Order::with(['orders_products' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            }])->where('id', $id)->first()->toArray();
        } else {
            $orderDetails = Order::with('orders_products')->where('id', $id)->first()->toArray();
        }
        $userDetails = User::where('id', $orderDetails['user_id'])->first()->toArray();
        $orderStatuses = OrderStatus::where('status', 1)->get()->toArray();
        $orderItemStatuses = OrderItemStatus::where('status', 1)->get()->toArray();
        $orderLog = OrdersLog::with('orders_products')->where('order_id', $id)->orderBy('id', 'Desc')->get()->toArray();
        // dd($orderLog); die;
        return view('admin.orders.order_details')->with(compact('orderDetails', 'userDetails', 'orderStatuses', 'orderItemStatuses', 'orderLog'));
    }

    public function updateOrderStatus(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // dd($data); die;
            // Update Order Status 
            Order::where('id', $data['order_id'])->update(['order_status' => $data['order_status']]);
            
            // Update Courier Name & Tracking Number
            if(!empty($data['courier_name']) && !empty($data['tracking_number'])){
                Order::where('id', $data['order_id'])->update(['courier_name' => $data['courier_name'], 'tracking_number' => $data['tracking_number']]);
            }
            // Update Order Log 
            $log =               new OrdersLog(); 
            $log->order_id =     $data['order_id'];
            $log->order_status = $data['order_status']; 
            $log->save(); 
            
            // Get Delivery Details 
            $deliveryDetails = Order::select('mobile', 'email', 'name')->where('id', $data['order_id'])->first()->toArray();
            $orderDetails = Order::with('orders_products')->where('id', $data['order_id'])->first()->toArray(); 
            
            if(!empty($data['courier_name']) && !empty($data['tracking_number'])){
                  // Send Order Status Update Email 
                $email = $deliveryDetails['email'];
                $messageData = [
                    'email' => $email,
                    'name' => $deliveryDetails['name'],
                    'order_id' => $data['order_id'],
                    'orderDetails' => $orderDetails, 
                    'order_status' => $data['order_status'], 
                    'courier_name' => $data['courier_name'], 
                    'tracking_number' => $data['tracking_number'], 
                ];
                Mail::send('emails.order_status', $messageData, function ($message) use ($email) {
                    $message->to($email)->Subject('Order Status Updated - ASoft.com');
                });
            }else{
                  // Send Order Status Update Email 
                $email = $deliveryDetails['email'];
                $messageData = [
                    'email' => $email,
                    'name' => $deliveryDetails['name'],
                    'order_id' => $data['order_id'],
                    'orderDetails' => $orderDetails, 
                    'order_status' => $data['order_status']
                ];
                Mail::send('emails.order_status', $messageData, function ($message) use ($email) {
                    $message->to($email)->Subject('Order Status Updated - ASoft.com');
                });
                
            }
          
            // Send Order Status Update SMS
            // $message = "Dear Customer, your order #".$data['order_id']." status has been updated to ".$data['order_status']. "placed with A-Soft";
            // $mobile = $deliveryDetails['mobile'];
            // Sms::sendSms($message, $mobile);

            $message = "Order Status has been updated successfully!";
            return redirect()->back()->with('success_message', $message);
        }
    }

    public function updateOrderItemStatus(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // dd($data); die;
            // Update Order Item Status 
            OrdersProduct::where('id', $data['order_item_id'])->update(['item_status' => $data['order_item_status']]);

            // Update Courier Name & Tracking Number
            if(!empty($data['item_courier_name']) && !empty($data['item_tracking_number'])){
                OrdersProduct::where('id', $data['order_item_id'])->update(['courier_name' => $data['item_courier_name'], 'tracking_number' => $data['item_tracking_number']]);
            }

            // Get Delivery Details 
            $getOrderId = OrdersProduct::select('order_id')->where('id', $data['order_item_id'])->first()->toArray();

            // Update Order Log 
            $log =               new OrdersLog(); 
            $log->order_id =     $getOrderId['order_id'];
            $log->order_item_id =     $data['order_item_id'];
            $log->order_status = $data['order_item_status']; 
            $log->save(); 

            $deliveryDetails = Order::select('mobile', 'email', 'name')->where('id', $getOrderId)->first()->toArray();
            $orderDetails = Order::with('orders_products')->where('id', $getOrderId['order_id'])->first()->toArray(); 

            if(!empty($data['item_courier_name']) && !empty($data['item_tracking_number'])){    
                // Send Order Status Update Email 
                $email = $deliveryDetails['email'];
                $messageData = [
                    'email' => $email,
                    'name' => $deliveryDetails['name'],
                    'order_id' => $getOrderId['order_id'],
                    'orderDetails' => $orderDetails, 
                    'order_status' => $data['order_item_status'], 
                    'courier_name' => $data['item_courier_name'], 
                    'tracking_number' => $data['item_tracking_number']
                ];
                Mail::send('emails.order_status', $messageData, function ($message) use ($email) {
                    $message->to($email)->Subject('Order Status Updated - ASoft.com');
                });
                
            }else{    
                // Send Order Status Update Email 
                $email = $deliveryDetails['email'];
                $messageData = [
                    'email' => $email,
                    'name' => $deliveryDetails['name'],
                    'order_id' => $getOrderId['order_id'],
                    'orderDetails' => $orderDetails, 
                    'order_status' => $data['order_item_status']
                ];
                Mail::send('emails.order_status', $messageData, function ($message) use ($email) {
                    $message->to($email)->Subject('Order Status Updated - ASoft.com');
                });
                
            }
             // Send Order SMS
            // $message = "Dear Customer, your order #".$order_id." status has been updated to ".$data['order_status']. "placed with A-Soft";
            // $mobile = $deliveryDetails['mobile'];
            // Sms::sendSms($message, $mobile);
            // Send Order Status Update SMS
            $message = "Order Item Status has been updated successfully!";
            return redirect()->back()->with('success_message', $message);
        }
    }
}
