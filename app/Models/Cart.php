<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    use HasFactory;
    
    public static function getCartItems(){
        if(Auth::check()){
            // If user logged in / pick auth id of the user 
            $getCartItems = Cart::with(['product' => function($query){
                $query->select('id', 'category_id', 'product_name', 'product_code', 'product_color', 'product_image', 'product_weight');
            }])->orderby('id', 'Desc')->where('user_id', Auth::user()->id)->get()->toArray(); 
        }else{
            // If user not logged in / pick session id of the user 
            $getCartItems = Cart::with(['product' => function($query){
                $query->select('id', 'category_id', 'product_name', 'product_code', 'product_color', 'product_image', 'product_weight');
            }])->orderby('id', 'Desc')->where('session_id', Session::get('session_id'))->get()->toArray();
        }
        return $getCartItems; 
    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
