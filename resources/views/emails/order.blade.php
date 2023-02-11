<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table style="width:700px;">
        <tr><td>&nbsp;</td></tr>
        <tr><td><img src="{{ asset('front/images/main-logo/stack-developers-logo.png') }}"></td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Hello {{ $name }}</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Thank You for shopping with us. Your Order details is as below:-</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Order No {{ $order_id }}</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>
            <table style="width:95%" cellpadding="5" cellspacing="5" bgcolor="#f7f4f4">
                <tr bgcolor="#cccccc">
                    <td>Product Name</td>
                    <td>Product Code</td>
                    <td>Product Size</td>
                    <td>Product Color</td>
                    <td>Product Quantity</td>
                    <td>Product Price</td>
                </tr>
                @foreach ($orderDetails['orders_products'] as $order)
                    <tr bgcolor="#cccccc">
                        <td>{{ $order['product_name'] }}</td>
                        <td>{{ $order['product_code'] }}</td>
                        <td>{{ $order['product_size'] }}</td>
                        <td>{{ $order['product_color'] }}</td>
                        <td>{{ $order['product_qty'] }}</td>
                        <td>{{ $order['product_price'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" align="right">Shipping Charges</td>
                    <td>INR {{ $orderDetails['shipping_charges'] }}</td>
                </tr>
                <tr>
                    <td colspan="5" align="right">Coupon Discount</td>
                    <td>INR {{ $orderDetails['coupon_amount'] }}</td>
                </tr>
                <tr>
                    <td colspan="5" align="right">Grand Total</td>
                    <td>INR {{ $orderDetails['grand_total'] }}</td>
                </tr>
            </table>    
        </td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>
            <table>
                <tr>
                    <td><strong>Delivery Address: </strong></td>    
                </tr>    
                <tr>
                    <td>{{ $orderDetails['name'] }}</td>    
                </tr>    
                <tr>
                    <td>{{ $orderDetails['address'] }}</td>    
                </tr>    
                <tr>
                    <td>{{ $orderDetails['city'] }}</td>    
                </tr>    
                <tr>
                    <td>{{ $orderDetails['state'] }}</td>    
                </tr>    
                <tr>
                    <td>{{ $orderDetails['country'] }}</td>    
                </tr>    
                <tr>
                    <td>{{ $orderDetails['pincode'] }}</td>    
                </tr>    
                <tr>
                    <td>{{ $orderDetails['mobile'] }}</td>    
                </tr>    
            </table>    
        </td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>For any queries, you can contact us at <a href="mailto:info@asoft.com">
        <tr><td>&nbsp;</td></tr>
        <tr><td>Regards, <br>Team ASOFT</td></tr>
        <tr><td>&nbsp;</td></tr>
    </table>
</body>
</html>