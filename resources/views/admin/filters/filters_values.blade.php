@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title"> Filters </h4>
                
                <a style="max-width: 150px; float: right; display: inline-block" href="{{ url('admin/add-edit-filter') }}" class="btn btn-block btn-primary">Add Filter</a>
                @if(Session::has('success_message')) 
                  <div class="alert alert-success alert-dismissbible fade show" role="alert">
                      <strong>Success: </strong> {{ Session::get('success_message') }}  
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                      <strong>{!! session('flash_message_success') !!}</strong>
                  </div>
                @endif  

                <div class="table-responsive pt-3">
                  <table id="filters" class="table table-bordered">
                    <thead>
                      <tr>
                        <th>
                          ID
                        </th>
                        <th>
                          Filter ID
                        </th>
                        <th>
                            Filter Name
                        </th>
                        <th>
                            Filter Values 
                        </th>   
                        <th>
                          Status 
                        </th>
                        <th>
                            Actions 
                          </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($filters_values as $filter)
                        <tr>
                          <td>
                            {{ $filter['id'] }}
                          </td>
                          <td>
                            {{ $filter['filter_id'] }}
                          </td>
                          <td>
                            {{ $filter['filter_id'] }}
                          </td>
                          <td>
                            {{ $filter['filter_value'] }}
                          </td>
                          
                          <td>
                            @if($filter['status'] == 1)
                              {{-- Active --}}
                              <a class="updateFilterValueStatus" id="filter-{{ $filter['id'] }}" filter_id="{{ $filter['id'] }}" href="javascript:void(0)"><i style="font-size:25px" class="mdi mdi-bookmark-check" status="Active"></i>
                              </a>
                            @else 
                              {{-- Inactive --}}
                              <a class="updateFilterValueStatus" id="filter-{{ $filter['id'] }}" filter_id="{{ $filter['id'] }}" href="javascript:void(0)">
                                <i style="font-size:25px" class="mdi mdi-bookmark-outline" status="Inactive"></i>
                              </a>
                            @endif
                          </td>
                          <td>
                           
                          </td>                     
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    
    <!-- content-wrapper ends -->
    <!-- partial:../../partials/_footer.html -->
    <footer class="footer">
      <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021.  Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
      </div>
    </footer>
    <!-- partial -->
  </div>
@endsection