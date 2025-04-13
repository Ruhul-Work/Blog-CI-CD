@extends('frontend.layouts.master')
@section('meta')
    <title>Point | {{ get_option('title') }}</title>
@endsection

@section('content')
<section class="login-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="login-card shadow">
                    <h2 class="text-center mb-4 text-glow">Log In</h2>
                    <p class="text-center text-muted mb-4">Access your account and explore amazing blogs.</p>
                    <form>
                        <!-- Email -->
                        <div class="form-group mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" class="form-control futuristic-input"
                                placeholder="Enter your email" required>
                        </div>
                        <!-- Password -->
                        <div class="form-group mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" class="form-control futuristic-input"
                                placeholder="Enter your password" required>
                        </div>
                        <!-- Remember Me and Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" id="rememberMe" class="form-check-input">
                                <label for="rememberMe" class="form-check-label">Remember Me</label>
                            </div>
                            <a href="#" class="text-glow">Forgot Password?</a>
                        </div>
                        <!-- Submit Button -->
                        <a href="dashboard.html" class="btn futuristic-btn w-100 mb-3">Log In</a>
                    </form>
                    <!-- Divider -->
                    <div class="text-center text-muted mb-3">
                        <span>or</span>
                    </div>
                    <!-- Social Login -->
                    <div class="d-flex justify-content-center">
                        <button class="btn login-google-btn">
                            <i class="fab fa-google me-2"></i> Login with Google
                        </button>
                    </div>

                    <!-- Register Link -->
                    <p class="text-center mt-4">Don’t have an account? <a href="#" class="text-glow">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
@section('scripts')
@endsection
