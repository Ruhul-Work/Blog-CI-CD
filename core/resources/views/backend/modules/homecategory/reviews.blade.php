@extends('backend.layouts.master')

@section('meta')
    <title>All Review - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h3><span class="badge bg-primary">{{ $hCategory->name }}</span></h2>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
        </ul>
        <div class="page-btn">
            <a href="{{ route('home-category.index') }}" class="btn btn-added"><i data-feather="plus-circle"
                    class="me-2"></i>Back To Home</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-2">
            <div class="row">
                <div class="mb-3 text-center">
                    <a href="{{ route('home-category.review-store') }}" class="category-btn" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Add to Review" data-parent-id="{{ $hCategory->id }}"><button
                            type="button" class="btn btn-success">Add Reviews</button></a>
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
                <table id="items" class="table AjaxDataTable" style="width:100%;">
                    <thead>
                        <tr style="width: 3%">
                            <th class="no-sort" data-orderable="false">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all" data-value="0">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>SN</th>
                            <th class="no-sort" style="width: 30%">Name</th>
                            <th class="no-sort" style="width: 30%">Comment</th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        AJAX_URL = "{{ route('home-category.review-ajax') }}";

        $(document).ready(function() {
            $(document).on('click', '.category-btn', function(event) {
                event.preventDefault();
                // Get the action URL and campaign ID from the button's data attribute
                var actionUrl = $(this).attr('href');
                var parentId = $(this).data('parent-id');
                var selectedIds = [];
                // Collect selected product IDs
                $('table#items tbody input[type="checkbox"]:checked').each(function() {
                    selectedIds.push($(this).data('value'));
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select Reviews.',
                    });
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You are about to create',
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
                                    parent_id: parentId // Pass the campaign ID
                                },
                                success: function(response) {
                                    if (response.message ===
                                        'Successfully Saved') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.message,
                                        }).then(function() {
                                            // Redirect to the campaigns index route
                                            window.location.href =
                                                "{{ route('home-category.index') }}";
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
    </script>
@endsection
