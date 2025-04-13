@extends('backend.layouts.master')

@section('meta')
    <title>All SubMenus - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h3><span class="badge bg-primary">{{ $menu->name }}</span></h2>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
        </ul>
        <div class="page-btn">
            <a href="{{ route('menus.index') }}" class="btn btn-added"><i data-feather="plus-circle" class="me-2"></i>Back
                To Menus</a>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body p-2">
                <div class="row text-center m-0 p-0">
                    <div class="col-md-6">

                        <div class="mb-3">
                            <a href="{{ route('menus.submenu.create') }}" class="submenu-btn" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Add to Submenu" data-menu-id="{{ $menu->id }}"><button
                                    type="button" class="btn btn-success">Add To Menu</button></a>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Add New Sub Menu"><button type="button" class="btn btn-success"
                                    data-bs-toggle="modal" data-bs-target="#subMenuAdd">Create SubMenu</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card table-list-card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">

                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table  table-hover AjaxDataTable" id="items">
                        <thead>
                            <tr>
                                <th class="no-sort" data-orderable="false">
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all" data-value="0">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th class="no-sort">SN</th>
                                <th class="no-sort">Name</th>
                                <th class="no-sort">Link</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="subMenuAdd">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">

                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Create New SubMenu</h4>
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
                                {{-- <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="mb-3 add-product">

                                        <label class="form-label">Link</label>
                                        <select class="form-control" name="link" id="routeSelect"></select>
                                    </div>
                                </div> --}}
                                <div class="mb-3">
                                    <label class="form-label">Link</label>
                                    <select class="form-control" name="link" id="routeSelect"></select>
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- DataTables RowReorder -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/editor/2.1.9/css/editor.dataTables.min.css">
    <!-- DataTables Editor JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/editor/2.1.9/js/dataTables.editor.min.js">
    </script>
    <script>
        AJAX_URL = "{{ route('menus.submenu.ajax') }}";

        $(document).ready(function() {
            $(document).on('click', '.submenu-btn', function(event) {
                event.preventDefault();

                var actionUrl = $(this).attr('href');
                var menuId = $(this).data('menu-id');

                var selectedIds = [];

                $('table#items tbody input[type="checkbox"]:checked').each(function() {
                    selectedIds.push($(this).data('value'));

                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select Submenu.',
                    });
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You are about to Add Submenus into this Menus',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, create it!',
                        cancelButtonText: 'No, cancel',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: actionUrl,
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    ids: selectedIds,
                                    menu_id: menuId
                                },
                                success: function(response) {
                                    if (response.message ===
                                        'Added successfully') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.message,
                                        }).then(function() {

                                            window.location.href =
                                                "{{ route('menus.index') }}";
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.message,
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'An error occurred: ' + error,
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });

        // add submenu
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
                                window.location.href = '';
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

        //sub menu destroy
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function(event) {
                event.preventDefault();

                // Show confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this data!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, proceed with deletion
                        var actionUrl = $(this).attr('href');

                        $.ajax({
                            url: actionUrl,
                            method: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                if (response.message === 'Deleted successfully') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message,
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.error ||
                                            'An error occurred while deleting.',
                                    });
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('AJAX Error:', textStatus, errorThrown);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred while deleting.',
                                });
                            }
                        });
                    }
                });
            });
        });


        $(document).ready(function() {
            try {
                var selectSimple = $('#routeSelect');
                selectSimple.select2({
                    placeholder: 'Search for routes',
                    dropdownParent: $('#subMenuAdd'),
                    minimumInputLength: 1,
                    width: '100%',
                    allowClear: true,
                    tags:true,
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
                                    text: item.text
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
            } catch (err) {
                console.log(err);
            }
        });
    </script>
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }
    </style>
@endsection
