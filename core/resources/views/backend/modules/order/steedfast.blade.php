@extends('backend.layouts.master')
@section('meta')
<title>All Orders - {{ get_option('title') }}</title>
@endsection
@section('content')
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4>Steedfast API</h4>
            <h6>List of Orders</h6>
        </div>
    </div>


</div>
<div class="card ">

    <div class="card-body">

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <!-- Progress Bar -->
                    <div class="progress mb-3" id="progress-container">
                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated"
                            role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                    <!-- Status Message -->
                    <div id="status-message" class="alert alert-info text-center" role="alert">
                        Status updates will appear here.
                    </div>
                    <!-- Start Update Button -->
                    <button id="start-update" class="btn btn-primary btn-block" onclick="fetchOrders()">Start
                        Update</button>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
@section('script')
<script>

    function fetchOrders() {
        fetch('{{route("std.get")}}', {  // Fetch the list of 300 orders
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => response.json())
            .then(data => {
                const orders = data.orders;  // Assuming the order numbers are returned as 'orders' array
                updateOrderStatuses(orders);
            })
            .catch(error => {
                console.error('Error fetching order numbers:', error);
            });
    }

    function updateOrderStatuses(orders) {
        let totalOrders = orders.length;
        let progressBar = document.getElementById('progress-bar');
        let statusMessage = document.getElementById('status-message');
        let completedRequests = 0;

        orders.forEach((order, index) => {
            setTimeout(() => {
                fetch(`{{route("std.fetch")}}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"// Pass CSRF token
                    },
                    body: JSON.stringify({
                        order_numbers: [order]  // Pass single order number at a time
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        completedRequests++;

                        let percentComplete = Math.min((completedRequests / totalOrders) * 100, 100);
                        progressBar.style.width = percentComplete + '%';
                        progressBar.setAttribute('aria-valuenow', percentComplete);

                        statusMessage.innerHTML = `Updating Order: ${data.data[0].order_number}, Status: ${data.data[0].status}`;

                        // Ensure the progress bar completes at 100%
                        if (completedRequests === totalOrders) {
                            progressBar.style.width = '100%';
                            statusMessage.classList.remove('alert-info');
                            statusMessage.classList.add('alert-success');
                            statusMessage.innerHTML = 'All orders updated!';
                        }
                    })
                    .catch(error => {
                        completedRequests++;
                        console.error('Error:', error);

                        // Increment the progress even if there's an error to avoid being stuck
                        let percentComplete = Math.min((completedRequests / totalOrders) * 100, 100);
                        progressBar.style.width = percentComplete + '%';
                        progressBar.setAttribute('aria-valuenow', percentComplete);

                        if (completedRequests === totalOrders) {
                            progressBar.style.width = '100%';
                            statusMessage.classList.remove('alert-info');
                            statusMessage.classList.add('alert-success');
                            statusMessage.innerHTML = 'All orders updated!';
                        }
                    });
            }, index * 100);  // Sequential delay for each request
        });
    }

</script>



@endsection