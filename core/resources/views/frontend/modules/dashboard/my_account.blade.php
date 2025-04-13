@extends('frontend.layouts.master')
@section('meta')
    <title>My Account | {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            @include('frontend.modules.dashboard.include.sidebar')
            <!-- Main Dashboard -->
            <div class="col-xl-10 col-lg-9 dashboard-section ">
                <div class="row dashboard-header">
                    <div class="col-md-6">
                        <h4 class="mb-0 text-white">ড্যাশবোর্ড / {{ $users->name }}</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="dropdown">
                            <img src="{{ asset($users->image ?? 'theme/frontend/assets/images/user.png') }}"
                                alt="User Profile" class="img-fluid rounded-circle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <div class="dropdown-menu user-dropdown dropdown-menu-end">


                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}">লগ আউট</a>
                            </div>
                            <i class="ri-arrow-down-s-line text-white" style="font-size: 20px; margin-left: 5px;"
                                data-bs-toggle="dropdown" aria-expanded="false"></i>
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="ri-user-line me-2 text-primary"></i> আমার অ্যাকাউন্ট</h5>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editProfileModal">
                                    <i class="ri-edit-line me-1"></i> সম্পাদনা করুন
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="info-box p-3">
                                            <p class="fw-bold mb-1"><i class="ri-mail-line me-2 text-primary"></i> ইমেইল
                                            </p>
                                            <p class="text-muted"> {{ $users->email ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="info-box p-3">
                                            <p class="fw-bold mb-1"><i class="ri-phone-line me-2 text-success"></i>
                                                যোগাযোগ</p>
                                            <p class="text-muted"> {{ $users->phone ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="info-box p-3">
                                            <p class="fw-bold mb-1"><i class="ri-calendar-line me-2 text-warning"></i>
                                                অ্যাকাউন্ট তৈরির তারিখ</p>
                                            <p class="text-muted"> {{ $users->created_at->format('y-m-d') }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="info-box p-3">
                                            <p class="fw-bold mb-1"><i class="ri-time-line me-2 text-danger"></i>
                                                সর্বশেষ আপডেট</p>
                                            <p class="text-muted">{{ $users->updated_at->format('y-m-d') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="info-box p-3">
                                            <p class="fw-bold mb-1"><i class="ri-star-line me-2 text-info"></i> সদস্য পদ</p>
                                            @if ($users->package && $users->package->user_id->exists)
                                                <p class="text-muted">প্রিমিয়াম</p>
                                            @else
                                                <p class="text-muted">বেসিক</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="info-box p-3">
                                            <p class="fw-bold mb-1"><i class="ri-map-pin-line me-2 text-dark"></i>
                                                ঠিকানা</p>
                                            <p class="text-muted">{{ $users->address ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- Edit Profile Modal -->
            <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="editProfileModalLabel"><i class="ri-edit-line me-2"></i> প্রোফাইল
                                সম্পাদনা করুন</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body ">
                            <form id="editProfile" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="name" class="form-label">নাম</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $users->name ?? '' }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="email" class="form-label">ইমেইল</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ $users->email ?? '' }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="phone" class="form-label">যোগাযোগ</label>
                                        <input type="tel" class="form-control" name="phone"
                                            value="{{ $users->phone ?? '' }}">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label">ঠিকানা (ঐচ্ছিক)</label>
                                        <input type="text" class="form-control" name="address"
                                            value="{{ $users->address ?? '' }}">
                                    </div>
                                    <!-- New Password Field (Optional) -->
                                    <div class="col-md-12 mb-3">
                                        <label for="password" class="form-label">নতুন পাসওয়ার্ড (ঐচ্ছিক)</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>

                                    <!-- Confirm Password Field -->
                                    <div class="col-md-12 mb-3">
                                        <label for="password_confirmation" class="form-label">পাসওয়ার্ড নিশ্চিত
                                            করুন</label>
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>

                                    <!-- Profile Image Preview -->
                                    <div class="col-md-12 text-center mb-3">
                                        <div id="imagePreviewContainer">
                                            <img id="imagePreview"
                                                src="{{ asset($users->image ?? 'theme/frontend/assets/images/user.png') }}"
                                                alt="User Image"
                                                style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                                        </div>
                                    </div>

                                    <!-- Profile Image Upload -->
                                    <div class="col-md-12 mb-3">
                                        <label for="profile_image" class="form-label">আপনার ছবি আপলোড করুন</label>
                                        <input type="file" class="form-control" name="image" id="profileImage"
                                            accept="image/*" onchange="previewImage(event)">
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary" value="সংরক্ষণ করুন">
                            </form>
                        </div>
                        <!-- Modal Footer -->
                        {{-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল করুন</button>
                            <button type="submit" form="editProfileForm" class="btn btn-primary">সংরক্ষণ করুন</button>
                        </div> --}}
                    </div>
                </div>
            </div>


            <style>
                /* Card Styling */
                .card {
                    border-radius: 15px;
                }

                .card-header {
                    border-top-left-radius: 15px;
                    border-top-right-radius: 15px;
                    font-size: 18px;
                    font-weight: bold;
                    background-color: #f8f9fa;
                    border-bottom: 1px solid #e9ecef;
                }

                .card-body {
                    padding: 20px;
                }

                /* Info Box Styling */
                .info-box {
                    background: #ffffff;
                    border: 1px solid #e9ecef;
                    border-radius: 10px;
                    transition: all 0.3s ease;
                }

                .info-box:hover {
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                    transform: translateY(-3px);
                }

                .info-box p {
                    margin: 0;
                    font-size: 14px;
                }

                .info-box .fw-bold {
                    font-size: 16px;
                    font-weight: bold;
                }

                /* Edit Button */
                .btn-outline-primary {
                    font-size: 14px;
                    font-weight: 500;
                    border-radius: 20px;
                    padding: 5px 15px;
                    transition: all 0.3s ease;
                }

                .btn-outline-primary:hover {
                    background-color: #0c685f;
                    color: #fff;
                    border-color: #0c685f;
                }

                .modal-content {
                    border-radius: 15px;
                }

                .modal-header {
                    border-top-left-radius: 15px;
                    border-top-right-radius: 15px;
                }

                .modal-footer button {
                    border-radius: 20px;
                    font-size: 14px;
                }
            </style>

        </div>
    </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $('#editProfile').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('dashboard.profile.edit') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href =
                                    '';
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        // Parse the JSON response from the server
                        try {
                            var responseObj = JSON.parse(xhr.responseText);
                            var errorMessages = responseObj.errors ? Object.values(responseObj
                                .errors).flat() : [responseObj.message];
                            var errorMessageHTML = '<ul>' + errorMessages.map(errorMessage =>
                                '<li>' + errorMessage + '</li>').join('') + '</ul>';

                            // Show error messages using SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errorMessageHTML,
                            });
                        } catch (e) {
                            console.error('Error parsing JSON response:', e);
                            // Show default error message using SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while processing your request. Please try again later.',
                            });
                        }
                    }

                });
            });
        });

        function previewImage(event) {
            const input = event.target;
            const previewImage = document.getElementById("imagePreview");

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
