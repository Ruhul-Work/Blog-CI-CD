@extends('backend.layouts.master')

@section('meta')
    <title>Permissions | {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-wrapper-new p-0">
        <div class="content row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Update Permission</h4>
                       
                    </div>
                    <div class="card-body">
                        <form action="{{ route('permission.update.ajax', $permission->id) }}" id="bintelForm"
                            enctype="multipart/form-data" method="post">
                            <div class="modal-body custom-modal-body">

                                @csrf
                                <div class="col-lg-12 pe-0">
                                    <div class="mb-3 required">
                                        <label class="form-label">Module Name</label>
                                        <input type="text" name="module" class="form-control"
                                            value="{{ $permission->module }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 pe-0">
                                    <div class="mb-3 required">
                                        <label class="form-label">Permission Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $permission->name }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="mb-3 add-product required">
                                        <label class="form-label">Routes</label>
                                        <select class="select2Ajax" name="routes[]" multiple>
                                            @php
                                                
                                                $urls = explode(',', $permission->slug);
                                            @endphp
                                            @foreach($urls as $single)
                                              <option value="{{$single}}" selected>{{$single}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer-btn">
                                    <a class="btn btn-cancel me-2"
                                        href="{{route('permission.list')}}">Back</a>
                                    <button type="submit" class="btn btn-submit">Update</button>
                                </div>

                            </div>

                        </form>
                    </div>

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
