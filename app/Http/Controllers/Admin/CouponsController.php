<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Auth;
class CouponsController extends Controller
{
    public function coupons()
    {
        Session::put('page', 'coupons');
        $coupons = Coupon::get()->toArray();
        // dd($coupons); die;
        return view('admin.coupons.coupons')->with(compact('coupons'));
    }

    public function updateCouponStatus(Request $request){
        if($request->ajax()){
            $data= $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status'] == "Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            
            Coupon::where('id', $data['coupon_id'])->update(['status'=> $status]);
            return response()->json(['status' =>$status, 'coupon_id' => $data['coupon_id']]);
        }
    }

    public function deleteCoupon($id){
        
        // Delete Coupon Image from Banners Table
        Coupon::where('id', $id)->delete();

        $message = "Coupon deleted Successfully";
        return redirect()->back()->with('success_message', $message); 
    }

    public function addEditCoupon(Request $request, $id = null){
        if($id == ""){
            // Add Coupon
            $title = "Add Coupon"; 
            $coupon = new Coupon;
            $selCats = array(); 
            $selBrands = array(); 
            $selUsers = array(); 
            $message = "Coupon Added Successfully!";
        }else{
            // Update Coupon 
            $title = "Edit Coupon";
            $coupon = Coupon::find($id);
            $selCats = explode(',', $coupon['categories']); 
            $selBrands = explode(',', $coupon['brands']);
            $selUsers = explode(',', $coupon['users']);
            $message = "Coupon Updated Successfully";
            // echo "<pre>"; print_r($coupon); die;
            // dd($coupon); die;
        }

        if($request->isMethod('post')){
            $data = $request->all(); 
            // echo "<pre>"; print_r($data); die;

            $rules = [
                'categories' => 'required',
                'brands'=> 'required',
                'coupon_option'=> 'required',
                'coupon_type'=> 'required',
                'amount_type'=> 'required',
                'amount'=> 'required|numeric',
                'expiry_date'=> 'required',
            ];

            // $this->validate($request, $rules);

            $customMessages= [
                'categories.required' => 'Select categories',
                'brands.required' => 'Select Brands',
                'coupon_option.required' => 'Select Coupon Option',
                'coupon_type.required' => 'Select Coupon Type',
                'amount_type.required' => 'Select Amount Type',
                'amount.required' => 'Enter Amount',
                'amount.numeric' =>   'Enter Valid Amount',
                'expiry_date.required' => 'Enter Expiry Date', 
            ];
            
            $this->validate($request, $rules, $customMessages);

            if(isset($data['categories'])){
                $categories = implode(",",$data['categories']);
            }else{
                $categories = "";
            }

            if(isset($data['brands'])){
                $brands = implode(",",$data['brands']);
            }else{
                $brands = "";
            }

            if(isset($data['users'])){
                $users = implode(",",$data['users']);
            }else{
                $users = "";
            }

            if($data['coupon_option'] == "Automatic"){
                // echo $coupon_code = str_random(8);
                $coupon_code = str_random(8);
            }else{
                $coupon_code = $data['coupon_code'];
            }
            // echo "<pre>"; print_r($data); die;
            
            $adminType = Auth::guard('admin')->user()->type;
            if($adminType == "vendor"){
                $coupon->vendor_id = Auth::guard('admin')->user()->vendor_id;
            }else{
                $coupon->vendor_id = 0;
            }

            $coupon->coupon_option = $data['coupon_option'];
            $coupon->coupon_code = $coupon_code;
            $coupon->categories =  $categories;
            $coupon->brands =      $brands;
            $coupon->users =       $users;
            $coupon->coupon_type = $data['coupon_type'];
            $coupon->amount_type = $data['amount_type'];
            $coupon->amount =      $data['amount'];
            $coupon->expiry_date = $data['expiry_date'];
            $coupon->status = 1;
            $coupon->save();
            return redirect('admin/coupons')->with('success_message', $message); 
        }
        // Get Sections with Categories and Sub Categories
        $categories = Section::with('categories')->get()->toArray();
        // dd($categories);die;
        // Get All Brands

        $brands = Brand::where('status', 1)->get()->toArray();
        // Get All Users Emails 
        $users = User::select('email')->where('status', 1)->get();
        return view('admin.coupons.add_edit_coupon')->with(compact('title', 'coupon', 'categories', 'brands', 'users', 'selCats', 'selBrands', 'selUsers'));
    }
}
