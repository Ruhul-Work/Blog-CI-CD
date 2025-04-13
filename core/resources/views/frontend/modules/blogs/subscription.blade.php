@extends('frontend.layouts.master')

@section('meta')
    <title>{{ $blog->title ?? 'Blog Details' }} | {{ get_option('title') }}</title>
    <meta property="og:title"
        content="{{ $blog->title ?? 'Blog Details' }} | {{ strtolower($blog->meta_title ?? get_option('title')) }}">
    <meta property="og:description" content="{{ strip_tags($blog->meta_description ?? get_option('description')) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($blog->meta_image ?? get_option('meta_image')) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    {{-- ✅ Show SweetAlert Notification for Subscription Error --}}
    @if (session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: ' একটি সক্রিয় সাবস্ক্রিপশন আছে।',
                text: "{{ session('error') }}",
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33',
            });
        </script>
    @endif

    {{-- ✅ Pricing Section --}}
    <section class="pricing-section py-5">
        <div class="container text-center">
            <div class="section-title mb-4">
                <h2 class="m-0"><span class="highlight">সেরা প্ল্যান</span> সাবস্ক্রাইব করুন</h2>
                <p>আপনার পছন্দসই প্রিমিয়াম ব্লগ পড়ার জন্য সেরা প্ল্যান বেছে নিন।</p>
            </div>

            <div class="row">
                @foreach ($packages as $index => $package)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div
                            class="pricing-card box-shadow p-4 rounded @if ($index == 1) premium-plan @endif">
                            <h4 class="plan-title mb-3">{{ $package->name }}</h4>

                            {{-- ✅ Subscription Price --}}
                            <div class="price mb-4">
                                <span class="currency">৳</span>
                                <span class="amount">{{ $package->current_price }}</span>
                                <span class="duration">/{{ $package->duration }} দিন</span>
                            </div>

                            {{-- ✅ Plan Features List (Check if Exists) --}}
                            {{-- <ul class="plan-features list-unstyled mb-4">
                                @if (!empty($package->features) && count($package->features) > 0)
                                    @foreach ($package->features as $feature)
                                        <li>{{ $feature->name }}</li>
                                    @endforeach
                                @else
                                    <li>কোনো বৈশিষ্ট্য উপলব্ধ নেই</li>
                                @endif
                            </ul> --}}
                            <ul class="plan-features list-unstyled mb-4">
                                @if (!empty($package->features) && count($package->features) > 0)
                                @foreach ($package->features as $feature)
                                <li>
                                    @if ($feature->icon)
                                        <i class="text-success mx-2 fas  fa-check"></i> 
                                    @else
                                        <i class="text-danger mx-2 fas  fa-times"></i> 
                                    @endif
                                    {{ $feature->name }}
                                </li>
                            @endforeach
                                @else
                                    <li>কোনো বৈশিষ্ট্য উপলব্ধ নেই</li>
                                @endif
                            </ul>

                            {{-- ✅ Subscription Button --}}
                            <a href="javascript:void(0)" class="btn btn-primary w-100"
                                onclick="handleSubscriptionRedirect({{ $package->id }})">
                                সাবস্ক্রাইব করুন
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ✅ Include Newsletter Section --}}
    @include('frontend.modules.blogs.newsletter')

    {{-- ✅ CSS Styling for Premium Plan Highlighting --}}
   
@endsection

@section('scripts')
    {{-- ✅ JavaScript for Subscription Handling --}}
    <script>
        function handleSubscriptionRedirect(packageId) {
            const isAuthenticated = @json(Auth::check());

            if (isAuthenticated) {
                // Redirect to Subscription Checkout
                const checkoutUrl = "{{ route('subscriptions.checkout', ':packageId') }}".replace(':packageId', packageId);
                window.location.href = checkoutUrl;
            } else {
                // Show SweetAlert and Redirect to Login
                const loginUrl = "{{ route('login') }}";
                Swal.fire({
                    title: 'আপনাকে প্রথমে লগইন করতে হবে!',
                    text: 'সাবস্ক্রিপশন পেতে লগইন করুন।',
                    icon: 'warning',
                    confirmButtonText: 'লগইন করুন',
                }).then(() => {
                    window.location.href = loginUrl;
                });
            }
        }
    </script>
@endsection
