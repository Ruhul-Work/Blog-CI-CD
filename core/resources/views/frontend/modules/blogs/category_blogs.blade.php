@extends('frontend.layouts.master')

@section('meta')
    <title>All Blogs of {{ $category->name }} | {{ get_option('title') }}</title>
    <meta property="og:title" content="{{ get_option('title') }}">
    <meta property="og:description" content="{{ strip_tags(get_option('description')) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset(get_option('meta_image')) }}">
@endsection

@section('content')
    <!-- BreadCrumb Starts -->
    <section class="breadcrumb-main pb-0 pt-6" style="background-image: url(images/bg/1.jpg);">
        <div class="breadcrumb-outer">
            <div class="container">
                <div class="breadcrumb-content d-md-flex align-items-center pt-6">
                    <h2 class="mb-2 py-2">ক্যাটাগরি ব্লগ</h2>
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb mb-3 py-2">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ক্যাটাগরি ব্লগ</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="dot-overlay"></div>
    </section>
    <!-- BreadCrumb Ends -->

    <!-- blog starts -->
    <section class="blog blog-left">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="listing-inner">
                        <div class="list-results d-flex align-items-center justify-content-between">
                            <div class="list-results-sort">
                                <p class="m-0">Showing {{ $blogs->firstItem() }}-{{ $blogs->lastItem() }} of
                                    {{ $blogs->total() }} results</p>
                            </div>
                            <div class="click-menu d-flex align-items-center justify-content-between">

                                <div class="sortby d-flex align-items-center justify-content-between ml-2">
                                    <select id="sortBlogs" class="niceSelect">
                                        <option value="all">সব</option>
                                        <option value="latest">সর্বশেষ ব্লগ</option>
                                        <option value="popular">সর্বাধিক পঠিত</option>
                                        <option value="most_commented">সর্বাধিক মন্তব্য</option>
                                        <option value="premium">প্রিমিয়াম ব্লগ</option>
                                        <option value="free">ফ্রি ব্লগ</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        {{-- blog card start --}}
                        <div class="row" id="blogContainer">
                            @forelse ($blogs as $blog)
                                @include('components.frontend.blog-card', ['blog' => $blog])
                            @empty
                                <p class="text-center">No blogs found in this category.</p>
                            @endforelse
                        </div>

                        <div class="pagination-main text-center" id="paginationContainer">
                            {{ $blogs->links('frontend.modules.pagination.custom_paginate') }}
                        </div>
                    </div>
                </div>

                <!-- sidebar starts -->

                <div class="col-lg-3 col-md-12">
                    @include('components.frontend.blog_filter', [
                        'categories' => $categories,
                    ])

                </div>
            </div>
    </section>
    <!-- blog Ends -->

@endsection


