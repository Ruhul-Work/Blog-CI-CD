<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="{{get_option('title')}}">
    <meta name="author" content="{{ get_option('dev') }}">
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" type="image/x-icon" href="{{image(get_option('icon'))}}">
     @yield('meta')
    {{-- Core CSS --}}
    <link rel="stylesheet" href="{{asset('theme/admin/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/admin/assets/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('theme/admin/assets/css/bootstrap-datetimepicker.min.css')}}">

    <link rel="stylesheet" href="{{asset('theme/admin/assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/admin/assets/css/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/admin/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/admin/assets/plugins/icons/ionic/ionicons.css')}}">
    <link rel="stylesheet" href="{{asset('theme/admin/assets/plugins/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/admin/assets/css/style.css')}}?id={{rand(121,122233)}}">
    <link rel="stylesheet" href="{{asset('theme/admin/assets/plugins/datepicker/daterangepicker.css')}}">
 

</head>
