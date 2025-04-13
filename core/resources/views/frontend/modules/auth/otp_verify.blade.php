@extends('frontend.layouts.master')

@section('meta')
    <title>OTP Verification | {{ get_option('title') }}</title>
@endsection

@section('content')
    <!-- Start breadcrumb section -->
    <section class="breadcrumb__section breadcrumb__bg">
        <div class="container">
            <div class="row row-cols-1">
                <div class="col">
                    <div class="breadcrumb__content text-center">
                        <h1 class="breadcrumb__content--title mb-25">OTP যাচাইকরণ</h1>
                        <ul class="breadcrumb__content--menu d-flex justify-content-center">
                            <li class="breadcrumb__content--menu__items"><a href="{{ route('home') }}">হোম</a></li>
                            <li class="breadcrumb__content--menu__items"><span>OTP যাচাইকরণ</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End breadcrumb section -->

    <!-- Start OTP verification section -->
    <div class="login__section section--padding">
        <div class="container">
            <form id="otpForm">
                @csrf
                <input type="hidden" name="email" value="{{ encrypt($email) }}">
                <div class="login__section--inner">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6">
                            <div class="card bg-white border-0"
                                style="box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1); border-radius:15px;">
                                <div class="card-body otp-body p-5 text-center">
                                    <img src="{{ asset('theme/frontend/assets/img/icon/otp.png') }}" alt="icon">
                                    <p>আপনার কোড আপনার ইমেইলে পাঠানো হয়েছে</p>
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="otp-field mb-4 d-flex justify-content-center gap-2">
                                        <input type="number" name="otp[]" required id="input1"
                                            oninput="enableNextInput(1)" maxlength="1" class="otp-input text-center" />
                                        <input type="number" name="otp[]" required id="input2"
                                            oninput="enableNextInput(2)" maxlength="1" class="otp-input text-center"
                                            disabled />
                                        <input type="number" name="otp[]" required id="input3"
                                            oninput="enableNextInput(3)" maxlength="1" class="otp-input text-center"
                                            disabled />
                                        <input type="number" name="otp[]" required id="input4" maxlength="1"
                                            class="otp-input text-center" disabled />
                                    </div>
                                    <button type="submit" class="cart__summary--footer__btn primary__btn mb-3 ">
                                        যাচাই করুন
                                    </button>
                                    {{-- <p class="resend text-muted mb-0">
                                        কোড পাননি? <a href="#" class="resend-otp">পুনরায় পাঠান</a>
                                    </p> --}}
                                    <!-- Resend OTP Section -->
                                    <div class="resend-otp-section mt-3">
                                        <button id="resendOtpButton" class="" disabled>
                                            পুনরায় পাঠান (<span id="countdown">120</span> seconds)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End OTP verification section -->
@endsection
<style>
    .otp-field .otp-input {
        width: 50px;
        height: 50px;
        font-size: 20px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        outline: none;
        padding: 10px;
    }

    .otp-field .otp-input:focus {
        border-color: #0c685f;
        box-shadow: 0 0 5px rgba(12, 104, 95, 0.5);
    }
</style>

@section('scripts')
    <script>
        //input filed value move function 
        function enableNextInput(currentInput) {
            const currentElement = document.getElementById(`input${currentInput}`);
            const nextInput = document.getElementById(`input${currentInput + 1}`);

            if (currentElement && currentElement.value.length === 1) {
                if (nextInput) {
                    nextInput.disabled = false;
                    nextInput.focus();
                }
            }
        }

        //otp validation function
        $(document).ready(function() {
            $('#otpForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('auth.otp.validate') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'OTP Verified',
                            text: 'Your account has been successfully verified!',
                            confirmButtonText: 'Go to Home'
                        }).then(() => {
                            window.location.href = "{{ route('home') }}";
                        });
                    },
                    error: function(xhr) {

                        let errors = xhr.responseJSON.errors;
                        let errorMessages = '';

                        for (let key in errors) {
                            errorMessages += errors[key][0] + '<br>';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'OTP ভুল। অনুগ্রহ করে আবার চেষ্টা করুন।',
                            html: errorMessages,
                            confirmButtonText: 'Try Again'
                        });
                    }
                });
            });
        });

        //resent otp time counter function
      
        let countdown = parseInt(localStorage.getItem('otpCountdown'));

        const countdownElement = document.getElementById('countdown');
        const resendButton = document.getElementById('resendOtpButton');

        // Disable the resend button initially if countdown exists
        if (!isNaN(countdown) && countdown > 0) {
            startCountdown();
        } else {
            resetResendButton();
        }

        // Function to start the countdown timer
        function startCountdown() {
            resendButton.disabled = true;

            const timer = setInterval(() => {
                if (countdown > 0) {
                    countdown--;
                    countdownElement.textContent = countdown;

                    // Save the countdown value in localStorage
                    localStorage.setItem('otpCountdown', countdown);
                } else {
                    clearInterval(timer);
                    resetResendButton();
                }
            }, 1000);
        }

        // Function to reset the resend button
        function resetResendButton() {
            resendButton.disabled = false;
            resendButton.textContent = "পুনরায় পাঠান ";
            localStorage.removeItem('otpCountdown');
        }

        // Resend OTP Logic
        resendButton.addEventListener('click', function() {
            $.ajax({
                url: "{{ route('auth.otp.resend') }}",
                type: "POST",
                data: {
                    email: $('input[name="email"]').val(),
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {

                    countdown = 120;
                    localStorage.setItem('otpCountdown', countdown);

                    Swal.fire({
                        icon: 'success',
                        title: 'OTP Sent',
                        text: response.message,
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Resend OTP',
                        text: xhr.responseJSON.message,
                    });
                },
            });
        });
    </script>
@endsection
