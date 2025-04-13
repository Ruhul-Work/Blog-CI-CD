@extends('backend.layouts.master')

@section('meta')
    <title>Create New Menus - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>New Menus</h4>
                <h6>Create new Menus</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                            class="me-2"></i>Back To Menus</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>

    <form action="" id="addMenu" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body add-product pb-0">
                <div class="accordion-card-one accordion" id="accordionExample">
                    <div class="accordion-item">
                        <div class="accordion-header" id="headingOne">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                aria-controls="collapseOne">
                                <div class="addproduct-icon">
                                    <h5><i data-feather="info" class="add-info"></i><span>Basic Information</span></h5>
                                    <a href="javascript:void(0);"><i data-feather="chevron-down"
                                            class="chevron-down-add"></i></a>
                                </div>
                            </div>
                        </div>

                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">

                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-lg-3 col-sm-6 col-12">

                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Enter text here">
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <label class="form-label">Menu Type</label>
                                        <select class="form-select select m_type" name="m_type" width="100%">
                                            <option value="Mega">Mega</option>
                                            <option value="General">General</option>
                                            <option value="Sub_Menu">Sub Menu</option>
                                        </select>
                                    </div>

                                    {{-- <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Sub Menu</label>
                                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#subMenuAdd"><i
                                                        data-feather="plus-circle"
                                                        class="plus-down-add"></i><span>Add
                                                        New</span></a>
                                            </div>
                                            <select class="form-select selectSimple" name="submenu_id" width="100%" multiple>
                                               @foreach ($submenus as $subMneu)
                                               <option value="{{ $subMneu->id }}">{{ $subMneu->name  }}</option>
                                               @endforeach
                                            </select>
                                        </div>
                                    </div> --}}



                                    {{-- <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="mb-3 add-product">

                                            <div class="add-newplus">
                                                <label class="form-label">Child  Menu</label>
                                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#add-childMenu"><i
                                                        data-feather="plus-circle"
                                                        class="plus-down-add"></i><span>Add
                                                        New</span></a>
                                            </div>

                                            <select class="form-select select" name="childmenu_id" width="100%" multiple>
                                                @foreach ($childmenus as $childMenu)
                                               <option value="{{ $childMenu->id }}">{{ $childMenu->name }}</option>
                                               @endforeach

                                            </select>
                                        </div>
                                    </div> --}}



                                    {{-- <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Link</label>
                                            <input type="text" class="form-control"  name="link" placeholder="url">

                                        </div>
                                    </div> --}}

                                    <div class="col-lg-3 col-sm-6 col-12 link">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Link</label>
                                            <select class="form-control" name="link" id="routeSelect"></select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-4 col-12 required">
                                        <label class="form-label">Status</label>
                                        <select class="form-select select" name="status" width="100%">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Icon Image</label>
                                            <div class="form-group">
                                                <div class="row" id="icon">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="col-lg-12">
            <div class="btn-addproduct mb-4">
                <button type="submit" class="btn btn-submit">Create Menu</button>
            </div>
        </div>

    </form>

    {{-- sub menu add modal --}}

    <div class="modal fade" id="subMenuAdd">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">

                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Add New SubMenu</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body">

                            <form action="{{ route('menus.store-submenu') }}" method="post" enctype="multipart/form-data"
                                id="addSubMenu">

                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Link</label>
                                    <input type="text" name="link" class="form-control">
                                </div>

                                <input type="submit" class="btn btn-submit" value="submit">

                            </form>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- child menu add modal --}}
    <div class="modal fade" id="add-childMenu">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Add New Child Menu</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body custom-modal-body">
                            <form action="" method="post" enctype="multipart/form-data" id="childMenuAdd">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Link</label>
                                    <input type="text" name="link" class="form-control">
                                </div>
                                <input type="submit" class="btn btn-submit" value="submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <style>
        .star-sign {
            color: red;
            font-weight: bold;
        }
    </style>
    <script src="{{ asset('theme/admin/assets/plugins/fileupload/spartan-multi-image-picker-min.js') }}"
        type="text/javascript"></script>
    <script>
        //store menu
        $(document).ready(function() {
            $('#addMenu').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('menus.store') }}',
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
                                window.location.href = '{{ route('menus.index') }}';
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
        // submenu
        $(document).ready(function() {
            $('#addSubMenu').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('menus.store-submenu') }}',
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
                                window.location.href = '{{ route('menus.create') }}';
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
        // child menu store
        $(document).ready(function() {
            $('#childMenuAdd').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('menus.store-childmenu') }}',
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
                                window.location.href = '{{ route('menus.create') }}';
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

            $("#icon").spartanMultiImagePicker({
                fieldName: 'icon',
                maxCount: 1,
                rowHeight: '200px',
                groupClassName: 'col',
                maxFileSize: '',
                dropFileLabel: "Drop Here",
                onExtensionErr: function(index, file) {
                    console.log(index, file, 'extension err');
                    alert('Please only input png or jpg type file')
                },
                onSizeErr: function(index, file) {
                    console.log(index, file, 'file size too big');
                    alert('File size too big max:250KB');
                }
            });
        });

        // $(document).ready(function() {
        //     try {
        //         var selectSimple = $('#routeSelect');

        //         selectSimple.select2({
        //             placeholder: 'Search for routes',
        //             minimumInputLength: 1,
        //             width: '100%',
        //             allowClear: true,
        //             minimumResultsForSearch: Infinity, // Disables the search input
        //             ajax: {
        //                 url: '{{ route('menus.search-routes') }}',
        //                 dataType: 'json',
        //                 type: "GET",
        //                 delay: 250, // Adjusted delay for smoother user experience
        //                 data: function(params) {
        //                     return {
        //                         q: params.term // Send the search term
        //                     };
        //                 },
        //                 processResults: function(data) {
        //                     var results = data.map(function(item) {
        //                         return {
        //                             id: item.value,
        //                             text: item.text
        //                         };
        //                     });

        //                     return {
        //                         results: results
        //                     };
        //                 }
        //             },
        //             templateResult: function(data) {
        //                 if (data.loading) return data.text; // Show the loading text
        //                 return $('<div><strong>' + data.text + '</strong></div>');
        //             }
        //         });

        //         // Double tap handling
        //         var lastTap = 0;
        //         selectSimple.on('select2:select', function(e) {
        //             var currentTime = new Date().getTime();
        //             var tapLength = currentTime - lastTap;
        //             lastTap = currentTime;

        //             if (tapLength < 300) { // Adjust the threshold as needed (300ms for double tap)
        //                 // Double tap action here (e.g., perform an action or show something)
        //                 console.log('Double tap detected!');
        //             }
        //         });
        //     } catch (err) {
        //         console.log(err);
        //     }
        // });
        $(document).ready(function() {
            try {
                var selectSimple = $('#routeSelect');

                selectSimple.select2({
                    placeholder: 'Search for routes',
                    minimumInputLength: 1,
                    width: '100%',
                    allowClear: true,
                    minimumResultsForSearch: Infinity, // Disables the search input
                    tags: true,
                    ajax: {
                        url: '{{ route('menus.search-routes') }}',
                        dataType: 'json',
                        type: "GET",
                        delay: 250, // Adjusted delay for smoother user experience
                        data: function(params) {
                            return {
                                q: params.term // Send the search term
                            };
                        },
                        processResults: function(data) {
                            var results = data.map(function(item) {
                                return {
                                    id: item.value,
                                    text: item.text,
                                    customData: item
                                        .customData // Example of a custom data field
                                };
                            });

                            return {
                                results: results
                            };
                        }
                    },
                    templateResult: function(data) {
                        if (data.loading) return data.text; // Show the loading text
                        return $('<div><strong>' + data.text + '</strong></div>');
                    }
                });

                // Event handling for custom data
                selectSimple.on('select2:select', function(e) {
                    var selectedData = e.params.data;

                    if (selectedData.customData) {
                        // Handle custom data as needed
                        console.log('Custom data:', selectedData.customData);
                    } else {
                        // Handle the default action if no custom data is specified
                        console.log('No custom data specified for this selection.');
                    }
                });
            } catch (err) {
                console.log(err);
            }
        });



        $(document).ready(function() {
            $('.m_type').change(function() {
                var selectedOption = $(this).val();
                if (selectedOption == 'General') {
                    $('.link').show();
                    // $('.cart_base').hide(); // If you have other elements to hide/show, manage them similarly
                } else if (selectedOption == 'Mega') {
                    $('.link').hide();
                    // $('#show_product').show();
                    // $('.cart_base').hide();
                } else if (selectedOption == 'Sub_Menu') {
                    $('.link').hide();
                } else {
                    $('.link').hide();
                    // $('.cart_base').show();
                }
            });
            $('.link').hide();
        });
    </script>

    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }
    </style>
@endsection
