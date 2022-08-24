<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductsAttribute;
use App\Models\ProductsFilter;
use App\Models\ProductsImage;
use Auth;
use Session;
use Image;

class ProductsController extends Controller
{
    public function products(){
        Session::put('page', 'products');
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
        Session::put('page', 'products');
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

            $productsFilters = ProductsFilter::productFilters();
            foreach($productsFilters as $filter){
                // echo $data[$filter['filter_column']]; die;
                $filterAvailable = ProductsFilter::filterAvailable($filter['id'], $data['category_id']);
                if($filterAvailable == "Yes"){
                    if(isset($filter['filter_column']) && $data[$filter['filter_column']]){
                        $product->{$filter['filter_column']} = $data[$filter['filter_column']];
                    }
                }
            }

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

            if(empty($data['product_discount'])){
                $data['product_discount'] = 0;
            }

            if(empty($data['product_weight'])){
                $data['product_weight'] = 0;
            }
            
            $product->product_name =       $data['product_name'];
            $product->product_code =       $data['product_code'];
            $product->product_color =      $data['product_color'];
            $product->product_price =      $data['product_price'];
            $product->product_discount =   $data['product_discount'];
            $product->product_weight =     $data['product_weight'];
            $product->description =        $data['description'];
            $product->meta_title =         $data['meta_title'];
            $product->meta_description =   $data['meta_description'];
            $product->meta_keywords =      $data['meta_keywords'];

            // meta keywords 
            if(!empty($data['is_featured'])){
                $product->is_featured = $data['is_featured'];
            }else{
                $product->is_featured = "No";
            }

            if(!empty($data['is_bestseller'])){
                $product->is_bestseller = $data['is_bestseller'];
            }else{
                $product->is_bestseller = "No";
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
        Session::put('page', 'products');
        $product = Product::select('id','product_name', 'product_code', 'product_color', 'product_price', 'product_image')->with('attributes')->find($id);
        // dd($product);
        $product = json_decode(json_encode($product), true);

        // dd($product); die;
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            foreach ($data['sku'] as $key => $value) {
                if(!empty($value)){
                    // SKU duplicate check
                    $skuCount = ProductsAttribute::where('sku', $value)->count();
                    if($skuCount> 0){
                        return redirect()->back()->with('error_message', 'SKU already exists! Please add another SKU!');
                    }
                    //Size duplicate check 
                    $sizeCount = ProductsAttribute::where(['product_id'=> $id, 'size' => $data['size'][$key]])->count();
                    if($sizeCount > 0){
                        return redirect()->back()->with('error_message', 'Size already exists! Please add another Size'); 
                    }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $value;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->status = 1;
                    $attribute->save();
                }   
            }

            return redirect()->back()->with('success_message', 'Products Attributes has been added Successfully');
        }
        return view('admin.attributes.add_edit_attributes')->with(compact('product'));
    }

    public function updateAttributeStatus(Request $request){
        if($request->ajax()){
            $data= $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status'] == "Active"){
                $status = 0;
            }else{
                $status = 1;
            }

            ProductsAttribute::where('id', $data['attribute_id'])->update(['status'=> $status]);
            return response()->json(['status' =>$status, 'attribute_id' => $data['attribute_id']]);
        }
    }

    public function editAttributes(Request $request){
        // Session::put('page', 'products');
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            foreach ($data['attributeId'] as $key => $attribute) {
                if(!empty($attribute)){
                    ProductsAttribute::where(['id' => $data['attributeId'][$key]])->update(['price' => $data['price'][$key], 'stock' => $data['stock'][$key]]);
                }
            }
            return redirect()->back()->with('success_message', "Product Attributes has been updated successfully!");
        }
    }

    public function addImages($id, Request $request){
        Session::put('page', 'products');
        $product = Product::select('id','product_name', 'product_code', 'product_color', 'product_price', 'product_image')->with('images')->find($id);
        // echo "<pre>"; print_r($product); die;
        // dd($product);die;
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            // dd($data); die;
            if($request->hasFile('images')){
                $images = $request->file('images');
                // echo "<pre>"; print_r($images); die;
                foreach ($images as $key => $image) {
                    // Generate Temp Image
                    $image_tmp = Image::make($image); 
                    // echo $image_tmp = $image->getClientOriginalName(); die;
                    $image_name = $image->getClientOriginalName();
                    // Get Image Extension
                    $extension = $image->getClientOriginalExtension();
                    // Generate new image name 
                    $imageName = $image_name.rand(111, 999999).'.'.$extension; 
                    // echo $imagePath = 'admin/images/photos/'.$imageName; die;
                    $largeImagePath = 'admin/images/product_images/large/'.$imageName;
                    $mediumImagePath = 'admin/images/product_images/medium/'.$imageName;
                    $smallImagePath = 'admin/images/product_images/small/'.$imageName;
                    // Upload the Large Image, Medium and Small Images and Resize
                    Image::make($image_tmp)->resize(1000, 1000)->save($largeImagePath);
                    Image::make($image_tmp)->resize(500, 500)->save($mediumImagePath);
                    Image::make($image_tmp)->resize(250, 250)->save($smallImagePath);
                    // Insert Image Name in products table

                    $image = new ProductsImage;
                    $image->image = $imageName;
                    $image->product_id = $id;
                    $image->status = 1;
                    $image->save();
                }
            }

            return redirect()->back()->with('success_message', 'Product Images has been updated successfully!');
        }
        return view('admin.images.add_images')->with(compact('product'));

    }

    public function updateImageStatus(Request $request){
        if($request->ajax()){
            $data= $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status'] == "Active"){
                $status = 0;
            }else{
                $status = 1;
            }

            ProductsImage::where('id', $data['image_id'])->update(['status'=> $status]);
            return response()->json(['status' =>$status, 'image_id' => $data['image_id']]);
        }
    }

    public function deleteImage($id){
        // Get product image
        $productImage = ProductsImage::select('image')->where('id', $id)->first();

        // Get Product Image Paths 
        $small_image_path = 'admin/images/product_images/small/';
        $medium_image_path = 'admin/images/product_images/medium/';
        $large_image_path = 'admin/images/product_images/large/';

        // Delete Product small image if exists  in small folder
        if(file_exists($small_image_path.$productImage->image)){
            unlink($small_image_path.$productImage->image);
        }

        // Delete Product medium image if exists in medium folder
        if(file_exists($medium_image_path.$productImage->image)){
            unlink($medium_image_path.$productImage->image);
        }

        // Delete Product large image if exists in large folder
        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }

        // Delete Product image from products images table
        ProductsImage::where('id', $id)->delete();
        $message = "Product Image been deleted Successfully";
        return redirect()->back()->with('success_message', $message);        
    }

    
}
 