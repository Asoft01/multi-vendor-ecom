<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;
use App\Models\Brand;
use App\Models\Category;
use Auth;
use Image;

class ProductsController extends Controller
{
    public function products(){
        $products = Product::with(['section' => function($query){
            $query->select('id', 'name');
        }, 'category' => function($query){
            $query->select('id', 'category_name');
        }])->get()->toArray();
        // dd($products);
        
        return view('admin.products.products')->with(compact('products'));
    }

    public function updateProductStatus(Request $request){
        if($request->ajax()){
            $data= $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status'] == "Active"){
                $status = 0;
            }else{
                $status = 1;
            }

            Product::where('id', $data['product_id'])->update(['status'=> $status]);
            return response()->json(['status' =>$status, 'product_id' => $data['product_id']]);
        }
    }

    public function deleteProduct($id){
        // Delete Category
        Product::where('id', $id)->delete();
        $message = "Product has been deleted successfully";
        return redirect()->back()->with('success_message', $message);
    }

    public function addEditProduct(Request $request, $id = null){
        if($id== ""){
            $title = "Add Product";
            $product = new Product;
            $message = "Product added successfully";
        }else{
            $title = "Edit Product";
            $product = Product::find($id);
            // dd($product); die;
            // echo "<pre>"; print_r($product); die;
            $message = "Product updated successfully";
        }

        if($request->isMethod('post')){
            $data = $request->all();
            // dd($data); die;
            // echo "<pre>"; print_r($data); die;

            // echo "<pre>"; print_r(Auth::guard('admin')->user()); die;

            $rules = [
                'category_id' => 'required',
                'product_name'=> 'required|regex:/^[\pL\s\-]+$/u',
                'product_code'=> 'required|regex:/^\w+$/',
                'product_price'=> 'required|numeric',
                'product_color'=> 'required|regex:/^[\pL\s\-]+$/u',
            ];

            // $this->validate($request, $rules);

            $customMessages= [
                'category_id.required' => 'Category is required',
                'product_name.required' => 'Product Name is required',
                'product_name.regex' => 'Valid Product Name is required',
                'product_code.required' => 'Product Code is required',
                'product_code.regex' => 'Valid Product Code is required',
                'product_price.required' => 'Product Price is required',
                'product_price.numeric' => 'Valid Product Price is required', 
                'product_color.required' => 'Product Color is required',
                'product_color.regex' => 'Valid Product Color is required', 
            ];
            
            $this->validate($request, $rules, $customMessages);

            // Upload Product Image After Resize online 
            // Small : 250 x 250  // Medium : 500 x 500 // Large : 1000 x 1000
            if($request->hasFile('product_image')){
                $image_tmp = $request->file('product_image');
                if($image_tmp->isValid()){
                    // Get Image Extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    // Generate new image name 
                    $imageName = rand(111, 999999).'.'.$extension; 
                    // echo $imagePath = 'admin/images/photos/'.$imageName; die;
                    $largeImagePath = 'admin/images/product_images/large/'.$imageName;
                    $mediumImagePath = 'admin/images/product_images/medium/'.$imageName;
                    $smallImagePath = 'admin/images/product_images/small/'.$imageName;
                    // Upload the Large Image, Medium and Small Images and Resize
                    Image::make($image_tmp)->resize(1000, 1000)->save($largeImagePath);
                    Image::make($image_tmp)->resize(500, 500)->save($mediumImagePath);
                    Image::make($image_tmp)->resize(250, 250)->save($smallImagePath);
                    
                    // Insert Image Name in products table
                    $product->product_image = $imageName;
                }
            }

            // Upload Product Video 
            if($request->hasFile('product_video')){
                $video_tmp = $request->file('product_video');
                if($video_tmp->isValid()){
                    // Upload Video in Video Folder
                    $extension = $video_tmp->getClientOriginalExtension();
                    $videoName= rand(111, 99999).'.'.$extension;
                    $videoPath = 'admin/videos/product_videos/';
                    $video_tmp->move($videoPath,$videoName);
                    // Insert Video Name in Products Table;
                    $product->product_video = $videoName;
                }
            }
            // Save Product details in products table 
            $categoryDetails =       Category::find($data['category_id']);
            $product->section_id  =  $categoryDetails['section_id'];
            $product->category_id  = $data['category_id'];
            $product->brand_id  =    $data['brand_id'];
    
            $adminType = Auth::guard('admin')->user()->type;
            $vendor_id = Auth::guard('admin')->user()->vendor_id;
            $admin_id =  Auth::guard('admin')->user()->id;

            $product->admin_type = $adminType;
            $product->admin_id = $admin_id;

            if($adminType == "vendor"){
                $product->vendor_id = $vendor_id;
            }else{  
                $product->vendor_id = 0;
            }

            $product->product_name =       $data['product_name'];
            $product->product_code =       $data['product_code'];
            $product->product_color =      $data['product_color'];
            $product->product_price =      $data['product_price'];
            $product->product_discount =   $data['product_discount'];
            $product->product_weight =     $data['product_name'];
            $product->description =        $data['description'];
            $product->meta_title =         $data['meta_title'];
            $product->meta_description =   $data['meta_description'];
            $product->meta_keywords =      $data['meta_keywords'];

            if(!empty($data['is_featured'])){
                $product->is_featured = $data['is_featured'];
            }else{
                $product->is_featured = "No";
            }
            $product->status = 1;
            $product->save();
            return redirect('admin/products')->with('success_message', $message);
        }
        

        // Get Sections with Categories and Sub Categories
        $categories = Section::with('categories')->get()->toArray();
        // dd($categories);die;
        // Get All Brands

        $brands = Brand::where('status', 1)->get()->toArray();

        return view('admin.products.add_edit_product')->with(compact('title', 'categories', 'brands', 'product'));
    }

    public function deleteProductImage($id){
        // Get product image
        $productImage = Product::select('product_image')->where('id', $id)->first();

        // Get Product Image Paths 
        $small_image_path = 'admin/images/product_images/small/';
        $medium_image_path = 'admin/images/product_images/medium/';
        $large_image_path = 'admin/images/product_images/large/';

        // Delete Product small image if exists  in small folder
        if(file_exists($small_image_path.$productImage->product_image)){
            unlink($small_image_path.$productImage->product_image);
        }

        // Delete Product medium image if exists in medium folder
        if(file_exists($medium_image_path.$productImage->product_image)){
            unlink($medium_image_path.$productImage->product_image);
        }

        // Delete Product large image if exists in large folder
        if(file_exists($large_image_path.$productImage->product_image)){
            unlink($large_image_path.$productImage->product_image);
        }

        // Delete Product image from 
        Product::where('id', $id)->update(['product_image' => '']);
        $message = "Product Image been deleted Successfully";
        return redirect()->back()->with('success_message', $message);        
    }

    public function deleteProductVideo($id){
        // Get Product Video
        $productVideo = Product::select('product_video')->where('id', $id)->first();

        // Get Product Video Path
        $product_video_path = 'admin/videos/product_videos/';

        // Delete Product Video from Product_Videos folder if exists 
        if(file_exists($product_video_path.$productVideo->product_video)){
            unlink($product_video_path.$productVideo->product_video);
        }

        // Delete Product Video Image from products table 
        Product::where('id', $id)->update(['product_video' => '']);

        $message = "Product Video has been deleted Successfully";
        return redirect()->back()->with('success_message', $message);
    }

    public function addAttributes(Request $request,  $id){
        $product = Product::find($id);
        // dd($product);
        if($request->isMethod('post')){
            $data = $request->all();
            echo "<pre>"; print_r($data); die;
        }
        return view('admin.attributes.add_edit_attributes')->with(compact('product'));
    }
}
