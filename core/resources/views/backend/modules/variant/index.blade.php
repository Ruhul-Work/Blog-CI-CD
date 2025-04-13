
@extends('backend.layouts.master')
@section('meta')
    <title>All variants - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Variant Management</h4>
                <h6>List of variants</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="delete-btn-group"
                   href="{{route("variants.destroy.all")}}"><i data-feather="trash-2"
                                                               class="feather-trash-2 text-danger"></i></a>
            </li>


        </ul>
        <div class="page-btn">


            <a href="#" data-bs-toggle="modal"
               data-bs-target="#create" class="btn btn-added"><i data-feather="plus-circle"
                                                                              class="me-2"></i>Add New Variant</a>
        </div>
    </div>

    <div class="card ">

        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                    </div>
                </div>

            </div>


            <div class="table-responsive">
                <table class="table  AjaxDataTable"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th width="5px" class="no-sort" data-orderable="false">
                            <label class="checkboxs">
                                <input type="checkbox" id="select-all" data-value="0">
                                <span class="checkmarks"></span>
                            </label>
                        </th>
                        <th width="10px">Sl</th>
                        <th class="no-sort">Variant Name</th>
                        <th class="no-sort">Type</th>
                        <th class="no-sort" width="10px">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>




    <div class="modal fade" id="create" tabindex="-1" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createForm" action="{{route('variants.store')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="input-blocks">
                                    <label>Variant Type <span class="text-danger fs-4">*</span></label>
                                    <select name="type" id="type" class="select">
                                        <option value="edition"> Edition</option>
                                        <option value="paper_quality">Paper Quality</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="input-blocks">
                                    <label>Variant Name <span class="text-danger fs-4">*</span></label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer d-sm-flex justify-content-end">
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-submit me-2">
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                      aria-hidden="true"></span>
                                <span class="text">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade text-left" id="editModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel4" aria-hidden="true">
        <div class="modal-dialog  modal-lg modal-dialog-centered modal-dialog-scrollable"
             role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel4">Edit Form
                    </h4>
                    <button type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body edit-modal-body">





                </div>
                <div class="modal-footer d-sm-flex justify-content-end">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        var AJAX_URL = '{{ route('variants.ajax.index') }}';



        $('#createForm').submit(function (event) {
            event.preventDefault();

            var formData = $(this).serialize();
            var $form = $(this);

            var $button = $form.find('.btn-submit');
            var $spinner = $button.find('.spinner-border');
            var $text = $button.find('.text');

            // Show loading indicator and disable button
            $spinner.removeClass('d-none');
            $text.hide();
            $button.prop('disabled', true);

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: formData,
                success: function (response) {


                    $spinner.addClass('d-none');
                    $text.show();
                    $button.prop('disabled', false);
                    // Show a success message using SweetAlert
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn btn-success"
                    }).then(function () {
                        $('.AjaxDataTable').DataTable().ajax.reload();
                        $('#create').modal('hide');
                        $('#create')[0].reset();
                        // Reload the AjaxDataTable


                    });
                },






                error: function (xhr, status, error) {
                    // Hide loading indicator and enable button
                    $spinner.addClass('d-none');
                    $text.show();
                    $button.prop('disabled', false);

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseText
                    });
                }
            });
        });




        $(document).on("click", ".openEditModal", function(e) {
            e.preventDefault();
            $('#editModal').modal('show');// Show the modal with the ID "editModal"
            var href = $(this).attr('href');
            $.ajax({
                type: 'GET',

                url: href,

                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    $('.edit-modal-body').html(response.html);

                },

                error: function(xhr, status, error) {
                    // Handle the error response from the server
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again later.'
                    });
                }
            });
        });

        $(document).on("submit", '#updateForm', function(event) {
            event.preventDefault(); // Prevent default form submission behavior
            var form_data = $(this).serialize(); // Create an object with the form data
            $.ajax({
                type: 'POST',
                url: '{{ route('variants.update')}}',
                data: form_data,
                success: function(response) {
                    // Handle the response from the server
                    if (response.message) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        });
                    }
                    $('.modal').modal('hide');
                    $('#listTable').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    // Handle the error response from the server
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.errors ? Object.values(xhr.responseJSON.errors)[0][0] : 'An error occurred while submitting the form. Please try again.'
                    });
                }
            });
        });




        {{--$(document).ready(function () {--}}

        {{--    $(document).on("click", '.changeStatus', function (e) {--}}

        {{--        e.preventDefault();--}}
        {{--        var variantId = $(this).data('variant-id');--}}

        {{--        $.ajax({--}}
        {{--            url: '{{ route('variants.updateStatus') }}',--}}
        {{--            type: 'POST',--}}
        {{--            data: {--}}
        {{--                id: variantId,--}}
        {{--            },--}}
        {{--            success: function (response) {--}}
        {{--                // Show a success message using SweetAlert--}}
        {{--                Swal.fire({--}}
        {{--                    title: "Success!",--}}
        {{--                    text: response.message,--}}
        {{--                    type: "success",--}}
        {{--                    confirmButtonClass: "btn btn-success"--}}
        {{--                }).then(function () {--}}
        {{--                    // Reload the AjaxDataTable--}}
        {{--                    $('.AjaxDataTable').DataTable().ajax.reload();--}}
        {{--                });--}}
        {{--            },--}}

        {{--            error: function (xhr, status, error) {--}}
        {{--                Swal.fire({--}}
        {{--                    title: "Error!",--}}
        {{--                    text: "An error occurred while updating the  status.",--}}
        {{--                    type: "error",--}}
        {{--                    confirmButtonClass: "btn btn-danger"--}}
        {{--                });--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}

        {{--});--}}
    </script>
@endsection
