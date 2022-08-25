<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsFilter extends Model
{
    use HasFactory;
    public static function getFilterName($filter_id){
        $getFilterName = ProductsFilter::select('filter_name')->where('id', $filter_id)->first();
        return $getFilterName->filter_name;
    }

    public function filter_values(){
        return $this->hasMany('App\Models\ProductsFilterValue', 'filter_id');
    }

    public static function productFilters(){
        $productFilters = ProductsFilter::with('filter_values')->where('status', 1)->get()->toArray();
        // dd($productFilters);
        return $productFilters; 
    }
    
    public static function filterAvailable($filter_id, $category_id){
        $filterAvailable = ProductsFilter::select('cat_ids')->where(['id' => $filter_id, 'status' => 1])->first()->toArray();
        $catidsArr = explode(",", $filterAvailable['cat_ids']);
        if(in_array($category_id, $catidsArr)){
            $available = "Yes";
        }else{
            $available = "No"; 
        }

        return $available; 
    }
}

