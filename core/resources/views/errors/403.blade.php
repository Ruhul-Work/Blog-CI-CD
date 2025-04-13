
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="robots" content="noindex, nofollow">
    <title>403 Forbidden - {{ get_option('title') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('theme/admin/assets/logo/icon.png') }}">
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/style.css') }}">

</head>

<body class="error-page">
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">
        <div class="error-box">
            <div class="error-img">
                <img src="{{ asset('theme/common/403.png') }}" class="img-fluid"
                    alt="">
            </div>
            <h3 class="h2 mb-3">Oops, something went wrong</h3>
            <p>Access Denied</p>
            @if (request()->is('backend/*'))
             <a href="{{route("dash.home")}}" class="btn btn-primary">Back to Home</a>
            @else
             <a href="{{route("home")}}" class="btn btn-primary">Back to Home</a>
            @endif
        </div>
    </div>

    <div class="customizer-links" id="setdata">
        <ul class="sticky-sidebar">
            <li class="sidebar-icons">
                <a href="#" class="navigation-add" data-bs-toggle="tooltip" data-bs-placement="left"
                    data-bs-original-title="Theme">
                    <i data-feather="settings" class="feather-five"></i>
                </a>
            </li>
        </ul>
    </div>

    <script src="{{ asset('theme/admin/assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/admin/assets/js/feather.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/admin/assets/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/admin/assets/js/theme-script.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/admin/assets/js/script.js') }}" type="text/javascript"></script>
</body>

</html>
