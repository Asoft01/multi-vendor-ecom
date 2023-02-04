<?php use App\Models\Product; ?>
@extends('front.layout.layout')
@section('content')
      <!-- Page Introduction Wrapper -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>Order #{{ $orderDetails['id'] }} Details</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="index.html">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="{{ url('user/orders') }}">Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Introduction Wrapper /- -->
    <!-- Cart-Page -->
    <div class="page-cart u-s-p-t-80">
        <div class="container">
            <div class="row">
                <table class="table table-striped table-borderless">
                    <tr><td colspan="2"><strong>Order Details</strong></td></tr>
                    <tr><td>Order Date</td><td>{{ date('Y-m-d h:i:s', strtotime($orderDetails['created_at'])); }}</td></tr>
                    <tr><td>Order Status</td>{{ $orderDetails['order_status'] }}<td></td></tr>
                    <tr><td>Order Total</td><td>{{ $orderDetails['order_status'] }}</td></tr>
                    <tr><td>Shipping Charges</td>{{ $orderDetails['shipping_charges'] }}<td></td></tr>
                    @if($orderDetails['coupon_code'] !="")
                        <tr><td>Coupon Code</td><td>{{ $orderDetails['coupon_code'] }}</td></tr>
                        <tr><td>Coupon Amount</td><td>{{ $orderDetails['coupon_amount'] }}</td></tr>
                    @endif
                    <tr><td>Payment Method</td><td>{{ $orderDetails['payment_method'] }}</td></tr>
                </table>
                <table class="table table-striped table-borderless">
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Product Size</th>
                        <th>Product Color</th>
                        <th>Product Qty</th>
                    </tr>
                    @foreach ($orderDetails['orders_products'] as $product)
                        <tr>
                            <td>{{ $product['product_code'] }}</td>
                            <td>{{ $product['product_name'] }}</td>
                            <td>{{ $product['product_size'] }}</td>
                            <td>{{ $product['product_color'] }}</td>
                            <td>{{ $product['product_qty'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <!-- Cart-Page /- -->
@endsection