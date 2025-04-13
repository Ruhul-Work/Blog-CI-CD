@extends('frontend.layouts.master')

@section('meta')
    <title>{{ $blog->title ?? 'Blog Details' }} | {{ get_option('title') }}</title>
    <!-- Add more Open Graph tags as needed -->
    <meta property="og:title" content="{{ $blog->title }}">
    <meta property="og:description" content="{{ strip_tags($blog->meta_description) }}">
    <meta property="og:image" content="{{ asset($blog->meta_image ?? $blog->thumbnail) }}">
    <meta property="og:url" content="{{ route('blogsDetails.show', ['slug' => $blog->slug]) }}">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="{{ get_option('title') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title"
        content="{{ $blog->title ?? 'Blog Details' }} | {{ strtolower($blog->meta_title ?? get_option('title')) }}">
    <meta name="twitter:description" content="{{ strip_tags($blog->meta_description ?? get_option('description')) }}">
    <meta name="twitter:image" content="{{ asset($blog->meta_image ?? get_option('meta_image')) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>
        .accordion-body {
            display: none;
        }

        .accordion-body.show {
            display: block;
        }
    </style>

    <!-- Add more Twitter meta tags as needed -->
@endsection

@section('content')
    <!-- blog starts -->
    <section class="blog blog-left pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="blog-single">

                        <div class="blog-single-in align-items-center d-md-flex bg-navy ">
                            <div class="blog-date w-25 me-4">

                                <div class="date text-center bg-theme p-2">
                                    @php
                                        $banglaDateParts = explode(' ', banglaDate($blog->created_at));
                                    @endphp
                                    <h1 class="day mb-0 white">{{ $banglaDateParts[0] }}</h1> <!-- Day -->
                                    <div class="month white">{{ $banglaDateParts[1] }}</div> <!-- Month -->
                                </div>
                            </div>
                            <div class="blog-single-in-cont w-75">
                                <div class="blog-content">
                                    <h3 class="blog-title mb-0"><a href="#" class="white">{{ $blog->title }}</a>
                                    </h3>
                                </div>
                            </div>
                            @if (Auth::check() && Auth::user()->is_admin == 1 && $device === 'PC')
                                <div class="Edit-blog text-center me-3">

                                    <a href="{{ route('blogs.edit', $blog->id) }}" class="text-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                </div>
                            @endif
                        </div>

                        <div class="blog-wrapper">
                            <div class="blog-content">
                                <div class="blog-imagelist d-flex justify-content-center mb-3">
                                    {{-- <img src="{{ asset($blog->thumbnail) }}" alt="{{ $blog->title }}"> --}}
                                    <img class="lazy-load" data-src="{{ asset($blog['thumbnail']) }}"
                                        src="{{ asset('logo/loader.gif') }}">
                                </div>
                                <!-- Blog Content -->
                                @if ($userHasSubscription)
                                    <div class="blog_content_img">
                                        {!! $blog->content !!}
                                    </div>
                                @else
                                    <div class="blog_content_img blur-content">
                                        <p> 🔒 এই কন্টেন্টটি প্রিমিয়াম ব্যবহারকারীদের জন্য। সাবস্ক্রাইব করুন <a
                                                href="{{ route('subscriptions.index') }}"
                                                class="text-danger fs-5 fw-bold">এখানে ক্লিক করুন</a></p>
                                    </div>
                                @endif

                            </div>
                            <!-- blog share -->
                            <div class="blog-share d-flex justify-content-between align-items-center mb-4 bg-lgrey border">

                                <!--</div>-->
                                <div class="blog-share-tag">
                                    <ul class="inline m-0 post-metadata">
                                        <li><strong>লেখক:</strong> <a href="#">এম. রফিক</a></li>
                                        <li><strong>প্রকাশিত:</strong> {{ banglaDate($blog->created_at) }}</li>
                                    </ul>
                                </div>
                                <!-- Social Media Sharing -->
                                <div class="header-social d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-wrap m-0">
                                        <!-- Like Button -->

                                        <div class="me-3 py-2">
                                            <button type="button"
                                                class="btn btn-light d-flex align-items-center px-3 py-1 border rounded">
                                                <i class="fa fa-eye me-2"></i>
                                                {{ formatViews($blog->total_views) }}
                                            </button>
                                        </div>
                                        <div class="me-3 py-2">
                                            <button type="button"
                                                class="btn {{ $isLiked ? 'btn-danger' : 'btn-light' }} d-flex align-items-center px-3 py-1 border rounded like-button"
                                                data-blog-id="{{ $blog->id }}">
                                                <i class="far fa-thumbs-up me-2"></i> লাইক (<span
                                                    id="likes-count-{{ $blog->id }}">{{ $blog->likes_count }}</span>)
                                            </button>
                                        </div>

                                        <!-- Comment Button -->

                                        <div class="me-3 py-2">
                                            <a href="javascript:void(0);"
                                                class="btn btn-light d-flex align-items-center px-3 py-1 border rounded"
                                                id="scrollToComment">
                                                <i class="far fa-comments me-2"></i> মন্তব্য
                                            </a>
                                        </div>
                                        <!-- Share Button -->
                                        <div class="me-3 py-2">
                                            @if (true)
                                                <!-- Trigger button for the dropup -->
                                                <div class="btn-group dropup">
                                                    <button type="button"
                                                        class="btn btn-light d-flex align-items-center px-3 py-1 border rounded share-btn"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-share-alt me-2"></i> শেয়ার
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <!-- Facebook share icon -->
                                                        <li>
                                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blogs.share', ['slug' => $blog->slug, 'ref' => bin2hex(pack('J', Auth::id()))])) }}"
                                                                target="_blank" class="dropdown-item share_link">
                                                                <i class="fab fa-facebook me-2"></i> Facebook
                                                            </a>
                                                        </li>
                                                        <!-- LinkedIn share icon -->

                                                        <li>
                                                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blogs.share', ['slug' => $blog->slug, 'ref' => bin2hex(pack('J', Auth::id()))])) }}&title={{ urlencode($blog->title) }}"
                                                                target="_blank" class="dropdown-item share_link">
                                                                <i class="fab fa-linkedin me-2"></i> LinkedIn
                                                            </a>
                                                        </li>
                                                        <!-- WhatsApp share icon -->
                                                        <li>
                                                            <a href="https://wa.me/?text={{ urlencode(route('blogs.share', ['slug' => $blog->slug, 'ref' => bin2hex(pack('J', Auth::id()))])) }}"
                                                                target="_blank" class="dropdown-item share_link">
                                                                <i class="fab fa-whatsapp me-2"></i> WhatsApp
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            @else
                                                <a href="javascript:void(0);"
                                                    onclick="alert('এই ব্লগটি শেয়ার করার জন্য আপনাকে লগ ইন করতে হবে।')"
                                                    class="btn btn-light d-flex align-items-center px-3 py-1 border rounded share-btn">
                                                    <i class="fas fa-share-alt me-2"></i> শেয়ার
                                                </a>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- author detail -->


                        <!-- blog next prev -->
                        <!--<div class="blog-pagination mb-4 d-flex justify-content-between align-items-center">-->
                        <!-- Previous Page -->
                        <!--    <a href="#" class="prev-page d-flex align-items-center ps-4">-->
                        <!--        <i class="fa fa-arrow-left me-2"></i>-->
                        <!--        <div>-->
                        <!--            <p class="m-0 fw-bold">পূর্ববর্তী পৃষ্ঠা</p>-->
                        <!--            <p class="m-0">আগের পৃষ্ঠার শিরোনাম</p>-->
                        <!--        </div>-->
                        <!--    </a>-->

                        <!-- Next Page -->
                        <!--    <a href="#" class="next-page d-flex align-items-center pe-4 text-end">-->
                        <!--        <div>-->
                        <!--            <p class="m-0 fw-bold">পরবর্তী পৃষ্ঠা</p>-->
                        <!--            <p class="m-0">পরবর্তী পৃষ্ঠার শিরোনাম</p>-->
                        <!--        </div>-->
                        <!--        <i class="fa fa-arrow-right ms-2"></i>-->
                        <!--    </a>-->
                        <!--</div>-->


                        <!-- Blog Comment List -->


                        <div class="single-comments single-box mb-4">
                            {{-- <h4 class="mb-4">{{ $comments->count() }} মন্তব্য</h4> --}}
                            <h4 class="mb-4"> <span id="comments-count">{{ $blog->comments_count }} মন্তব্য</h4>

                            @foreach ($comments as $comment)
                                <style>
                                    .comment-box {
                                        max-width: 950px;
                                        margin: 0 auto;
                                        border: 1px solid #ddd;
                                        padding: 15px;
                                        border-radius: 8px;
                                        width: 100%;
                                        box-sizing: border-box;
                                        overflow: hidden;
                                    }

                                    .comment-image {
                                        width: 60px;
                                        height: 60px;
                                        overflow: hidden;
                                        border-radius: 50%;
                                        margin-right: 15px;
                                    }

                                    .comment-image img {
                                        width: 100%;
                                        height: 100%;
                                        object-fit: cover;
                                    }

                                    .comment-content {
                                        text-align: left;
                                        word-wrap: break-word;
                                        width: 100%;
                                    }

                                    .comment-content h4 {
                                        font-size: 16px;
                                        font-weight: bold;
                                    }

                                    .comment-date {
                                        color: #666;
                                        font-size: 14px;
                                    }

                                    .comment-rate {
                                        margin-top: 10px;
                                    }

                                    .comment {
                                        text-align: justify;
                                        font-size: 14px;
                                    }

                                    .reply-box {
                                        background: #f8f9fa;
                                        padding: 10px;
                                        border-radius: 6px;
                                        margin-top: 10px;
                                    }

                                    .reply-box h6 {
                                        font-size: 14px;
                                        text-align: justify;
                                        color: #3a3a3a;
                                    }


                                    body.night-mode .reply-box {
                                        background: transparent;
                                        border: 1px solid #ddd;
                                    }

                                    body.night-mode .reply-box h6 {
                                        color: #ffffff;
                                    }

                                    .reply-date {
                                        font-size: 12px;
                                        color: #666;
                                    }

                                    .comment-like {
                                        margin-top: 10px;
                                    }

                                    .reply-toggle-btn {
                                        margin-bottom: 10px;
                                        font-size: 14px;
                                    }

                                    @media (max-width: 768px) {
                                        .comment-box {
                                            padding: 10px;
                                        }

                                        .comment-image {
                                            width: 50px;
                                            height: 50px;
                                            margin-right: 10px;
                                        }

                                        .comment-content h4 {
                                            font-size: 14px;
                                        }

                                        .comment-date,
                                        .comment,
                                        .reply-date {
                                            font-size: 12px;
                                        }

                                        .reply-box {
                                            padding: 8px;
                                        }
                                    }

                                    .Edit-blog a i {
                                        font-size: 20px;
                                    }
                                </style>

                                <div class="comment-box">
                                    <div class="comment-image">
                                        <img src="{{ $comment->user->profile_image ?? asset('theme/frontend/assets/images/user.png') }}"
                                            alt="image">
                                    </div>
                                    <div class="comment-content">
                                        <h4><i class="ri-user-3-fill"></i> {{ $comment->user->name }}</h4>
                                        <p class="comment-date">
                                            {{ \Carbon\Carbon::parse($comment->created_at)->format('F d, Y | h:i A') }}</p>
                                        <div class="comment-rate"></div>
                                        <p class="comment">{{ $comment->comment }}</p>
                                        <hr>
                                        <div class="replies">
                                            <button class="nir-btn reply-toggle-btn"
                                                data-toggle="replies-{{ $comment->id }}">উত্তর দেখুন</button>
                                            <div id="replies-{{ $comment->id }}" class="reply-accordion"
                                                style="display: none;">
                                                @foreach ($comment->replies as $reply)
                                                    <div class="reply-box">
                                                        <h6><i class="ri-reply-fill"></i> {{ $reply->user->name }} <i
                                                                class="ri-user-3-fill"></i></h6>
                                                        <p class="reply-date">
                                                            {{ \Carbon\Carbon::parse($reply->created_at)->format('F d, Y | h:i A') }}
                                                        </p>
                                                        <p>{{ $reply->reply }}</p>
                                                    </div>
                                                    <hr>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="comment-like">
                                            <a href="javascript:void(0);" class="nir-btn reply-btn"
                                                data-comment-id="{{ $comment->id }}">উত্তর দিন</a>
                                        </div>
                                    </div>
                                </div>





                                <!-- Hidden reply form -->
                                <div class="reply-form mt-3" id="reply-form-{{ $comment->id }}" style="display: none;">
                                    <form action="{{ route('reply.comment') }}" method="POST"
                                        class="reply-comment-form">
                                        @csrf
                                        <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                        <textarea name="reply" class="form-control answer_area mb-2" placeholder="আপনার উত্তর লিখুন..." required></textarea>
                                        <button type="submit" class="nir-btn mb-3">পাঠান</button>
                                    </form>
                                </div>
                            @endforeach

                        </div>


                        <!-- Blog Review -->

                        @auth
                            <div class="single-add-review comment-section">
                                <h4 class="">মন্তব্য লিখুন</h4>
                                <form action="{{ route('add.comment') }}" method="POST" enctype="multipart/form-data"
                                    id="createComments">
                                    @csrf
                                    <input type="text" name="blog_id" value="{{ $blog->id }}" hidden>
                                    <div class="form-group">
                                        <textarea name="comment" class="comment_area" placeholder="আপনার মন্তব্য লিখুন"></textarea>
                                    </div>
                                    <input type="submit" class='btn btn-success' value="Submit">
                                </form>

                                <!-- Debug Area -->
                                <div id="debug-response" style="margin-top: 20px; color: green;"></div>


                            </div>



                        @endauth
                        @guest
                            <div class="single-add-review ">
                                <h4 class="text-center">কমেন্ট করার জন্য আপনাকে লগইন করতে হবে।

                                    <a href="{{ route('login') }}" class="text-danger">লগইন করুন </a>
                                </h4>

                            </div>
                        @endguest

                    </div>
                </div>

                <!-- sidebar starts -->
                <div class="col-lg-3 col-md-12">
                    <div class="sidebar-sticky">
                        <div class="list-sidebar">

                            @if ($blog->author)
                                <div class="author-news mb-4 box-shadow p-4 text-center">
                                    <div class="author-news-content">
                                        <div class="author-thumb mb-1">
                                            <img src="{{ asset($blog->author->icon) }}" alt="{{ $blog->author->name }}">
                                        </div>
                                        <div class="author-content">
                                            <h3 class="title mb-1">{{ $blog->author->name }}</h3>
                                            <p class="mb-2">{{ $blog->author->description }}</p>
                                            <div class="header-social">
                                                <ul>
                                                    <li><a href="https://www.facebook.com/md.rafiqulislam1979"><i
                                                                class="fab fa-facebook-f"></i></a></li>
                                                    <!--<li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>-->
                                                    <!--<li><a href="#"><i class="fab fa-twitter"></i></a></li>-->
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!-- Author Section -->


                            <div class="sidebar-item mb-4">
                                <h4 class="">সমস্ত ক্যাটাগরি</h4>
                                <ul class="sidebar-category">
                                    <li><a href="{{ route('bloglist.index') }}">সব</a></li>
                                    @foreach ($categories as $category)
                                        <li>
                                            <a
                                                href="{{ route('categoryblogs.blogs', ['slug' => $category->slug]) }}">{{ $category->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>


                            <div class="popular-post sidebar-item mb-4">
                                <div class="sidebar-tabs">
                                    <div class="post-tabs">
                                        <!-- Tab Navigation -->
                                        <ul class="nav nav-tabs nav-pills nav-fill" id="postsTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button aria-controls="popular" aria-selected="true"
                                                    class="nav-link active" data-bs-target="#popular"
                                                    data-bs-toggle="tab" id="popular-tab" role="tab" type="button">
                                                    জনপ্রিয়
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button aria-controls="recent" aria-selected="false" class="nav-link"
                                                    data-bs-target="#recent" data-bs-toggle="tab" id="recent-tab"
                                                    role="tab" type="button">
                                                    সাম্প্রতিক
                                                </button>
                                            </li>
                                        </ul>

                                        <!-- Tab Content -->
                                        <div class="tab-content" id="postsTabContent">
                                            <!-- Popular Posts -->
                                            <div aria-labelledby="popular-tab" class="tab-pane fade active show"
                                                id="popular" role="tabpanel">
                                                @foreach ($popularBlogs as $popular)
                                                    <article class="post mb-3 border-b pb-3">
                                                        <div
                                                            class="s-content d-flex align-items-center justify-space-between">
                                                            <div class="sidebar-image w-25 me-3">
                                                                <a
                                                                    href="{{ route('blogsDetails.show', ['slug' => $popular->slug]) }}">
                                                                    <img src="{{ asset($popular->thumbnail) }}"
                                                                        alt="{{ $popular->title }}">
                                                                </a>
                                                            </div>
                                                            <div class="content-list w-75">
                                                                <h5 class="mb-1">
                                                                    <a
                                                                        href="{{ route('blogsDetails.show', ['slug' => $popular->slug]) }}">
                                                                        {{ Str::limit($popular['title'], 60) }}
                                                                    </a>
                                                                </h5>
                                                                <div class="date">
                                                                    {{ banglaDate($popular->created_at) }}</div>
                                                            </div>
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </div>

                                            <!-- Recent Posts -->
                                            <div aria-labelledby="recent-tab" class="tab-pane fade" id="recent"
                                                role="tabpanel">
                                                @foreach ($recentBlogs as $recent)
                                                    <article class="post mb-3 border-b pb-3">
                                                        <div
                                                            class="s-content d-flex align-items-center justify-space-between">
                                                            <div class="sidebar-image w-25 me-3">
                                                                <a
                                                                    href="{{ route('blogsDetails.show', ['slug' => $recent->slug]) }}">
                                                                    <img src="{{ asset($recent->thumbnail) }}"
                                                                        alt="{{ $recent->title }}">
                                                                </a>
                                                            </div>
                                                            <div class="content-list w-75">
                                                                <h5 class="mb-1">
                                                                    <a
                                                                        href="{{ route('blogsDetails.show', ['slug' => $recent->slug]) }}">
                                                                        {{ str::limit($recent['title'], 60) }}
                                                                    </a>
                                                                </h5>
                                                                <div class="date">
                                                                    {{ banglaDate($recent->created_at) }}</div>
                                                            </div>
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="sidebar-item mb-4">
                                <h4 class="">ট্যাগস</h4>
                                <ul class="sidebar-tags">
                                    @foreach ($tags as $tag)
                                        <li><a
                                                href="{{ route('tag.blogs', ['slug' => $tag->slug]) }}">{{ $tag->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- blog Ends -->
@endsection



@section('scripts')
    <script>
        $(document).on('click', '.reply-btn', function() {
            var commentId = $(this).data('comment-id');
            $('#reply-form-' + commentId).toggle(); // Toggle the reply form visibility
        });



        $(document).ready(function() {

            $('#createComments').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('add.comment') }}',
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


        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.reply-toggle-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var targetId = this.getAttribute('data-toggle');
                    var targetDiv = document.getElementById(targetId);
                    if (targetDiv.style.display === 'none' || targetDiv.style.display === '') {
                        targetDiv.style.display = 'block';
                        this.innerText = 'উত্তর লুকান'; // Change button text
                    } else {
                        targetDiv.style.display = 'none';
                        this.innerText = 'উত্তর দেখুন'; // Change button text
                    }
                });
            });
        });

        //comment scroll function
        document.getElementById('scrollToComment').addEventListener('click', function() {
            // Target the comment section by the new ID or class
            const commentBox = document.querySelector('.comment-section');
            if (commentBox) {
                commentBox.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });




        //like function
        $(document).ready(function() {
            $('.like-button').on('click', function() {
                const blogId = $(this).data('blog-id');
                const button = $(this); // Cache the button element
                const url = '{{ route('blog.like', ':id') }}'.replace(':id', blogId);

                $.ajax({
                    url: url, 
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // CSRF token for security
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the like count dynamically
                            $(`#likes-count-${blogId}`).text(response.likes_count);

                            // Toggle the button color and text based on the action
                            if (response.action === 'liked') {
                                button.addClass('btn-danger').removeClass(
                                    'btn-light'); // Change to 'liked' style
                            } else if (response.action === 'unliked') {
                                button.addClass('btn-light').removeClass(
                                    'btn-success'); // Change back to default style
                            }
                        } else {
                            console.error('Failed to process the request.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    },
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(function(dropdown) {
                new bootstrap.Dropdown(dropdown);
            });
        });
    </script>
@endsection
