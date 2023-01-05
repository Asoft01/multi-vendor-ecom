@extends('admin.layout.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Coupons</h3>
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
                    
                    <form class="forms-sample" @if(empty($coupon['id'])) action="{{ url('admin/add-edit-coupon') }}" @else action="{{ url('admin/add-edit-coupon/'.$coupon['id']) }}" @endif method="post" id="updateAdminPasswordForm" enctype="multipart/form-data">
                    @csrf
                        
                    <div class="form-group">
                        <label for="category_id">Select Category</label>
                        <select name="category_id" id="category_id" class="form-control text-dark">
                            <option value="">Select</option>
                            @foreach($categories as $section)
                                <optgroup label="{{ $section['name'] }}"></optgroup>
                                    @foreach ($section['categories'] as $category)
                                        <option @if(!empty($coupon['catrgory_id']== $category['id'])) selected="" @endif value="{{ $category['id'] }}">&nbsp;&nbsp;&nbsp;---&nbsp;{{ $category['category_name'] }}</option>
                                            @foreach ($category['subcategories'] as $subcategory)
                                                <option @if(!empty($coupon['category_id']== $subcategory['id'])) selected="" @endif value="{{ $subcategory['id'] }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---&nbsp;{{ $subcategory['category_name'] }}</option>
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
                                <option value="{{ $brand['id'] }}" @if(!empty($coupon['brand_id'] == $brand['id'])) selected="" @endif>{{ $brand['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="coupon_name"> coupon Name </label>
                        <input type="text" class="form-control" @if(!empty($coupon['coupon_name'])) value="{{ $coupon['coupon_name'] }}" @else value="{{ old('coupon_name') }}" @endif id="coupon_name" name="coupon_name" placeholder="Enter coupon Name" required>
                    </div>

                    <div class="form-group">
                        <label for="coupon_code"> coupon Code </label>
                        <input type="text" class="form-control" @if(!empty($coupon['coupon_code'])) value="{{ $coupon['coupon_code'] }}" @else value="{{ old('coupon_code') }}" @endif id="coupon_code" name="coupon_code" placeholder="Enter coupon Code" required>
                    </div>

                    <div class="form-group">
                        <label for="coupon_color"> coupon Color </label>
                        <input type="text" class="form-control" @if(!empty($coupon['coupon_color'])) value="{{ $coupon['coupon_color'] }}" @else value="{{ old('coupon_color') }}" @endif id="coupon_color" name="coupon_color" placeholder="Enter coupon Color" required>
                    </div>

                    <div class="form-group">
                        <label for="coupon_price"> coupon Price </label>
                        <input type="text" class="form-control" @if(!empty($coupon['coupon_price'])) value="{{ $coupon['coupon_price'] }}" @else value="{{ old('coupon_price') }}" @endif id="coupon_price" name="coupon_price" placeholder="Enter coupon Price" required>
                    </div>

                    <div class="form-group">
                        <label for="coupon_discount"> coupon Discount (%) </label>
                        <input type="text" class="form-control" @if(!empty($coupon['coupon_discount'])) value="{{ $coupon['coupon_discount'] }}" @else value="{{ old('coupon_discount') }}" @endif id="coupon_discount" name="coupon_discount" placeholder="Enter coupon Discount" required>
                    </div>

                    <div class="form-group">
                        <label for="coupon_weight"> coupon Weight </label>
                        <input type="text" class="form-control" @if(!empty($coupon['coupon_weight'])) value="{{ $coupon['coupon_weight'] }}" @else value="{{ old('coupon_weight') }}" @endif id="coupon_weight" name="coupon_weight" placeholder="Enter coupon Name" required>
                    </div>

                    <div class="form-group">
                        <label for="group_code"> Group Code </label>
                        <input type="text" class="form-control" @if(!empty($coupon['group_code'])) value="{{ $coupon['group_code'] }}" @else value="{{ old('group_code') }}" @endif id="group_code" name="group_code" placeholder="Enter coupon Name" required>
                    </div>

                    <div class="form-group">
                        <label for="coupon_image"> coupon Image (Recommended Size: 1000 x 1000)</label>
                        <input type="file" class="form-control" id="coupon_image"  name="coupon_image">
                    </div>

                    @if(!empty($coupon['coupon_image']))
                        <a target="_blank" href="{{ url('admin/images/coupon_images/large/'.$coupon['coupon_image']) }}">View Image</a>&nbsp; | &nbsp; 
                        <a href="javascript:void(0)" class="confirmDelete" module="coupon-image" moduleid="{{ $coupon['id'] }}">Delete Image</a>
                    @endif

                    <div class="form-group">
                        <label for="coupon_image"> coupon Video (Recommended Size: Less than 2MB)</label>
                        <input type="file" class="form-control" id="coupon_video"  name="coupon_video">
                    </div>

                    @if(!empty($coupon['coupon_video']))
                        <a target="_blank" href="{{ url('admin/videos/coupon_videos/'.$coupon['coupon_video']) }}">View Video</a>&nbsp; | &nbsp; 
                        <a href="javascript:void(0)" class="confirmDelete" module="coupon-video" moduleid="{{ $coupon['id'] }}">Delete Video</a>
                    @endif

                    <div class="form-group">
                        <label for="coupon_description"> coupon Description </label>
                        <textarea name="description" id ="description" class="form-control" rows="3">{{ $coupon['description'] }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_title"> Meta Title </label>
                        <input type="text" class="form-control" @if(!empty($coupon['meta_title'])) value="{{ $coupon['meta_title'] }}" @else value="{{ old('meta_title') }}" @endif id="meta_title" name="meta_title" placeholder="Enter Meta Title">
                    </div>

                    <div class="form-group">
                        <label for="meta_description"> Meta Description </label>
                        <input type="text" class="form-control" @if(!empty($coupon['meta_description'])) value="{{ $coupon['meta_description'] }}" @else value="{{ old('meta_description') }}" @endif id="meta_description" name="meta_description" placeholder="Enter Meta Description">
                    </div>

                    <div class="form-group">
                        <label for="meta_keywords"> Meta Keywords </label>
                        <input type="text" class="form-control" @if(!empty($coupon['meta_keywords'])) value="{{ $coupon['meta_keywords'] }}" @else value="{{ old('meta_keywords') }}" @endif id="meta_keywords" name="meta_keywords" placeholder="Enter coupon Keywords">
                    </div>

                    <div class="form-group">
                        <label for="is_featured"> Featured Items </label>
                        <input type="checkbox" name="is_featured" id="is_featured" value="Yes" @if(!empty($coupon['is_featured']) && $coupon['is_featured'] == "Yes") checked="" @endif>
                    </div>

                    <div class="form-group">
                        <label for="is_bestseller"> Best Seller Items </label>
                        <input type="checkbox" name="is_bestseller" id="is_bestseller" value="Yes" @if(!empty($coupon['is_bestseller']) && $coupon['is_bestseller'] == "Yes") checked="" @endif>
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