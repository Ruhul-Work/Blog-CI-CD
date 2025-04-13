@extends('frontend.layouts.master')
@section('meta')
    <title>English Moja Blogs | {{ get_option('title') }}</title>

    <meta property="og:title" content="{{ get_option('title') }}">
    <meta property="og:description" content="{{ strip_tags(get_option('description')) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset(get_option('meta_image')) }}">
    <meta property="og:site_name" content="{{ get_option('company_name') }}">
    <!-- Add more Open Graph tags as needed -->

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ get_option('title') }}">
    <meta name="twitter:description" content="{{ strip_tags(get_option('description')) }}">
    <meta name="twitter:image" content="{{ asset(get_option('meta_image')) }}">
    <!-- Add more Twitter meta tags as needed -->
@endsection

@section('content')
    <!-- <body> -->
    <!-- Preloader -->
    <div id="preloader">
        <div id="status"></div>
    </div>
    <!-- Preloader Ends -->
    
       @if ($popup)
            <!-- Start Newsletter Popup -->
            <div class="newsletter__popup" data-animation="slideInUp">
                <div id="boxes" class="newsletter__popup--inner">
                    <button class="newsletter__popup--close__btn" aria-label="search close button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 512 512">
                            <path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="32" d="M368 368L144 144M368 144L144 368"></path>
                        </svg>
                    </button>
                    <div class="box newsletter__popup--box d-flex align-items-center">
                        <div class="newsletter__popup--thumbnail">
                            <img class="newsletter__popup--thumbnail__img display-block"
                                src="{{$popup->image}}"
                                alt="newsletter-popup-thumb" onclick="window.location.href='{{$popup->link}}'">
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Newsletter Popup -->
        @endif
        



    @if (Auth::check() && Auth::user()->is_admin == 1 && $device === 'PC')
        


        <section class="container py-5">
            <div class="row">
                <div class="section-title  mb-4 pb-1 w-50">
                    <h2 class="m-0">What's <span>Your Article Today?</span></h2>
                </div>
                <div class="col-md-12">
                    <div class="custom-card card">
                        <div class="card-body">
                            <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data"
                                id="blogForm">
                                @csrf
                                <!-- Hidden Inputs for Default Values -->
                                <input type="hidden" name="publish_status" value="published">
                                <input type="hidden" name="status" value="1">
                                <!-- Blog Title -->
                                <div class="form-group mb-3">
                                    <label for="blogTitle" class="form-label">Blog Title</label>

                                    <div class="futuristic-input-container">
                                        <input type="text" id="title" name="title" class="futuristic-input"
                                            placeholder="Enter your blog title" required>
                                        <span class="futuristic-border"></span>
                                        <input type="hidden" id="slug" name="slug">
                                    </div>
                                </div>

                                <!-- Blog Content -->
                                <label for="blogContent" class="form-label">Blog Content</label>
                                <div class="writer_img mb-3">
                                    <textarea name="content" class="ck" rows="60" cols="120"></textarea>
                                </div>
                                <!-- Actions -->
                                <div class="row">

                                    {{-- <div class="col-md-4">


                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select class="form-control multi_select" name="category_ids[]"
                                                multiple="multiple">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="category_slugs" name="category_slugs">

                                        </div>

                                    </div> --}}
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>

                                            <select class="form-control multi_select" name="category_ids[]"
                                                multiple="multiple" style="width: 375px!important">
                                                @foreach ($categories as $category)
                                                    <option style="color: #000 !important" value="{{ $category->id }}">
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="category_slugs" name="category_slugs">
                                        </div>
                                    </div>
                                    <style>
                                        .select2-container--default ul li {
                                            color: #462c2c !important;
                                        }
                                    </style>
                                    <!-- Thumbnail Image -->
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <!-- Image Preview (Left Side) -->
                                            <div id="imagePreviewContainer"
                                                style="display: none; margin-right: 10px; margin-top: 25px;">
                                                <img id="imagePreview" src="#" alt="Thumbnail Preview"
                                                    style="width: 65px; height: 50px; border-radius: 5px; object-fit: cover; border: 1px solid #ddd;">
                                            </div>

                                            <!-- File Upload Input -->
                                            <div class="custom-file-container">
                                                <label class="form-label text-danger">Thumbnail (1280x960)</label>
                                                <input type="file" name="thumbnail" class="custom-file-input"
                                                    id="thumbImage" accept="image/*" onchange="previewImage(event)">
                                                <label for="thumbImage" class="custom-file-label" id="fileLabel">
                                                    Thumbnail <i class="ri-download-cloud-2-line"></i>
                                                </label>

                                                <small class="text-muted">Recommended size: 1280x960 pixels</small>
                                            </div>
                                        </div>
                                    </div>



                                    <!-- Blog Type -->
                                    <div class="col-md-4 d-flex align-items-center">
                                        <div class="form-group d-flex justify-content-around align-items-center">
                                            <label class="form-label" style="font-weight: 600; color: #333;">Blog
                                                Type:</label>
                                            <div class="preferred-contact-options">
                                                <label class="radio-label">
                                                    <input type="radio" name="blog_type" value="0" checked
                                                        required>
                                                    <span>Free</span>
                                                </label>
                                                <label class="radio-label">
                                                    <input type="radio" name="blog_type" value="1">
                                                    <span>Premium</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <!-- Submit and Preview Buttons -->
                                <div class="d-flex justify-content-between">

                                    <button type="submit" class="custom-btn-primary btn w-50 mx-auto">Post</button>
                                    <button type="button" class="btn btn-secondary" onclick="openPreview()"><i
                                            class="ri-eye-line"></i> Preview</button>

                                </div>
                            </form>


                        </div>
                    </div>
                </div>



            </div>
        </section>
    @endif


    {{-- top new section  start --}}
    <section class="about-us trending pb-0">
        <div class="container">
            <div class="about-image-box">
                <div class="row">
                    <!-- Main Content Section -->
                    <div class="col-lg-8">
                        <div class="section-title mb-4 pb-1">
                            <h3 class="m-0">Top New Post</h3>
                        </div>

                        <!-- Blog Cards Section -->

                        {{-- <div class="trend-item position-relative d-flex align-items-center box-shadow p-4 mb-4"
                            onclick="handleBlogClick(event, '{{ route('blogsDetails.show', $blogs['slug']) }}', '{{ route('subscriptions.index') }}', {{ $blogs['blog_type'] === true ? 'true' : 'false' }}, {{ canAccessPremiumBlog() ? 'true' : 'false' }});"
                            style="cursor: pointer;">

                            @if ($blogs['blog_type'] === true)
                                <!-- Premium Blog Accessible -->
                                <div class="premium-ribbon" data-tooltip="You have access to this premium blog.">
                                    <img src="{{ asset('theme/frontend/assets/images/icon/premium.png') }}"
                                        alt="Premium Blog">
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-lg-5 px-0">
                                    @php
                                        $thumbnail = !empty($blogs['thumbnail'])
                                            ? asset($blogs['thumbnail'])
                                            : asset('logo/em_blog.png');
                                    @endphp
                                    <div class="image-wrapper">
                                        <img style="width: 100%; height: 100%;" src="{{ $thumbnail }}"
                                            alt="img">
                                    </div>
                                </div>

                                <div class="col-lg-7">
                                    <div class="trend-content-main">
                                        <div class="trend-content">
                                            <h5 class="mb-2 theme">
                                                @foreach ($blogs['categories'] as $category)
                                                    {{ $category['name'] }}@if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </h5>

                                            <h4 class="border-b pb-2">
                                                <!-- Accessible Blog content-->
                                                <a>
                                                    {{ Str::limit($blogs['title'], 60) }}
                                                </a>
                                            </h4>

                                            <div
                                                class="entry-meta d-flex align-items-center justify-content-between border-b pb-2 mb-2">
                                                <a href="#"><i class="fa fa-calendar"></i>
                                                    {{ banglaDate($blogs['created_at']) }}</a>
                                                <ul class="entry-metalist d-flex align-items-center">
                                                    <li class="me-2"><i class="fa fa-eye"></i>
                                                        {{ formatViews($blogs['total_views'] ?? 0) }}</li>
                                                    <li class="me-2"><i class="fa fa-heart"></i>
                                                        {{ formatViews($blogs['likes_count'] ?? 0) }}</li>
                                                    <li><i class="fa fa-comments"></i>
                                                        {{ formatViews($blogs['comments_count'] ?? 0) }}</li>
                                                </ul>
                                            </div>

                                            {!! Str::limit(strip_tags($blogs['content']), 200) !!}
                                            <!-- "See More" Button -->
                                            <a href="{{ route('blogsDetails.show', $blogs['slug']) }}"
                                                class="text-danger mt-4">
                                                See More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                        <div class="trend-item position-relative d-flex align-items-center box-shadow p-4 mb-4"
                            onclick="handleBlogClick(event, '{{ route('blogsDetails.show', $blogs['slug']) }}', '{{ route('subscriptions.index') }}', {{ $blogs['blog_type'] === true ? 'true' : 'false' }}, {{ canAccessPremiumBlog() ? 'true' : 'false' }});"
                            style="cursor: pointer;">

                            @if ($blogs['blog_type'] === true)
                                <!-- Premium Blog Accessible -->
                                <div class="premium-ribbon" data-tooltip="You have access to this premium blog.">
                                    <img src="{{ asset('theme/frontend/assets/images/icon/premium.png') }}"
                                        alt="Premium Blog">
                                </div>
                            @endif

                            <div class="row w-100 g-3 align-items-center">
                                <!-- Image Section -->
                                <div class="col-md-5 col-12 px-0">
                                    @php
                                        $thumbnail = !empty($blogs['thumbnail'])
                                            ? asset($blogs['thumbnail'])
                                            : asset('logo/em_blog.png');
                                    @endphp
                                    <div class="image-wrapper text-center">
                                        <img class="img-fluid rounded w-100" src="{{ $thumbnail }}" alt="img">
                                    </div>
                                </div>

                                <!-- Content Section -->
                                <div class="col-md-7 col-12">
                                    <div class="trend-content-main">
                                        <div class="trend-content">
                                            <h5 class="mb-2 theme">
                                                @foreach ($blogs['categories'] as $category)
                                                    {{ $category['name'] }}@if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </h5>

                                            <h4 class="border-b pb-2">
                                                <!-- Accessible Blog content-->
                                                <a class="d-block text-dark fw-bold">
                                                    {{ Str::limit($blogs['title'], 60) }}
                                                </a>
                                            </h4>

                                            <div
                                                class="entry-meta d-flex flex-wrap align-items-center justify-content-between border-b pb-2 mb-2">
                                                <a href="#"><i class="fa fa-calendar"></i>
                                                    {{ banglaDate($blogs['created_at']) }}</a>
                                                <ul class="entry-metalist d-flex align-items-center list-unstyled">
                                                    <li class="me-2"><i class="fa fa-eye"></i>
                                                        {{ formatViews($blogs['total_views'] ?? 0) }}</li>
                                                    <li class="me-2"><i class="fa fa-heart"></i>
                                                        {{ formatViews($blogs['likes_count'] ?? 0) }}</li>
                                                    <li><i class="fa fa-comments"></i>
                                                        {{ formatViews($blogs['comments_count'] ?? 0) }}</li>
                                                </ul>
                                            </div>

                                            <p class="text-muted">{!! Str::limit(strip_tags($blogs['content']), 200) !!}</p>

                                            <!-- "See More" Button -->
                                            <a href="{{ route('blogsDetails.show', $blogs['slug']) }}"
                                                class="text-danger mt-2 d-inline-block">
                                                See More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row" id="blogContainer">

                        </div>
                        <div class="col-lg-12 text-center">
                            <a href="{{ route('bloglist.index') }}" class="nir-btn">আরও</a>
                        </div>
                    </div>

                    <!-- Sidebar Section -->
                    <div class="col-lg-4 col-sm-12 mb-2">
                        <div class="sidebar-sticky">
                            <div class="list-sidebar">
                                <!-- Popular Posts Section -->
                                <div class="sidebar-item mb-2">
                                    <h4>জনপ্রিয় পোস্ট</h4>
                                    @foreach ($popularPosts as $post)
                                        <article class="post mb-3 position-relative box-shadow p-3 bg-white">
                                            @if ($post->blog_type === true)
                                                <div class="premium-ribbon"
                                                    data-tooltip="If you want to read this blog | <a href='#' class='go-premium'>Go Premium</a>">
                                                    <img src="{{ asset('theme/frontend/assets/images/icon/premium.png') }}"
                                                        alt="Premium Blog">
                                                </div>
                                            @endif

                                            <div class="s-content d-flex align-items-center"
                                                onclick="handleBlogClick(event, '{{ route('blogsDetails.show', $post['slug']) }}', '{{ route('subscriptions.index') }}', {{ $post['blog_type'] === true ? 'true' : 'false' }}, {{ canAccessPremiumBlog() ? 'true' : 'false' }});"
                                                style="cursor: pointer;">

                                                <div class="sidebar-image w-25 me-3">
                                                    <!-- Accessible Blog -->
                                                    <a>
                                                        <img class="lazy-load"
                                                            data-src="{{ asset($post->thumbnail ?? 'logo/em_blog.png') }}"
                                                            src="{{ asset('logo/loader.gif') }}"
                                                            alt="{{ $post->title }}">
                                                    </a>

                                                </div>

                                                <div class="content-list w-75">
                                                    <h5 class="mb-1">
                                                        <!-- Accessible Blog -->
                                                        <a>{{ Str::limit($post['title'], 60) }}</a>
                                                    </h5>
                                                    <div class="date">{{ banglaDate($post->created_at) }}</div>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>

                                <!-- Sidebar Advertisement -->
                                {{-- <div class="about-image p-3 box-shadow sidebar-item">
                                        <a href="https://englishmoja.com/product/need">
                                            <img src="{{ asset('theme/frontend/assets/img/banner/Ad-banner.jpg') }}"
                                                class="w-100">
                                        </a>
                                    </div> --}}
                                @if ($banner)
                                    <div class="about-image p-3 box-shadow sidebar-item">
                                        <a href="{{ $banner->link ?? '#' }}">
                                            <img src="{{ asset($banner->image) }}" class="w-100"
                                                alt="{{ $banner->name }}">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- top new section  end --}}

    <!-- trending-topic starts -->

    <section class="trending-topic pt-3 pb-6">
        <div class="container">
            <div class="section-title mb-4 pb-1 w-50">
                <h2 class="m-0">Trending <span>Topics</span></h2>
            </div>
            <div class="trending-topic-main">
                <div class="row shop-slider">
                    @foreach ($trendingTopics as $topic)
                        @php
                            $categoryImage = !empty($topic->cover_image)
                                ? asset($topic->cover_image)
                                : asset('logo/em_blog.png');
                        @endphp

                        <div class="col-lg-3 col-md-6 mb-4"
                            onclick="window.location='{{ route('categoryblogs.blogs', ['slug' => $topic->slug]) }}';"
                            style="cursor: pointer;">

                            <div class="trending-topic-item box-shadow">
                                <div class="trending-topic-image overflow-hidden">
                                    <!-- Category Image -->
                                    <a href="{{ route('categoryblogs.blogs', ['slug' => $topic->slug]) }}">
                                        <img class="lazy-load" data-src="{{ $categoryImage }}"
                                            src="{{ asset('logo/loader.gif') }}" alt="{{ $topic->name }}">
                                    </a>

                                    <div class="trending-topic-content">
                                        <h4 class="mb-0 text-center py-1 px-3 bg-white position-absolute">
                                            <a href="{{ route('categoryblogs.blogs', ['slug' => $topic->slug]) }}">
                                                {{ $topic->name }}
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>



    <!-- trending-topic  ends -->


    <!-- subscription starts -->
    <section class="pricing-section py-5">
        <div class="container text-center">
            <div class="section-title mb-4">
                <h2 class="m-0"><span class="highlight">সেরা প্ল্যান</span> সাবস্ক্রাইব করুন</h2>
                <p>আপনার পছন্দসই প্রিমিয়াম ব্লগ পড়ার জন্য সেরা পরিকল্পনা বেছে নিন।</p>
            </div>
            <div class="row">
                @foreach ($packages as $package)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div
                            class="pricing-card box-shadow p-4 rounded {{ $loop->iteration == 2 ? 'premium-plan' : '' }}">
                            <h4 class="plan-title mb-3">{{ $package->name }}</h4>
                            <div class="price mb-4">
                                <span class="currency">৳</span><span
                                    class="amount">{{ number_format($package->current_price, 2) }}</span>
                                <span
                                    class="duration">/{{ $package->duration > 1 ? $package->duration . ' দিন' : 'দিন' }}</span>
                            </div>
                            <ul class="plan-features list-unstyled mb-4">
                                @foreach ($package->features as $feature)
                                    <li>
                                        @if ($feature->icon)
                                            <i class="text-success mx-2 fas  fa-check"></i> {{-- Green check ✔ --}}
                                        @else
                                            <i class="text-danger mx-2 fas  fa-times"></i> {{-- Red cross ✖ --}}
                                        @endif
                                        {{ $feature->name }}
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('subscriptions.checkout', $package->id) }}"
                                class="btn {{ $loop->iteration == 2 ? 'btn-success' : 'btn-primary' }} w-100">সাবস্ক্রাইব

                                করুন</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!--subscription ends -->


    <!-- More Featured Starts -->
    <section class="trending pt-0 ptop">
        <div class="container">
            <div class="section-title mb-4 pb-1 w-50">
                <h2 class="m-0 mt-3">More Featured <span>Posts</span></h2>
            </div>
            <div class="trend-box">
                <div class="row">
                    @foreach ($featuredBlogs as $blog)
                        <div class="col-lg-6 mb-4">
                            <div class="trend-item d-flex align-items-center shadow p-4 {{ $loop->iteration % 2 == 0 ? 'flex-row-reverse' : '' }}"
                                onclick="handleBlogClick(event, '{{ route('blogsDetails.show', $blog['slug']) }}', '{{ route('subscriptions.index') }}', {{ $blog['blog_type'] === true ? 'true' : 'false' }}, {{ canAccessPremiumBlog() ? 'true' : 'false' }});"
                                style="cursor: pointer;">
                                <div class="trend-content-main {{ $loop->iteration % 2 == 0 ? 'mx-4' : 'me-4' }} w-75">
                                    @if ($blog['blog_type'] === true)
                                        <div class="premium-ribbon"
                                            data-tooltip="If you want to read this blog | <a href='#' class='go-premium'>Go Premium</a>">
                                            <img src="{{ asset('theme/frontend/assets/images/icon/premium.png') }}"
                                                alt="Premium Blog">
                                        </div>
                                    @endif
                                    <div class="trend-content">
                                        <h5 class="theme">
                                            {{ !empty($blog['categories']) ? implode(', ', $blog['categories']) : 'Uncategorized' }}
                                        </h5>
                                        <h4>
                                            <!-- Accessible Blog -->
                                            <a>{{ Str::limit($blog['title'], 80) }}</a>
                                        </h4>
                                        <div class="entry-meta d-flex align-items-center justify-content-between">

                                            <div class="entry-author">
                                                <a>
                                                    <img class="lazy-load rounded-circle me-1"
                                                        data-src="{{ asset($blog['icon']) }}"
                                                        src="{{ asset('theme/frontend/assets/images/user.png') }}"
                                                        alt="{{ $blog['author'] }}">
                                                </a>
                                                <span class="d-none d-md-inline">{{ $blog['author'] }}</span>
                                            </div>


                                            <ul class="entry-metalist d-flex align-items-center">
                                                <li class="me-2"><a><i class="fa fa-eye"></i>
                                                        {{ formatViews($blog['views']) }}</a></li>
                                                <li class="me-2"><a><i class="fa fa-heart"></i>
                                                        {{ formatViews($blog['likes']) }}</a></li>
                                                <li><a><i class="fa fa-comments"></i>
                                                        {{ formatViews($blog['comments']) }}</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="trend-image w-25">
                                    <img src="{{ asset($blog['thumbnail']) }}" alt="{{ $blog['title'] }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-lg-12">
                        <div class="trend-btn text-center">
                            <a href="{{ route('bloglist.index') }}" class="nir-btn">আরও দেখুন</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- More Featured Ends -->



    <!-- More Featured Ends -->

    <!-- Counter Section -->
    <section class="counter-main pb-6"
        style="background: url('{{ asset('theme/frontend/assets/images/bg/1.jpg') }}'); background-size: cover;">
        <div class="container">
            <div class="counter text-center">
                <div class="row">


                    <!-- Published Blogs -->

                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mb-4">
                        <div class="counter-item bg-lgrey">
                            <i class="fa fa-file-alt white bg-theme mb-1"></i>
                            <h3 class="value mb-0 theme">
                                {{ App\Models\Blog::where('blog_type', '0')->count() }}
                            </h3>
                            <h4 class="m-0">ফ্রি ব্লগ</h4>
                        </div>
                    </div>



                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mb-4">
                        <div class="counter-item bg-lgrey">
                            <i class="fa fa-calendar-alt white bg-theme mb-1"></i>

                            <h3 class="value mb-0 theme">
                                {{ App\Models\Blog::where('blog_type', '1')->count() }}
                            </h3>
                            <h4 class="m-0">প্রিমিয়াম ব্লগ </h4>
                        </div>
                    </div>


                    <!-- Happy Readers -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mb-4">
                        <div class="counter-item bg-lgrey">
                            <i class="fa fa-users white bg-theme mb-1"></i>
                            <h3 class="value mb-0 theme">{{ App\Models\Point::count() }}</h3>
                            <h4 class="m-0">শেয়ার</h4>
                        </div>
                    </div>

                    <!-- Years of Blogging -->

                    <!-- Support Provided -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mb-4">
                        <div class="counter-item bg-lgrey">
                            <i class="fa fa-globe white bg-theme mb-1"></i>
                            <h3 class="value mb-0 theme">{{ App\Models\Comment::count() }}</h3>
                            <h4 class="m-0">মন্তব্য</h4>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="overlay"></div>
    </section>
    <!-- End Counter Section -->

    <!-- top deal/trending starts -->


    <section class="trending recent-articles pb-4">
        <div class="container">
            <div class="section-title mb-4 pb-1 w-50">
                <h2 class="m-0">সাম্প্রতিক <span>লেখা ও পোস্ট</span></h2>
            </div>
            <div class="recent-articles-inner">
                <div class="row">
                    <!-- First 3 Cards -->
                    @foreach ($detailedBlogs as $blog)
                        <div class="col-lg-4 mb-4"
                            onclick="handleBlogClick(event, '{{ route('blogsDetails.show', $blog['slug']) }}', '{{ route('subscriptions.index') }}', {{ $blog['blog_type'] === true ? 'true' : 'false' }}, {{ canAccessPremiumBlog() ? 'true' : 'false' }});"
                            style="cursor: pointer;">
                            <div class="trend-item box-shadow bg-white p-4 mb-2">
                                @if ($blog['blog_type'] === true)
                                    <div class="premium-ribbon"
                                        data-tooltip="If you want to read this blog | <a href='#' class='go-premium'>Go Premium</a>">
                                        <img src="{{ asset('theme/frontend/assets/images/icon/premium.png') }}"
                                            alt="Premium Blog">
                                    </div>
                                @endif
                                <div class="trend-image">
                                    @php
                                        $thumbnail = !empty($blog->thumbnail)
                                            ? asset($blog->thumbnail)
                                            : asset('logo/loader.gif');
                                    @endphp
                                    <!-- Accessible Blog -->

                                    <img class="lazy-load" data-src="{{ $thumbnail }}"
                                        src="{{ asset('logo/loader.gif') }}" alt="{{ $blog->title }}"
                                        style="max-height: 250px; height: auto; width: 100%; min-height: 250px; object-fit: cover;">

                                </div>
                                <div class="trend-content-main pt-3">
                                    <div class="trend-content">
                                        <h5 class="theme">
                                            {{ $blog->categories->pluck('name')->join(', ') }}
                                        </h5>
                                        <h4 class="custom_height">

                                            <!-- Accessible Blog -->
                                            <a>{{ Str::limit($blog['title'], 60) }}</a>

                                        </h4>
                                        <p class="mb-3 mt-4">
                                            {{ Str::limit(strip_tags($blog->content), 70) }}
                                            <a href="{{ route('blogsDetails.show', $blogs['slug']) }}"
                                                class="text-danger">
                                                See More
                                            </a>
                                        </p>


                                        <div class="entry-meta d-flex align-items-center justify-content-between">
                                            <div class="entry-author d-flex align-items-center">
                                                <img class="lazy-load rounded-circle me-2"
                                                    data-src="{{ asset($blog->author->icon ?? 'theme/frontend/assets/images/user.png') }}"
                                                    src="{{ asset('logo/loader.gif') }}"
                                                    alt="{{ $blog->author->name ?? 'M.Rafique' }}">

                                                <!-- Hide author name on small screens -->
                                                <span
                                                    class="d-none d-md-inline">{{ $blog->author->name ?? 'M.Rafique' }}</span>
                                            </div>

                                            <ul class="entry-metalist d-flex align-items-center">
                                                <li class="me-2"><a><i class="fa fa-eye"></i>
                                                        {{ formatViews($blog['total_views'] ?? 0) }}</a></li>
                                                <li class="me-2"><a><i class="fa fa-heart"></i>
                                                        {{ formatViews($blog['likes_count'] ?? 0) }}</a></li>
                                                <li><a><i class="fa fa-comments"></i>
                                                        {{ formatViews($blog['comments_count'] ?? 0) }}</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Last 4 Cards -->
                <div class="row">
                    @foreach ($minimalBlogs as $blog)
                        <div class="col-lg-4 mb-4"
                            onclick="handleBlogClick(event, '{{ route('blogsDetails.show', $blog['slug']) }}', '{{ route('subscriptions.index') }}', {{ $blog['blog_type'] === true ? 'true' : 'false' }}, {{ canAccessPremiumBlog() ? 'true' : 'false' }});"
                            style="cursor: pointer;">
                            <div class="trend-item d-flex align-items-center box-shadow p-3 mb-2 bg-white">
                                @if ($blog['blog_type'] === true)
                                    <div class="premium-ribbon"
                                        data-tooltip="If you want to read this blog | <a href='#' class='go-premium'>Go Premium</a>">
                                        <img src="{{ asset('theme/frontend/assets/images/icon/premium.png') }}"
                                            alt="Premium Blog">
                                    </div>
                                @endif
                                <div class="trend-image w-25 me-4">
                                    <!-- Accessible Blog -->
                                    <img class="lazy-load" data-src="{{ asset($blog->thumbnail) }}"
                                        src="{{ asset('logo/loader.gif') }}" alt="{{ $blog->title }}">
                                </div>
                                <div class="trend-content-main w-75">
                                    <div class="trend-content">
                                        <h4 class="mb-1">

                                            <!-- Accessible Blog -->
                                            <a>{{ $blog->title }}</a>

                                        </h4>
                                        <small class="grey">
                                            <i class="fa fa-calendar"></i> {{ banglaDate($blog->created_at) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>


    <!-- top deal/trending end -->





    <!-- App Promotion Section -->
    <section class="app-promotion py-5"
        style="background: url('{{ asset('theme/frontend/assets/images/bg/bg1.jpg') }}'); background-position: bottom;">


        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="app-content text-white">
                        <h2 class="mb-3 display-4 fw-bold text-center text-white">আমাদের মোবাইল<br>অ্যাপ ডাউনলোড করুন
                        </h2>
                        <div class="app-buttons text-center">
                            <a href="https://play.google.com/store/apps/details?id=com.nihazmi.englishmoja&pcampaignid=web_share"
                                class="download-btn">
                                <i class="fab fa-google-play"></i>
                                <span class="btn-text"><strong>Google Play</strong></span>
                                <div class="btn-bg-gradient"></div>
                            </a>
                        </div>


                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="app-image position-relative">
                        <img src="https://englishmoja.com/theme/frontend/assets/img/banner/app2.png" alt="Mobile App"
                            class="img-fluid floating-animation">
                        <div class="app-badge position-absolute top-0 end-0 bg-warning text-dark p-2 rounded-pill">
                            <i class="fas fa-star"></i> 5.0 Rating
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- App Promotion Section end  -->

    <!-- Blog Reviews Section -->

    <section class="testimonial pb-6">
        <div class="container">
            <div class="section-title mb-4 pb-1 w-50">

                <h2 class="m-0">পাঠকদের <span>অভিমত</span></h2>

            </div>
            <div class="row review-slider">
                @php
                    $comments = App\Models\Review::with('user')->get();
                @endphp


                @foreach ($comments as $comment)
                    <div class="col-sm-4 item">
                        <div class="testimonial-item1 text-center">
                            <div class="details">
                                <p class="m-0">{{ $comment->comment }}</p>
                            </div>
                            <div class="author-info mt-2">
                                <a href="#"><img
                                        src="{{ asset($comment->image ?? 'theme/frontend/assets/images/icon/defualt_user.png') }}"
                                        alt="image"></a>
                                <div class="author-title">
                                    <h4 class="m-0 theme">{{ $comment->name }}</h4>

                                </div>
                            </div>
                            <i class="fa fa-quote-left mb-2"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!--  ends -->


    <!-- Content Line Start -->
    <div class="content-line bg-theme pb-6 pt-6">
        <div class="container">
            <div class="content-line-inner">
                <div class="row d-md-flex align-items-center justify-content-between">
                    <div class="col-lg-9 col-md-9">
                        <p class="mb-0 white h4">
                            নতুন কিছু শিখুন! আপনার জ্ঞান বাড়াতে আমাদের ব্লগ পড়ুন।
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <a href="{{ route('bloglist.index') }}" class="nir-btn-black">আরও ব্লগ পড়ুন</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Line End -->




    <!-- cta-horizon starts -->

    @include('frontend.modules.blogs.newsletter')

    <!-- cta-horizon Ends -->
@endsection



@section('scripts')
    @if (Auth::check() && Auth::user()->is_admin == 1 && $device === 'PC')
        <script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js" crossorigin></script>
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css" crossorigin>

        <script>
            let editorInstance;
            $(document).ready(function() {


                // To store the CKEditor instance

                const {
                    ClassicEditor,
                    Alignment,
                    Autoformat,
                    AutoImage,
                    AutoLink,
                    Autosave,
                    BlockQuote,
                    Bold,
                    Bookmark,
                    Code,
                    CodeBlock,
                    Essentials,
                    FindAndReplace,
                    FontBackgroundColor,
                    FontColor,
                    FontFamily,
                    FontSize,
                    GeneralHtmlSupport,
                    Heading,
                    Highlight,
                    HorizontalLine,
                    HtmlEmbed,
                    ImageBlock,
                    ImageCaption,
                    ImageInline,
                    ImageInsert,
                    ImageInsertViaUrl,
                    ImageResize,
                    ImageStyle,
                    ImageTextAlternative,
                    ImageToolbar,
                    ImageUpload,
                    Indent,
                    IndentBlock,
                    Italic,
                    Link,
                    LinkImage,
                    List,
                    ListProperties,
                    MediaEmbed,
                    Mention,
                    PageBreak,
                    Paragraph,
                    PasteFromOffice,
                    RemoveFormat,
                    SimpleUploadAdapter,
                    SpecialCharacters,
                    SpecialCharactersArrows,
                    SpecialCharactersCurrency,
                    SpecialCharactersEssentials,
                    SpecialCharactersLatin,
                    SpecialCharactersMathematical,
                    SpecialCharactersText,
                    Strikethrough,
                    Style,
                    Subscript,
                    Superscript,
                    Table,
                    TableCaption,
                    TableCellProperties,
                    TableColumnResize,
                    TableProperties,
                    TableToolbar,
                    TextTransformation,
                    TodoList,
                    Underline
                } = window.CKEDITOR;

                const LICENSE_KEY =
                    'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3Njk0NzE5OTksImp0aSI6IjNiZmU3NmRlLWM0MjEtNDI0ZC1iZjA3LWNhYzU4NTRmYTVmOCIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCJdLCJ2YyI6ImE0ZWEzNzE2In0.b2JDGshg594h_cCHbFA6andIsicOXG1NDVtNXzbph2yL3CNNEATGX5oCEL-AhhrphnmK-wB-m9uO0UncXJaoJQ';

                const editorConfig = {
                    toolbar: {
                        items: [
                            'findAndReplace',
                            '|',
                            'heading',
                            'style',
                            '|',
                            'fontSize',
                            'fontFamily',
                            'fontColor',
                            'fontBackgroundColor',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'strikethrough',
                            'subscript',
                            'superscript',
                            'code',
                            'removeFormat',
                            '|',
                            'specialCharacters',
                            'horizontalLine',
                            'pageBreak',
                            'link',
                            'bookmark',
                            'insertImage',
                            'insertImageViaUrl',
                            'mediaEmbed',
                            'insertTable',
                            'highlight',
                            'blockQuote',
                            'codeBlock',
                            'htmlEmbed',
                            '|',
                            'alignment',
                            '|',
                            'bulletedList',
                            'numberedList',
                            'todoList',
                            'outdent',
                            'indent',
                            'imageTextAlternative',
                            'imageStyle:inline',
                            'imageStyle:wrapText',
                            'imageStyle:breakText',
                            'imageStyle:side'
                        ],
                        styles: [
                            'inline', 'wrapText', 'breakText', 'side'
                        ],
                        shouldNotGroupWhenFull: false
                    },
                    plugins: [
                        Alignment,
                        Autoformat,
                        AutoImage,
                        AutoLink,
                        Autosave,
                        BlockQuote,
                        Bold,
                        Bookmark,
                        Code,
                        CodeBlock,
                        Essentials,
                        FindAndReplace,
                        FontBackgroundColor,
                        FontColor,
                        FontFamily,
                        FontSize,
                        GeneralHtmlSupport,
                        Heading,
                        Highlight,
                        HorizontalLine,
                        HtmlEmbed,
                        ImageBlock,
                        ImageCaption,
                        ImageInline,
                        ImageInsert,
                        ImageInsertViaUrl,
                        ImageResize,
                        ImageStyle,
                        ImageTextAlternative,
                        ImageToolbar,
                        ImageUpload,
                        Indent,
                        IndentBlock,
                        Italic,
                        Link,
                        LinkImage,
                        List,
                        ListProperties,
                        MediaEmbed,
                        Mention,
                        PageBreak,
                        Paragraph,
                        PasteFromOffice,
                        RemoveFormat,
                        SimpleUploadAdapter,
                        SpecialCharacters,
                        SpecialCharactersArrows,
                        SpecialCharactersCurrency,
                        SpecialCharactersEssentials,
                        SpecialCharactersLatin,
                        SpecialCharactersMathematical,
                        SpecialCharactersText,
                        Strikethrough,
                        Style,
                        Subscript,
                        Superscript,
                        Table,
                        TableCaption,
                        TableCellProperties,
                        TableColumnResize,
                        TableProperties,
                        TableToolbar,
                        TextTransformation,
                        TodoList,
                        Underline
                    ],
                    simpleUpload: {
                        uploadUrl: '{{ route('ck.upload', ['_token' => csrf_token()]) }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    placeholder: 'Type or paste your content here!',
                    licenseKey: LICENSE_KEY
                };

                // Initialize CKEditor
                // ClassicEditor.create(document.querySelector('.ck'), editorConfig)
                //     .then(editor => {
                //         editorInstance = editor; // Store the editor instance
                //         console.log('CKEditor initialized successfully:', editor);

                //         // Attach a click event listener to the save button
                //         document.getElementById('save-button').addEventListener('click', (e) => {
                //             e.preventDefault(); // Prevent default form submission

                //             if (editorInstance) {
                //                 const htmlContent = editorInstance.getData(); // Get the editor content
                //                 if (htmlContent.trim() === '') {
                //                     console.warn('Editor content is empty!');
                //                 } else {
                //                     console.log('Editor content on submission:', htmlContent);
                //                     // Process the content (e.g., save to the server or handle it in JS)
                //                 }
                //             } else {
                //                 console.error('Editor instance is not ready.');
                //             }
                //         });
                //     })
                //     .catch(error => {
                //         console.error('There was a problem initializing CKEditor:', error);
                //     });


                // Initialize CKEditor
                ClassicEditor.create(document.querySelector('.ck'), editorConfig)
                    .then(editor => {
                        editorInstance = editor; // Store the editor instance
                        console.log('CKEditor initialized successfully:', editor);

                        // Ensure that the button exists before adding an event listener
                        const saveButton = document.getElementById('save-button');
                        if (saveButton) {
                            saveButton.addEventListener('click', (e) => {
                                e.preventDefault(); // Prevent default form submission

                                if (editorInstance) {
                                    const htmlContent = editorInstance.getData(); // Get the editor content
                                    if (htmlContent.trim() === '') {
                                        console.warn('Editor content is empty!');
                                    } else {
                                        console.log('Editor content on submission:', htmlContent);

                                    }
                                } else {
                                    console.error('Editor instance is not ready.');
                                }
                            });
                        } else {
                            console.warn("Save button with ID 'save-button' not found.");
                        }
                    })
                    .catch(error => {
                        console.error('There was a problem initializing CKEditor:', error);
                    });


            })
        </script>
    @endif


    <script>
        $(document).ready(function() {
            //blog card fatch data
            $.ajax({
                url: "{{ route('blogs.fatchcard') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#blogContainer').html(response.blogs.join(''));

                        observeLazyLoadImages();
                    }
                },
                error: function() {
                    console.error('Failed to fetch blogs.');
                },
            });

            function generateSlug(title) {

                var pattern = /[a-zA-Z0-9\u0980-\u09FF]+/g;

                return title.toLowerCase().match(pattern).join('-');
            }

            // Event listener for name field
            $('#title').on('input', function() {
                var title = $(this).val();
                var slug = title ? generateSlug(title) : null;
                $('#slug').val(slug);

            });

            // Initialize Select2 with "createTag" feature
            $(".multi_select").select2({
                tags: true, // Enable creating new tags
                tokenSeparators: [','], // Allow comma-separated values
                createTag: function(params) {
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: term,
                        text: term
                    };
                },
                maximumInputLength: 50,
            });

            // Handle form submission
            $('#blogForm').on('submit', function(e) {
                e.preventDefault();

                if (editorInstance) {
                    editorInstance.updateSourceElement();
                }

                const formData = new FormData(this);

                // Collect selected categories (ID or name)
                const selectedCategories = $('.multi_select[name="category_ids[]"]').val();
                selectedCategories.forEach(function(category) {
                    formData.append('category_ids[]', category);
                });

                $.ajax({
                    url: '{{ route('blogs.store') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Blog Posted!',
                            text: 'Your blog has been posted successfully.',
                            timer: 1000,
                            showConfirmButton: false,
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            const errorMessages = Object.values(errors)
                                .flat()
                                .join('\n');
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Errors',
                                text: errorMessages,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An unexpected error occurred.',
                            });
                        }
                        console.error('Error:', xhr);
                    },
                });
            });


        });


        $(document).ready(function() {

            $('#createComments').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('suscribe.newsletter') }}',
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

            $('.reply-comment-form').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('reply.comment') }}',
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


        $(document).ready(function() {

            $('#createComments').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('suscribe.newsletter') }}',
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

            $('.reply-comment-form').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('reply.comment') }}',
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




        ///-->image preview function start <--///
        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById("imagePreviewContainer");
            const previewImage = document.getElementById("imagePreview");

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = "block"; // Show preview image
                };

                reader.readAsDataURL(input.files[0]); // Convert image to base64 URL
            } else {
                previewContainer.style.display = "none"; // Hide preview if no file selected
            }
        }

        ///-->image preview function end <--///


        ///----->blog preview functionality start<------///

        function openPreview() {
            let title = document.getElementById("title").value;
            let content = editorInstance.getData();
            let thumbnailInput = document.getElementById("thumbImage");
            let blogType = document.querySelector("input[name='blog_type']:checked").value;

            let imageURL = "{{ asset('logo/em_blog.png') }}";
            if (thumbnailInput.files.length > 0) {
                let file = thumbnailInput.files[0];
                imageURL = URL.createObjectURL(file);
            }

            // Get current date
            let today = new Date();

            // Convert to Bangla format
            let banglaDay = new Intl.NumberFormat('bn-BD').format(today.getDate());
            let banglaMonth = today.toLocaleString('bn-BD', {
                month: 'long'
            });

            // Open a new blank tab
            let previewWindow = window.open("", "_blank");

            // Generate and inject the Laravel Blade design dynamically
            previewWindow.document.write(`
        <html>
        <head>
            <title>Preview: ${title}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="{{ asset('theme/frontend/assets/css/style.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('theme/frontend/assets/css/bintel.css') }}" rel="stylesheet" type="text/css">
        </head>
        <body style="background-image: url('{{ asset('theme/frontend/assets/images/2.png') }}'); background-size: cover;">
            
            <section class="blog blog-left pt-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9 col-md-12 col-sm-12">
                            <div class="blog-single">

                                <!-- Blog Header -->
                                <div class="blog-single-in align-items-center d-md-flex bg-navy ">
                                    <div class="blog-date w-25 me-4">
                                        <div class="date text-center bg-theme p-2">
                                            <h1 class="day mb-0 white">${banglaDay}</h1>
                                            <div class="month white">${banglaMonth}</div>
                                        </div>
                                    </div>
                                    <div class="blog-single-in-cont w-75">
                                        <div class="blog-content">
                                            <h3 class="blog-title mb-0">
                                                <a href="#" class="white">${title}</a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                 <div class="blog-wrapper">
                            <div class="blog-content">
                                <!-- Blog Image -->
                                <div class="blog-imagelist d-flex justify-content-center mb-3">
                                    <img src="${imageURL}" class="img-fluid" alt="Blog Preview Image">
                                </div>

                                <!-- Blog Content -->
                                <div class="blog_content_img">
                                    <div>${content}</div>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </body>
        </html>
    `);

            previewWindow.document.close();
        }
        ///----->blog preview functionality end<------///


        //Auto - trigger modal using JavaScript//

        //     document.addEventListener("DOMContentLoaded", function() {
        //         let loginModal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
        //         loginModal.show();
        //     });



        // const newsletterPopup = function() {
        //     let newsletterWrapper = document.querySelector(".newsletter__popup"),
        //         newsletterCloseButton = document.querySelector(
        //             ".newsletter__popup--close__btn"
        //         ),
        //         dontShowPopup = document.querySelector("#newsletter__dont--show"),
        //         popuDontShowMode = localStorage.getItem("newsletter__show");

        //     if (newsletterWrapper && popuDontShowMode == null) {
        //         window.addEventListener("load", (event) => {
        //             setTimeout(function() {
        //                 document.body.classList.add("overlay__active");
        //                 newsletterWrapper.classList.add("newsletter__show");

        //                 document.addEventListener("click", function(event) {
        //                     if (!event.target.closest(".newsletter__popup--inner")) {
        //                         document.body.classList.remove("overlay__active");
        //                         newsletterWrapper.classList.remove("newsletter__show");
        //                     }
        //                 });

        //                 newsletterCloseButton.addEventListener("click", function() {
        //                     document.body.classList.remove("overlay__active");
        //                     newsletterWrapper.classList.remove("newsletter__show");
        //                 });

        //                 dontShowPopup.addEventListener("click", function() {
        //                     if (dontShowPopup.checked) {
        //                         localStorage.setItem("newsletter__show", true);
        //                     } else {
        //                         localStorage.removeItem("newsletter__show");
        //                     }
        //                 });
        //             }, 3000);
        //         });
        //     }
        // };
        // newsletterPopup();

        // Function to set a cookie
        // Function to set a cookie
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        }

        // Function to get a cookie value
        function getCookie(name) {
            let nameEQ = name + "=";
            let cookiesArray = document.cookie.split(';');
            for (let i = 0; i < cookiesArray.length; i++) {
                let cookie = cookiesArray[i].trim();
                if (cookie.indexOf(nameEQ) == 0) return cookie.substring(nameEQ.length, cookie.length);
            }
            return null;
        }

        // Newsletter modal function
        const newsletterPopup = function() {
            let newsletterWrapper = document.querySelector(".newsletter__popup"),
                newsletterCloseButton = document.querySelector(".newsletter__popup--close__btn"),
                dontShowPopup = document.querySelector("#newsletter__dont--show"),
                lastShownDate = getCookie("newsletter_last_shown"),
                today = new Date().toISOString().split('T')[0];

            // Check if session has login_success (only set by PHP on successful login)
            let showPopup = document.body.getAttribute("data-login-success");

            if (newsletterWrapper && showPopup && lastShownDate !== today) {
                window.addEventListener("load", () => {
                    setTimeout(() => {
                        document.body.classList.add("overlay__active");
                        newsletterWrapper.classList.add("newsletter__show");

                        document.addEventListener("click", function(event) {
                            if (!event.target.closest(".newsletter__popup--inner")) {
                                document.body.classList.remove("overlay__active");
                                newsletterWrapper.classList.remove("newsletter__show");
                            }
                        });

                        newsletterCloseButton.addEventListener("click", function() {
                            document.body.classList.remove("overlay__active");
                            newsletterWrapper.classList.remove("newsletter__show");

                            // Set cookie to remember this for the day
                            setCookie("newsletter_last_shown", today, 1);
                        });

                        if (dontShowPopup) {
                            dontShowPopup.addEventListener("click", function() {
                                if (dontShowPopup.checked) {
                                    setCookie("newsletter_last_shown", today, 1);
                                }
                            });
                        }
                    }, 3000);
                });
            }
        };

        // Call the function
        newsletterPopup();
    </script>
@endsection
