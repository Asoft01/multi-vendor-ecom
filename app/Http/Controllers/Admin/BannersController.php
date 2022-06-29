<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannersController extends Controller
{
    public function banners(){
        $banners = Banner::get()->toArray();
        // dd($banners); die;
        return view('admin.banners.banners')->with(compact('banners'));
    }

    public function updateBannerStatus(Request $request){
        if($request->ajax()){
            $data= $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status'] == "Active"){
                $status = 0;
            }else{
                $status = 1;
            }

            Banner::where('id', $data['banner_id'])->update(['status'=> $status]);
            return response()->json(['status' =>$status, 'banner_id' => $data['banner_id']]);
        }
    }
}
