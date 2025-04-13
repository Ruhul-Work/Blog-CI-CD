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
                            <h5 class="mb-1 text-white">আপনার পয়েন্ট</h5>
                            <h1 class="mb-0 text-white"><strong>{{ $userpoints->point ?? '' }}</strong> পয়েন্ট</h1>
                            <p class="mt-2">সমমান মূল্য:100 পয়েন্ট <strong>৳ 100</strong></p>
                        </div>
                        <div>
                            <img src="{{ asset('theme/frontend/assets/images/icon/coin-lg.png') }}" alt="Points Icon"
                                style="width: 100px;">
                        </div>
                    </div>
                </div>


                {{-- <div class="row mt-5 mb-5">
                    <!-- Quick Action Cards -->
                    <div class="col-md-3">
                        <a href="read_blogs.html">
                            <div class="card p-3 text-center custom-bg-success custom-card-hover">
                                <i class="ri-book-read-line"></i>
                                <p>ব্লগ পড়ুন</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="like_comment_share.html">
                            <div class="card p-3 text-center custom-bg-info custom-card-hover">
                                <i class="ri-heart-3-fill"></i>
                                <p>লাইক এবং শেয়ার</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="redeem_points.html">
                            <div class="card p-3 text-center custom-bg-warning custom-card-hover">
                                <i class="ri-copper-diamond-line"></i>
                                <p>পয়েন্ট রিডিম</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="premium_subscription.html">
                            <div class="card p-3 text-center custom-bg-danger custom-card-hover">
                                <i class="ri-star-fill"></i>
                                <p>প্রিমিয়াম সাবস্ক্রিপশন</p>
                            </div>
                        </a>
                    </div>
                </div> --}}

                <div class="row mt-5">
                    <!-- Accuracy Chart -->
                    {{-- <div class="col-md-6">
                        <div class="card chart-card p-3 bg-gradient-light shadow-sm">
                            <h5>আপনার কার্যকারিতা</h5>
                            <div id="accuracyChart" class="chart-container"></div>
                        </div>
                    </div> --}}

                    <!-- Leaderboard -->
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            {{-- <div class="card-header text-white"
                                style="background: linear-gradient(45deg, #73eea6, #72ffd0); padding: 10px;">
                                <h5 class="mb-0"><i class="ri-trophy-line me-2"></i> সাপ্তাহিক লিডারবোর্ড</h5>
                            </div> --}}
                            <div class="card-body">
                                <!-- Top 3 Performers -->
                                {{-- <div class="d-flex justify-content-around mb-4 mt-3">
                                    <!-- 2nd Place -->
                                    <div class="text-center">
                                        <div class="position-relative student-img-wrapper">
                                            <img src="{{ asset('theme/frontend/assets/images/about_5.png') }}"
                                                class="rounded-circle mb-2" width="60" height="60">
                                            <span class="student-rank student-rank-silver">2</span>
                                        </div>
                                        <h6 class="mb-0">মাহমুদুল হাসান</h6>
                                        <small class="text-success fw-bold">৮০০ পয়েন্ট</small>
                                    </div>

                                    <!-- 1st Place -->
                                    <div class="text-center">
                                        <div class="position-relative student-img-wrapper">
                                            <img src="{{ asset('theme/frontend/assets/images/about_5.png') }}"
                                                class="rounded-circle mb-2" width="80" height="80">
                                            <span class="student-rank student-rank-gold">1</span>
                                        </div>
                                        <h6 class="mb-0">সাইফুল ইসলাম</h6>
                                        <small class="text-success fw-bold">১০০০ পয়েন্ট</small>
                                    </div>

                                    <!-- 3rd Place -->
                                    <div class="text-center">
                                        <div class="position-relative student-img-wrapper">
                                            <img src="{{ asset('theme/frontend/assets/images/about_5.png') }}"
                                                class="rounded-circle mb-2" width="60" height="60">
                                            <span class="student-rank student-rank-bronze">3</span>
                                        </div>
                                        <h6 class="mb-0">নুসরাত ফারিয়া</h6>
                                        <small class="text-success fw-bold">৭৫০ পয়েন্ট</small>
                                    </div>
                                </div> --}}
                                <!-- Remaining Rankings -->
                                <div class="modal fade" id="generateCouponModal" tabindex="-1"
                                    aria-labelledby="generateCouponModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="generateCouponModalLabel">Generate Coupon</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Select the platform for which you want to generate the coupon:</p>
                                                <div class="d-flex justify-content-between">
                                                    <a class="btn btn-primary generate-coupon"
                                                        data-platform="ecommerce">Generate for Ecommerce</a>
                                                    <a class="btn btn-secondary generate-coupon"
                                                        data-platform="subscriptions">Generate for Subscriptions</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th scope="col">SN</th> --}}
                                            <th scope="col">User</th>
                                            <th scope="col">Points</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @if ($userpoints && $userpoints->user_id == Auth::id())
                                            @foreach ($points as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>{{ $item->balance }}</td>
                                                    <td>
                                                        <a class="btn btn-info me-2 p-2 open-modal" data-bs-toggle="modal"
                                                            data-bs-target="#generateCouponModal" data-id="{{ $item->id }}">
                                                            <i class="ri-add-fill"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">You have no enough points to generate coupons. Thank you.</td>
                                            </tr> --}}

                                        <tr>
                                            {{-- <td>{{ $userpoints + 1 }}</td> --}}
                                            <td>{{ $userpoints->name }}</td>
                                            <td>{{ $userpoints->points }}</td>
                                            <td>
                                                <a class="btn btn-info me-2 p-2 open-modal" data-bs-toggle="modal"
                                                    data-bs-target="#generateCouponModal" data-id="{{ $userpoints->id }}">
                                                    <i class="ri-add-fill"></i>
                                                </a>
                                            </td>
                                        </tr>




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
    <script>
        $(document).ready(function() {

            let selectedUserId = null;

            // Capture the "Open Modal" button click
            $('.open-modal').on('click', function() {
                selectedUserId = $(this).data('id'); // Get the point ID from the button's data attribute
            });


            $(document).ready(function() {
                $('.generate-coupon').on('click', function() {
                    const platform = $(this).data(
                    'platform'); // Get the platform (ecommerce or subscriptions)

                    if (!selectedUserId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'No user selected!',
                        });
                        return;
                    }

                    // Generate the route URL dynamically
                    const url = `{{ route('coupons.generate', ':id') }}`.replace(':id',
                        selectedUserId);
                    const redirectUrl =
                    `{{ route('dashboard.coupon-users') }}`; // Define the redirection route

                    // Show a loading alert while the request is being processed
                    Swal.fire({
                        title: 'Generating Coupon...',
                        text: 'Please wait while we generate the coupon.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    // Send the AJAX request to generate the coupon
                    $.ajax({
                        url: url, // Use the dynamically generated route URL
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data: {
                            platform: platform, // Include platform in the request payload
                        },
                        success: function(response) {
                            Swal.close(); // Close the loading alert

                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'কুপন তৈরি হয়েছে!',
                                    text: `Coupon Code: ${response.coupon_code}`,
                                    confirmButtonText: 'Go to Coupons',
                                }).then(() => {
                                    window.location.href =
                                    redirectUrl; // Redirect to the coupon dashboard
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message,
                                });
                            }
                            // Close the modal
                            $('#generateCouponModal').modal('hide');
                        },
                        error: function(xhr) {
                            Swal.close(); // Close the loading alert

                            let errorMessage = 'An unknown error occurred.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMessage,
                            });
                        },
                    });
                });
            });

        });







        // document.addEventListener('DOMContentLoaded', function() {
        //     // Capture the coupon generation button clicks
        //     document.querySelectorAll('.generate-coupon').forEach(button => {
        //         button.addEventListener('click', function() {
        //             const platform = this.getAttribute('data-platform');
        //             const id = document.querySelector('#generateCouponModal').getAttribute(
        //                 'data-id');

        //             // Send an AJAX request
        //             fetch(`/generate-coupon/${id}`, {
        //                     method: 'POST',
        //                     headers: {
        //                         'Content-Type': 'application/json',
        //                         'X-CSRF-TOKEN': document.querySelector(
        //                             'meta[name="csrf-token"]').content
        //                     },
        //                     body: JSON.stringify({
        //                         platform
        //                     })
        //                 })
        //                 .then(response => response.json())
        //                 .then(data => {
        //                     if (data.success) {
        //                         alert('Coupon generated successfully: ' + data.coupon_code);
        //                     } else {
        //                         alert('Error: ' + data.message);
        //                     }
        //                     // Close the modal
        //                     bootstrap.Modal.getInstance(document.querySelector(
        //                         '#generateCouponModal')).hide();
        //                 })
        //                 .catch(error => console.error('Error:', error));
        //         });
        //     });
        // });


        $(document).on("click", '.changeStatus', function(e) {
            e.preventDefault();
            var slidersId = $(this).data('coupon-id');
            // Send an AJAX request to update the status of the category
            $.ajax({
                url: '{{ route('coupons.updateStatus') }}',
                type: 'POST',
                data: {
                    id: slidersId,
                },
                success: function(response) {
                    // Show a success message using SweetAlert
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000 // Optional: automatically close after 2 seconds
                    }).then(function() {
                        // Reload the AjaxDataTable
                        $('.AjaxDataTable').DataTable().ajax.reload();
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors if any
                    Swal.fire({
                        title: "Error!",
                        text: "An error occurred while updating the Author status.",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 2000 // Optional: automatically close after 2 seconds
                    });
                }
            });
        });

        $(document).on("click", '.changeValidStatus', function(e) {
            e.preventDefault();
            var slidersId = $(this).data('coupon-id');
            // Send an AJAX request to update the status of the category
            $.ajax({
                url: '{{ route('coupons.chnage-valid-status') }}',
                type: 'POST',
                data: {
                    id: slidersId,
                },
                success: function(response) {
                    // Show a success message using SweetAlert
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000 // Optional: automatically close after 2 seconds
                    }).then(function() {
                        // Reload the AjaxDataTable
                        $('.AjaxDataTable').DataTable().ajax.reload();
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors if any
                    Swal.fire({
                        title: "Error!",
                        text: "An error occurred while updating the Author status.",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 2000 // Optional: automatically close after 2 seconds
                    });
                }
            });
        });

        // JavaScript to handle the image modal
    </script>
@endsection
