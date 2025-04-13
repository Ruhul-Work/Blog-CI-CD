<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

    <meta name="author" content="{{ get_option('dev') }}">
    <meta name="robots" content="noindex, nofollow">
     @yield('meta')
     <link rel="shortcut icon" type="image/x-icon" href="{{image(get_option('icon'))}}">
     <link rel="stylesheet" href="{{asset('theme/admin/assets/css/bootstrap.min.css')}}">
     <link rel="stylesheet" href="{{asset('theme/admin/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
     <link rel="stylesheet" href="{{asset('theme/admin/assets/plugins/fontawesome/css/all.min.css')}}">
     <link rel="stylesheet" href="{{asset('theme/admin/assets/css/style.css')}}">
</head>
