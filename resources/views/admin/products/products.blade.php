@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title"> Products </h4>
                
                <a style="max-width: 150px; float: right; display: inline-block" href="{{ url('admin/add-edit-product') }}" class="btn btn-block btn-primary">Add Products</a>
                @if(Session::has('success_message')) 
                  <div class="alert alert-success alert-dismissbible fade show" role="alert">
                      <strong>Success: </strong> {{ Session::get('success_message') }}  
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                @endif  

                <div class="table-responsive pt-3">
                  <table id="products" class="table table-bordered">
                    <thead>
                      <tr>
                        <th>
                          ID
                        </th>
                        <th>
                          Product Name
                        </th>
                        <th>
                          Product Code
                        </th>
                        <th>
                          Product Color
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
                      @foreach($products as $product)
                        <tr>
                          <td>
                            {{ $product['id'] }}
                          </td>
                          <td>
                            {{ $product['product_name'] }}
                          </td>
                          <td>
                             {{ $product['product_code'] }}
                          </td>
                          <td>
                            {{ $product['product_color'] }}
                          </td>
                          
                          <td>
                            @if($product['status'] == 1)
                              {{-- Active --}}
                              <a class="updateProductStatus" id="product-{{ $product['id'] }}" product_id="{{ $product['id'] }}" href="javascript:void(0)"><i style="font-size:25px" class="mdi mdi-bookmark-check" status="Active"></i>
                              </a>
                            @else 
                              {{-- Inactive --}}
                              <a class="updateProductStatus" id="product-{{ $product['id'] }}" product_id="{{ $product['id'] }}" href="javascript:void(0)">
                                <i style="font-size:25px" class="mdi mdi-bookmark-outline" status="Inactive"></i>
                              </a>
                            @endif
                          </td>
                          <td>
                              <?php /*
                              <a title="product" class="confirmDelete" href="{{ url('admin/delete-product/'.$product['id']) }}"><i style="font-size:25px" class="mdi mdi-file-excel-box"></i></a>
                              */ 
                              ?>
                              <a href="{{ url('admin/add-edit-product/'.$product['id']) }}"><i style="font-size:25px" class="mdi mdi-pencil-box"></i></a>

                              <a href="javascript:void(0)" class="confirmDelete" module="product" moduleid="{{ $product['id'] }}"><i style="font-size:25px" class="mdi mdi-file-excel-box"></i></a>
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