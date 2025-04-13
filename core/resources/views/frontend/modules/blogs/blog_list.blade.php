 @extends('frontend.layouts.master')

 @section('meta')
     <title>{{ $blog->title ?? 'Blog Details' }} | {{ get_option('title') }}</title>

     <meta property="og:title"
         content="{{ $blog->title ?? 'Blog Details' }} | {{ strtolower($blog->meta_title ?? get_option('title')) }}">
     <meta property="og:description" content="{{ strip_tags($blog->meta_description ?? get_option('description')) }}">
     <meta property="og:type" content="website">
     <meta property="og:url" content="{{ url()->current() }}">
     <meta property="og:image" content="{{ asset($blog->meta_image ?? get_option('meta_image')) }}">
     <meta property="og:site_name" content="{{ get_option('company_name') }}">
     <!-- Add more Open Graph tags as needed -->

     <meta name="twitter:card" content="summary_large_image">
     <meta name="twitter:title"
         content="{{ $blog->title ?? 'Blog Details' }} | {{ strtolower($blog->meta_title ?? get_option('title')) }}">
     <meta name="twitter:description" content="{{ strip_tags($blog->meta_description ?? get_option('description')) }}">
     <meta name="twitter:image" content="{{ asset($blog->meta_image ?? get_option('meta_image')) }}">
     <!-- Add more Twitter meta tags as needed -->
 @endsection

 @section('content')
     <!-- BreadCrumb Starts -->
     <section class="breadcrumb-main pb-0 pt-6" style="background-image: url(images/bg/1.jpg);">
         <div class="breadcrumb-outer">
             <div class="container">
                 <div class="breadcrumb-content d-md-flex align-items-center pt-6">
                     <h2 class="mb-2 py-3">সকল ব্লগ</h2>
                     <nav aria-label="breadcrumb">
                         <ul class="breadcrumb mb-3 mt-5">
                             <li class="breadcrumb-item "><a href="{{ route('home') }}">হোম</a></li>
                             <li class="breadcrumb-item active" aria-current="page">সকল ব্লগ</li>
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
                                 <p class="m-0">Showing 1-5 of 80 results</p>
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

                         {{-- blog card show container --}}
                         <div class="row" id="blogContainer">

                         </div>
                         {{-- pagination show container --}}
                         <div class="pagination-main text-center" id="paginationContainer">

                             {{-- {!! $blogs->links('frontend.modules.pagination.custom_paginate') !!} --}}

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
         </div>
     </section>
     <!-- blog Ends -->


     <style>
         .loading-indicator {
             display: block;
             margin: 20px auto;
             width: 50%;
             height: 50%;
         }
     </style>
 @endsection






 @section('scripts')
     <script src="https://cdn.jsdelivr.net/npm/pg-calendar@1.4.31/dist/js/pignose.calendar.min.js"></script>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pg-calendar@1.4.31/dist/css/pignose.calendar.min.css">
     <script>
         // Define fetchBlogs globally or in a higher scope
         $(document).ready(function() {

             function fetchBlogs(page = 1, sort_by = null, category = 'all', blog_type = null, selectedDate = null) {
                 $.ajax({
                     url: "{{ route('blogs.fetchAll') }}",
                     type: 'GET',
                     data: {
                         page: page,
                         sort_by: sort_by,
                         category: category,
                         blog_type: blog_type,
                         selectedDate: selectedDate
                     },
                     beforeSend: function() {
                         $('#blogContainer').html(
                             '<img src="{{ asset('logo/loader.gif') }}" alt="Loading..." class="loading-indicator">'
                         );
                     },
                     success: function(response) {
                         if (response.success) {
                             $('#blogContainer').html(response.blogs.join(''));
                             $('#paginationContainer').html(response.pagination);

                             const {
                                 current_page,
                                 per_page,
                                 total
                             } = response.meta;
                             $('.list-results-sort p').text(
                                 `Showing ${(current_page - 1) * per_page + 1}-${Math.min(current_page * per_page, total)} of ${total} results`
                             );

                             observeLazyLoadImages(); // Reinitialize lazy load

                         } else {
                             $('#blogContainer').html('<p>No blogs found.</p>');
                         }
                     },
                     error: function() {
                         console.error('Failed to fetch blogs.');
                         $('#blogContainer').html(
                             '<p>Something went wrong. Please try again later.</p>');
                     },
                 });
             }

             // Initial fetch
             fetchBlogs();

             // Handle pagination
             $(document).on('click', '#paginationContainer a', function(e) {
                 e.preventDefault();
                 const page = $(this).attr('href').split('page=')[1];
                 const sort_by = $('#sortBlogs').val();
                 const category = $('input[name="category"]:checked').val() || 'all';
                 const blog_type = $('input[name="blog_type"]:checked').val();
                 const selectedDate = $('#selectedDate').val();
                 fetchBlogs(page, sort_by, category, blog_type, selectedDate);
             });


             // Handle sorting change
             $('#sortBlogs').on('change', function() {
                 const sort_by = $(this).val();

                 if (sort_by === 'all') {
                     // Reset all filters
                     $('input[name="category"]').prop('checked', false);
                     $('input[name="category"][value="all"]').prop('checked',
                         true);
                     $('input[name="blog_type"]').prop('checked', false);

                     fetchBlogs(1, null, 'all', null);
                 } else {
                     const category = $('input[name="category"]:checked').val() || 'all';
                     const blog_type = $('input[name="blog_type"]:checked').val();
                     fetchBlogs(1, sort_by, category, blog_type);
                 }
             });

             // Handle category filter
             $(document).on('change', 'input[name="category"]', function() {
                 const category = $(this).val();
                 const sort_by = $('#sortBlogs').val();
                 const blog_type = $('input[name="blog_type"]:checked').val();
                 const selectedDate = $('#selectedDate').val();
                 fetchBlogs(1, sort_by, category, blog_type, selectedDate);
             });

             // Handle blog type filter
             $(document).on('change', 'input[name="blog_type"]', function() {
                 const blog_type = $(this).val();
                 const sort_by = $('#sortBlogs').val();
                 const category = $('input[name="category"]:checked').val() || 'all';
                 const selectedDate = $('#selectedDate').val();
                 fetchBlogs(1, sort_by, category, blog_type, selectedDate);
             });

             // Handle date filter
             //  $('.calendar').pignoseCalendar({
             //      theme: 'blue',
             //      select: function(date) {
             //          if (date[0]) {
             //              const selectedDate = date[0].format('YYYY-MM-DD');
             //              const sort_by = $('#sortBlogs').val();
             //              const category = $('input[name="category"]:checked').val() || 'all';
             //              const blog_type = $('input[name="blog_type"]:checked').val();
             //              fetchBlogs(1, sort_by, category, blog_type, selectedDate);
             //          }
             //      }
             //  });

             // Initialize Calendar Filter
             $('.calendar').pignoseCalendar({
                 theme: 'blue',
                 select: function(date) {
                     if (!date || date.length === 0 || !date[0]) {
                         // If no date selected, reset the filter
                         fetchBlogs(1, null, 'all', null, null);
                     } else {
                         const selectedDate = date[0].format('YYYY-MM-DD');
                         const sort_by = $('#sortBlogs').val();
                         const category = $('input[name="category"]:checked').val() || 'all';
                         const blog_type = $('input[name="blog_type"]:checked').val();
                         fetchBlogs(1, sort_by, category, blog_type, selectedDate);
                     }
                 },
                 clear: function() {
                     // Reset when calendar is cleared
                     fetchBlogs(1, null, 'all', null, null);
                 }
             });


         });
     </script>
 @endsection
