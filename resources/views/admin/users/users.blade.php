@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title"> Users </h4>
                
                {{-- <a style="max-width: 150px; float: right; display: inline-block" href="{{ url('admin/add-edit-category') }}" class="btn btn-block btn-primary">Add Users</a> --}}
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
                  <table id="users" class="table table-bordered">
                    <thead>
                      <tr>
                        <th>
                          ID
                        </th>
                        <th>
                          Name 
                        </th>
                        <th>
                          Address 
                        </th>
                        <th>
                          City
                        </th>
                        <th>
                          State
                        </th>
                        <th>
                            Country
                        </th>
                        <th>
                            Pincode
                        </th>
                        <th>
                            Mobile
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Status
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($users as $user)
                        <tr>
                          <td>
                            {{ $user['id'] }}
                          </td>
                          <td>
                            {{ $user['name'] }}
                          </td>
                          <td>
                              {{ $user['address'] }}
                          </td>
                          <td>
                            {{ $user['city']}}
                          </td>
                          <td>
                            {{ $user['state']}}
                          </td>
                          <td>
                            {{ $user['country']}}
                          </td>
                          <td>
                            {{ $user['pincode']}}
                          </td>
                          <td>
                            {{ $user['mobile']}}
                          </td>
                          <td>
                            {{ $user['email']}}
                          </td>
                          
                          <td>
                            @if($user['status'] == 1)
                              {{-- Active --}}
                              <a class="updateUserStatus" id="user-{{ $user['id'] }}" user_id="{{ $user['id'] }}" href="javascript:void(0)"><i style="font-size:25px" class="mdi mdi-bookmark-check" status="Active"></i>
                              </a>
                            @else 
                              {{-- Inactive --}}
                              <a class="updateUserStatus" id="user-{{ $user['id'] }}" user_id="{{ $user['id'] }}" href="javascript:void(0)">
                                <i style="font-size:25px" class="mdi mdi-bookmark-outline" status="Inactive"></i>
                              </a>
                            @endif
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