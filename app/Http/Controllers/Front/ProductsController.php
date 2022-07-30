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
            $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1);

            // checking for Sort
            if(isset($_GET['sort']) && !empty($_GET['sort'])){
                if($_GET['sort'] == "product_latest"){
                    $categoryProducts->orderBy('products.id', 'Desc');
                }else if($_GET['sort'] == "price_lowest"){
                    $categoryProducts->orderBy('products.product_price', 'Asc');
                }else if($_GET['sort'] == "price_highest"){
                    $categoryProducts->orderBy('products.product_price', 'Desc');
                }
            }

            $categoryProducts = $categoryProducts->Paginate(30);
            // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->simplePaginate(3);
            // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->cursorPaginate(3);
            // dd($categoryProducts); die;
            // dd($categoryDetails); die;
            // echo "Category Exists"; die;
            return view('front.products.listing')->with(compact('categoryDetails', 'categoryProducts'));
        }else{
            abort(404);
        }
    }
}
