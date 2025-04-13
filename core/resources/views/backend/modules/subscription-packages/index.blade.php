@extends('backend.layouts.master')

@section('meta')
    <title>All Subscription Packages - {{ get_option('title') }}</title>
@endsection

@section('content')
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4>Subscription Package Management</h4>
            <h6>Subscription Package List</h6>
        </div>
    </div>
    <ul class="table-top-head">

        @include('backend.include.buttons')

        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="delete-btn-group"
               href="{{ route('subscription-packages.destroyAll') }}"><i data-feather="trash-2"
                                                                         class="feather-trash-2 text-danger"></i></a>
        </li>
    </ul>
    <div class="page-btn">
        <a href="{{ route('subscription-packages.create') }}" class="btn btn-added"><i data-feather="plus-circle"
                                                                                      class="me-2"></i>Add New Subscription Package</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-top">
            <div class="search-set">
                <div class="search-input">
                    <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table AjaxDataTable" style="width:100%">
                <thead>
                <tr>
                    <th width="5px" class="no-sort" data-orderable="false">
                        <label class="checkboxs">
                            <input type="checkbox" id="select-all" data-value="0">
                            <span class="checkmarks"></span>
                        </label>
                    </th>
                    <th>Sn</th>
                    <th>Title</th>
                    <th>Name</th>
                    <th>MRP Price</th>
                    <th>Current Price</th>
                    <th>Discount</th>
                    <th>Duration</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Action</th>
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
    var AJAX_URL = '{{ route('subscription-packages.ajax.index') }}';

    $(document).ready(function() {

    $(document).on("click", '.changeStatus', function (e) {
    e.preventDefault();
    const packageId = $(this).data('id');

    $.ajax({
        url: '{{ route('subscription-packages.updateStatus') }}',
        type: 'POST',
        data: {
            id: packageId,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
            });
            // Optionally reload the table or update the button dynamically
            $('.AjaxDataTable').DataTable().ajax.reload();
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while updating the status.',
            });
        }
    });
});
})

</script>
@endsection
