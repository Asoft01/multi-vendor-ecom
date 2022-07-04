<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Session;
use Image;

class BannersController extends Controller
{
    public function banners(){
        Session::put('page', 'banners');
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

    public function deleteBanner($id){
        // Get Banner Image 
        $bannerImage= Banner::where('id', $id)->first();

        // Get Banner Image Path 
        $banner_image_path = 'front/images/banner_images/';
        // Delete Banner Image if exists in Folder
        if(file_exists($banner_image_path.$bannerImage->image)){
            unlink($banner_image_path.$bannerImage->image);
        }

        // Delete Banner Image from Banners Table
        Banner::where('id', $id)->delete();

        $message = "Banner deleted Successfully";
        return redirect('admin/banners')->with('success_message', $message);
    }

    public function addEditBanner(Request $request, $id = null){
        if($id == ""){
            // Add Banner 
            $banner = new Banner;
            $title = "Add Banner Image";
            $message = "Banner added successfully";
        }else{
            // Update Banner
            $banner = Banner::find($id);
            $title = "Edit Banner Image"; 
            $message = "Banner updated Successfully!";
        }

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
              // Upload Banner Image

              $banner->link = $data['link'];
              $banner->title = $data['title'];
              $banner->alt = $data['alt'];
              $banner->status = 1;

              if($request->hasFile('image')){
                // echo $image_tmp = $request->file('image'); die;
                $image_tmp = $request->file('image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    // Generate new image name 
                    $imageName = rand(111, 999999).'.'.$extension; 
                    // echo $imagePath = 'admin/images/photos/'.$imageName; die;
                    $imagePath = 'front/images/banner_images/'.$imageName;
                    // Upload the Image
                    Image::make($image_tmp)->resize(1920, 720)->save($imagePath);
                    $banner->image = $imageName;
                }
            }

            $banner->save();
            return redirect('admin/banners')->with('success_message', $message);
        }

        return view('admin.banners.add_edit_banner')->with(compact('title', 'banner'));
    }
}
