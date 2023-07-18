<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CmsController extends Controller
{
    public function contact(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // send user query to admin
            $email = "lekhad19@gmail.com"; 
            $messageData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'subject' => $data['subject'],
                'comment' => $data['message'],
            ]; 

            Mail::send('emails.enquiry', $messageData, function($messageData) use($email){
                $messageData->to($email)->subject("Enquiry from Website Developer");
            });

            $message = "Thanks for your enquiry. we will get back to you soon."; 
            return redirect()->back()->with('success_message', $message);
        }
        return view('front.pages.contact');
    }
}
