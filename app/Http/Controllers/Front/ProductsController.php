<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\ProductsFilter;
use App\Models\Vendor;
use Session;
use DB;

class ProductsController extends Controller
{
    public function listing(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
              // echo "test"; die;
                // echo $url = Route::getFacadeRoot()->current()->uri(); die;
                // $url = Route::getFacadeRoot()->current()->uri();
                $url = $data['url'];
                $_GET['sort'] = $data['sort'];
                $categoryCount = Category::where(['url' => $url, 'status' => 1])->count();
                if($categoryCount > 0){
                    // Get Category Details 
                    $categoryDetails = Category::categoryDetails($url);
                    // dd($categoryDetails); die;
                    // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->get()->toArray();
                    $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1);

                    // Checking for Dynamic Filters
                    $productFilters = ProductsFilter::productFilters();
                    foreach ($productFilters as $key => $filter) {
                        // If Filter is selected 
                        if(isset($filter['filter_column']) && isset($data[$filter['filter_column']]) && !empty($filter['filter_column']) && !empty($data[$filter['filter_column']])){
                            $categoryProducts->whereIn($filter['filter_column'], $data[$filter['filter_column']]); 
                        }
                    } 
                    
                    // if(isset($data['fabric']) && !empty($data['fabric'])){
                    //     $categoryProducts->whereIn('products.fabric', $data['fabric']);
                    // }

                    // checking for sort s
                    if(isset($_GET['sort']) && !empty($_GET['sort'])){
                        if($_GET['sort'] == "product_latest"){
                            $categoryProducts->orderBy('products.id', 'Desc');
                        }else if($_GET['sort'] == "price_lowest"){
                            $categoryProducts->orderBy('products.product_price', 'Asc');
                        }else if($_GET['sort'] == "price_highest"){
                            $categoryProducts->orderBy('products.product_price', 'Desc');
                        }else if($_GET['sort'] == "name_z_a"){
                            $categoryProducts->orderBy('products.product_name', 'Desc');
                        }else if($_GET['sort'] == "name_a_z"){
                            $categoryProducts->orderBy('products.product_name', 'Asc');
                        }
                    }

                    // Checking for size 
                    if(isset($data['size']) && !empty($data['size'])){
                        $productIds = ProductsAttribute::select('product_id')->whereIn('size', $data['size'])->pluck('product_id')->toArray();
                        $categoryProducts->whereIn('products.id', $productIds); 
                    }

                    // Checking for Color 
                    if(isset($data['color']) && !empty($data['color'])){
                        $productIds = Product::select('id')->whereIn('product_color', $data['color'])->pluck('id')->toArray();
                        $categoryProducts->whereIn('products.id', $productIds); 
                    }

                    // Checking for size 
                    if(isset($data['brand']) && !empty($data['brand'])){
                        $productIds = Product::select('id')->whereIn('brand_id', $data['brand'])->pluck('id')->toArray();
                        $categoryProducts->whereIn('products.id', $productIds); 
                    }

                    // checking for Price 
                    if(isset($data['price']) && !empty($data['price'])){

                        //////////////////////// First Method  But it is displaying other products outside range of the price  ///////////////
                        // echo "<pre>"; print_r($data['price']); die;
                        // echo $implodePrices = implode('-', $data['price']); 
                                // $implodePrices = implode('-', $data['price']); 
                                // $explodePrices = explode('-', $implodePrices); 
                                // // echo "<pre>"; print_r($explodePrices); die;
                                // $min = reset($explodePrices); 
                                // $max = end($explodePrices); 
                                // $productIds = Product::select('id')->whereBetween('product_price', [$min, $max])->pluck('id')->toArray(); 
                                // $categoryProducts->whereIn('products.id', $productIds);
                        ////////////////////// End of First Method ////////////////////////////////
                        foreach ($data['price'] as $key => $price){
                            $priceArr = explode("-", $price);
                            $productIds[] = Product::select('id')->whereBetween('product_price', [$priceArr[0], $priceArr[1]])->pluck('id')->toArray(); 
                        }
                        // echo "<pre>"; print_r($productIds); die;
                        $productIds = call_user_func_array('array_merge', $productIds); 
                        // echo "<pre>"; print_r($productIds); die;
                        $categoryProducts->whereIn('products.id', $productIds); 
                    }

                    $categoryProducts = $categoryProducts->Paginate(30);
                    // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->simplePaginate(3);
                    // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->cursorPaginate(3);
                    // dd($categoryProducts); die;
                    // dd($categoryDetails); die;
                    // echo "Category Exists"; die;
                    return view('front.products.ajax_products_listing')->with(compact('categoryDetails', 'categoryProducts', 'url'));
                }else{
                    abort(404);
                }
        }else{
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

                // checking for sort s
                if(isset($_GET['sort']) && !empty($_GET['sort'])){
                    if($_GET['sort'] == "product_latest"){
                        $categoryProducts->orderBy('products.id', 'Desc');
                    }else if($_GET['sort'] == "price_lowest"){
                        $categoryProducts->orderBy('products.product_price', 'Asc');
                    }else if($_GET['sort'] == "price_highest"){
                        $categoryProducts->orderBy('products.product_price', 'Desc');
                    }else if($_GET['sort'] == "name_z_a"){
                        $categoryProducts->orderBy('products.product_name', 'Desc');
                    }else if($_GET['sort'] == "name_a_z"){
                        $categoryProducts->orderBy('products.product_name', 'Asc');
                    }
                }

                $categoryProducts = $categoryProducts->Paginate(3);
                // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->simplePaginate(3);
                // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->cursorPaginate(3);
                // dd($categoryProducts); die;
                // dd($categoryDetails); die;
                // echo "Category Exists"; die;
                return view('front.products.listing')->with(compact('categoryDetails', 'categoryProducts', 'url'));
            }else{
                abort(404);
            }
        }
        
        // // echo "test"; die;
        // // echo $url = Route::getFacadeRoot()->current()->uri(); die;
        // $url = Route::getFacadeRoot()->current()->uri();
        // $categoryCount = Category::where(['url' => $url, 'status' => 1])->count();
        // if($categoryCount > 0){
        //     // Get Category Details 
        //     $categoryDetails = Category::categoryDetails($url);
        //     // dd($categoryDetails); die;
        //     // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->get()->toArray();
        //     $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1);

        //     // checking for sort s
        //     if(isset($_GET['sort']) && !empty($_GET['sort'])){
        //         if($_GET['sort'] == "product_latest"){
        //             $categoryProducts->orderBy('products.id', 'Desc');
        //         }else if($_GET['sort'] == "price_lowest"){
        //             $categoryProducts->orderBy('products.product_price', 'Asc');
        //         }else if($_GET['sort'] == "price_highest"){
        //             $categoryProducts->orderBy('products.product_price', 'Desc');
        //         }else if($_GET['sort'] == "name_z_a"){
        //             $categoryProducts->orderBy('products.product_name', 'Desc');
        //         }else if($_GET['sort'] == "name_a_z"){
        //             $categoryProducts->orderBy('products.product_name', 'Asc');
        //         }
        //     }

        //     $categoryProducts = $categoryProducts->Paginate(3);
        //     // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->simplePaginate(3);
        //     // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->cursorPaginate(3);
        //     // dd($categoryProducts); die;
        //     // dd($categoryDetails); die;
        //     // echo "Category Exists"; die;
        //     return view('front.products.listing')->with(compact('categoryDetails', 'categoryProducts', 'url'));
        // }else{
        //     abort(404);
        // }
    }

    public function vendorListing($vendorid){
        // Get Vendor shopname
        // dd("Hello"); die; 
        // echo $getVendorShop = Vendor::getVendorShop($vendorid); die;
        $getVendorShop = Vendor::getVendorShop($vendorid);
        // Get Vendor Products 
        $vendorProducts = Product::with('brand')->where('vendor_id', $vendorid)->where('status', 1);
        $vendorProducts = $vendorProducts->paginate(30);
        // dd($vendorProducts); die; 
        return view('front.products.vendor_listing')->with(compact('getVendorShop', 'vendorProducts'));
    }
    
    public function detail($id){
        $productDetails = Product::with(['section','category', 'brand', 'attributes' => function($query){
            $query->where('stock', '>', 0)->where('status', 1);
        }, 'images', 'vendor'])->find($id)->toArray();
        // dd($productDetails); die;
        // Displaying the breadcrumb
        $categoryDetails = Category::categoryDetails($productDetails['category']['url']);
        // dd($categoryDetails); die;
        
        // Similar Products 
        $similarProducts = Product::with('brand')->where('category_id', $productDetails['category']['id'])->where('id', '!=', $id)->limit(4)->inRandomOrder()->get()->toArray();
        // dd($similarProducts); die;
        
        // Set Session for Recently Viewed Products 
        if(empty(Session::get('session_id'))){
            // echo $session_id = md5(uniqid(rand(), true)); die;
            $session_id = md5(uniqid(rand(), true)); 
        }else{
            $session_id = Session::get('session_id');
        }

        Session::put('session_id', $session_id);

        // Insert product in table if not already exists 
        $countRecentViewedProducts = DB::table('recently_viewed_products')->where(['product_id' => $id, 'session_id'=> $session_id])->count();
        
        if($countRecentViewedProducts == 0){
            DB::table('recently_viewed_products')->insert(['product_id' => $id, 'session_id' => $session_id]);
        }

        // Get Recently Viewed Products Ids 
        $recentProductsIds = DB::table('recently_viewed_products')->select('product_id')->where('product_id', '!=', $id)->where('session_id', $session_id)->inRandomOrder()->get()->take(4)->pluck('product_id');
        // dd($recentProductsIds); die; 
        
        // Get Recently Viewed Products 
        $recentlyViewedProducts = Product::with('brand')->whereIn('id', $recentProductsIds)->get()->toArray();
        // dd($recentlyViewedProducts); die;
        
        // Get Products (Prouduct Color)
        $groupProducts = array(); 
        if(!empty($productDetails['group_code'])){
            $groupProducts = Product::select('id', 'product_image')->where('id', '!=', $id)->where(['group_code' => $productDetails['group_code'], 'status' => 1])->get()->toArray();
            // dd($groupProducts); die;
        }

        // echo $totalStock = ProductsAttribute::where('product_id', $id)->sum('stock'); die;
        $totalStock = ProductsAttribute::where('product_id', $id)->sum('stock');

        return view('front.products.detail')->with(compact('productDetails', 'categoryDetails', 'totalStock', 'similarProducts', 'recentlyViewedProducts', 'groupProducts'));
    }

    public function getProductPrice(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $getDiscountAttributePrice = Product::getDiscountAttributePrice($data['product_id'], $data['size']);
            return $getDiscountAttributePrice;
        }
    }
}
