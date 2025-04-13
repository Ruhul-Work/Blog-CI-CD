
<!-- cta-horizon starts -->
<section class="cta-horizon pt-7 pb-7" style="background-color:#effff7;">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-6 col-offset-lg-2">
                <div class="section-title mb-4 pb-1">
                    <h2 class="mb-0">Subscribe To <span>Our Newsletter</span></h2>
                </div>
                <p>Want to be notified when we launch a new template or an update? Just sign up, and we'll send you a
                    notification by email.</p>
                <div class="newsletter-form">
                    <form id="newsletterForm" class="d-flex align-items-center">
                        @csrf
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <input type="submit" class="nir-btn" value="Subscribe">
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="newsletter-image float-right">
                    <img src="{{ asset('theme/frontend/assets/images/newsletter1.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- cta-horizon Ends -->

<script>
    // Handle the form submission
    document.getElementById('newsletterForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Prepare form data
        let formData = new FormData(this);

        fetch('{{ route('suscribe.newsletter') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
           
                Swal.fire({
                    icon: 'success',
                    title: 'সাবস্ক্রাইব!',
                    text: data.message,
                });

                document.getElementById('newsletterForm').reset();
            } else {
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong. Please try again later.',
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An unexpected error occurred. Please try again later.',
            });
            console.error('Error:', error);
        });
    });
</script>
