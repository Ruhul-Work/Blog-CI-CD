 @extends('frontend.layouts.master')

 @section('meta')
     <title>{{ $blog->title ?? 'Blog Details' }} | {{ get_option('title') }}</title>

     <meta property="og:title"
         content="{{ $blog->title ?? 'Blog Details' }} | {{ strtolower($blog->meta_title ?? get_option('title')) }}">
     <meta property="og:description" content="{{ strip_tags($blog->meta_description ?? get_option('description')) }}">
     <meta property="og:type" content="website">
     <meta property="og:url" content="{{ url()->current() }}">
     <meta property="og:image" content="{{ asset($blog->meta_image ?? get_option('meta_image')) }}">
     <meta property="og:site_name" content="{{ get_option('company_name') }}">
     <!-- Add more Open Graph tags as needed -->

     <meta name="twitter:card" content="summary_large_image">
     <meta name="twitter:title"
         content="{{ $blog->title ?? 'Blog Details' }} | {{ strtolower($blog->meta_title ?? get_option('title')) }}">
     <meta name="twitter:description" content="{{ strip_tags($blog->meta_description ?? get_option('description')) }}">
     <meta name="twitter:image" content="{{ asset($blog->meta_image ?? get_option('meta_image')) }}">
     <!-- Add more Twitter meta tags as needed -->
 @endsection

 @section('content')
     <section class="checkout-section py-5">
         <div class="container">
             <form id="checkoutForm" action="{{ route('subscriptions.order.store') }}" method="POST">
                 @csrf
                 <div class="row">
                     <!-- Checkout Form -->

                     <div class="col-lg-7 mb-4">
                         <div class="checkout-form-container p-4 rounded shadow-sm bg-white">
                             <h3 class="text-center mb-4">সাবস্ক্রিপশন চেকআউট</h3>


                             <!-- Select Plan -->
                             <div class="form-group mb-3">
                                 <label for="plan" class="form-label">আপনার প্ল্যান নির্বাচন করুন</label>
                                 <select id="plan" class="form-control">
                                     @foreach ($packages as $package)
                                         <option value="{{ $package->id }}"
                                             {{ $package->id == $selectedPackage->id ? 'selected' : '' }}>
                                             {{ $package->name }} - ৳{{ $package->current_price }}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>

                             <!-- Hidden Fields for Authenticated User -->
                             <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                             <input type="hidden" name="package_id" value="{{ $package->id }}">
                             <input type="hidden" name="package_price" value="{{ $package->current_price }}">
                             <input type="hidden" name="start_date" value="{{ now() }}">

                             <!-- Payment Method -->
                             <div class="form-group mb-4">
                                 <label for="paymentMethod" class="form-label">পেমেন্ট পদ্ধতি নির্বাচন করুন</label>
                                 <div class="d-flex gap-3 flex-wrap">
                                     <label class="wallet-option rounded shadow-sm p-3">
                                         <input type="radio" name="payment_method" value="bkash" required>
                                         <img src="{{ asset('theme/frontend/assets/images/icon/bkash.svg') }}"
                                             alt="bKash" class="img-fluid">
                                     </label>
                                 </div>
                             </div>

                         </div>
                     </div>

                     <!-- Plan Summary and Promo Code -->
                     <div class="col-lg-5">
                         <div class="plan-summary p-4 rounded shadow-sm bg-light">
                             <h5 class="mb-3">আপনার প্ল্যান সারাংশ</h5>
                             <!-- Summary Details -->
                             <ul class="list-unstyled mb-4">
                                 <li class="d-flex justify-content-between">
                                     <span>নির্বাচিত প্ল্যান:</span>
                                     <strong id="selectedPlan">{{ $selectedPackage->name }}</strong>
                                 </li>
                                 <li class="d-flex justify-content-between">
                                     <span>মূল্য:</span>
                                     <strong id="planPrice">৳ {{ $selectedPackage->current_price }}</strong>
                                 </li>
                                 <li class="d-flex justify-content-between">
                                     <span>ডিসকাউন্ট:</span>
                                     <strong id="discountAmount">৳ ০</strong>
                                 </li>
                                 <li class="d-flex justify-content-between">
                                     <span>সর্বমোট:</span>
                                     <strong id="totalAmount">৳ {{ $selectedPackage->current_price }}</strong>
                                 </li>
                             </ul>
                             <!-- Promo Code Input -->
                             <input type="hidden" id="packagePrice" name="package_price"
                                 value="{{ $selectedPackage->current_price }}">
                             <div class="promo-code-section">
                                 <label for="promoCode" class="form-label mb-2">প্রোমো কোড প্রয়োগ করুন</label>
                                 <div class="input-group">
                                     <input type="text" id="promoCode" name="coupon_code" class="form-control"
                                         placeholder="প্রোমো কোড লিখুন">
                                     <button type="button" class="btn btn-success" id="applyPromoCode">প্রয়োগ
                                         করুন</button>
                                 </div>
                             </div>
                         </div>
                         <button type="submit" class="btn btn-custom btn-block w-100 mt-4">পেমেন্ট সম্পন্ন করুন</button>
                     </div>


                 </div>
             </form>
         </div>
     </section>
 @endsection

 <style>
     body.night-mode .plan-summary ul li {
         color: #777 !important;
         font-weight: 700;
     }
      .plan-summary ul li {
         color: #777 !important;
         font-weight: 700;
     }
 </style>

 @section('scripts')
     <script>
         $(document).ready(function() {
             $('#checkoutForm').on('submit', function(e) {
                 e.preventDefault();

                 const formData = $(this).serialize();

                 $.ajax({
                     url: "{{ route('subscriptions.order.store') }}",
                     type: "POST",
                     data: formData,
                     success: function(response) {
                         if (response.redirect_url) {
                             window.location.href = response.redirect_url;
                         } else {
                             Swal.fire('Error!', 'Payment initialization failed.', 'error');
                         }
                     },
                     error: function(xhr) {
                         Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                     }
                 });
             });
         });


         document.addEventListener('DOMContentLoaded', function() {
             const planDropdown = document.getElementById('plan');
             const packageIdInput = document.querySelector('input[name="package_id"]');
             const packagePriceInput = document.querySelector('input[name="package_price"]');
             const selectedPlanElement = document.getElementById('selectedPlan');
             const planPriceElement = document.getElementById('planPrice');
             const totalAmountElement = document.getElementById('totalAmount');
             const discountAmountElement = document.getElementById('discountAmount');

             let discount = 0;

             // Store plan details in a JavaScript object
             const plans = @json($packages);

             // Function to update hidden fields and UI
             function updatePlanDetails(planId) {
                 const selectedPlan = plans.find(plan => plan.id == planId);

                 if (selectedPlan) {
                     // Update hidden inputs
                     packageIdInput.value = selectedPlan.id;
                     packagePriceInput.value = selectedPlan.current_price;

                     // Update UI summary
                     selectedPlanElement.textContent = selectedPlan.name;
                     planPriceElement.textContent = `৳ ${selectedPlan.current_price}`;

                     // Update total amount considering any applied discount
                     const totalAmount = selectedPlan.current_price - discount;
                     totalAmountElement.textContent = `৳ ${totalAmount.toFixed(2)}`;
                     packagePriceInput.value = totalAmount.toFixed(2); // Update the hidden field
                 }
             }

             // On page load, update details to match the initially selected plan
             updatePlanDetails(planDropdown.value);

             // Update details when the user selects a new plan
             planDropdown.addEventListener('change', function() {
                 discount = 0;
                 discountAmountElement.textContent = `৳ 0.00`;
                 updatePlanDetails(planDropdown.value);
             });


             // Promo Code Application
             $('#applyPromoCode').on('click', function() {
                 const couponCode = $('#promoCode').val();
                 const packagePrice = parseFloat(planPriceElement.textContent.replace('৳', '').trim());

                 $.ajax({
                     url: "{{ route('subscriptions.validate-coupon') }}",
                     type: "POST",
                     data: {
                         _token: "{{ csrf_token() }}",
                         coupon_code: couponCode,
                         package_price: packagePrice,
                     },
                     success: function(response) {
                         if (response.success) {
                             // Update discount and total in UI using server response
                             discount = parseFloat(response.discount);
                             discountAmountElement.textContent = `৳ ${discount.toFixed(2)}`;
                             const totalAmount = packagePrice - discount;
                             totalAmountElement.textContent = `৳ ${totalAmount.toFixed(2)}`;
                             packagePriceInput.value = totalAmount.toFixed(
                                 2); // Update hidden field for package price
                         } else {
                             Swal.fire(response.message);
                         }
                     },
                     error: function() {
                         Swal.fire('Failed to validate the coupon. Please try again.');
                     },
                 });
             });
         });
     </script>
 @endsection
