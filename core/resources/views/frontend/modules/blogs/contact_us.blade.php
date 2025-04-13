 
 @extends('frontend.layouts.master')

 @section('meta')
     <title>{{ $blog->title ?? 'INFORMATION ABOUT US' }} | {{ get_option('title') }}</title>
 
     <meta property="og:title" content="{{ $blog->title ?? 'Blog Details' }} | {{ strtolower($blog->meta_title ?? get_option('title')) }}">
     <meta property="og:description" content="{{ strip_tags($blog->meta_description ?? get_option('description')) }}">
     <meta property="og:type" content="website">
     <meta property="og:url" content="{{ url()->current() }}">
     <meta property="og:image" content="{{ asset($blog->meta_image ??get_option('meta_image')) }}">
     <meta property="og:site_name" content="{{ get_option('company_name') }}">
     <!-- Add more Open Graph tags as needed -->
 
     <meta name="twitter:card" content="summary_large_image">
     <meta name="twitter:title" content="{{ $blog->title ?? 'Blog Details' }} | {{ strtolower($blog->meta_title ?? get_option('title')) }}">
     <meta name="twitter:description" content="{{ strip_tags($blog->meta_description ?? get_option('description')) }}">
     <meta name="twitter:image" content="{{ asset($blog->meta_image ?? get_option('meta_image')) }}">
     <!-- Add more Twitter meta tags as needed -->
 @endsection
 
 @section('content')
   <!-- contact starts -->
   <section class="contact-main pb-0 contact1 bg-grey">
        
    <div class="container">
        <div class="contact-info">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="contact-info">
                        <h3 class="">INFORMATION ABOUT US</h3>
                        <p class="mb-4">Sagittis posuere id nam quis vestibulum vestibulum a facilisi at elit hendrerit scelerisque sodales nam dis orci.</p>
                        <div class="info-item d-flex align-items-center bg-white mb-3">
                            <div class="info-icon">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="info-content ps-4">
                                <p class="m-0">130, EM Villa Raninagar,</p>
                                <p class="m-0"> Rajshahi-6100, Bangladesh</p>
                            </div>
                        </div>
                        <div class="info-item d-flex align-items-center bg-white mb-3">
                            <div class="info-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="info-content ps-4">
                                <p class="m-0">+880 1894539910</p>
                                <p class="m-0">+880 1894539910</p>
                            </div>
                        </div>
                        <div class="info-item d-flex align-items-center bg-white mb-3">
                            <div class="info-icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="info-content ps-4">
                                <p class="m-0">englishmoja.yt@gmail.com</p>
                                <p class="m-0">www.englishmoja.com</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div id="contact-form1" class="contact-form">
                        <h3 class="">Keep in Touch</h3>
                        <p class="mb-4">Fundpress site thoughtfully designed for real humans which means the best user experience for your entire community.</p>
                        
                        <div id="contactform-error-msg"></div>

                        <form method="post" action="#" name="contactform" id="contactform">
                            <div class="form-group mb-2">
                                <input type="text" name="first_name" class="form-control" id="fname" placeholder="First Name">
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" name="last_name" class="form-control" id="lname" placeholder="Last Name">
                            </div>
                            <div class="form-group mb-2">
                                <input type="email" name="email"  class="form-control" id="email" placeholder="Email">
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" name="phone" class="form-control" id="phnumber" placeholder="Phone">
                            </div>
                            <div class="textarea mb-2">
                                <textarea name="comments" placeholder="Enter a message"></textarea>
                            </div>
                            <div class="comment-btn">
                                <input type="submit" class="nir-btn" id="submit" value="Send Message">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="map mb-10"></div>
        <div style="width: 100%">
            <iframe height="500" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=english%20moja&zoom=10&maptype=roadmap"></iframe>
        </div>
    </div>
</section>
<!-- contact Ends -->

 @endsection


@section('scripts')

@endsection