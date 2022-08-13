<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductsFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FilterController extends Controller
{
    public function filters(){
        Session::put('page', 'filters');
        $filters = ProductsFilter::get()->toArray();
        // dd($filters); die;
        return view('admin.filters.filters')->with(compact('filters'));
    }
}
