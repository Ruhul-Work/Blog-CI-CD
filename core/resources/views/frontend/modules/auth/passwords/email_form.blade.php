
@extends('frontend.layouts.master')

@section('meta')
    <title>Send Password Reset Link | {{ get_option('title') }}</title>
@endsection

@section('content')
    <!-- Start breadcrumb section -->
    
    <!-- End breadcrumb section -->


    <!-- Start login section  -->
    <style>
     
        .login-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .login-card-body {
            padding: 15px;
            text-align: center;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .login-input {
            border-radius: 30px;
            padding: 12px;
            border: 1px solid #bdbcbc;
            font-size: 16px;
        }
        .login-btn {
            background: #2575fc;
            border: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 30px;
            width: 100%;
            transition: background 0.3s;
        }
        .login-btn:hover {
            background: #6a11cb;
        }
        .login-text-muted {
            display: block;
            margin-top: 15px;
            color: #666;
        }
    </style>
    
    <div class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="login-card">
                        <div class="text-center">
                            <a href="{{route('home')}}" class="logo-link ">
                                <img src="{{ asset('theme/frontend/assets/images/logo/Logo.webp') }}" alt="English Moja Logo" class="img-fluid">
                            </a>
                        </div>
                        <div class="login-card-body">
                            <h3 class="fw-bold mb-4" style="color: #2c3e50;">পাসওয়ার্ড রিসেট</h3>
                            
                            <form id="passwordForm" method="post" action="{{ route('password.email') }}">
                                @csrf
                                
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                
                                <div class="form-group text-start">
                                    <label for="email" class="form-label">ইমেইল ঠিকানা:</label>
                                    <input id="email" type="email" name="email" class="login-input" placeholder="আপনার ইমেইল লিখুন" value="{{ old('email') }}" required>
                                    <span id="emailError" class="text-danger small" style="display: none;"></span>
                                </div>
                                
                                <button class="login-btn mt-4" type="submit">পাসওয়ার্ড রিসেট লিঙ্ক পাঠান</button>
                            </form>
                            
                            <a href="{{ route('login') }}" class="login-text-muted"><i class="fas fa-arrow-left"></i> লগইন পেজে ফিরে যান</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <!-- End login section  -->




@endsection
@section('scripts')

    <style>
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
    <script>


        $(document).ready(function() {
            $('#passwordForm').on('submit', function(e) {
                e.preventDefault();
                validateForm();
            });
        });

        function validateForm() {
            $('.error-message').text('').hide();

            const email = $('#email').val().trim();

            let isValid = true;

            if (!email) {
                $('#emailError').text('ইমেইল প্রয়োজন।').show();
                isValid = false;
            }

            if (isValid) {
                $('#passwordForm').unbind('submit').submit();
            }
        }

    </script>
@endsection







