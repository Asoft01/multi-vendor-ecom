@extends('admin.layout.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Filter</h3>
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
                    
                    <form class="forms-sample" @if(empty($filter['id'])) action="{{ url('admin/add-edit-filter-value') }}" @else action="{{ url('admin/add-edit-filter-value/'.$filter['id']) }}" @endif method="post" id="updateAdminPasswordForm" enctype="multipart/form-data">
                    @csrf
                        
                    <div class="form-group">
                        <label for="filter_id">Select Filter</label>
                        <select name="filter_id" id="filter_id" class="form-control text-dark">
                            <option value="">Select</option>
                                @foreach ($filters as $filter)
                                    <option value="{{ $filter['id'] }}">{{ $filter['filter_name'] }}</option>
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="filter_value"> Filter Value </label>
                        <input type="text" class="form-control" @if(!empty($filter['filter_value'])) value="{{ $filter['filter_value'] }}" @else value="{{ old('filter_value') }}" @endif id="filter_value" name="filter_value" placeholder="Enter filter Name" required>
                    </div>

                    {{-- <div class="form-group">
                        <label for="filter_column"> Filter Column </label>
                        <input type="text" class="form-control" @if(!empty($filter['filter_column'])) value="{{ $filter['filter_column'] }}" @else value="{{ old('filter_column') }}" @endif id="filter_column" name="filter_column" placeholder="Enter filter Code" required>
                    </div> --}}

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