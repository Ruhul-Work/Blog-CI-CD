@include('backend.include.header')

<body class="mini-sidebar">
{{-- Preloader --}}
<div id="global-loader">
    {{-- <div class="whirly-loader"> </div> --}}
    <img src="https://www.groupeclarins.com/default/dist/img/circle-loader.gif" style="height: 180px;" alt="">
</div>
<div class="main-wrapper">
    @include('backend.include.topbar')


    @yield('content')
</div>

@include('backend.include.footer')
@include('backend.include.scripts')
@yield('script')

<!-- Hidden popup -->
<div id="columnPopup" style="display: none;">
    <h3>Select Columns to Hide</h3>
    <div id="columnList">
        <!-- Column checkboxes will be added here -->
    </div>
    <button id="applyBtn">Apply</button>
</div>

</body>

</html>
