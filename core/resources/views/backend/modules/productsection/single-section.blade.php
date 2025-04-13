@extends('backend.layouts.master')

@section('meta')
    <title>All Products - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h3><span class="badge bg-primary">{{ $singleSection->name }}</span></h2>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
        </ul>
        <div class="page-btn">
            <a href="{{ route('sections.index') }}" class="btn btn-added"><i data-feather="plus-circle" class="me-2"></i>Back
                To Sections</a>
        </div>
    </div>


    <div class="card">
        <div class="card-body p-2">
            <div class="row">
                <div class="mb-3 text-center">
                    <a href="{{ route('sections.product.new.create', ['id' => $singleSection->id]) }}"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Add To New Product This Sections"><button
                            type="button" class="btn btn-success">Add
                            New Products This Sections</button></a>
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

                <table class="table AjaxDataTable table-hover" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="no-sort">Reorder</th>
                            <th>SN</th>
                            <th class="no-sort">Name</th>
                            <th class="no-sort">Category</th>
                            <th class="no-sort">Author</th>
                            <th class="no-sort">Publisher</th>
                            <th class="no-sort">Price</th>
                            <th class="no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <div class="modal fade" id="imageViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" id="modalImage" class="img-fluid" alt="Image Preview">
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
            if ($('.AjaxDataTable').length > 0) {
                table = $('.AjaxDataTable').DataTable({
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
                        'url': "{{ route('section-product-view.ajax', ['id' => $singleSection->id]) }}"
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
                            previous: '<i class="fa fa-angle-left"></i> '
                        },
                    },
                    'buttons': [{
                            extend: 'copy',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'csv',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        'colvis'
                    ],
                    rowReorder: {
                        selector: 'td:first-child',
                        update: true,
                        dataSrc: 'id',
                        snapX: 20,
                        snapY: 20,
                        dropCallback: function(node, data, items) {
                            // Your drop callback function
                        }
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

                        function toggleColumn(index) {
                            table.column(index).visible(!table.column(index).visible());
                        }

                        $(document).on('click', '.export-hide-column', function() {
                            var columnCheckboxes = '';
                            table.columns().every(function() {
                                var column = this;
                                var columnTitle = $(column.header()).text().trim();
                                var columnIndex = column.index();
                                columnCheckboxes +=
                                    `<div style="text-align:left;">
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
                                            toggleColumn(columnIndex);
                                        }
                                    });
                                }
                            });
                        });

                        // Handle checkbox click
                        $(document).on('click', '.checkboxs input', function(e) {
                            e.stopPropagation();
                            // Handle checkbox click action here
                        });

                        table.on('row-reorder', function(e, details, edit) {
                            var reorderData = details.map(function(detail) {
                                var oldData = table.row(detail.oldPosition).data();
                                var newData = table.row(detail.newPosition).data();
                                var oldProductId = extractProductId(oldData[0]);
                                var newProductId = extractProductId(newData[0]);
                                return {
                                    oldId: oldProductId,
                                    newId: newProductId
                                };
                            });

                            $.ajax({
                                url: "{{ route('sections.product-sorting') }}",
                                type: 'POST',
                                data: {
                                    reorderData: reorderData
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
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        title: "Error",
                                        text: "An error occurred while updating the order",
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

        // Function to extract product ID from HTML string
        function extractProductId(htmlString) {
            var regex = /data-product-id="(\d+)"/;
            var match = regex.exec(htmlString);
            return match ? parseInt(match[1]) : null;
        }


        function applyFilters() {
            var filters = {
                category_id: $("#category_id").val(),
                subcategory_id: $("#subcategory_id").val(),
                author_id: $("#author_id").val(),
                publisher_id: $("#publisher_id").val(),
                searchValue: $("#searchValue").val()
            };
            updateDataTable(filters);
        }

        function updateDataTable(filters) {
            var dataTable = $('.AjaxDataTable').DataTable();
            var queryString = $.param(filters);
            var url = "{{ route('campaigns-product.ajax') }}" + "?" + queryString;
            dataTable.ajax.url(url).load();
        }

        $(document).ready(function() {
            $(document).on('click', '.view-image-btn', function() {
                var imageUrl = $(this).data('cover-url');
                if (imageUrl) {
                    $('#modalImage').attr('src', imageUrl);
                    $('#imageViewModal').modal('show'); // Show the modal
                } else {
                    console.error('Image URL not found.');
                }
            });
        });


        $(document).ready(function() {
            $(document).on('click', '.campaign-btn', function(event) {
                event.preventDefault();
                // Get the action URL and campaign ID from the button's data attribute
                var actionUrl = $(this).attr('href');
                var campaignId = $(this).data('campaign-id');
                var selectedIds = [];

                // Collect selected product IDs
                $('table#items tbody input[type="checkbox"]:checked').each(function() {
                    selectedIds.push($(this).data('value'));
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select Product.',
                    });
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You are about to create a campaign with selected products!',
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
                                    campaign_id: campaignId // Pass the campaign ID
                                },
                                success: function(response) {
                                    if (response.message ===
                                        'Campaign products added successfully.') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.message,
                                        }).then(function() {
                                            // Redirect to the campaigns index route
                                            window.location.href =
                                                "{{ route('campaigns.index') }}";
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

        $(document).ready(function() {
  

    // Re-bind the delete event on each draw event of the DataTable
    $('.AjaxDataTable').on('draw.dt', function() {
        $(document).on("click", '.delete-btn', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: false
            }).then(function(t) {
                if (t.isConfirmed) {
                    $.ajax({
                        url: href,
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );

                                // Reload the DataTable after successful deletion
                                $('.AjaxDataTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the item.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
});

    </script>
@endsection
