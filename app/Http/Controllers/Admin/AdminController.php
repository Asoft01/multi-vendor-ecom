<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\Admin;

class AdminController extends Controller
{
    public function dashboard(){
        return view('admin.dashboard');
    }

    public function updateAdminPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // $data = $request->collect();
            // $data = $request->collect('users')->each(function(){

            // });

            echo "<pre>"; print_r($data); die;
            // Check if current password entered by admin is correct
            if(Hash::check($data['current_password'], Auth::guard('admin')->user()->password)){
                // Check if new password is matching with confirm password
                if($data['confirm_password'] == $data['new_password']){
                    Admin::where('id', Auth::guard('admin')->user()->id)->update(['password' => bcrypt($data['new_password'])]);
                    return redirect()->back()->with('success_message', 'Password has been updated successfully');
                }else{
                    return redirect()->back()->with('error_message', 'New password and confirm password does not match');
                }
            }else{
                return redirect()->back()->with('error_message', 'Your Current Password is Incorrect!');
            }
        }
        // echo "<pre>"; print_r(Auth::guard('admin')->user()); die;
        $adminDetails = Admin::where('email', Auth::guard('admin')->user()->email)->first()->toArray();
        
        return view('admin.settings.update_admin_password')->with(compact('adminDetails'));
    }

    public function updateAdminDetails(Request $request){
        if($request->isMethod('post')){
            $data= $request->all();
            echo "<pre>"; print_r($data); die;

            $rules = [
                'admin_name'=> 'required|regex:/^[\pL\s\-]+$/u',
                'admin_mobile' => 'required|numeric'
            ];

            // $this->validate($request, $rules);

            $customMessages= [
                'admin_name.required' => 'Name is required',
                'admin_name.regex' => 'Valid Name is required',
                'admin_mobile.required' => 'Mobile is required',
                'admin_mobile.numeric' => 'Valid mobile is required'
            ];

            $this->validate($request, $rules, $customMessages);

            // Upload Admin Photo

            // Update Admin Details
            Admin::where('id', Auth::guard('admin')->user()->id)->update(['name' => $data['admin_name'], 'mobile' => $data['admin_mobile']]);
            return redirect()->back()->with('success_message', 'Admin details updated successfully');
        }
        return view('admin.settings.update_admin_details');
    }

    public function login(Request $request){
        // echo $password = Hash::make('Adeleke1234'); die;

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            
            // $validated = $request->validate([
            //     'email' => 'required|email|max:255',
            //     'password' => 'required', 
            // ]);
                
            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required'
            ];

            $customMessages = [
                // Add Custom Messages here 
                'email.required' => 'Email Address is required',
                'email.email' => 'Valid Email Address is required',
                'password.required' => 'Password is required'

            ];

            $this->validate($request, $rules, $customMessages);


            if(Auth::guard('admin')->attempt(['email'=> $data['email'], 'password' => $data['password'], 'status' => 1])){
                return redirect('admin/dashboard');
            }else{
                return redirect()->back()->with('error_message', 'Invalid Email or Password');
            }
        }
        return view('admin.login');
    }

    public function checkAdminPassword(Request $request){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        if(Hash::check($data['current_password'], Auth::guard('admin')->user()->password)){
            return "true";
        }else{
            return "false";
        }
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }
}
