{{-- card start --}}
<div class="col-lg-6 mb-4">
    <div class="trend-item box-shadow bg-white position-relative d-flex align-items-center justify-content-between p-3"
        onclick="handleBlogClick(event, '{{ route('blogsDetails.show', $blog['slug']) }}', '{{ route('subscriptions.index') }}', {{ $blog['blog_type'] === true ? 'true' : 'false' }}, {{ canAccessPremiumBlog() ? 'true' : 'false' }});"
        style="cursor: pointer;">

        @if ($blog['blog_type'] === true)
            @if (canAccessPremiumBlog())
                <!-- User can access premium content -->
                <div class="premium-ribbon" data-tooltip="You have access to this premium blog.">
                    <img src="{{ asset('theme/frontend/assets/images/icon/premium.png') }}" alt="Premium Blog">
                </div>
            @else
                <!-- User cannot access premium content -->
                <div class="premium-ribbon" data-tooltip="Subscribe to access this premium blog.">
                    <img src="{{ asset('theme/frontend/assets/images/icon/premium.png') }}" alt="Locked Premium Blog">
                </div>
            @endif
        @endif

        <div class="trend-content-main w-75">
            <h4><a>{{ Str::limit($blog['title'], 70) }}</a></h4>
        </div>

        <div class="trend-image w-25 me-3">
            <img class="lazy-load"
                data-src="{{ $blog['thumbnail'] ? asset($blog['thumbnail']) : asset('logo/em_blog.png') }}"
                src="{{ asset('logo/loader.gif') }}" alt="{{ $blog['title'] }}">
        </div>
    </div>
</div>
{{-- card end --}}






@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/pg-calendar@1.4.31/dist/js/pignose.calendar.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pg-calendar@1.4.31/dist/css/pignose.calendar.min.css">
    <script>
        $(document).ready(function() {

            // fetchblog and filter method 

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
            // fetchBlogs();

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

        function showSubscriptionMessage() {
            Swal.fire({
                icon: 'info',
                title: 'সাবস্ক্রিপশন প্রয়োজন',
                text: 'এই প্রিমিয়াম কন্টেন্টটি দেখতে আপনাকে সাবস্ক্রাইব করতে হবে।',
                confirmButtonText: 'সাবস্ক্রিপশন প্ল্যান দেখুন'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('subscriptions.index') }}";
                }
            });
        }
    </script>

<style>
    .loading-indicator {
        display: block;
        margin: 20px auto;
        width: 50%;
        height: 50%;
    }
</style>
@endsection
