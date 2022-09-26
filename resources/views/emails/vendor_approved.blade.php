<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <tr><td>Dear {{ $name }}!</td></tr>
    <tr><td>&nbsp;<br></td></tr>
    <tr><td>Your Vendor has been approved. Now you can login and add products. </td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>Your Account Details are as below :-<br></td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>Name: {{ $name }}</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>Mobile: {{ $mobile }}</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>Email: {{ $email }}</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>Password: ***** (as chosen by you)</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>Thanks & Regards, </td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>A-Soft</td></tr>
</body>
</html>