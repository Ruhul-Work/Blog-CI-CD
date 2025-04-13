@extends('backend.layouts.master')

@section('meta')
    <title>All Reviews - {{ get_option('title') }}</title>
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
                    <a href="{{ route('home-category.review-add', ['id' => $hCategory->id]) }}" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Add To Review"><button type="button" class="btn btn-success">Add
                            To Review</button></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card table-list-card">

                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table reviewAjaxDataTable  table-hover" style="width:100%;" id="items">
                            <thead>
                                <tr>
                                    <th class="no-sort" data-orderable="false" style="width: 5%">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all" data-value="0">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th class="no-sort" style="width: 5%">SN</th>
                                    <th class="no-sort" style="width: 10%">Name</th>
                                    <th class="no-sort" style="width: 20%">Comment</th>
                                    <th class="no-sort" style="width: 5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
        $(document).ready(function() {
            var table;
            if ($('.reviewAjaxDataTable').length > 0) {
                table = $('.reviewAjaxDataTable').DataTable({
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    "ordering": true,
                    "responsive": true,
                    'order': [
                        [1, 'desc']
                    ],
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'ajax': {
                        url: "{{ route('home-category.review-all-ajax', ['id' => $hCategory->id]) }}"
                    },
                    'aLengthMenu': [
                        [10, 50, 100, 200, 500, -1],
                        [10, 50, 100, 200, 500, "ALL"]
                    ],
                    "language": {
                        search: '',
                        sLengthMenu: '_MENU_',
                        searchPlaceholder: "Search",
                        info: "_START_ - _END_ of _TOTAL_ items",
                        paginate: {
                            next: ' <i class="fa fa-angle-right"></i>',
                            previous: '<i class="fa fa-angle-left"></i>'
                        }
                    },
                    'buttons': ['copy', 'csv', 'excel', 'print', 'colvis'],
                    rowReorder: {
                        selector: 'td:first-child',
                        update: true,
                        dataSrc: 'DT_RowId'
                    },
                    initComplete: function(settings, json) {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');

                        $(document).on('click', '.export-excel', function() {
                            $('.dt-buttons .buttons-excel').click();
                        });

                        $(document).on('click', '.export-print', function() {
                            $('.dt-buttons .buttons-print').click();
                        });

                        $(document).on('click', '.export-copy', function() {
                            $('.dt-buttons .buttons-copy').click();
                            Swal.fire({
                                title: "Success",
                                text: "Successfully copied",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        });

                        $(document).on('click', '.export-refresh', function() {
                            table.ajax.reload();
                            Swal.fire({
                                title: "Success",
                                text: "Successfully Reloaded",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        });

                        $(document).on('click', '.export-hide-column', function() {
                            var columnCheckboxes = '';
                            table.columns().every(function() {
                                var column = this;
                                var columnTitle = $(column.header()).text().trim();
                                var columnIndex = column.index();
                                columnCheckboxes += `<div style="text-align:left;">
                            <input type="checkbox" id="chk_${columnIndex}" class="column-checkbox" value="${columnIndex}" ${column.visible() ? 'checked' : ''}>
                            <label for="chk_${columnIndex}">${columnTitle}</label>
                        </div>`;
                            });

                            Swal.fire({
                                title: 'Hide/Unhide Columns',
                                html: columnCheckboxes,
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Apply',
                                cancelButtonText: 'Cancel',
                                preConfirm: () => {
                                    $('.column-checkbox').each(function() {
                                        var columnIndex = $(this).val();
                                        var isChecked = $(this).prop(
                                            'checked');
                                        if (isChecked !== table.column(
                                                columnIndex).visible()) {
                                            table.column(columnIndex)
                                                .visible(isChecked);
                                        }
                                    });
                                }
                            });
                        });

                        $(document).on('click', '.checkboxs input', function(e) {
                            e.stopPropagation();
                        });

                        table.on('row-reorder', function(e, details, edit) {
                            var newOrder = details.map(function(detail) {
                                return {
                                    id: detail.node.id.replace('row_', ''),
                                    newPosition: detail.newPosition + 1
                                };
                            });
                            var tableId = $('.reviewAjaxDataTable').attr('id');

                            $.ajax({
                                url: "{{ route('home-category.review-sorting') }}",
                                type: 'POST',
                                data: {
                                    tableId: tableId,
                                    newOrder: newOrder
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: "Success",
                                        text: "Updated successfully",
                                        icon: "success",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    table.ajax.reload();
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        title: "Error",
                                        text: xhr.responseJSON?.message ||
                                            "An error occurred while updating the order",
                                        icon: "error",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            });
                        });
                    }
                });
            }
        });



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
    </script>
@endsection
