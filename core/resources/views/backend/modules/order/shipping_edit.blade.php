@extends('backend.layouts.master')
@section('meta')
    <title>Shipping Edit - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Order Management</h4>
                <h6>Shipping Details Edit</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn d-flex">
                    <a href="{{route('orders.index')}}" class="btn btn-secondary me-2"><i data-feather="arrow-left"
                                                                                          class="me-2"></i>Back to orders</a>
                    <a href="{{route('orders.edit',$order->id)}}" class="btn btn-info"><i data-feather="edit"
                                                                                          class="me-2 text-white"></i>Edit</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('orders.shipping.update', $order->id) }}" method="POST">
                        @csrf
                        <div class="row mt-2">
                            <!-- Personal Info Section -->
                            <div class="col-md-12 mt-3">
                                <h5 class="text-primary">Personal Info</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name: *</label>
                                    <input id="name" type="text" class="form-control" name="name" required value="{{ $order->shipping->name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email: <small>(Alternative)</small></label>
                                    <input id="email" type="text" class="form-control" name="email" value="{{ $order->shipping->email }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mobile Number:</label>
                                    <input type="text" class="form-control" name="phone" value="{{ $order->shipping->phone }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mobile Number: <small>(Alternative)</small></label>
                                    <input id="phone_alt" type="text" class="form-control" name="alternate_phone" value="{{ $order->shipping->alternate_phone }}">
                                </div>
                            </div>

                            <!-- Address Details Section -->
                            <div class="col-md-12 mt-3">
                                <h5 class="text-primary">Address Details</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address *</label>
                                    <textarea id="address" class="form-control" name="address" required>{{ $order->shipping->address }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <select class="form-control" id="country_id" name="country_id" onchange="GetCities()">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $key => $co)
                                            <option value="{{ $co->id }}" {{ $co->id == $order->shipping->country_id ? 'selected' : '' }}>{{ $co->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">City: * <span class="refresh" title="Refresh City"><i class="text-danger ri-restart-line"></i></span></label>
                                    <select class="form-control select2" id="city_id" name="city_id" onchange="GetUpazilas()">
                                        <option value="{{ $order->shipping->city_id }}">{{ $order->shipping->city->name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="upazila">Thana/Upazila: *</label>
                                    <select class="form-control select2" id="upazila_id" name="upazila_id">
                                        <option value="{{ $order->shipping->upazila_id }}">{{ $order->shipping->upazila->name }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Other Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sale Date:</label>
                                    <input id="sale_date" type="date" class="form-control" name="sale_date" value="{{ $order->sale_date }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Post Code:</label>
                                    <input id="zip_code" type="text" class="form-control" name="zip_code" value="{{ $order->shipping->zip_code }}">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success w-100 mt-3 py-3">Update Order Shipping</button>
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
        function GetCities() {
            var countryId = $('#country_id').val();
            if (countryId) {
                $.ajax({
                    url: '{{ route('places.cities') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        country_id: countryId
                    },
                    success: function(data) {
                        $('#city_id').html(null);
                        $.each(data, function(index, city) {
                            $('#city_id').append($('<option>', {
                                value: city.id,
                                text: city.name
                            }));
                        });
                        GetUpazilas();  // Fetch Upazilas after populating cities
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching cities:', error);
                    }
                });
            }
        }

        function GetUpazilas() {
            var cityId = $('#city_id').val();
            if (cityId) {
                $.ajax({
                    url: '{{ route('places.upazilas') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        city_id: cityId
                    },
                    success: function(data) {
                        $('#upazila_id').html(null);
                        $.each(data, function(index, upazila) {
                            $('#upazila_id').append($('<option>', {
                                value: upazila.id,
                                text: upazila.name
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching upazilas:', error);
                    }
                });
            }
        }


        $('.refresh').on('click', function(e) {
            GetCities();
        });
    </script>
@endsection



