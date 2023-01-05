<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
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
        if($id = ""){
            // Add Coupon
            $title = "Add Coupon"; 
            $coupon = new Coupon;
            $message = "Coupon Added Successfully!";
        }else{
            // Update Coupon 
            $title = "Edit Coupon";
            $coupon = Coupon::find($id);
            $message = "Coupon Updated Successfully";
        }
        // Get Sections with Categories and Sub Categories
        $categories = Section::with('categories')->get()->toArray();
        // dd($categories);die;
        // Get All Brands

        $brands = Brand::where('status', 1)->get()->toArray();
        // Get All Users Emails 
        $users = User::select('email')->where('status', 1)->get();
        return view('admin.coupons.add_edit_coupon')->with(compact('title', 'coupon', 'categories', 'brands', 'users'));
    }
}
