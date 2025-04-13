@extends('frontend.layouts.master')
@section('meta')
    <title>User Dashboard | {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('frontend.modules.dashboard.include.sidebar')
            <!-- Main Dashboard -->
            <div class="col-xl-10 col-lg-9 dashboard-section">

                <div class="card points-card shadow-sm border-0 mb-4 mt-2">
                    <div class="card-body d-flex justify-content-between align-items-center"
                        style="background: linear-gradient(to right, #0c685f, #05998a); color: white; border-radius: 8px;">
                        <div>
                            <h1 class="mb-0 text-white"><strong>আপনার কুপন</strong></h1>
                             
                        </div>
                        
                        <div>
                            <img src="{{ asset('theme/frontend/assets/images/icon/coin-lg.png') }}" alt="Points Icon"
                                style="width: 100px;">
                        </div>
                    </div>
                </div>
           

                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-end align-items-center mb-3">
                                    <a href="{{ route('dashboard.coupon') }}" class="btn "style="background: linear-gradient(to right, #0c685f, #05998a); color: white; border-radius: 8px;">
                                        <i class="fa fa-plus"></i> কুপন তৈরি করুন
                                    </a>
                                </div>
                                <table id="couponsTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">SN</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Coupon Type</th>
                                            <th scope="col">Coupon Code</th>
                                            <th scope="col">Validity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coupons as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->couponUser->name }}</td>
                                                <td>{{ $item->c_type }}</td>
                                                <td>{{ $item->code }}</td>
                                                <td>
                                                    @if (\Carbon\Carbon::now()->lessThanOrEqualTo(\Carbon\Carbon::parse($item->end_date)))
                                                        {{ \Carbon\Carbon::parse($item->end_date)->format('Y-m-d') }}
                                                    @else
                                                        <button class="bg bg-danger p-2 text-white">Expired</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            let selectedUserId = null;
            // Capture the "Open Modal" button click
            $('.open-modal').on('click', function() {
                selectedUserId = $(this).data('id'); // Get the point ID from the button's data attribute
            });

            // Capture the "Generate Coupon" button click
            $('.generate-coupon').on('click', function() {
                const platform = $(this).data('platform'); // Get the platform (ecommerce or subscriptions)
                if (!selectedUserId) {
                    alert('Error: No user selected.');
                    return;
                }
                // Generate the route URL dynamically
                const url = `{{ route('coupons.generate', ':id') }}`.replace(':id', selectedUserId);
                // Send the AJAX request to generate the coupon
                $.ajax({
                    url: url, // Use the dynamically generated route URL
                    type: 'get',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        platform: platform, // Include platform in the request payload
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Coupon generated successfully: ' + response.coupon_code);
                            window.location.reload()
                        } else {
                            alert('Error: ' + response.message);
                        }
                        // Close the modal
                        $('#generateCouponModal').modal('hide');
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            alert('Error: ' + xhr.responseJSON.message);
                        } else {
                            alert('An unknown error occurred.');
                        }
                    },
                });
            });
        });
        // JavaScript to handle the image modal
        $(document).ready(function() {
            $('#couponsTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "Search Coupons:",
                    lengthMenu: "Display _MENU_ records per page",
                    zeroRecords: "No matching records found",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoEmpty: "No records available",
                    infoFiltered: "(filtered from _MAX_ total records)"
                }
            });
        });
    </script>
@endsection
