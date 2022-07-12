<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class IndexController extends Controller
{
    public function index(){
        $banners = Banner::where('status', 1)->get()->toArray();
        return view('front.index')->with(compact('banners'));
    }
}
