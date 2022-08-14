<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function section(){
        return $this->belongsTo('App\Models\Section', 'section_id')->select('id', 'name');
    }

    public function parentcategory(){
        return $this->belongsTo('App\Models\Category', 'parent_id')->select('id', 'category_name');
    }

    public function subcategories(){
        return $this->hasMany('App\Models\Category', 'parent_id')->where('status', 1);
    }

    public static function categoryDetails($url){
        $categoryDetails = Category::select('id', 'parent_id', 'category_name', 'url', 'description')->with(['subcategories'=> function($query){
            $query->select('id', 'parent_id', 'category_name', 'url', 'description');
        }])->where('url', $url)->first()->toArray();
        // dd($categoryDetails); die;

        $catIds = array();
        $catIds[] = $categoryDetails['id'];

        if($categoryDetails['parent_id'] == 0){
            // Only Show Main Category in Breadcrumb
            $breadcrumbs = '<li class="is-marked">
                <a href="'.url($categoryDetails['url']).'">'.$categoryDetails['category_name'].' </a>
            </li>';
        }else{
            // Show Main and SubCategory in Breadcrumb
            $parentCategory = Category::select('category_name', 'url')->where('id', $categoryDetails['parent_id'])->first()->toArray();
            $breadcrumbs = '<li class="has-separator">
                <a href="'.url($parentCategory['url']).'">'.$parentCategory['category_name'].' </a>
            </li><li class="is-marked">
            <a href="'.url($categoryDetails['url']).'">'.$categoryDetails['category_name'].' </a>
            </li>';
        }

        foreach ($categoryDetails['subcategories'] as $key => $subcat) {
            $catIds[] = $subcat['id'];
        }

        // dd($catIds); die;
        $resp = array('catIds' => $catIds, 'categoryDetails' => $categoryDetails, 'breadcrumbs' => $breadcrumbs);
        // dd($resp); die;
        return $resp;
    }

    public static function getCategoryName($category_id){
        $getCategoryName = Category::select('category_name')->where('id', $category_id)->first();
        return $getCategoryName->category_name;
    }
}
