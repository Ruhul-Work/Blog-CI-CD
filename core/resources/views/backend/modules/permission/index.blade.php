@extends('backend.layouts.master')

@section('meta')
    <title>Permissions | {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Permission Management</h4>

            </div>
        </div>

        <ul class="table-top-head">
            @include('backend.include.buttons')
            <li>
                <a href="{{route('permission.all.delete.ajax')}}" class="delete-btn-group" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Delete Selected"><img src="{{ asset('theme/admin/assets/img/icons/delete.svg') }}"
                        alt="img"></a>
            </li>

        </ul>

        <div class="page-btn">
            <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#permissionModal"><i
                    data-feather="plus-circle" class="me-2"></i>Add New</a>
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
                <table class="table AjaxDataTable" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="no-sort" width="20px" data-orderable="false">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th width="150px">Module</th>
                            <th class="no-sort" data-orderable="false">Name</th>
                            <th class="no-sort" data-orderable="false">URL</th>
                            <th class="no-sort" width="50px" data-orderable="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route("permission.new.ajax")}}" id="createPermission"  method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-body add-product pb-0">
                                <div class="accordion-card-one accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="headingOne">
                                            <div class="accordion-button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne" aria-controls="collapseOne">
                                                <div class="addproduct-icon">
                                                    <h5><i data-feather="info" class="add-info"></i><span>Basic
                                                            Information</span></h5>
                                                    <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                            class="chevron-down-add"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">

                                                    <div class="col-lg-12 col-sm-12 col-12">
                                                        <div class="mb-3 add-product required">
                                                            <label class="form-label">Module Name</label>
                                                            <select name="module" id="module" class="selectModule">
                                                                @foreach ( $folderNames as $item)
                                                                <option value="{{$item}}">{{$item}}</option>
                                                                @endforeach
                                                            </select>
                                                            
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-sm-12 col-12">
                                                        <div class="mb-3 add-product required">
                                                            <label class="form-label">Permission Name</label>
                                                            <input type="text" class="form-control" id="name"
                                                                name="name" placeholder="Enter text here">
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-12 col-sm-12 col-12">
                                                        <div class="mb-3 add-product required">
                                                            <label class="form-label">Routes</label>
                                                            <select class="select2Ajax" name="routes[]" multiple>
                                                            </select>
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
                                <button type="submit" class="btn btn-submit">Create</button>
                            </div>
                        </div>

                    </form>



                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var AJAX_URL = "{{ route('permission.list.ajax') }}";
        try {
            var selectSimple = $('.select2Ajax');

            selectSimple.select2({

                minimumInputLength: 3,
                tags: [],
                ajax: {
                    url: '{{ route('permission.routes') }}',
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    data: function(term) {
                        return {
                            q: term.term
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }


            });



        } catch (err) {
            console.log(err);
        }


        $(document).ready(function() {
            $('.selectModule').select2({
                tags: true,
                dropdownParent: $('#permissionModal')
            });
        });


        $(document).on('submit', '#createPermission', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Create FormData object
            var formData = new FormData(this);

            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                processData: false, // Prevent jQuery from automatically processing the FormData
                contentType: false, // Prevent jQuery from automatically setting the content type
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            onClose: function() {
                                $('#permissionModal').modal('hide'); // Close the Ajax modal
                                // Check if .datatable is initialized and reload it
                                if ($.fn.DataTable.isDataTable('.AjaxDataTable')) {
                                    $('.AjaxDataTable').DataTable().ajax.reload();
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseText
                    });
                }
            });
        });
    </script>
@endsection
