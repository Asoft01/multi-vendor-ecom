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

        // Update Admin Status 
        Route::post('update-admin-status', 'AdminController@updateAdminStatus');

        // Admin Logout 
        Route::get('logout', 'AdminController@logout');

        
        // Brands 
        Route::get('brands', 'BrandController@brands');
        Route::post('update-brand-status', 'BrandController@updateBrandStatus');
        Route::get('delete-brand/{id}', 'BrandController@deleteBrand');
        Route::match(['get', 'post'], 'add-edit-brand/{id?}', 'BrandController@addEditBrand');

        // Sections
        Route::get('sections', 'SectionController@sections');
        Route::post('update-section-status', 'SectionController@updateSectionStatus');
        Route::get('delete-section/{id}', 'SectionController@deleteSection');
        Route::match(['get', 'post'], 'add-edit-section/{id?}', 'SectionController@addEditSection');

        // Categories 
        Route::get('categories', 'CategoryController@categories');
        Route::post('update-category-status', 'CategoryController@updateCategoryStatus');
        Route::match(['get', 'post'], 'add-edit-category/{id?}', 'CategoryController@addEditCategory');

        Route::get('append-categories-level', 'CategoryController@appendCategoryLevel');
        Route::get('delete-category/{id}', 'CategoryController@deleteCategory');
        Route::get('delete-category-image/{id}', 'CategoryController@deleteCategoryImage');
        
        // Categories 
        Route::get('products', 'ProductsController@products');
        Route::post('update-product-status', 'ProductsController@updateProductStatus');
        Route::get('delete-product/{id}', 'ProductsController@deleteProduct');
        Route::match(['get', 'post'], 'add-edit-product/{id?}', 'ProductsController@addEditProduct');

        Route::get('delete-product-image/{id}', 'ProductsController@deleteProductImage');
        Route::get('delete-product-video/{id}', 'ProductsController@deleteProductVideo');

        // Attributes
        Route::match(['get', 'post'], 'add-edit-attributes/{id}', 'ProductsController@addAttributes');
        Route::post('update-attribute-status', 'ProductsController@updateAttributeStatus');
        Route::get('delete-attribute/{id}', 'ProductsController@deleteAttribute');
        Route::match(['get', 'post'], 'edit-attributes/{id}', 'ProductsController@editAttributes');

        // Images 
        Route::match(['get', 'post'], 'add-images/{id}', 'ProductsController@addImages');
        Route::post('update-image-status', 'ProductsController@updateImageStatus');
        Route::get('delete-image/{id}', 'ProductsController@deleteImage');
        Route::get('banners', 'BannersController@banners');
        Route::post('update-banner-status', 'BannersController@updateBannerStatus');
    });
});

Route::namespace('App\Http\Controllers\Front')->group(function(){
    Route::get('/','IndexController@index');
});

// Admin Login Routes without the admin group
// Route::get('admin/login', 'App\Http\Controllers\Admin\AdminController@login');

// Admin Dashboard Route with Admin Group
// Route::get('admin/dashboard', 'App\Http\Controllers\Admin\AdminController@dashboard');

