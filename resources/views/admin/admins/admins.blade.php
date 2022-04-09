@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">{{ $title }}</h4>
              
              <div class="table-responsive pt-3">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>
                        Admin ID
                      </th>
                      <th>
                        Name
                      </th>
                      <th>
                        Type
                      </th>
                      <th>
                        Mobile
                      </th>
                      <th>
                        Email 
                      </th>
                      <th>
                        Image 
                      </th>
                      <th>
                        Status 
                      </th>
                      <th>
                        Action 
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($admins as $admin)
                      <tr>
                        <td>
                          {{ $admin['id'] }}
                        </td>
                        <td>
                          {{ $admin['name'] }}
                        </td>
                        <td>
                          {{ $admin['type'] }}
                        </td>
                        <td>
                          {{ $admin['mobile'] }}
                        </td>
                        <td>
                          {{ $admin['email'] }}
                        </td>
                        <td>
                          <img src="{{ asset('admin/images/photos/'.$admin['image']) }}"> {{ $admin['image'] }}
                        </td>
                        <td>
                          @if($admin['status'] == 1)
                            {{-- Active --}}
                            <a class="updateAdminStatus" id="admin-{{ $admin['id'] }}" admin_id="{{ $admin['id'] }}" href="javascript:void(0)"><i style="font-size:25px" class="mdi mdi-bookmark-check" status="Active"></i>
                            </a>
                          @else 
                            {{-- Inactive --}}
                            <a class="updateAdminStatus" id="admin-{{ $admin['id'] }}" admin_id="{{ $admin['id'] }}" href="javascript:void(0)">
                              <i style="font-size:25px" class="mdi mdi-bookmark-outline" status="Inactive"></i>
                            </a>
                          @endif
                        </td>
                        <td>
                          @if($admin['type'] == "vendor")
                            <a href="{{ url('admin/view-vendor-details/'.$admin['id']) }}"><i style="font-size:25px" class="mdi mdi-file-document"></i></a>
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