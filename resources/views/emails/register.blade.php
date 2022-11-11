<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        <tr><td>Dear {{ $name }}</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Welcome to E-commerce. Your Account has been successfully created with the below information</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Name: {{ $name }}</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Mobile: {{ $mobile }}</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Email: {{ $email }}</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Password: ********* (as chosen by You) </td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Thanks & Regards</td></tr>
        <tr><td>A-Soft Shares </td></tr>
    </table>
</body>
</html>