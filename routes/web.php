<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function(){
    //Admin Login Routes without the admin group
    Route::match(['get', 'post'], 'login', 'AdminController@login');
    
    Route::group(['middleware'=> ['admin']], function(){
        // Admin Dashboard Route with Admin Group
        Route::get('dashboard', 'AdminController@dashboard');

        // Update Admin Password
        Route::match(['get', 'post'], 'update-admin-password', 'AdminController@updateAdminPassword');

        // Check Admin Password
        Route::post('check-admin-password', 'AdminController@checkAdminPassword');

        // Updating Admin Details
        Route::match(['get', 'post'], 'update-admin-details', 'AdminController@updateAdminDetails');

        // Update Vendor Details
        Route::match(['get', 'post'], 'update-vendor-details/{slug}', 'AdminController@updateVendorDetails');

        // View Admins / Subadmins / Vendors

        Route::get('/admins/{type?}', 'AdminController@admins');

        // View Vendor Details 
        Route::get('view-vendor-details/{id}', 'AdminController@viewVendorDetails');
        // Admin Logout 
        Route::get('logout', 'AdminController@logout');
    });
});

// Admin Login Routes without the admin group
// Route::get('admin/login', 'App\Http\Controllers\Admin\AdminController@login');

// Admin Dashboard Route with Admin Group
// Route::get('admin/dashboard', 'App\Http\Controllers\Admin\AdminController@dashboard');