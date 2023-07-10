<!DOCTYPE html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">

    <meta name="description"

        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">

    <meta name="keywords"

        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">

    <meta name="author" content="PIXINVENT">

    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>Dashboard Ranking Checker</title>

    <link rel="apple-touch-icon" href="{{asset('admin_assets/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin_assets/images/ico/favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/components.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
</head>



<!-- END: Head-->



<!-- BEGIN: Body-->



<body class="pace-done vertical-layout vertical-menu-modern footer-static menu-expanded navbar-floating" data-open="click"

    data-menu="vertical-menu-modern" data-col="">



@include('seo.layout.header')



@yield('content')



@include('seo.layout.footer')



</body>

<!-- END: Body-->
<script>
<?php 
  if(Session::has('message')) { ?>
var type = "{{Session::get('alert-type','info')}}";
switch (type) {
    case 'info':
        toastr.info(" {{Session::get('message')}} ");
        break;

    case 'success':
        toastr.success(" {{Session::get('message')}} ");
        break;

    case 'warning':
        toastr.warning(" {{Session::get('message')}} ");
        break;

    case 'error':
        toastr.error(" {{Session::get('message')}} ");
        break;
}
<?php } ?>
</script>


</html>