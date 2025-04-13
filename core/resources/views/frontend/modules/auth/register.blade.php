
@extends('frontend.layouts.master')

@section('meta')
    <title>Registration | {{ get_option('title') }}</title>
@endsection

@section('content')
<section class="register-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-7">
                <div class="register-card">
                    <div class="text-center">
                        <a href="{{route('home')}}" class="logo-link">
                            <img src="{{ asset('theme/frontend/assets/images/logo/Logo.webp') }}" alt="English Moja Logo" class="img-fluid">
                        </a>
    
                        <h2 class="register-title">Register!</h2>
                        <p class="register-subtext">Create an account and start exploring amazing blogs.</p>
                    </div>
                        <hr>
                    <form class="registration-form" id="registrationForm">
                        @csrf <!-- CSRF Token -->

                        <!-- Full Name -->
                        <div class="form-group mb-4">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" id="fullName" name="fullName" class="form-control futuristic-input"
                                placeholder="Enter your full name" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Mobile -->
                                <div class="form-group mb-4">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" id="phone" name="phone" class="form-control futuristic-input"
                                        placeholder="Enter your Mobile Number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Email -->
                                <div class="form-group mb-4">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control futuristic-input"
                                        placeholder="Enter your email" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Password -->
                                <div class="form-group mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control futuristic-input"
                                        placeholder="Enter your password" required>
                                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                        id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Confirm Password -->
                                <div class="form-group mb-4">
                                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                                    <input type="password" id="confirmPassword" name="password_confirmation"
                                        class="form-control futuristic-input" placeholder="Confirm your password"
                                        required>
                                </div>
                            </div>
                        </div>

                         <!-- Agree to Terms -->
                         <div class="mb-3 form-check">
                            <input type="checkbox" id="terms" class="form-check-input" required>
                            <label for="terms" class="form-check-label">I agree to the <a href="#" class="terms-link">Terms & Conditions</a></label>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn register-btn w-100">Register</button>
                    </form>
                    <!-- Divider -->
                    <div class="text-center my-3 text-muted">or</div>
    
                    <!-- Social Login -->
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('login.google') }}" class="btn google-btn">
                            <img src="{{ asset('theme/frontend/assets/images/icon/google.png') }}" alt=""> &nbsp; Register with Google
                        </a>
                    </div>


                    <!-- Register Link -->
                    <p class="text-center mt-3">Already have an account? <a href="{{route('login')}}" class="login-link">Log In</a></p>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')


<script>
     // Registration Form Submission
    $('#registrationForm').on('submit', function (e) {
    e.preventDefault();

    let formData = $(this).serialize(); 

    $.ajax({
        url: "{{ route('register') }}", 
        type: "POST",
        data: formData,
        success: function (response) {
            if (response.success) {

                window.location.href = response.redirect_url;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again later.',
                });
            }
        },
        error: function (xhr) {
            let errors = xhr.responseJSON.errors;
            let errorMessages = '';

            for (let key in errors) {
                errorMessages += errors[key][0] + '<br>';
            }

            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                html: errorMessages,
                confirmButtonText: 'Try Again',
            });
        }
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
