<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function coupons()
    {
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
}
