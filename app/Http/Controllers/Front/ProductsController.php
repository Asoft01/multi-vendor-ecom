<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\OrdersProduct;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\ProductsFilter;
use App\Models\Sms;
use App\Models\User;
use App\Models\Vendor;
// use Session;
// use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ProductsController extends Controller
{
    public function listing(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            // echo "test"; die;
            // echo $url = Route::getFacadeRoot()->current()->uri(); die;
            // $url = Route::getFacadeRoot()->current()->uri();
            $url = $data['url'];
            $_GET['sort'] = $data['sort'];
            $categoryCount = Category::where(['url' => $url, 'status' => 1])->count();
            if ($categoryCount > 0) {
                // Get Category Details 
                $categoryDetails = Category::categoryDetails($url);
                // dd($categoryDetails); die;
                // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->get()->toArray();
                $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1);

                // Checking for Dynamic Filters
                $productFilters = ProductsFilter::productFilters();
                foreach ($productFilters as $key => $filter) {
                    // If Filter is selected 
                    if (isset($filter['filter_column']) && isset($data[$filter['filter_column']]) && !empty($filter['filter_column']) && !empty($data[$filter['filter_column']])) {
                        $categoryProducts->whereIn($filter['filter_column'], $data[$filter['filter_column']]);
                    }
                }

                // if(isset($data['fabric']) && !empty($data['fabric'])){
                //     $categoryProducts->whereIn('products.fabric', $data['fabric']);
                // }

                // checking for sort s
                if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                    if ($_GET['sort'] == "product_latest") {
                        $categoryProducts->orderBy('products.id', 'Desc');
                    } else if ($_GET['sort'] == "price_lowest") {
                        $categoryProducts->orderBy('products.product_price', 'Asc');
                    } else if ($_GET['sort'] == "price_highest") {
                        $categoryProducts->orderBy('products.product_price', 'Desc');
                    } else if ($_GET['sort'] == "name_z_a") {
                        $categoryProducts->orderBy('products.product_name', 'Desc');
                    } else if ($_GET['sort'] == "name_a_z") {
                        $categoryProducts->orderBy('products.product_name', 'Asc');
                    }
                }

                // Checking for size 
                if (isset($data['size']) && !empty($data['size'])) {
                    $productIds = ProductsAttribute::select('product_id')->whereIn('size', $data['size'])->pluck('product_id')->toArray();
                    $categoryProducts->whereIn('products.id', $productIds);
                }

                // Checking for Color 
                if (isset($data['color']) && !empty($data['color'])) {
                    $productIds = Product::select('id')->whereIn('product_color', $data['color'])->pluck('id')->toArray();
                    $categoryProducts->whereIn('products.id', $productIds);
                }

                // Checking for brands 
                if (isset($data['brand']) && !empty($data['brand'])) {
                    $productIds = Product::select('id')->whereIn('brand_id', $data['brand'])->pluck('id')->toArray();
                    $categoryProducts->whereIn('products.id', $productIds);
                }

                // checking for Price 
                // if (isset($data['price']) && !empty($data['price'])) {

                //     //////////////////////// First Method  But it is displaying other products outside range of the price  ///////////////
                //     // echo "<pre>"; print_r($data['price']); die;
                //     // echo $implodePrices = implode('-', $data['price']); 
                //     // $implodePrices = implode('-', $data['price']); 
                //     // $explodePrices = explode('-', $implodePrices); 
                //     // // echo "<pre>"; print_r($explodePrices); die;
                //     // $min = reset($explodePrices); 
                //     // $max = end($explodePrices); 
                //     // $productIds = Product::select('id')->whereBetween('product_price', [$min, $max])->pluck('id')->toArray(); 
                //     // $categoryProducts->whereIn('products.id', $productIds);
                //     ////////////////////// End of First Method ////////////////////////////////
                //     foreach ($data['price'] as $key => $price) {
                //         $priceArr = explode("-", $price);
                //         $productIds[] = Product::select('id')->whereBetween('product_price', [$priceArr[0], $priceArr[1]])->pluck('id')->toArray();
                //     }
                //     // echo "<pre>"; print_r($productIds); die;
                //     $productIds = call_user_func_array('array_merge', $productIds);
                //     // echo "<pre>"; print_r($productIds); die;
                //     $categoryProducts->whereIn('products.id', $productIds);
                // }

                // Checking for price
                $productIds = array();
                if (isset($data['price']) && !empty($data['price'])) {

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
                    foreach ($data['price'] as $key => $price) {
                        $priceArr = explode("-", $price);
                        if (isset($priceArr[0]) && isset($priceArr[1])) {
                            $productIds[] = Product::select('id')->whereBetween('product_price', [$priceArr[0], $priceArr[1]])->pluck('id')->toArray();
                        }
                    }
                    // echo "<pre>"; print_r($productIds); die;
                    $productIds = array_unique(array_flatten($productIds));
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
            } else {
                abort(404);
            }
        } else {
            // echo "test"; die;
            // echo $url = Route::getFacadeRoot()->current()->uri(); die;
            $url = Route::getFacadeRoot()->current()->uri();
            $categoryCount = Category::where(['url' => $url, 'status' => 1])->count();
            if ($categoryCount > 0) {
                // Get Category Details 
                $categoryDetails = Category::categoryDetails($url);
                // dd($categoryDetails); die;
                // $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1)->get()->toArray();
                $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1);

                // checking for sort s
                if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                    if ($_GET['sort'] == "product_latest") {
                        $categoryProducts->orderBy('products.id', 'Desc');
                    } else if ($_GET['sort'] == "price_lowest") {
                        $categoryProducts->orderBy('products.product_price', 'Asc');
                    } else if ($_GET['sort'] == "price_highest") {
                        $categoryProducts->orderBy('products.product_price', 'Desc');
                    } else if ($_GET['sort'] == "name_z_a") {
                        $categoryProducts->orderBy('products.product_name', 'Desc');
                    } else if ($_GET['sort'] == "name_a_z") {
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
            } else {
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

    public function vendorListing($vendorid)
    {
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

    public function detail($id)
    {
        $productDetails = Product::with(['section', 'category', 'brand', 'attributes' => function ($query) {
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
        if (empty(Session::get('session_id'))) {
            // echo $session_id = md5(uniqid(rand(), true)); die;
            $session_id = md5(uniqid(rand(), true));
        } else {
            $session_id = Session::get('session_id');
        }

        Session::put('session_id', $session_id);

        // Insert product in table if not already exists 
        $countRecentViewedProducts = DB::table('recently_viewed_products')->where(['product_id' => $id, 'session_id' => $session_id])->count();

        if ($countRecentViewedProducts == 0) {
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
        if (!empty($productDetails['group_code'])) {
            $groupProducts = Product::select('id', 'product_image')->where('id', '!=', $id)->where(['group_code' => $productDetails['group_code'], 'status' => 1])->get()->toArray();
            // dd($groupProducts); die;
        }

        // echo $totalStock = ProductsAttribute::where('product_id', $id)->sum('stock'); die;
        $totalStock = ProductsAttribute::where('product_id', $id)->sum('stock');

        return view('front.products.detail')->with(compact('productDetails', 'categoryDetails', 'totalStock', 'similarProducts', 'recentlyViewedProducts', 'groupProducts'));
    }

    public function getProductPrice(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $getDiscountAttributePrice = Product::getDiscountAttributePrice($data['product_id'], $data['size']);
            return $getDiscountAttributePrice;
        }
    }

    public function cartAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // Check Product Stock is Available or Not 
            // echo $getProductStock = ProductsAttribute::getProductStock($data['product_id'], $data['size']); die;
            $getProductStock = ProductsAttribute::getProductStock($data['product_id'], $data['size']);
            if ($getProductStock < $data['quantity']) {
                return redirect()->back()->with('error_message', 'Required Quantity is not available');
            }

            // Generate the session id if already exists
            $session_id = Session::get('session_id');
            if (empty($session_id)) {
                $session_id = Session::getId();
                Session::put('session_id', $session_id);
            }

            // Check Products if already exists in the User cart 
            if (Auth::check()) {
                // User is logged in 
                $user_id = Auth::user()->id;
                $countProducts = Cart::where(['product_id' => $data['product_id'], 'size' => $data['size'], 'user_id' => $user_id])->count();
            } else {
                // User is not logged in
                $user_id = 0;
                $countProducts = Cart::where(['product_id' => $data['product_id'], 'size' => $data['size'], 'session_id' => $session_id])->count();
            }

            if ($countProducts > 0) {
                return redirect()->back()->with('error_message', 'Product Already exists in cart');
            }

            // Save Products in Carts table 
            $item = new Cart;
            $item->session_id = $session_id;
            $item->user_id = $user_id;
            $item->product_id = $data['product_id'];
            $item->size = $data['size'];
            $item->quantity = $data['quantity'];
            $item->save();
            return redirect()->back()->with('success_message', 'Product has been added in Cart! <a href="/cart" style="text-decoration: underline !importance"> View Cart </a>');
        }
    }

    public function cart()
    {
        $getCartItems = Cart::getCartItems();
        // dd($getCartItems); die;
        return view('front.products.cart')->with(compact('getCartItems'));
    }

    public function cartUpdate(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // Get Cart Details 
            $cartDetails = Cart::find($data['cartid']);

            // Get Available Product Stock 
            $availableStock = ProductsAttribute::select('stock')->where(['product_id' => $cartDetails['product_id'], 'size' => $cartDetails['size']])->first()->toArray();

            // echo "<pre>"; print_r($availableStock); die;

            // Check if desired Stock from user is available 
            if ($data['qty'] > $availableStock['stock']) {
                $getCartItems = Cart::getCartItems();
                return response()->json([
                    'status' => false,
                    'message' => 'Product Stock is not available',
                    'view' => (string)View::make('front.products.cart_items')->with(compact('getCartItems')),
                    'headerview' => (string)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
                ]);
            }

            // Check if product size is available
            $availableSize = ProductsAttribute::where(['product_id' => $cartDetails['product_id'], 'size' => $cartDetails['size'], 'status' => 1])->count();
            if ($availableSize == 0) {
                $getCartItems = Cart::getCartItems();
                return response()->json([
                    'status' => false,
                    'message' => 'Product Size is not available. Please remove this Product and choose another one!',
                    'view' => (string)View::make('front.products.cart_items')->with(compact('getCartItems')),
                    'headerview' => (string)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
                ]);
            }

            // Update the Quantity
            Cart::where('id', $data['cartid'])->update(['quantity' => $data['qty']]);
            $getCartItems = Cart::getCartItems();
            $totalCartItems = totalCartItems();
            Session::forget('couponAmount');
            Session::forget('couponCode');
            return response()->json([
                'status' => true,
                'totalCartItems' => $totalCartItems,
                'view' => (string)View::make('front.products.cart_items')->with(compact('getCartItems')),
                'headerview' => (string)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
            ]);
        }
    }

    public function cartDelete(Request $request)
    {
        if ($request->ajax()) {
            Session::forget('couponAmount');
            Session::forget('couponCode');
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            Cart::where('id', $data['cartid'])->delete();
            $getCartItems = Cart::getCartItems();
            $totalCartItems = totalCartItems();
            return response()->json([
                'totalCartItems' => $totalCartItems,
                'view' => (string)View::make('front.products.cart_items')->with(compact('getCartItems')),
                'headerview' => (string)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
            ]);
        }
    }

    public function applyCoupon(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            Session::forget('couponAmount');
            Session::forget('couponCode');
            // echo "<pre>"; print_r($data); die;
            $getCartItems = Cart::getCartItems();
            // echo "<pre>"; print_r($getCartItems); die;
            $totalCartItems = totalCartItems();
            $couponCount = Coupon::where('coupon_code', $data['code'])->count();
            if ($couponCount == 0) {
                return response()->json([
                    'status' => false,
                    'totalCartItems' => $totalCartItems,
                    'message' => 'The coupon is not valid!',
                    'view' => (string)View::make('front.products.cart_items')->with(compact('getCartItems')),
                    'headerview' => (string)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
                ]);
            } else {
                // echo "Check for other coupon conditions"; die; 

                // Get Coupon Details 
                $couponDetails = Coupon::where('coupon_code', $data['code'])->first();
                //Check If Coupon is active
                if ($couponDetails->status == 0) {
                    $message = "The coupon is not active!";
                }

                // Minor Fix 
                // Check if coupon is expired 
                $expiry_date = $couponDetails->expiry_date;
                $current_date = date('Y-m-d');
                if ($expiry_date < $current_date) {
                    $message = "The coupon is expired!";
                }

                // Check if coupon is for single time 
                if($couponDetails->coupon_type == "Single Time"){
                    // check in orders table if the coupon is already availed by the user
                    $couponCount = Order::where(['coupon_code' => $data['code'], 'user_id' => Auth::user()->id])->count();
                    if($couponCount >=1){
                        $message = "This Coupon Code is already availed by you";
                    }
                }else if ($couponDetails->coupon_type == "Multiple Times"){
                    $couponDetails = Order::where(['coupon_code' => $data['code'], 'user_id' => Auth::user()->id])->count(); 
                    if($couponCount >=1){
                        // uhk
                        $message = "This Coupon Code is already availed by you";
                    }
                }

                // Check if coupon is from selected categories 

                // Get all selected categories from coupon and convert to array 
                $catArr = explode(",", $couponDetails->categories);

                // Check if any cart item not belong to coupon category
                $total_amount = 0;
                // dd($getCartItems); die;
                foreach ($getCartItems as $key => $item) {
                    if (!in_array($item['product']['category_id'], $catArr)) {
                        $message = "This coupon is not for one of the selected product!";
                    }
                    $attrPrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                    // echo "<pre>"; print_r($attrPrice); die;
                    $total_amount = $total_amount + ($attrPrice['final_price'] * $item['quantity']);
                }

                // Check if coupon is from selected userss
                // Get all selected users from coupon and convert to array 
                // dd($couponDetails->users); die;
                if (isset($couponDetails->users) && !empty($couponDetails->users)) {
                    $usersArr = explode(",", $couponDetails->users);
                    // dd($usersArr); die;
                    if (count($usersArr)) {
                        // dd($usersArr); die;
                        // Get User Id's of all selected users
                        foreach ($usersArr as $key => $user) {
                            $getUserId = User::select('id')->where('email', $user)->first()->toArray();
                            $usersId[] = $getUserId['id'];
                        }

                        // Check if any cart item not belong to coupon user 
                        foreach ($getCartItems as $item) {
                            // if(count($usersArr) > 0){
                            if (!in_array($item['user_id'], $usersId)) {
                                $message = "This coupon code is not for you. Try with valid coupon";
                            }
                            // }
                        }
                    }
                }

                if ($couponDetails->vendor_id > 0) {
                    // echo $couponDetails->vendor_id;die;
                    $productIds = Product::select('id')->where('vendor_id', $couponDetails->vendor_id)->pluck('id')->toArray();
                    // echo "<pre>";print_r($productIds); die;
                    // Check if coupon belongs to Vendor Products 
                    // dd($getCartItems); die;
                    foreach ($getCartItems as $item) {
                        if (!in_array($item['product']['id'], $productIds)) {
                            $message = "The coupon code is not for you. Try with valid coupon code (vendor validation!)";
                        }
                    }
                }
                // If error message is there 
                if (isset($message)) {
                    return response()->json([
                        'status' => false,
                        'totalCartItems' => $totalCartItems,
                        'message' => $message,
                        'view' => (string)View::make('front.products.cart_items')->with(compact('getCartItems')),
                        'headerview' => (string)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
                    ]);
                } else {
                    // Coupon code is correct 

                    // Check if Coupon Amount type is Fixed or Percentage 
                    if ($couponDetails->amount_type == "Fixed") {
                        $couponAmount = $couponDetails->amount;
                    } else {
                        $couponAmount = $total_amount * ($couponDetails->amount / 100);
                    }

                    $grand_total = $total_amount - $couponAmount;
                    // Add Coupon Code and Amount in Session Variables 
                    Session::put('couponAmount', $couponAmount);
                    Session::put('couponCode', $data['code']);
                    $message = "Coupon Code Successfully applied. You are availing disount!";

                    return response()->json([
                        'status' => true,
                        'totalCartItems' => $totalCartItems,
                        'couponAmount' => $couponAmount,
                        'grand_total' => $grand_total,
                        'message' => $message,
                        'view' => (string)View::make('front.products.cart_items')->with(compact('getCartItems')),
                        'headerview' => (string)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))
                    ]);
                }
            }
        }
    }

    public function checkout(Request $request)
    {
        $deliveryAddresses = DeliveryAddress::deliveryAddresses();
        $countries = Country::where('status', 1)->get()->toArray();
        $getCartItems = Cart::getCartItems();
        // dd($countries); die;
        // dd($getCartItems); die;

        if (count($getCartItems) == 0) {
            $message = "Shopping Cart is empty! Please add products to checkout";
            return redirect('cart')->with('error_message', $message);
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // Delivery Address Validation
            if (empty($data['address_id'])) {
                $message = "Please select the delivery address";
                return redirect()->back()->with('error_message', $message);
            }

            // Payment Method Validation 
            if (empty($data['payment_gateway'])) {
                $message = "Please select the Payment Method!";
                return redirect()->back()->with('error_message', $message);
            }

            // Agree to T&C Validation
            if (empty($data['accept'])) {
                $message = "Please agree to T&C!";
                return redirect()->back()->with('error_message', $message);
            }

            // echo "<pre>"; print_r($data); die;

            // echo "ready to place order"; die;

            // Get Delivery Address from address_id 
            $deliveryAddress = DeliveryAddress::where('id', $data['address_id'])->first()->toArray();
            // dd($deliveryAddress); die;

            // Set Payment Method as COD if COD is selected from user otherwise set as Prepaid 
            if ($data['payment_gateway'] == "COD") {
                $payment_method = "COD";
                $order_status = "New";
            } else {
                $payment_method = "Prepaid";
                $order_status = "Pending";
            }

            DB::beginTransaction();

            // Fetch Order Total Price
            $total_price = 0;
            foreach ($getCartItems as $item) {
                $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                $total_price = $total_price + ($getDiscountAttributePrice['final_price'] * $item['quantity']);
            }
            // echo $total_price; die;

            // Calculate Shipping Charges 
            $shipping_charges = 0;

            // Calculate Grand Total 
            $grand_total = $total_price + $shipping_charges - Session::get('couponAmount');

            // Insert Grand Total in Session Variable 
            Session::put('grand_total', $grand_total);

            // Insert Order Details 
            $order = new Order();
            $order->user_id =          Auth::user()->id;
            $order->name =             $deliveryAddress['name'];
            $order->address =          $deliveryAddress['address'];
            $order->city =             $deliveryAddress['city'];
            $order->state =            $deliveryAddress['state'];
            $order->country =          $deliveryAddress['country'];
            $order->pincode =          $deliveryAddress['pincode'];
            $order->mobile =           $deliveryAddress['mobile'];
            $order->email =            Auth::user()->email;
            $order->shipping_charges = $shipping_charges;
            $order->coupon_code  =     Session::get('couponCode');
            $order->coupon_amount =    Session::get('couponAmount');
            $order->order_status =     $order_status;
            $order->payment_method =   $payment_method;
            $order->payment_gateway =  $data['payment_gateway'];
            $order->grand_total =      $grand_total;
            $order->save();
            $order_id = DB::getPdo()->lastInsertId();

            foreach ($getCartItems as $item) {
                $cartItem = new OrdersProduct();
                $cartItem->order_id = $order_id;
                $cartItem->user_id = Auth::user()->id;
                $getProductDetails = Product::select('product_code', 'product_name', 'product_color', 'admin_id', 'vendor_id')->where('id', $item['product_id'])->first()->toArray();
                // dd($getProductDetails); die;
                $cartItem->admin_id =     $getProductDetails['admin_id'];
                $cartItem->vendor_id =    $getProductDetails['vendor_id'];
                $cartItem->product_id =   $item['product_id'];
                $cartItem->product_code = $getProductDetails['product_code'];
                $cartItem->product_name = $getProductDetails['product_name'];
                $cartItem->product_color = $getProductDetails['product_color'];
                $cartItem->product_size = $item['size'];
                $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                $cartItem->product_price = $getDiscountAttributePrice['final_price'];
                $cartItem->product_qty =    $item['quantity'];
                $cartItem->save();
            }

            // Insert order ID in Session Variable 
            Session::put('order_id', $order_id);

            DB::commit();
            // echo "order successfully placed"; die;

            $orderDetails = Order::with('orders_products')->where('id', $order_id)->first()->toArray();

            if ($data['payment_gateway'] == "COD") {
                // Send Order Email 
                $email = Auth::user()->email;
                $messageData = [
                    'email' => $email,
                    'name' => Auth::user()->name,
                    'order_id' => $order_id,
                    'orderDetails' => $orderDetails
                ];
                Mail::send('emails.order', $messageData, function ($message) use ($email) {
                    $message->to($email)->Subject('Order Placed - ASoft.com');
                });
                // Send Order SMS
                // $message = "Dear Customer, your order ".$order_id." has been successfully placed with ASoft.com. We will intimate you once your order is shipped";
                // $mobile = Auth::user()->mobile;
                // Sms::sendSms($message, $mobile);
            } else {
                echo "Prepaid payment methods coming soon";
            }

            return redirect('thanks');
        }

        return view('front.products.checkout')->with(compact('deliveryAddresses', 'countries', 'getCartItems'));
    }

    public function thanks()
    {
        if (Session::has('order_id')) {
            // Empty the Cart
            Cart::where('user_id', Auth::user()->id)->delete();
            return view('front.products.thanks');
        } else {
            return redirect('cart');
        }
    }
}
