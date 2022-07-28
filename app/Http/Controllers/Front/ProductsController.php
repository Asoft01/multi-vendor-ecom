<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductsController extends Controller
{
    public function listing(){
        // echo "test"; die;
        // echo $url = Route::getFacadeRoot()->current()->uri(); die;
        $url = Route::getFacadeRoot()->current()->uri();
        $categoryCount = Category::where(['url' => $url, 'status' => 1])->count();
        if($categoryCount > 0){
            // Get Category Details 
            $categoryDetails = Category::categoryDetails($url);
            // dd($categoryDetails); die;
            // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->get()->toArray();
            $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->Paginate(3);
            // dd($categoryProducts); die;
            // dd($categoryDetails); die;
            // echo "Category Exists"; die;
            return view('front.products.listing')->with(compact('categoryDetails', 'categoryProducts'));
        }else{
            abort(404);
        }
    }
}
