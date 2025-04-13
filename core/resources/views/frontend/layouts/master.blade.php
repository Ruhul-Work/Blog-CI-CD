<!doctype html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta name="description" content="{{ strip_tags(get_option('description')) }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('theme/frontend/assets/img/logo/Logo.webp') }}">
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('theme/frontend/assets/css/style.css') }}?id={{rand(121,122233)}}" rel="stylesheet" type="text/css">
    {{-- <link rel="stylesheet" href="{{asset('theme/admin/assets/css/style.css')}}?id={{rand(121,122233)}}"> --}}
    <link href="{{ asset('theme/frontend/assets/css/bintel.css') }}?id={{rand(121,122233)}}" rel="stylesheet" type="text/css">
    {{-- <link href="{{ asset('theme/frontend/assets/css/custom.css') }}" rel="stylesheet" type="text/css"> --}}
    <!-- Plugin CSS -->
    <link href="{{ asset('theme/frontend/assets/css/plugin.css') }}" rel="stylesheet" type="text/css">
    <!--Flaticon CSS -->
    <link href="{{ asset('theme/frontend/assets/fonts/flaticon.css') }}" rel="stylesheet" type="text/css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
    <!-- Remixicon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('theme/admin/assets/plugins/select2/css/select2.min.css')}}">
    <!-- Line Icons -->
    <link href="{{ asset('theme/frontend/assets/fonts/line-icons.css') }}" rel="stylesheet" type="text/css">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-1THY1BY8GY"></script>

</head>

<body data-login-success="{{ session('login_success') ? 'true' : 'false' }}" style="background-image: url('{{ asset('theme/frontend/assets/images/2.png') }}'); background-size: cover;" >

    @if (empty($isAuthPage) && (!isset($isDashboard) || !$isDashboard))
        @include('frontend.include.blogs.header')
    @endif

    @yield('content')

    @if (empty($isAuthPage) && (!isset($isDashboard) || !$isDashboard))
        @include('frontend.include.blogs.footer')
    @endif

    <!-- All Script JS Plugins here -->
    <!-- jQuery -->
    <script src="{{ asset('theme/frontend/assets/js/jquery-3.5.1.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="{{asset('theme/admin/assets/plugins/select2/js/select2.min.js')}}" type="text/javascript"></script> --}}
    <script src="{{asset('theme/admin/assets/plugins/select2/js/select2.min.js')}}" type="text/javascript"></script>
    <!-- Plugin JS -->
    <script src="{{ asset('theme/frontend/assets/js/plugin.js') }}"></script>

    <!-- CKEditor -->
    {{-- <script src="{{ asset('theme/frontend/assets/js/ckeditor.js') }}"></script> --}}
 
    <script src="{{ asset('theme/admin/assets/plugins/sweetalert/sweetalert2.all.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('theme/admin/assets/plugins/sweetalert/sweetalerts.min.js') }}" type="text/javascript"></script>
    <!-- Custom Scripts -->
    <script src="{{ asset('theme/frontend/assets/js/main.js') }}"></script>
    <script src="{{ asset('theme/frontend/assets/js/custom-swiper.js') }}"></script>
    <script src="{{ asset('theme/frontend/assets/js/custom-nav.js') }}"></script>
    {{-- apexcharts script --}}
    <script src="{{ asset('theme/frontend/assets/js/apexcharts.min.js') }}"></script>



    @include('frontend.include.custom_script')

    @yield('scripts')
    @if (session('success') || session('error') || session('warning') || session('message'))
        <script>
            $(document).ready(function() {
                const message =
                    '{{ session('success') ?? (session('error') ?? (session('warning') ?? session('message'))) }}';
                const type =
                    '{{ session('success') ? 'success' : (session('error') ? 'error' : (session('warning') ? 'warning' : 'message')) }}';
                showToast(message, type);
            });
        </script>
    @endif

</body>

</html>
