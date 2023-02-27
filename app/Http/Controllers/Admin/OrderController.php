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
// use DomPdf\DomPdf;
use Dompdf\Dompdf;

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
            if (!empty($data['courier_name']) && !empty($data['tracking_number'])) {
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

            if (!empty($data['courier_name']) && !empty($data['tracking_number'])) {
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
            } else {
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
            if (!empty($data['item_courier_name']) && !empty($data['item_tracking_number'])) {
                OrdersProduct::where('id', $data['order_item_id'])->update(['courier_name' => $data['item_courier_name'], 'tracking_number' => $data['item_tracking_number']]);
            }

            // Get Delivery Details 
            $getOrderId = OrdersProduct::select('order_id')->where('id', $data['order_item_id'])->first()->toArray();

            // Update Order Log 
            $log =               new OrdersLog();
            $log->order_id =      $getOrderId['order_id'];
            $log->order_item_id = $data['order_item_id'];
            $log->order_status =  $data['order_item_status'];
            $log->save();

            $deliveryDetails = Order::select('mobile', 'email', 'name')->where('id', $getOrderId)->first()->toArray();

            $order_item_id = $data['order_item_id'];
            $orderDetails = Order::with(['orders_products' => function ($query) use ($order_item_id) {
                $query->where('id', $order_item_id);
            }])->where('id', $getOrderId['order_id'])->first()->toArray();

            if (!empty($data['item_courier_name']) && !empty($data['item_tracking_number'])) {
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
                Mail::send('emails.order_item_status', $messageData, function ($message) use ($email) {
                    $message->to($email)->Subject('Order Status Updated - ASoft.com');
                });
            } else {
                // Send Order Status Update Email 
                $email = $deliveryDetails['email'];
                $messageData = [
                    'email' => $email,
                    'name' => $deliveryDetails['name'],
                    'order_id' => $getOrderId['order_id'],
                    'orderDetails' => $orderDetails,
                    'order_status' => $data['order_item_status']
                ];
                Mail::send('emails.order_item_status', $messageData, function ($message) use ($email) {
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

    public function viewOrderInvoice($order_id){
        $orderDetails = Order::with('orders_products')->where('id', $order_id)->first()->toArray();
        // $user_id = $orderDetails['user_id'];
        $userDetails = User::where('id', $orderDetails['user_id'])->first()->toArray();
        return view('admin.orders.order_invoice')->with(compact('orderDetails', 'userDetails')); 
    }

    public function viewPDFInvoice($order_id){
        // dd("Hello"); die;
        $orderDetails = Order::with('orders_products')->where('id', $order_id)->first()->toArray();
        // $user_id = $orderDetails['user_id'];
        $userDetails = User::where('id', $orderDetails['user_id'])->first()->toArray();
        
        $invoiceHTML = '<!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="utf-8">
            <title>Example 3</title>
            <link rel="stylesheet" href="style.css" media="all" />
          </head>
          <body>
            <main>
              <h1  class="clearfix"><small><span>DATE</span><br />August 17, 2015</small> INVOICE 3-2-1 <small><span>DUE DATE</span><br /> September 17, 2015</small></h1>
              <table>
                <thead>
                  <tr>
                    <th class="service">SERVICE</th>
                    <th class="desc">DESCRIPTION</th>
                    <th>PRICE</th>
                    <th>QTY</th>
                    <th>TOTAL</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="service">Design</td>
                    <td class="desc">Creating a recognizable design solution based on the company's existing visual identity</td>
                    <td class="unit">$40.00</td>
                    <td class="qty">26</td>
                    <td class="total">$1,040.00</td>
                  </tr>
                  <tr>
                    <td class="service">Development</td>
                    <td class="desc">Developing a Content Management System-based Website</td>
                    <td class="unit">$40.00</td>
                    <td class="qty">80</td>
                    <td class="total">$3,200.00</td>
                  </tr>
                  <tr>
                    <td class="service">SEO</td>
                    <td class="desc">Optimize the site for search engines (SEO)</td>
                    <td class="unit">$40.00</td>
                    <td class="qty">20</td>
                    <td class="total">$800.00</td>
                  </tr>
                  <tr>
                    <td class="service">Training</td>
                    <td class="desc">Initial training sessions for staff responsible for uploading web content</td>
                    <td class="unit">$40.00</td>
                    <td class="qty">4</td>
                    <td class="total">$160.00</td>
                  </tr>
                  <tr>
                    <td colspan="4" class="sub">SUBTOTAL</td>
                    <td class="sub total">$5,200.00</td>
                  </tr>
                  <tr>
                    <td colspan="4">TAX 25%</td>
                    <td class="total">$1,300.00</td>
                  </tr>
                  <tr>
                    <td colspan="4" class="grand total">GRAND TOTAL</td>
                    <td class="grand total">$6,500.00</td>
                  </tr>
                </tbody>
              </table>
              <div id="details" class="clearfix">
                <div id="project">
                  <div class="arrow"><div class="inner-arrow"><span>PROJECT</span> Website development</div></div>
                  <div class="arrow"><div class="inner-arrow"><span>CLIENT</span> John Doe</div></div>
                  <div class="arrow"><div class="inner-arrow"><span>ADDRESS</span> 796 Silver Harbour, TX 79273, US</div></div>
                  <div class="arrow"><div class="inner-arrow"><span>EMAIL</span> <a href="mailto:john@example.com">john@example.com</a></div></div>
                </div>
                <div id="company">
                  <div class="arrow back"><div class="inner-arrow">Company Name <span>COMPANY</span></div></div>
                  <div class="arrow back"><div class="inner-arrow">455 Foggy Heights, AZ 85004, US <span>ADDRESS</span></div></div>
                  <div class="arrow back"><div class="inner-arrow">(602) 519-0450 <span>PHONE</span></div></div>
                  <div class="arrow back"><div class="inner-arrow"><a href="mailto:company@example.com">company@example.com</a> <span>EMAIL</span></div></div>
                </div>
              </div>
              <div id="notices">
                <div>NOTICE:</div>
                <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
              </div>
            </main>
            <footer>
              Invoice was created on a computer and is valid without the signature and seal.
            </footer>
          </body>
        </html>';

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        // $dompdf->loadHtml('hello world');
        $dompdf->loadHtml($invoiceHTML);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
    }
}
