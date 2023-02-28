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
            <title>Example 1</title>
            <style>
                .clearfix:after {
                    content: "";
                    display: table;
                    clear: both;
                }
                
                a {
                    color: #5D6975;
                    text-decoration: underline;
                }
                
                body {
                    position: relative;
                    width: 21cm;  
                    height: 29.7cm; 
                    margin: 0 auto; 
                    color: #001028;
                    background: #FFFFFF; 
                    font-family: Arial, sans-serif; 
                    font-size: 12px; 
                    font-family: Arial;
                }
                
                header {
                    padding: 10px 0;
                    margin-bottom: 30px;
                }
                
                #logo {
                    text-align: center;
                    margin-bottom: 10px;
                }
                
                #logo img {
                    width: 90px;
                }
                
                h1 {
                    border-top: 1px solid  #5D6975;
                    border-bottom: 1px solid  #5D6975;
                    color: #5D6975;
                    font-size: 2.4em;
                    line-height: 1.4em;
                    font-weight: normal;
                    text-align: center;
                    margin: 0 0 20px 0;
                    background: url(dimension.png);
                }
                
                #project {
                    float: left;
                }
                
                #project span {
                    color: #5D6975;
                    text-align: right;
                    width: 52px;
                    margin-right: 10px;
                    display: inline-block;
                    font-size: 0.8em;
                }
                
                #company {
                    float: right;
                    text-align: right;
                }
                
                #project div,
                #company div {
                    white-space: nowrap;        
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    border-spacing: 0;
                    margin-bottom: 20px;
                }
                
                table tr:nth-child(2n-1) td {
                    background: #F5F5F5;
                }
                
                table th,
                table td {
                    text-align: center;
                }
                
                table th {
                    padding: 5px 20px;
                    color: #5D6975;
                    border-bottom: 1px solid #C1CED9;
                    white-space: nowrap;        
                    font-weight: normal;
                }
                
                table .service,
                table .desc {
                    text-align: left;
                }
                
                table td {
                    padding: 20px;
                    text-align: right;
                }
                
                table td.service,
                table td.desc {
                    vertical-align: top;
                }
                
                table td.unit,
                table td.qty,
                table td.total {
                    font-size: 1.2em;
                }
                
                table td.grand {
                    border-top: 1px solid #5D6975;;
                }
                
                #notices .notice {
                    color: #5D6975;
                    font-size: 1.2em;
                }
                
                footer {
                    color: #5D6975;
                    width: 100%;
                    height: 30px;
                    position: absolute;
                    bottom: 0;
                    border-top: 1px solid #C1CED9;
                    padding: 8px 0;
                    text-align: center;
                }
            </style>
          </head>
          <body>
            <header class="clearfix">
              <div id="logo">
                <img src="logo.png">
              </div>
              <h1>INVOICE 3-2-1</h1>
              <div id="company" class="clearfix">
                <div>ASOFT DEVELOPERS</div>
                <div>455 Foggy Heights,<br /> AZ 85004, US</div>
                <div>(602) 519-0450</div>
                <div><a href="mailto:company@example.com">company@example.com</a></div>
              </div>
              <div id="project">
                <div><span>PROJECT</span> Website development</div>
                <div><span>CLIENT</span> '.$orderDetails['name'].' </div>
                <div><span>ADDRESS</span> '.$orderDetails['address'].' '.$orderDetails['city'].' '.$orderDetails['state'].' '.$orderDetails['country'].'-'.$orderDetails['pincode'].' </div>
                <div><span>EMAIL</span> <a href="mailto:'.$orderDetails['email'].'">'.$orderDetails['email'].'</a></div>
                <div><span>'.$orderDetails['id'].'</span></div>
                <div><span>Order Date</span>'.date('Y-m-d h:i:s', strtotime($orderDetails['created_at'])).'</div>
                <div><span>Order Amount:</span> '.$orderDetails['grand_total'].'</div>
                <div><span>Order Status:</span> '.$orderDetails['order_status'].'</div>
                <div><span>Payment Method:</span> '.$orderDetails['payment_method'].'</div>
              </div>
            </header>
            <main>
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
                    <td class=service">Design</td>
                    <td class="desc">Creating a recognizable design solution based on the company existing visual identity</td>
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
                    <td colspan="4">SUBTOTAL</td>
                    <td class="total">$5,200.00</td>
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
