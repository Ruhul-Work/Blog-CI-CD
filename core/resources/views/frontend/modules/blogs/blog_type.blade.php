@extends('frontend.layouts.master')

@section('meta')
    <title>{{ $pageTitle ?? 'সকল ব্লগ' }} | {{ get_option('title') }}</title>

    <meta property="og:title" content="{{ $pageTitle ?? 'সকল ব্লগ' }} | {{ get_option('title') }}">
    <meta property="og:description" content="{{ get_option('description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset(get_option('meta_image')) }}">
    <meta property="og:site_name" content="{{ get_option('company_name') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle ?? 'সকল ব্লগ' }} | {{ get_option('title') }}">
    <meta name="twitter:description" content="{{ get_option('description') }}">
    <meta name="twitter:image" content="{{ asset(get_option('meta_image')) }}">
@endsection

@section('content')
    <!-- Breadcrumb Section -->
    <section class="breadcrumb-main pb-0 pt-6" style="background-image: url(images/bg/1.jpg);">
        <div class="breadcrumb-outer">
            <div class="container">
                <div class="breadcrumb-content d-md-flex align-items-center pt-6">
                    <h2 class="mb-0  py-3 mt-3">{{ $pageTitle ?? 'সকল ব্লগ' }}</h2>
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                            <li class="breadcrumb-item active " aria-current="page">{{ $pageTitle ?? 'সকল ব্লগ' }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="dot-overlay"></div>
    </section>
    <!-- End Breadcrumb -->

    <!-- Blog Listing -->
    <section class="blog blog-left">
        <div class="container">
          
            <div class="recent-articles-inner">
                <div class="row">
                   
                    @foreach ($blogs as $blog)
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
                                  

                                    <img class="lazy-load" data-src="{{ $thumbnail }}"
                                        src="{{ asset('logo/loader.gif') }}" alt="{{ $blog->title }}"
                                        style="max-height: 250px; height: auto; width: 100%; min-height: 250px; object-fit: cover;">

                                </div>
                                <div class="trend-content-main pt-3">
                                    <div class="trend-content">
                                        <h5 class="theme">
                                            {{ $blog->categories->pluck('name')->join(', ') }}
                                        </h5>
                                        <h4 class="custom_height ">

                                           
                                            <a>{{ Str::limit($blog['title'], 60) }}</a>

                                        </h4>
                                        <p class="mb-3 mt-4">
                                            {{ Str::limit(strip_tags($blog->content), 70) }}
                                            <a href="{{ route('blogsDetails.show', $blog['slug']) }}"
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
                                        
                                                
                                                <span class="d-none d-md-inline">{{ $blog->author->name ?? 'M.Rafique' }}</span>
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

                    <!-- Pagination -->
                    <div class="pagination-main text-center mt-4">
                        {{ $blogs->links('frontend.modules.pagination.custom_paginate') }}
                    </div>

            </div>
        </div>
    </section>
@endsection
