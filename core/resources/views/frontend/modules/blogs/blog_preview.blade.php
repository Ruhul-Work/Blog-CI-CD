@extends('frontend.layouts.master')

@section('meta')
    <title>Preview: <span id="previewTitleText"></span> | {{ get_option('title') }}</title>
@endsection

@section('content')
    <section class="blog blog-left pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="blog-single">

                        <!-- Blog Header -->
                        <div class="blog-single-in align-items-center d-md-flex bg-navy p-3 rounded">
                            <div class="blog-date w-25 me-4">
                                <div class="date text-center bg-theme p-2">
                                    <h1 class="day mb-0 white">{{ now()->format('d') }}</h1>
                                    <div class="month white">{{ now()->format('F') }}</div>
                                </div>
                            </div>
                            <div class="blog-single-in-cont w-75">
                                <div class="blog-content">
                                    <h3 class="blog-title mb-0">
                                        <a href="#" class="white" id="previewTitle"></a>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- Blog Image -->
                        <div class="blog-wrapper text-center mt-3">
                            <img id="previewImage" class="img-fluid rounded" alt="Blog Preview Image">
                        </div>

                        <!-- Blog Content -->
                        <div class="blog-content mt-3">
                            <div id="previewContent"></div> <!-- CKEditor Content will appear here -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
