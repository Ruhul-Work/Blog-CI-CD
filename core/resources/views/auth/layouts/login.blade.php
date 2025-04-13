@extends('auth.master')

@section('content')
    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper login-new">
                <div class="container">
                    <div class="login-content user-login">
                        <div class="login-logo">
                            <img src="{{image(get_option('logo'))}}" alt="img">
                        </div>

                        <form id="login-form">
                            @csrf
                            <div class="login-userset" style="padding: 20px">
                                <div class="login-userheading">
                                    <h3 class="text-center">Sign In</h3>
                                    <h4 class="text-center">Please log in to the <strong
                                            class="text-danger">{{ get_option('panel_name') }}</strong> using your username
                                        and
                                        password.</h4>
                                </div>
                                <div class="form-login">
                                    <label class="form-label fw-bold">Email or Username<span
                                            class="text-danger">*</span></label>
                                    <div class="form-addons">
                                        <input type="text" name="username" class="form-control">
                                        <img src="{{ asset('theme/admin/assets/img/icons/users1.svg') }}" alt="img">
                                    </div>
                                </div>
                                <div class="form-login">
                                    <label class="fw-bold">Password<span class="text-danger">*</span></label>
                                    <div class="pass-group">
                                        <input type="password" name="password" class="pass-input">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                                <div class="form-login authentication-check">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="custom-control custom-checkbox">
                                                <label class="checkboxs ps-4 mb-0 pb-0 line-height-1 fw-bold">
                                                    <input type="checkbox">
                                                    <span class="checkmarks"></span>Remember me
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <a class="forgot-link"
                                                href="javascript:void(0);"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-login">
                                    <button class="btn btn-login bg-danger" type="submit">

                                        <span class="spinner-border spinner-border-sm d-none"
                                            style="width: 1.2rem; height: 1.2rem;" role="status" aria-hidden="true"></span>
                                        Sign In
                                    </button>
                                </div>
                                <div class="signinform d-none">
                                    <h4>New on our {{ get_option('short_name') }}?<a href="#" class="hover-a">
                                            Create
                                            an
                                            account</a></h4>
                                </div>


                            </div>

                        </form>
                    </div>
                    <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                        <p><strong> Copyright &copy; {{ date('Y') }}</strong> <strong><a
                                    class="text-danger text-uppercase" target="_blank"
                                    href="{{ get_option('dev_url') }}">{{ get_option('dev') }}</a>.</strong> <strong> All
                                rights reserved</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('meta')
    <title>Login - {{ get_option('title') }}</title>
    <meta name="description" content="Login - {{ get_option('title') }}">
@endsection

@section('script')
    <script src="{{ asset('theme/admin/assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Retrieve values from local storage
            var rememberMeChecked = localStorage.getItem('rememberMe');
            var savedUsername = localStorage.getItem('username');
            var savedPassword = localStorage.getItem('password');

            // Check if "Remember me" was checked previously
            if (rememberMeChecked && rememberMeChecked === 'true') {
                // Fill the checkbox and input fields
                $('input[type="checkbox"]').prop('checked', true);
                $('input[name="username"]').val(savedUsername);
                $('input[name="password"]').val(savedPassword);
            }
        });
        $(document).ready(function() {
            $('#login-form').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                // Retrieve form data
                var formData = $(this).serialize();
                $('#login-form :input').prop('disabled', true);
                // Show spinner
                $('.btn-login .spinner-border').removeClass('d-none');

                // Save checkbox state to local storage
                var rememberMeChecked = $('input[type="checkbox"]').is(':checked');
                localStorage.setItem('rememberMe', rememberMeChecked);

                // Save input field values to local storage
                var usernameValue = $('input[name="username"]').val();
                localStorage.setItem('username', usernameValue);

                var passwordValue = $('input[name="password"]').val();
                localStorage.setItem('password', passwordValue);


                // Perform AJAX login request
                $.ajax({
                    url: '{{ route('backend.login.action') }}', // Route for handling login
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {



                        if (response.login) {

                            Swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success",
                                showConfirmButton: false,
                            });
                            //perform redirect;
                            setTimeout(function() {
                                window.location.href = "{{ route('dash.home') }}";
                            }, 2000);
                        } else {
                            $('#login-form :input').prop('disabled', false);
                            // Show spinner
                            $('.btn-login .spinner-border').addClass('d-none');

                            Swal.fire({
                                title: "Failed",
                                text: response.message,
                                icon: "error"
                            });
                        }

                    },
                    error: function(xhr) {
                        $('#login-form :input').prop('disabled', false);
                        // Show spinner
                        $('.btn-login .spinner-border').addClass('d-none');
                        Swal.fire({
                            title: "Failed",
                            text: response.message,
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>
@endsection
