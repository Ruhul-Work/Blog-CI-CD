@extends('backend.layouts.master')

@section('meta')
    <title>Options - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Options</h4>
                <h6>Manage Email Settings</h6>
            </div>
        </div>
        <div class="page-btn">
            <a href="javascript:void(0)" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#statusModal">
                <i data-feather="plus-circle"></i>Email
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="settings-wrapper d-flex">
                @include('backend.modules.setting.option.sidebar')
                @if (Auth::user()->user_role == 1)
                    <div class="settings-page-wrap">
                        <form action="{{ route('settings.email.store') }}" method="POST" enctype="multipart/form-data"
                            id="createSocialSetting">
                            @csrf
                            <div class="setting-title">
                                <h4>Email Settings</h4>
                            </div>
                            <div class="company-info">
                                <div class="card-title-head">
                                    <h6><span><i data-feather="zap"></i></span>Email</h6>
                                </div>
                                <div class="row">

                                    <div class="col-xl-4 col-lg-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">MAIL HOST</label>
                                            <input type="text" class="form-control" name="MAIL_HOST"
                                                value="{{ $MAIL_HOST ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">MAIL USERNAME</label>
                                            <input type="text" class="form-control" name="MAIL_USERNAME"
                                                value="{{ $MAIL_USERNAME ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">MAIL PASSWORD</label>
                                            <input type="text" class="form-control" name="MAIL_PASSWORD"
                                                value="{{ $MAIL_PASSWORD ?? '' }}">
                                        </div>
                                    </div>


                                    <div class="col-xl-4 col-lg-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">MAIL FROM ADDRESS</label>
                                            <input type="text" class="form-control" name="MAIL_FROM_ADDRESS"
                                                value="{{ $MAIL_FROM_ADDRESS ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">MAIL FROM NAME</label>
                                            <input type="text" class="form-control" name="MAIL_FROM_NAME"
                                                value="{{ $MAIL_FROM_NAME ?? '' }}">
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="modal-footer-btn">
                                <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-submit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p class="mb-0 p-5 fs-6">You do not have admin access.</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <!-- Non-admin content -->
                @endif




            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#createSocialSetting').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '{{ route('settings.email.store') }}',
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
                            window.location.href = '{{ route('settings.email') }}';
                        }
                    });
                },
                error: function(xhr, status, error) {
                    try {
                        var responseObj = JSON.parse(xhr.responseText);
                        var errorMessages = responseObj.invalid_urls ?
                            responseObj.invalid_urls.map(url => 'Invalid URL for: ' + url).flat() : [
                                responseObj.message
                            ];

                        var errorMessageHTML = '<ul>' + errorMessages.map(errorMessage =>
                            '<li>' + errorMessage + '</li>').join('') + '</ul>';

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessageHTML,
                        });
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while processing your request. Please try again later.',
                        });
                    }
                }
            });
        });
    </script>
@endsection
