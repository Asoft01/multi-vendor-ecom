<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Session;
use Omnipay\Omnipay;
use App\Models\Payment;
use Auth;
use Illuminate\Support\Facades\Mail;

class PaypalController extends Controller
{
    private $gateway;

    public function __construct(){
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }

    public function paypal(){
        if(Session::has('order_id')){
            return view('front.paypal.paypal');
        }else{
            return redirect('cart');
        }
    }

    public function pay(Request $request){
        try{
            $paypal_amount = round(Session::get('grand_total')/80,2);
            $response = $this->gateway->purchase(array(
                'amount' => $paypal_amount, 
                'currency' => env('PAYPAL_CURRENCY'), 
                'returnUrl' => url('success'), 
                'cancelUrl' => url('error')
            ))->send();

            if($response->isRedirect()){
                $response->redirect();
            }else{
                return $response->getMessage();
            }
        }catch (\Throwable $th){
            return $th->getMessage();
        }
    }

    public function success(Request $request){
        if(!Session::has('order_id')){
            return redirect('cart');
        }
        if($request->input('paymentId') && $request->input('PayerID')){
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' =>$request->input('paymentId')
            ));

            // response to the paypal 
            $response = $transaction->send(); 
            if($response->isSuccessful()){
                $arr = $response->getData(); 
                $payment = new Payment(); 
                $payment->order_id = Session::get('order_id');
                $payment->user_id = Session::get('order_id');
                $payment->payment_id = $arr['id']; 
                $payment->payer_id  = $arr['payer']['payer_info']['payer_id']; 
                $payment->payer_email  = $arr['payer']['payer_info']['email']; 
                $payment->amount  = $arr['transactions'][0]['amount']['total'];
                $payment->currency= env('PAYPAL_CURRENCY');
                $payment->payment_status = $arr['state']; 
                $payment->save();
                // return "Payment is Successful. Your transaction is ".$arr['id'];
                
                // Update the Order 
                $order_id = Session::get('order_id'); 

                // Update Order Status to Paid 
                Order::where('id', $order_id)->update(['order_status' => 'Paid']);

                // Send Order SMS 
                // $message = "Dear Customer, your order ".$order_id." has been successfully placed with ASoft.com. We will intimate you once your order is shipped";
                // $mobile = Auth::user()->mobile;
                // Sms::sendSms($message, $mobile);

                $orderDetails = Order::with('orders_products')->where('id', $order_id)->first()->toArray();
                // Send Order Email 
                $email = Auth::user()->email;
                $messageData = [
                    'email' => $email,
                    'name' => Auth::user()->name,
                    'order_id' => $order_id,
                    'orderDetails' => $orderDetails
                ];
                Mail::send('emails.order', $messageData, function ($message) use ($email) {
                    $message->to($email)->Subject('Order Placed - ASoft.com');
                });

                // Reduce Stock Script Starts 
                foreach($orderDetails['orders_products'] as $key => $order){
                    $getProductStock = ProductsAttribute::getProductStock($order['product_id'], $order['product_size']); 
                    $newStock = $getProductStock - $item['product_qty']; 
                    ProductsAttribute::where(['product_id' => $order['product_id'], 'size' => $order['product_size']])->update(['stock' => $newStock]);
                }
                 // Reduce Stock Script Ends 

                // Empty the cart 
                Cart::where('user_id', Auth::user()->id)->delete();
                return view('front.paypal.success');
            }else{
                return $response->getMessage(); 
            }
        }else{
            return "Payment Declined!";
        }
    }

    public function error(){
        // return "User declined the payment"; 
        return view('front.paypal.fail');
    }

}
