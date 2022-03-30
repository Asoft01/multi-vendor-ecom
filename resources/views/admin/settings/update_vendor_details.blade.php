@extends('admin.layout.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Update Vendor Details</h3>
                    {{-- <h6 class="font-weight-normal mb-0">Update Admin Password </h6> --}}
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
        @if($slug=="personal")
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Update Personal Information</h4>
                        
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
                        
                        <form class="forms-sample" action="{{ url('admin/update-vendor-details/personal') }}" method="post" id="updateAdminPasswordForm" enctype="multipart/form-data">
                            @csrf
                        <div class="form-group">
                            <label for="admin_email">Vendor Username/Email</label>
                            <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" name="admin_email" readonly="">
                        </div>
                        {{-- <div class="form-group">
                            <label for="admin_type">Admin Type</label>
                            <input type="email" class="form-control" value="{{ Auth::guard('admin')->user()->type }}" readonly>
                        </div> --}}
                        <div class="form-group">
                            <label for="admin_name"> Name </label>
                            <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->name }}" id="vendor_name" name="vendor_name" placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label for="vendor_address"> Address </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['address'] }}" id="vendor_address" name="vendor_address" placeholder="Enter Address" required>
                        </div>
                        <div class="form-group">
                            <label for="vendor_city"> City </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['city'] }}" id="vendor_city" name="vendor_city" placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label for="vendor_state"> State </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['state'] }}" id="vendor_state" name="vendor_state" placeholder="Enter State" required>
                        </div>
                        <div class="form-group">
                            <label for="vendor_country"> Country </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['country'] }}" id="vendor_country" name="vendor_country" placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label for="vendor_pincode"> Pincode </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['pincode'] }}" id="vendor_pincode" name="vendor_pincode" placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label for="vendor_mobile"> Mobile </label>
                            <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->mobile }}" id="vendor_mobile" name="vendor_mobile" placeholder="Enter Mobile" required>
                        </div>
                        <div class="form-group">
                            <label for="vendor_image"> Photo </label>
                            <input type="file" class="form-control" value="{{ Auth::guard('admin')->user()->mobile }}" id="vendor_image"  name="vendor_image">
                            @if(!empty(Auth::guard('admin')->user()->image))
                                <a target = "_blank" href="{{ url('admin/images/photos/'.Auth::guard('admin')->user()->image) }}">View Image</a>
                                <input type="hidden" name="current_vendor_image" value="{{ url('admin/images/photos/'.Auth::guard('admin')->user()->image) }}">
                            @endif

                        </div>
                    
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <button class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        @elseif($slug == "business")
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Update Business Information</h4>
                        
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
                        
                        <form class="forms-sample" action="{{ url('admin/update-vendor-details/business') }}" method="post" id="updateAdminPasswordForm" enctype="multipart/form-data">
                            @csrf
                        <div class="form-group">
                            <label for="admin_email">Vendor Username/Email</label>
                            <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" name="admin_email" readonly="">
                        </div>
                        {{-- <div class="form-group">
                            <label for="admin_type">Admin Type</label>
                            <input type="email" class="form-control" value="{{ Auth::guard('admin')->user()->type }}" readonly>
                        </div> --}}
                        <div class="form-group">
                            <label for="shop_name"> Shop Name </label>
                            <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->name }}" id="shop_name" name="shop_name" placeholder="Enter Shop Name" required>
                        </div>
                        <div class="form-group">
                            <label for="shop_address"> Shop Address </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['address'] }}" id="shop_address" name="shop_address" placeholder="Enter Shop Address" required>
                        </div>
                        <div class="form-group">
                            <label for="shop_city"> Shop City </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['city'] }}" id="shop_city" name="shop_city" placeholder="Enter Shop Name" required>
                        </div>
                        <div class="form-group">
                            <label for="shop_state"> Shop State </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['state'] }}" id="shop_state" name="shop_state" placeholder="Enter Shop State" required>
                        </div>
                        <div class="form-group">
                            <label for="shop_country"> Shop Country </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['country'] }}" id="shop_country" name="shop_country" placeholder="Enter Shop Country" required>
                        </div>
                        <div class="form-group">
                            <label for="shop_pincode"> Shop Pincode </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['pincode'] }}" id="shop_pincode" name="shop_pincode" placeholder="Enter Shop Pincode" required>
                        </div>
                        <div class="form-group">
                            <label for="business_license_number"> Business License Number </label>
                            <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->mobile }}" id="business_license_number" name="business_license_number" placeholder="Enter Business License Number" required>
                        </div>
                        <div class="form-group">
                            <label for="gst_number"> GST Number </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['country'] }}" id="gst_number" name="gst_number" placeholder="Enter GST Number" required>
                        </div>
                        <div class="form-group">
                            <label for="pan_number"> PAN Number </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['country'] }}" id="pan_number" name="pan_number" placeholder="Enter PAN Number" required>
                        </div>
                        <div class="form-group">
                            <label for="shop_country"> Shop Country </label>
                            <input type="text" class="form-control" value="{{ $vendorDetails['country'] }}" id="shop_country" name="shop_country" placeholder="Enter Shop Country" required>
                        </div>
                        <div class="form-group">
                            <label for="address_proof"> Address Proof </label>
                            <select class="form-control" name="address_proof" id="address_proof">
                                <option value="Passport">Passport</option>
                                <option value="Voting Card">Voting Card</option>
                                <option value="PAN">PAN</option>
                                <option value="Driving License">Driving License</option>
                                <option value="Aadhar Card">Aadhar Card</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="address_proof_image"> Address Proof Image </label>
                            <input type="file" class="form-control" value="{{ Auth::guard('admin')->user()->mobile }}" id="address_proof_image"  name="address_proof_image">
                            @if(!empty(Auth::guard('admin')->user()->image))
                                <a target = "_blank" href="{{ url('admin/images/proofs/'.Auth::guard('admin')->user()->image) }}">View Image</a>
                                <input type="hidden" name="current_shop_image" value="{{ url('admin/images/photos/'.Auth::guard('admin')->user()->image) }}">
                            @endif

                        </div>
                    
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <button class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        @elseif($slug == "bank")

        @endif
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    @include('admin.layout.footer')
    <!-- partial -->
</div>
@endsection