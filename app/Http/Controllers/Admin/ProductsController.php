<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;

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
        }else{
            $title = "Edit Product";
        }

        // Get Sections with Categories and Sub Categories
        $categories = Section::with('categories')->get()->toArray();
        // dd($categories);die;
        return view('admin.products.add_edit_product')->with(compact('title', 'categories'));
    }
}
