@extends('frontend.layouts.master')
@section('meta')
    <title>Login | {{ get_option('title') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <section class="login-section py-5">
        @if (session('message'))
            <div class="alert alert-warning">
                {{ session('message') }}
            </div>
        @endif

        <input type="hidden" id="redirectUrl" value="{{ request('redirect_url') }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="login-card">

                        <div class="text-center">
                            <a href="{{ route('home') }}" class="logo-link">
                                <img src="{{ asset('theme/frontend/assets/images/logo/Logo.webp') }}"
                                    alt="English Moja Logo" class="img-fluid">
                            </a>

                            <h2 class="login-title">Login!</h2>
                            <p class="login-subtext">Access your account and explore amazing blogs.</p>
                        </div>
                        <hr>

                        <form class="login-form" id="loginForm" action="{{ route('login') }}" method="POST">
                            @csrf
                            <!-- Email -->
                            <div class="form-group mb-4">
                                <label for="email" class="form-label">ইমেইল বা ফোন নম্বর</label>
                                <input type="text" id="identifier" name="identifier"
                                    class="form-control futuristic-input" placeholder="Enter your email" required>
                            </div>
                            <!-- Password -->
                            {{-- <div class="form-group mb-4">
                            <label for="password" class="form-label">পাসওয়ার্ড</label>
                            <input type="password" id="password" name="password" class="form-control futuristic-input"
                                placeholder="Enter your password" required>
                        </div> --}}

                            <!-- Password Field with Eye Button Inside Input Field -->
                            <div class="form-group mb-4">
                                <label for="password" class="form-label">পাসওয়ার্ড</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                        class="form-control futuristic-input" placeholder="Enter your password" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>


                            <!-- Remember Me and Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input type="checkbox" id="rememberMe" class="form-check-input">
                                    <label for="rememberMe" class="form-check-label">Remember Me</label>
                                </div>

                                <a class="forgot-password-link"
                                    onclick="window.location='{{ route('password.request') }}'">আপনার পাসওয়ার্ড ভুলে
                                    গেছেন?</a>
                            </div>
                            <!-- Submit Button -->
                            <button class="btn login-btn w-100" type="submit">Login</button>
                        </form>
                        <!-- Divider -->
                     <div class="text-center my-3 text-muted">or</div>
    
                     <!-- Social Login -->
                     <div class="d-flex justify-content-center">
                        <a href="{{ route('login.google') }}" class="btn google-btn">
                             <img src="{{ asset('theme/frontend/assets/images/icon/google.png') }}" alt=""> &nbsp; Login with Google
                       </a>
                     </div>

                        <!-- Register Link -->
                        <p class="mt-3 text-center">Don’t have an account? <a href="{{ route('register') }}"
                                class="register-link">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection

@section('scripts')
    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('login') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        const redirectUrl = $('#redirectUrl').val() || "{{ route('home') }}";
                        window.location.href = redirectUrl;
                    }
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON.message ||
                        'An error occurred. Please try again.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: errorMessage,
                    });
                },
            });
        });

        //password show hide function
        document.getElementById('togglePassword').addEventListener('click', function() {
            let passwordField = document.getElementById('password');
            let icon = this.querySelector('i');

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
@endsection
