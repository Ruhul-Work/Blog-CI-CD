        <!-- Navigation Bar -->
        <div class="header_menu" id="header_menu">
            <nav class="navbar navbar-default">
                <div class="container">
                    <div class="navbar-flex d-flex align-items-center justify-content-between w-100 pb-2 pt-2">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <a class="navbar-brand" href="{{ route('home') }}">
                                <img src="{{ asset('logo/logo.png') }}" alt="image">
                                <img src="{{ asset('logo/logo.png') }}" alt="image">
                            </a>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="navbar-collapse1 d-flex align-items-center" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav" id="responsive-menu">
                                <li><a href="{{ route('home') }}">হোম</a></li>
                                <!-- <li><a href="#">আমাদের সম্পর্কে</a></li> -->
                                <!--<li><a href="{{ route('bloglist.index') }}">ব্লগ</a></li>-->
                                <li class="submenu dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        ক্যাটাগরি <i class="icon-arrow-down" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('bloglist.index') }}">সব ক্যাটাগরি</a></li>
                                        @foreach ($categories as $category)
                                            <li>
                                                <a
                                                    href="{{ route('categoryblogs.blogs', ['slug' => $category->slug]) }}">{{ $category->name }}</a>
                                            </li>
                                        @endforeach
                                        <li>
                                            <a href="{{ route('blogs.free') }}">ফ্রি ব্লগ</a>
                                        </li>

                                        <!-- Premium Blogs -->
                                        <li>
                                            <a href="{{ route('blogs.premium') }}">প্রিমিয়াম ব্লগ</a>
                                        </li>
                                    </ul>
                                </li>


                                {{-- <li class="submenu dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        ব্লগ টাইপ <i class="icon-arrow-down" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <!-- Free Blogs -->
                                        <li>
                                            <a href="{{ route('blogs.free') }}">ফ্রি ব্লগ</a>
                                        </li>

                                        <!-- Premium Blogs -->
                                        <li>
                                            <a href="{{ route('blogs.premium') }}">প্রিমিয়াম ব্লগ</a>
                                        </li>
                                    </ul>
                                </li> --}}


                                <li><a href="{{ route('subscriptions.index') }}">সাবস্ক্রিপশন</a></li>
                                {{-- <li class="submenu dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-haspopup="true" aria-expanded="false">ক্যাটাগরি <i class="icon-arrow-down"
                                            aria-hidden="true"></i></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{route('bloglist.cetagory')}}">সব ক্যাটাগরি</a></li>
                                        <li><a href="category_blogs.html">একাডেমিক</a></li>
                                        <li><a href="category_blogs.html">বিশ্ববিদ্যালয়</a></li>
                                        <li><a href="category_blogs.html">মোটিভেশন</a></li>
                                        <li><a href="category_blogs.html">ক্যারিয়ার গাইডেন্স</a></li>
                                    </ul>
                                </li> --}}



                                <li><a href="https://englishmoja.com/contact-us">যোগাযোগ</a></li>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                        <div class="register-login d-flex align-items-center">
                            <div class="header_sidemenu me-2">

                                <div class="button-wrapper">
                                    <a href="{{ route('subscriptions.index') }}" class="button">Subscribe</a>
                                    <div class="button-bg"></div>
                                </div>
                            </div>

                            <div class="search-main"><a href="#search1" class="mt_search"><i
                                        class="ri-search-line"></i></a></div>


                        </div>

                        {{-- <div id="slicknav-mobile"></div> --}}
                        <div class="mobile-header-mobile d-flex align-items-center">
                            <!-- Search Button -->
                            <div class="search-main-mobile mx-2">
                                <a href="#search1" class="mt_search"><i class="ri-search-line"></i></a>
                            </div>

                            <!-- Mobile Menu Toggle Button -->
                            <div id="slicknav-mobile"></div>


                        </div>

                    </div>
                </div><!-- /.container-fluid -->
            </nav>
        </div>
        <!-- Navigation Bar Ends -->
        <style>




        </style>
