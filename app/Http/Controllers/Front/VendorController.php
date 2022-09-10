<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;
class VendorController extends Controller
{
    public function loginRegister(){
        // echo "test"; die;
        return view('front.vendors.login_register'); 
    }

    public function vendorRegister(Request $request){
        if($request->isMethod('post')){
            $data = $request->all(); 
            // echo "<pre>"; print_r($data); die;

            // Validate Vendor 
            $rules = [
                "name" => "required", 
                "email" => "required|email|unique:admins|unique:vendors", 
                "mobile" => "required|min:10|numeric|unique:admins|unique:vendors",
                "accept" => "required" 
            ]; 
            $customMessages = [
                "name.required" => "Name is required", 
                "email.required" => "Email is required", 
                "email.unique" => "Email already exists", 
                "accept.required" => "Please accept T&C", 
                "mobile.required" => "Mobile is required", 
                "mobile.unique" => "Mobile already exists", 
                "accept.required" => "Please accept T&C", 
            ]; 

            $validator = Validator::make($data, $rules, $customMessages);
            if($validator->fails()){
                return Redirect::back()->withErrors($validator); 
            }
        }
    }
}
