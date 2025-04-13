@extends('backend.layouts.master')
@section('meta')
    <title>All Monthly Sales- {{ get_option('title') }}</title>
@endsection

@section('content')
    <div id="app">




        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Sales</h4>
                    <h6>Manage Monthly Sales</h6>
                </div>
            </div>
            <ul class="table-top-head">
                @include('backend.include.buttons')

            </ul>
            <div class="page-btn">
                <a href="{{ route('sales.report') }}" class="btn btn-primary me-2">
                    <i data-feather="arrow-left"></i>Back
                </a>
            </div>
        </div>



        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="row">


                            <form>
                                <label for="year">Year:</label>
                                <select id="year" name="year" class="form-control"></select>
                                <label for="month">Month:</label>

                                <select id="month" name="month" class="form-control"></select>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="row">
            <div class="col-md-12">
                <div class="card table-list-card">
                    <div class="card-body p-4">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-input">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover AjaxDataTable" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="no-sort" style="font-size: 14px; font-weight:600px; width:100px">SN</th>
                                        <th style="font-size: 14px; font-weight:600px; width:100px">Month</th>
                                        <th class="no-sort" style="font-size: 14px; font-weight:600px; width:200px;">Total
                                            Order</th>
                                        <th class="no-sort" style="font-size: 14px; font-weight:600px; width:200px;">Total
                                            Sale</th>
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
    </div>
@endsection
@section('script')
    <script>
        AJAX_URL = "{{ route('sales.monthly-ajax') }}";


        $(document).ready(function() {
            const currentYear = new Date().getFullYear();
            const currentMonth = new Date().getMonth() + 1; // Months are 0-indexed

            const $yearSelect = $('#year');
            const $monthSelect = $('#month');

            // Array of full month names
            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            // Populate the year select box with a range of years
            for (let year = currentYear; year >= currentYear - 10; year--) {
                $yearSelect.append($('<option>', {
                    value: year,
                    text: year
                }));
            }

            // Populate the month select box
            function updateMonths() {
                $monthSelect.empty(); // Clear existing options

                const selectedYear = parseInt($yearSelect.val());

                // Show all months
                for (let month = 1; month <= 12; month++) {
                    $monthSelect.append($('<option>', {
                        value: month,
                        text: monthNames[month - 1] // Get full month name
                    }));
                }


            }

            // Initial population of months
            updateMonths();

            // Update months when the year changes
            $yearSelect.change(updateMonths);

            // Function to update DataTables AJAX URL
            function updateDataTablesUrl() {
                const year = $yearSelect.val();
                const month = $monthSelect.val();
                const newUrl = `${AJAX_URL}?year=${year}&month=${month}`;

                // Update all DataTables instances with the new URL
                $('.dataTable').each(function() {
                    $(this).DataTable().ajax.url(newUrl).load();
                });
            }

            // Update DataTables URL on select change
            $yearSelect.change(updateDataTablesUrl);
            $monthSelect.change(updateDataTablesUrl);
        });
    </script>
@endsection
