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
                        <label for="coupon_option">Coupon Option</label><br>
                        <span><input type="radio" id="AutomaticCoupon" name="coupon_option" value="Automatic" checked="">&nbsp;Automatic&nbsp;&nbsp;</span>
                        <span><input type="radio" id="ManualCoupon" name="coupon_option" value="Manual">&nbsp;Manual&nbsp;&nbsp;</span>
                    </div>
                    <div class="form-group" style="display: none" id="couponField">
                        <label for="coupon_code">Coupon Code</label>
                        <input type="text" class="form-control" name="coupon_code"  placeholder="Enter coupon Code" required>
                    </div>
                    <div class="form-group">
                        <label for="coupon_type">Coupon Type</label><br>
                        <span><input type="radio" name="coupon_type" value="Multiple Times" checked="">&nbsp;Multiple Times&nbsp;&nbsp;</span>
                        <span><input type="radio" name="coupon_type" value="Single Times">&nbsp;Single Times&nbsp;&nbsp;</span>
                    </div>
                    <div class="form-group">
                        <label for="amount_type">Amount Type</label><br>
                        <span><input type="radio" name="amount_type" value="Percentage" checked="">&nbsp;Percentage&nbsp;(in %)&nbsp;</span>
                        <span><input type="radio" name="amount_type" value="Fixed">&nbsp;Fixed&nbsp;&nbsp;(in INR or USD)</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="categories">Select Category</label>
                        <select name="categories[]" class="form-control text-dark" multiple="">
                            @foreach($categories as $section)
                                <optgroup label="{{ $section['name'] }}"></optgroup>
                                    @foreach ($section['categories'] as $category)
                                        <option value="{{ $category['id'] }}">&nbsp;&nbsp;&nbsp;---&nbsp;{{ $category['category_name'] }}</option>
                                            @foreach ($category['subcategories'] as $subcategory)
                                                <option value="{{ $subcategory['id'] }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---&nbsp;{{ $subcategory['category_name'] }}</option>
                                            @endforeach
                                    @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="coupon_name"> Amount </label>
                        <input type="text" class="form-control" id="coupon_name" name="coupon_name" placeholder="Enter coupon Name" required>
                    </div>
                    <div class="form-group">
                        <label for="brands">Select Brand</label>
                        <select name="brands[]" class="form-control text-dark" multiple="">
                            @foreach($brands as $brand)
                                <option value="{{ $brand['id'] }}">{{ $brand['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="users">Select User</label>
                        <select name="users[]" class="form-control text-dark" multiple="">
                            @foreach($users as $user)
                                <option value="{{ $user['email'] }}">{{ $user['email'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expiry_date"> Expiry Date </label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date" placeholder="Enter Expiry Date" required>
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