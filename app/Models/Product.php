<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function section(){
        return $this->belongsTo('App\Models\Section', 'section_id');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function attributes(){
        return $this->hasMany('App\Models\ProductsAttribute');
    }

    public function images(){
        return $this->hasMany('App\Models\ProductsImage');
    }

    public static function getDiscountPrice($product_id){
        $proDetails = Product::select('product_price', 'product_discount', 'category_id')->where('id', $product_id)->first();
        $proDetails = json_decode(json_encode($proDetails), true);
        // dd($proDetails); die;
        $catDetails = Category::select('category_discount')->where('id', $proDetails['category_id'])->first();
        $catDetails = json_decode(json_encode($catDetails), true);
        // dd($catDetails); die;
        if($proDetails['product_discount'] > 0){
            // If Product Discount is added from the admin panel
            $discounted_price  = $proDetails['product_price'] - ($proDetails['product_price'] * $proDetails['product_discount'] / 100);
        }else if($catDetails['category_discount'] > 0){
            // If Product Discount is not added but category discount added from the admin panel
            $discounted_price = $proDetails['product_price'] - ($proDetails['product_price'] * $catDetails['category_discount'] / 100); 
        }else {
            $discounted_price = 0;
        }
        return $discounted_price;
    }
}
