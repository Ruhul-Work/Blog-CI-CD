@include('backend.include.header')
<body class="mini-sidebar">
    {{-- Preloader --}}
    <div id="global-loader">
        {{-- <div class="whirly-loader"> </div> --}}
        <img src="{{ asset('theme/admin/assets/logo/loader.gif') }}" style="height: 180px;" alt="">
    </div>
    <div class="main-wrapper">
        @include('backend.include.topbar')
        @include('backend.include.sidebar')
        <div class="page-wrapper">
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    @include('backend.include.footer')
    @include('backend.include.scripts')
    @yield('script')
    <!-- Hidden popup -->
    <div id="columnPopup" style="display: none;">
        <h3>Select Columns to Hide</h3>
        <div id="columnList">

        </div>
        <button id="applyBtn">Apply</button>
    </div>


    <script>
       $(document).ready(function () {
    $('#qs').on('input', function () {
        let query = $(this).val();

        if (query.length > 0) {
            $.ajax({
                url: '{{ route("quick.search.orders") }}',
                type: 'GET',
                data: { order_number: query }, // Pass the correct parameter
                success: function (response) {
                    let results = '';
                    let orders = response.orders; // Access the orders array from the response

                    if (orders.length > 0) {
                        orders.forEach(order => {
                            // Construct the URL using the orders.show route pattern
                            let orderUrl = `{{ route('orders.show', ['id' => ':id']) }}`.replace(':id', order.id);
                            results += `<li><a href="${orderUrl}">${order.order_number}</a></li>`;
                        });
                    } else {
                        results = '<li>No results found</li>';
                    }

                    $('#search-results').html(results); // Update the search results list
                },
                error: function () {
                    // Handle the error case
                    $('#search-results').html('<li>Error fetching results</li>');
                }
            });
        } else {
            $('#search-results').html(''); // Clear results if query is empty
        }
    });

    // Clear input and results when clicking the clear icon
    $('.search-addon').on('click', function () {
        $('#qs').val('');
        $('#search-results').html('');
    });
});

    </script>

</body>

</html>
