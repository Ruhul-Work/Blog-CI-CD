@extends('backend.layouts.master')

@section('meta')
    <title>Create new pages - {{ get_option('title') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Pages Management</h4>
                <h6>Create new Pages</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                            class="me-2"></i>Back to
                        Products</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>

    <style>
        #drop-area {
            border: 2px dashed #0087F7;
            width: 100%;
            height: 200px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .progress {
            height: 20px;
            margin-top: 10px;
        }



        .image-gallery img {
            width: 100%;
            height: 200px;
            /* Fixed height for uniformity */
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .image-gallery img:hover {
            transform: scale(1.05);
        }

        .image-container {
            position: relative;
        }

        .delete-button {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255, 255, 255, 0.7);
            border: none;
            border-radius: 50%;
            padding: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .delete-button:hover {
            background: rgba(255, 0, 0, 0.8);
            color: #fff;
        }

        .delete-button i {
            pointer-events: none;
        }
        
        .loading {
    font-weight: bold;
    color: #007bff;
}

.complete {
    color: green;
    font-weight: bold;
}
    </style>


    <div class="container">
        <!--<h2>Drag and Drop Pages Upload</h2>-->
        <!--<input type="hidden" id="product_id" value="{{ $Id }}">-->
        <!--<div id="drop-area">-->
        <!--    <p>Drag and drop images here or click to select</p>-->
        <!--    <input type="file" id="fileElem" multiple accept="image/*" style="display:none;">-->
        <!--    <label for="fileElem" style="cursor:pointer;">Select files</label>-->
        <!--</div>-->
        <!--<div id="progress-container"></div>-->
        
        
       <div id="drop-area">
            <p>Drag and drop images here or click to select</p>
            <input type="file" id="fileElem" multiple accept="image/*" style="display:none;">
            <label for="fileElem" style="cursor:pointer;">Select files</label>
        </div>
        
        <div class="progress" id="progress-container" style="display: none;">
            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div id="upload-status"></div>
        
        <!-- Loading spinner -->
        <div id="loader" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p id="loader-text"></p>
        </div>



        <div class="row">
            <div class="col-md-12">
                <div class="card">
<div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">{{ $product->bangla_name }} (Pages)</h5>
                        <h5 class="card-title mb-0">
                            <button type="button" class="all-delete-button btn btn-danger btn-sm"
                                data-id="{{ $product->id }}"
                                data-url="{{ route('product_pages.all.destroy', ['id' => $product->id]) }}">
                                <span> <i class="fas fa-trash-alt"></i>Clear All</span>
                            </button>
                        </h5>
                    </div>
                    <div class="card-body">



                        <div class="row image-gallery">
                            @foreach ($product_pages as $pages)
                                <div class="col-md-2 mb-4 image-container" id="image-{{ $pages->id }}">
                                    <div class="position-relative">
                                        <a href="{{ image($pages->pages_photos) }}" class="image-popup">
                                            <img src="{{ image($pages->pages_photos) }}" alt="image">
                                        </a>
                                        <!-- Delete Icon -->
                                        <button type="button" class="delete-button" data-id="{{ $pages->id }}"
                                            data-url="{{ route('product_pages.destroy', ['id' => $pages->id]) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>



        </div>
    @endsection
    @section('script')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
        <script>
          
          
             $(document).ready(function() {
    const dropArea = $('#drop-area');
    const progressContainer = $('#progress-container');
    const progressBar = $('.progress-bar');
    const uploadStatus = $('#upload-status');
    const loader = $('#loader');
    const loaderText = $('#loader-text');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    dropArea.on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropArea.addClass('drag-over');
    });

    dropArea.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropArea.removeClass('drag-over');
    });

    dropArea.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropArea.removeClass('drag-over');
        const files = e.originalEvent.dataTransfer.files;
        handleFiles(files);
    });

    $('#fileElem').on('change', function(e) {
        const files = e.target.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        const totalFiles = files.length;
        uploadStatus.text(`Uploading 0 of ${totalFiles} images...`);
        progressContainer.show(); // Show the progress bar
        progressBar.css('width', '0%'); // Reset the progress bar
        loader.show(); // Show the loader
        loaderText.text('Starting upload...'); // Initial loader text
        uploadFilesSequentially(files, 0, totalFiles);
    }

    function uploadFilesSequentially(files, index, totalFiles) {
        if (index >= files.length) {
            console.log("All files uploaded!");
            progressBar.css('width', '100%');
            progressBar.attr('aria-valuenow', '100');
            uploadStatus.text(`Upload complete!`);
            loader.hide(); // Hide loader when done
             setTimeout(() => {
                location.reload(); // Reload the page after a short delay
            }, 1000); // Adjust delay as needed
            return; // End when all files are uploaded
            
        }

        const file = files[index];
        uploadFile(file, index, totalFiles, function() {
            uploadFilesSequentially(files, index + 1, totalFiles); // Move to the next file
        });
    }

    function uploadFile(file, index, totalFiles, callback) {
        const formData = new FormData();
        formData.append('image', file); // Send only one image
        formData.append('compacted_id', '{{$Id}}'); // Send compacted ID

        $.ajax({
            url: '{{ route('products.pages-upload', ['compacted_id' => '']) }}' + {{$Id}},
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                loaderText.text(`Uploading ${index + 1} of ${totalFiles} images...`); // Update loader text
            },
            success: function(response) {
                console.log(response.success);
                progressBar.css('width', ((index + 1) / totalFiles) * 100 + '%');
                uploadStatus.text(`Uploaded ${index + 1} of ${totalFiles} images...`); // Update status
                if (typeof callback === 'function') {
                    callback(); // Call the callback to proceed to the next file
                }
            },
            error: function(xhr) {
                console.error('Upload failed: ' + xhr.statusText);
                uploadStatus.text(`Uploading ${index + 1} of ${totalFiles} images...`); // Update status even if it fails
                if (typeof callback === 'function') {
                    callback(); // Proceed to the next file even if upload fails
                }
            }
        });
    }
});


            
            //end

            $(document).ready(function() {
                $('.image-popup').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true // Enable gallery mode to navigate between images
                    }
                });
            });


            $(document).ready(function() {
                $('.delete-button').click(function() {
                    var imageId = $(this).data('id'); // Get the image ID
                    var url = $(this).data('url'); // Get the URL for the delete request

                    if (confirm('Are you sure you want to delete this image?')) {
                        $.ajax({
                            url: url,
                            type: 'get',
                            data: {
                                _token: '{{ csrf_token() }}' // CSRF token for Laravel security
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Remove the image container from the DOM
                                    $('#image-' + imageId).remove();
                                    alert(response.message);
                                } else {
                                    alert(response.message);
                                }
                            },
                            error: function(xhr) {
                                alert('An error occurred while trying to delete the image.');
                            }
                        });
                    }
                });
            });
            
            
            
              $(document).ready(function() {
                $('.all-delete-button').click(function() {
                    var url = $(this).data('url'); // Get the URL for the delete request

                    if (confirm('Are you sure you want to delete all images?')) {
                        $.ajax({
                            url: url,
                            type: 'get',
                            data: {
                                _token: '{{ csrf_token() }}' // CSRF token for Laravel security
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Optional: Alert the user of successful deletion
                                    alert(response.message);

                                    // Redirect to the products.add-pages route after successful deletion
                                    window.location.href =
                                        '{{ route('products.add-pages', ['slug' => $product->slug]) }}';
                                } else {
                                    alert(response.message);
                                }
                            },
                            error: function(xhr) {
                                alert('An error occurred while trying to delete the images.');
                            }
                        });
                    }
                });
            });
            
            
            
        </script>
    @endsection
