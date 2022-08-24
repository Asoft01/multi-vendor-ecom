@extends('admin.layout.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Products</h3>
                    </div>
                    <div class="col-12 col-xl-4">
                        <div class="justify-content-end d-flex">
                            <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                    <a class="dropdown-item" href="#">January - March</a>
                                    <a class="dropdown-item" href="#">March - June</a>
                                    <a class="dropdown-item" href="#">June - August</a>
                                    <a class="dropdown-item" href="#">August - November</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>
                    
                    @if(Session::has('error_message')) 
                        <div class="alert alert-danger alert-dismissbible fade show" role="alert">
                            <strong>Error: </strong> {{ Session::get('error_message') }}  
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>{!! session('flash_message_error') !!}</strong>
                        </div>
                    @endif  
                    
                    @if(Session::has('success_message')) 
                        <div class="alert alert-success alert-dismissbible fade show" role="alert">
                            <strong>Success: </strong> {{ Session::get('success_message') }}  
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>{!! session('flash_message_success') !!}</strong>
                        </div>
                    @endif  

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissbible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>

                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>   
                            @endforeach
                        </div>
                    @endif 
                    
                    <form class="forms-sample" @if(empty($product['id'])) action="{{ url('admin/add-edit-product') }}" @else action="{{ url('admin/add-edit-product/'.$product['id']) }}" @endif method="post" id="updateAdminPasswordForm" enctype="multipart/form-data">
                    @csrf
                        
                    <div class="form-group">
                        <label for="category_id">Select Category</label>
                        <select name="category_id" id="category_id" class="form-control text-dark">
                            <option value="">Select</option>
                            @foreach($categories as $section)
                                <optgroup label="{{ $section['name'] }}"></optgroup>
                                    @foreach ($section['categories'] as $category)
                                        <option @if(!empty($product['catrgory_id']== $category['id'])) selected="" @endif value="{{ $category['id'] }}">&nbsp;&nbsp;&nbsp;---&nbsp;{{ $category['category_name'] }}</option>
                                            @foreach ($category['subcategories'] as $subcategory)
                                                <option @if(!empty($product['category_id']== $subcategory['id'])) selected="" @endif value="{{ $subcategory['id'] }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---&nbsp;{{ $subcategory['category_name'] }}</option>
                                            @endforeach
                                    @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="loadFilters">
                        @include('admin.filters.category_filters')
                    </div>
                    <div class="form-group">
                        <label for="brand_id">Select Brand</label>
                        <select name="brand_id" id="brand_id" class="form-control text-dark">
                            <option value="">Select</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand['id'] }}" @if(!empty($product['brand_id'] == $brand['id'])) selected="" @endif>{{ $brand['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product_name"> Product Name </label>
                        <input type="text" class="form-control" @if(!empty($product['product_name'])) value="{{ $product['product_name'] }}" @else value="{{ old('product_name') }}" @endif id="product_name" name="product_name" placeholder="Enter product Name" required>
                    </div>

                    <div class="form-group">
                        <label for="product_code"> Product Code </label>
                        <input type="text" class="form-control" @if(!empty($product['product_code'])) value="{{ $product['product_code'] }}" @else value="{{ old('product_code') }}" @endif id="product_code" name="product_code" placeholder="Enter Product Code" required>
                    </div>

                    <div class="form-group">
                        <label for="product_color"> Product Color </label>
                        <input type="text" class="form-control" @if(!empty($product['product_color'])) value="{{ $product['product_color'] }}" @else value="{{ old('product_color') }}" @endif id="product_color" name="product_color" placeholder="Enter Product Color" required>
                    </div>

                    <div class="form-group">
                        <label for="product_price"> Product Price </label>
                        <input type="text" class="form-control" @if(!empty($product['product_price'])) value="{{ $product['product_price'] }}" @else value="{{ old('product_price') }}" @endif id="product_price" name="product_price" placeholder="Enter Product Price" required>
                    </div>

                    <div class="form-group">
                        <label for="product_discount"> Product Discount (%) </label>
                        <input type="text" class="form-control" @if(!empty($product['product_discount'])) value="{{ $product['product_discount'] }}" @else value="{{ old('product_discount') }}" @endif id="product_discount" name="product_discount" placeholder="Enter Product Discount" required>
                    </div>

                    <div class="form-group">
                        <label for="product_weight"> Product Weight </label>
                        <input type="text" class="form-control" @if(!empty($product['product_weight'])) value="{{ $product['product_weight'] }}" @else value="{{ old('product_weight') }}" @endif id="product_weight" name="product_weight" placeholder="Enter product Name" required>
                    </div>

                    <div class="form-group">
                        <label for="product_image"> Product Image (Recommended Size: 1000 x 1000)</label>
                        <input type="file" class="form-control" id="product_image"  name="product_image">
                    </div>

                    @if(!empty($product['product_image']))
                        <a target="_blank" href="{{ url('admin/images/product_images/large/'.$product['product_image']) }}">View Image</a>&nbsp; | &nbsp; 
                        <a href="javascript:void(0)" class="confirmDelete" module="product-image" moduleid="{{ $product['id'] }}">Delete Image</a>
                    @endif

                    <div class="form-group">
                        <label for="product_image"> Product Video (Recommended Size: Less than 2MB)</label>
                        <input type="file" class="form-control" id="product_video"  name="product_video">
                    </div>

                    @if(!empty($product['product_video']))
                        <a target="_blank" href="{{ url('admin/videos/product_videos/'.$product['product_video']) }}">View Video</a>&nbsp; | &nbsp; 
                        <a href="javascript:void(0)" class="confirmDelete" module="product-video" moduleid="{{ $product['id'] }}">Delete Video</a>
                    @endif

                    <div class="form-group">
                        <label for="product_description"> Product Description </label>
                        <textarea name="description" id ="description" class="form-control" rows="3">{{ $product['description'] }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_title"> Meta Title </label>
                        <input type="text" class="form-control" @if(!empty($product['meta_title'])) value="{{ $product['meta_title'] }}" @else value="{{ old('meta_title') }}" @endif id="meta_title" name="meta_title" placeholder="Enter Meta Title">
                    </div>

                    <div class="form-group">
                        <label for="meta_description"> Meta Description </label>
                        <input type="text" class="form-control" @if(!empty($product['meta_description'])) value="{{ $product['meta_description'] }}" @else value="{{ old('meta_description') }}" @endif id="meta_description" name="meta_description" placeholder="Enter Meta Description">
                    </div>

                    <div class="form-group">
                        <label for="meta_keywords"> Meta Keywords </label>
                        <input type="text" class="form-control" @if(!empty($product['meta_keywords'])) value="{{ $product['meta_keywords'] }}" @else value="{{ old('meta_keywords') }}" @endif id="meta_keywords" name="meta_keywords" placeholder="Enter Product Keywords">
                    </div>

                    <div class="form-group">
                        <label for="is_featured"> Featured Items </label>
                        <input type="checkbox" name="is_featured" id="is_featured" value="Yes" @if(!empty($product['is_featured']) && $product['is_featured'] == "Yes") checked="" @endif>
                    </div>

                    <div class="form-group">
                        <label for="is_bestseller"> Best Seller Items </label>
                        <input type="checkbox" name="is_bestseller" id="is_bestseller" value="Yes" @if(!empty($product['is_bestseller']) && $product['is_bestseller'] == "Yes") checked="" @endif>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button type="reset" class="btn btn-light">Cancel</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    @include('admin.layout.footer')
    <!-- partial -->
</div>
@endsection