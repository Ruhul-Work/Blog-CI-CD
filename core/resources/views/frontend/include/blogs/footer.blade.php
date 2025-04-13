    <!-- footer starts -->
    <footer class="py-3 footermain">
        <div class="footer-upper pb-4">
            <div class="container">
                <div class="row">
                    <!-- About Section -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <div class="footer-about">
                            <img src="{{ asset('logo/logo.png') }}" alt="image">
                            <p class="mt-3 mb-3 white">
                                {{ get_option('description') }}
                            </p>


                            <div class="social-links">
                                <ul>
                                    <li><a href="https://www.facebook.com/englishmoja"><i class="fab fa-facebook"
                                                aria-hidden="true"></i></a></li>
                                    <!--<li><a href="javascript:void(0)"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>-->
                                    <!--<li><a href="javascript:void(0)"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>-->
                                    <!--<li><a href="javascript:void(0)"><i class="fab fa-linkedin" aria-hidden="true"></i></a></li>-->
                                </ul>
                            </div>

                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 col-sm-12 mb-4">
                        <div class="footer-links">
                            <h3 class="white">দ্রুত লিংক</h3>
                            <ul>
                                <li><a href="https://englishmoja.com/about-us">আমাদের সম্পর্কে</a></li>
                                <li><a href="https://englishmoja.com/privacy-policy">গোপনীয়তা নীতি</a></li>
                                <li><a href="https://englishmoja.com/terms-and-conditions">শর্তাবলী</a></li>
                                <li><a href="https://englishmoja.com/contact-us">যোগাযোগ করুন</a></li>
                                <!--<li><a href="javascript:void(0)">প্রশ্নোত্তর</a></li>-->
                            </ul>
                        </div>
                    </div>


                    <!-- Important Links -->
                    <div class="col-lg-2 col-md-6 col-sm-12 mb-4">
                        <div class="footer-links">
                            <h3 class="white">গুরুত্বপূর্ণ লিংক</h3>
                            <ul>
                                <li><a href="{{ route('subscriptions.index') }}">প্রাইসিং</a></li>
                                <li><a href="{{ route('bloglist.index') }}">ক্যাটাগরি</a></li>
                                <li><a href="{{ route('bloglist.index') }}">ব্লগ</a></li>

                            </ul>
                        </div>
                    </div>

                    <!-- Popular Tags -->
                    <div class="col-lg-2 col-md-6 col-sm-12 mb-4">
                        <div class="footer-links">
                            <h3 class="white">জনপ্রিয় ট্যাগ</h3>
                            <div class="tagcloud">
                                @foreach ($popularTags as $tag)
                                    <a class="tag-cloud-link bg-white black p-2 mb-1"
                                        href="{{ route('tag.blogs', ['slug' => $tag->slug]) }}">{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Popular Posts -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <div class="footer-links">
                            <h3 class="white">যোগাযোগ</h3>
                            <ul>
                                <li class="white mb-2"><strong>ফোন:</strong> {{ get_option('phone_number') }} </li>
                                <li class="white mb-2"><strong>ঠিকানা:</strong> {{ get_option('address') }} </li>
                                <li class="white mb-2"><strong>ইমেইল:</strong> {{ get_option('email') }}</li>
                                <li class="white mb-2"><strong>ওয়েবসাইট:</strong> {{ get_option('website_url') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Bottom -->
        <div class="footer-copyright pt-2 pb-2">
            <div class="container">
                <div class="copyright-inner d-md-flex align-items-center justify-content-between">
                    <div class="copyright-text">
                        <p class="m-0 white">{{ banglaDate(now()->year) }} English Moja. সমস্ত অধিকার সংরক্ষিত।</p>
                    </div>
                    <div class="social-links">
                        <p class="developer-text">
                            ডেভেলপড বাই <i class="ri-code-s-slash-line text-info"></i> <a
                                href="https://bintelbd.com/">বিনটেল ফিউচার টেক</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer ends -->

    <!-- Back to top start -->
    <div id="back-to-top">
        <a href="#"></a>
    </div>
    <!-- Back to top ends -->

    <div class="view-port">
        <div class="dark-mode"><a href="javascript:void()"><i class="fa fa-moon"></i> Night</a></div>
        <div class="light-mode"><a href="javascript:void()"><i class="fa fa-sun"></i> Day</a></div>
    </div>

    <!-- search popup -->

    <!-- Search Popup -->
    <div id="search1">
        <button type="button" class="close" onclick="closeSearch()">×</button>
        <div class="search-container">
            <input type="search" id="searchInputPopup" placeholder="Type keyword(s) here" autocomplete="off">
            <button type="submit" class="search-btn"><i class="ri-search-line"></i></button>
            <!-- Search Results Appear Here -->
            <div id="searchResultsPopup" class="search-results"></div>
        </div>
    </div>



    <!-- header side menu -->
    <div class="header_sidemenu">
        <div class="header_sidemenu_in">
            <div class="menu bg-navy py-5 px-4">
                <div class="close-menu">
                    <i class="fa fa-times white"></i>
                </div>
                <div class="m-contentmain">
                    <div class="m-contentmain">
                        <div class="m-logo mb-5">
                            <img src="images/logo-white.png" alt="m-logo">
                        </div>

                        <div class="content-box mb-5">
                            <h3 class="white">Get In Touch</h3>
                            <p class="white mb-2">We must explain to you how all seds this mistakens idea off denouncing
                                pleasures and praising pain was born and I will give you a completed accounts..</p>
                            <a href="#" class="nir-btn">Consultation</a>
                        </div>

                        <div class="contact-info">
                            <h3 class="white">Contact Info</h3>
                            <ul>
                                <li class="white d-block mb-1"><i class="fa fa-map-marker-alt me-1"></i>
                                    {{ get_option('address') }} </li>
                                <li class="white d-block mb-1"><i class="fa fa-phone-alt me-1"></i>
                                    {{ get_option('phone_number') }}</li>
                                <li class="white d-block mb-1"><i
                                        class="fa fa-envelope-open me-1"></i>support@magberg.com</li>
                                <li class="white d-block"><i class="fa fa-clock me-1"></i> Week Days: 09.00 to 18.00
                                    Sunday: Closed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overlay hide"></div>
        </div>
    </div>


{{-- 🔹🔹🔹Navber search result show functionlity below 🔹🔹🔹 --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let baseUrl = "{{ url('/') }}"; // Get the base URL dynamically

            // Open search popup
            $(".mt_search").on("click", function() {
                $("#search1").addClass("open");
                setTimeout(() => $("#searchInputPopup").focus(), 500); // Auto-focus input
            });

            // Close search popup
            function closeSearch() {
                $("#search1").removeClass("open");
                $("#searchResultsPopup").hide(); // Hide results when closing
            }

            $(".close").on("click", function() {
                closeSearch();
            });

            // Handle Search Query
            $("#searchInputPopup").on("keyup", function() {
                let query = $(this).val().trim();

                if (query.length > 2) {
                    $.ajax({
                        url: "{{ route('blogs.search') }}",
                        type: "GET",
                        data: {
                            query: query
                        },
                        beforeSend: function() {
                            $("#searchResultsPopup").html("<p>Searching...</p>").show();
                        },
                        success: function(response) {
                            let searchResults = $("#searchResultsPopup");
                            searchResults.empty();

                            if (response.length > 0) {
                                searchResults.show();
                                response.forEach(blog => {
                                    let thumbnail = blog.thumbnail ?
                                        `<img src="${blog.thumbnail}" alt="Thumbnail">` :
                                        '<img src="logo/em_blog.png" alt="No Image">'; // ✅ Default image

                                    searchResults.append(`
                                <a href="${baseUrl}/blog-details/${blog.slug}" class="search-result-item">
                                    ${thumbnail}
                                    <span>${blog.title}</span>
                                </a>
                            `);
                                });
                            } else {
                                searchResults.html(
                                    "<p style='padding:10px;'>No results found</p>").show();
                            }
                        },
                        error: function(xhr) {
                            console.error("Search failed:", xhr.responseText);
                            $("#searchResultsPopup").html("<p>Error fetching results.</p>")
                                .show();
                        }
                    });
                } else {
                    $("#searchResultsPopup").hide();
                }
            });

            // Hide search results when clicking outside
            $(document).on("click", function(e) {
                if (!$(e.target).closest("#searchInputPopup, #searchResultsPopup").length) {
                    $("#searchResultsPopup").hide();
                }
            });
        });
    </script>
