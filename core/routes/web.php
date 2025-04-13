<?php

use App\Http\Controllers\BkashController;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\frontend\AuthController;
use App\Http\Controllers\frontend\AuthorController;
use App\Http\Controllers\frontend\BlogDashboardController;
use App\Http\Controllers\frontend\BloglistController;
use App\Http\Controllers\frontend\CampaignController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\CheckoutController;
use App\Http\Controllers\frontend\ContactUsController;
use App\Http\Controllers\frontend\FooterNavController;
use App\Http\Controllers\frontend\HomeBlogController;
use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\MenuController;
use App\Http\Controllers\frontend\OtpController;
use App\Http\Controllers\frontend\PasswordResetController;
use App\Http\Controllers\frontend\ProductController;
use App\Http\Controllers\frontend\BlogController;
use App\Http\Controllers\frontend\PublisherController;
use App\Http\Controllers\frontend\ReviewController;
use App\Http\Controllers\frontend\SearchController;
use App\Http\Controllers\frontend\StationaryController;
use App\Http\Controllers\frontend\SubscriptionController;
use App\Http\Controllers\frontend\UserDashboardController;
use App\Http\Controllers\frontend\UserPasswordController;
use App\Http\Controllers\frontend\UserProfileController;
use App\Http\Controllers\frontend\UserSettingsController;
use App\Http\Controllers\frontend\WishlistController;
use App\Http\Controllers\PlaceController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\frontend\CategoryController;
use \App\Http\Controllers\frontend\ShopController;
use App\Http\Controllers\frontend\ShareController;
use \App\Http\Controllers\frontend\CouponGenerateController;
use \App\Http\Controllers\frontend\CommentController;





Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');  // Clear application cache
    Artisan::call('config:clear'); // Clear config cache
    Artisan::call('route:clear');  // Clear route cache
    Artisan::call('view:clear');   // Clear view cache
    return response()->json(['message' => 'Cache cleared successfully!']);
});



Route::post('/reply-comment', [CommentController::class, 'reply'])->name('reply.comment');


Route::post('/blog-comment', [CommentController::class, 'addComment'])->name('add.comment');
Route::post('/bloglike/{id}/like', [CommentController::class, 'like'])->name('blog.like');

//login-register

//otp auth
// Route::get('otp/verify/{email?}', [AuthOtpController::class, 'showOtpForm'])->name('auth.otp.verify');
// Route::post('otp/verify', [AuthOtpController::class, 'verifyOtp'])->name('auth.otp.verify.post');
// Route::post('/otp/resend', [AuthOtpController::class, 'resendOtp'])->name('auth.otp.resend');

//login-register route
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

//google login
Route::get('login/google', [AuthController::class,'redirectToGoogleProvider'])->name('login.google');
Route::get('login/google/callback', [AuthController::class,'handleGoogleCallback'])->name('login.google.callback');

//otp route
Route::get('/otp-verify/{email}', [OtpController::class, 'showOtpForm'])->name('auth.otp.verify');
Route::post('/otp-validate', [OtpController::class, 'validateOtp'])->name('auth.otp.validate');
Route::post('/resend-otp', [OtpController::class, 'resendOtp'])->name('auth.otp.resend');

//Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/section-products', [HomeController::class, 'sectionProduct'])->name('section.products');
Route::get('/get-section-content', [HomeController::class, 'getSectionContent'])->name('getSectionContent');

//shop
Route::get('/shop', [ShopController::class, 'shopIndex'])->name('shop.index');
Route::get('/shop/products', [ShopController::class, 'getShopProducts'])->name('shop.products');
Route::get('/shop/filter', [ShopController::class, 'getShopFilter'])->name('shop.filter');
//categories
Route::get('/categories/all', [CategoryController::class, 'allCategories'])->name('category.all');
Route::get('/categories/get', [CategoryController::class, 'getCategories'])->name('category.get');
Route::get('/category/{slug}', [CategoryController::class, 'categorySingle'])->name('category.single');
Route::get('/category/products/{slug}', [CategoryController::class, 'getCategoryProducts'])->name('category.products');
Route::get('/categories/search', [CategoryController::class, 'searchCategories'])->name('category.search');

//authors
Route::get('/authors/all', [AuthorController::class, 'allAuthors'])->name('author.all');
Route::get('/authors/get', [AuthorController::class, 'getAuthors'])->name('author.get');
Route::get('/author-single/{slug}', [AuthorController::class, 'authorSingle'])->name('author.single');
Route::get('/author/products/{slug}', [AuthorController::class, 'getAuthorProducts'])->name('author.products');
Route::get('/authors/search', [AuthorController::class, 'searchAuthors'])->name('author.search');

// publishers
Route::get('/publishers/all', [PublisherController::class, 'allPublishers'])->name('publisher.all');
Route::get('/publishers/get', [PublisherController::class, 'getPublishers'])->name('publisher.get');
Route::get('/publisher-single/{slug}', [PublisherController::class, 'publisherSingle'])->name('publisher.single');
Route::get('publisher/products/{slug}', [PublisherController::class, 'getPublisherProducts'])->name('publisher.products');
Route::get('/publishers/search', [PublisherController::class, 'searchPublishers'])->name('publisher.search');

//campaigns
Route::get('/campaigns/all', [CampaignController::class, 'allCampaigns'])->name('campaign.all');
Route::get('/campaigns/get', [CampaignController::class, 'getCampaigns'])->name('campaign.get');
Route::get('/campaign/{slug}', [CampaignController::class, 'campaignSingle'])->name('campaign.single');
Route::get('/campaign/products/{slug}', [CampaignController::class, 'getCampaignProducts'])->name('campaign.products');
//searching
Route::get('/search', [SearchController::class, 'searchingAjax'])->name('search');
Route::get('search/{search}/{productType?}', [SearchController::class, 'searchSingleQuery'])->name('search.single');
Route::get('/products/{search}/{productType?}', [SearchController::class, 'getSearchProducts'])->name('search.products');

//Route::get('/books', [SearchController::class, 'allbooks'])->name('all.search');

//stationary
Route::get('/stationary', [StationaryController::class, 'index'])->name('stationary.index');
Route::get('/stationary-category', [StationaryController::class, 'getStationaryCategories'])->name('stationary.category.get');

Route::get('product/{slug_or_id}', [ProductController::class, 'productDetails'])->name('product.details');
Route::get('/related-products/{product}', [ProductController::class, 'fetchRelatedProducts'])->name('product.related');
Route::get('/related-authors-products/{product}', [ProductController::class, 'productAuthorBooks'])->name('product.author.books');
Route::get('/latest-products', [ProductController::class, 'latestProduct'])->name('product.latest');

Route::get('/product/{id}/images', [ProductController::class, 'getProductImages'])->name('product.images');

//review
Route::post('/submit-review/{product}', [ReviewController::class, 'submitReview'])->name('submit-review');

//menu
Route::get('menu/search_nav', [MenuController::class, 'searchNav'])->name('menu.search_nav');

//footer
Route::get('/about-us', [FooterNavController::class, 'aboutUs'])->name('about');
Route::get('/privacy-policy', [FooterNavController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions', [FooterNavController::class, 'termsCondition'])->name('terms');
Route::get('/contact-us', [FooterNavController::class, 'contactUs'])->name('contact');
Route::post('/contact/store', [FooterNavController::class, 'storeContact'])->name('contact.store');
Route::post('/subscribe', [FooterNavController::class, 'storeSubscribe'])->name('subscribe.store');

//cart
Route::prefix('cart')->group(function () {
    Route::get('/show', [CartController::class, 'getCart'])->name('cart.show');
    Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/count', [CartController::class, 'countItems'])->name('cart.count');
    Route::get('/total', [CartController::class, 'getTotal'])->name('cart.total');

});

Route::prefix('wishlist')->group(function () {
    Route::post('/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('/remove', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::get('/show', [WishlistController::class, 'getWishlist'])->name('wishlist.index');
    Route::get('/count', [WishlistController::class, 'countWishlistItems'])->name('wishlist.count');
});

Route::prefix('checkout')->group(function () {
    Route::get('/index', [CheckoutController::class, 'orderCheckOutForm'])->name('checkout.order.form');
    Route::post('/placed/order', [CheckoutController::class, 'storeWebsiteOrder'])->name('checkout.store.order');
    Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply.coupon');
    Route::post('/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove.coupon');
    Route::get('/success/{id}', [CheckoutController::class, 'showSuccessOrder'])->name('checkout.orders.complete');
});

// Route::prefix('blog')->group(function () {

//     Route::get('/all', [BlogController::class, 'allBlogs'])->name('blogs.all');
//     Route::get('/get', [BlogController::class, 'getBlogs'])->name('blogs.get');
//     Route::get('/single/{slug_or_id}', [BlogController::class, 'blogSingle'])->name('blogs.single');
//     Route::get('/category/{slug_or_id}', [BlogController::class, 'showByCategory'])->name('blogs.by.categories');
//     Route::post('/submit-review/{blog}', [BlogController::class, 'submitComment'])->name('blogs.comment.store');
//     Route::get('/search', [BlogController::class, 'search'])->name('blogs.search');

// });

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders/track/{id}', [UserDashboardController::class, 'track'])->name('orders.track');
    Route::get('/orders/items/{id}', [UserDashboardController::class, 'showOrderItems'])->name('orders.show.items');
    // Profile Routes
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/password/change', [UserPasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/dashboard/password/update', [UserPasswordController::class, 'updatePassword'])->name('dashboard.password.update');
    Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [UserSettingsController::class, 'update'])->name('settings.update');

});

// Checkout (URL) User Part
Route::get('/bkash-pay', [BkashController::class, 'payment'])->name('url-pay');
Route::get('/bkash-create/{order_id}', [BkashController::class, 'createPayment'])->name('create.bkash.payment');
Route::get('/bkash-callback/{order_id}', [BkashController::class, 'callback'])->name('url-callback');

// Checkout (URL) Admin Part
Route::get('/bkash-refund', [BkashController::class, 'getRefund'])->name('url-get-refund');
Route::post('/bkash-refund', [BkashController::class, 'refundPayment'])->name('url-post-refund');
Route::get('/bkash-search', [BkashController::class, 'getSearchTransaction'])->name('url-get-search');
Route::post('/bkash-search', [BkashController::class, 'searchTransaction'])->name('url-post-search');

Route::get('/payment-failed', function () {
    return view('bkash.fail');
})->name('payment.failed');

//Route::get('/best-sellers', [HomeController::class, 'bestSeller'])->name('bestSellers');

Route::post('/places/divisions', [PlaceController::class, 'getDivisions'])->name('places.divisions');
Route::post('/places/cities', [PlaceController::class, 'getCitiesByCountry'])->name('places.cities');
Route::post('/places/upazilas', [PlaceController::class, 'getUpazilasByCity'])->name('places.upazilas');
Route::post('/places/districts', [PlaceController::class, 'getDistrictsBYDivisions'])->name('places.districts_by_division');
Route::post('/places/unions', [PlaceController::class, 'getUnionsByUpazila'])->name('places.unions');

Route::get('password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

Route::get('/steedfast/get-order-numbers', [CoreController::class, 'getOrderNumbers'])->name('std.get');
Route::post('/steedfast/update-order-status', [CoreController::class, 'updateOrderStatus'])->name('std.fetch');

Route::get('backend/steedfast/automation', function () {
    return view("backend.modules.order.steedfast");
})->name('std.view');



//::::---->blog website new route<-----::://

//home route
Route::get('/', [HomeBlogController::class, 'index'])->name('home');
Route::get('/blog-card', [HomeBlogController::class, 'fetchBlogsCard'])->name('blogs.fatchcard');

Route::get('/blog-details/{slug}', [HomeBlogController::class, 'show'])->name('blogsDetails.show');


Route::post('/subscribe-newsletter', [HomeBlogController::class, 'suscribeNewsletter'])
->name('suscribe.newsletter');
Route::get('/search-blogs', [HomeBlogController::class, 'search'])->name('blogs.search');


//blog list route
Route::get('/bloglist', [BloglistController::class, 'index'])->name('bloglist.index');
Route::get('/fetch-blogs', [BloglistController::class, 'fetchAllBlogs'])->name('blogs.fetchAll');
Route::get('/category-blogs/{slug}', [BloglistController::class, 'showCategoryBlogs'])->name('categoryblogs.blogs');

Route::get('/tag/{slug}', [BloglistController::class, 'showTagBlogs'])->name('tag.blogs');
Route::get('/blogcategory', [BloglistController::class, 'blogCategory'])->name('bloglist.cetagory');
Route::get('/blogdetails', [BloglistController::class, 'blogDetails'])->name('bloglist.blogdetails');

//free and primium blog show route 
Route::get('/blogs-free', [BloglistController::class, 'freeBlogs'])->name('blogs.free');
Route::get('/blogs-premium', [BloglistController::class, 'premiumBlogs'])->name('blogs.premium');


//subscription route

    
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');

    Route::middleware(['active.checkout'])->group(function () {
        Route::get('/subscriptions/checkout/{id}', [SubscriptionController::class, 'checkout'])
            ->name('subscriptions.checkout');

        Route::post('/subscriptions/order/store', [SubscriptionController::class, 'storeOrder'])
            ->name('subscriptions.order.store');

        Route::post('/subscriptions/validate-coupon', [SubscriptionController::class, 'validateCoupon'])->name('subscriptions.validate-coupon');

    });



//share route
Route::post('/share-blog', [ShareController::class, 'recordShare'])->name('share.blog');
Route::get('/blogs/share/{slug}', [ShareController::class, 'validateShare'])->name('blogs.share');



//Contact us route
Route::get('/contact', [ContactUsController::class, 'index'])->name('contact.index');

Route::middleware(['auth'])->group(function () {
//dashboard  route
    Route::get('/dashboard', [HomeBlogController::class, 'dashboardIndex'])->name('dashboard.index');
    Route::get('/my-account', [BlogDashboardController::class, 'myAccount'])->name('dashboard.myAccount');
    Route::post('/profile-edit', [BlogDashboardController::class, 'profileEdit'])->name('dashboard.profile.edit');
    Route::get('/my-plan', [BlogDashboardController::class, 'myPlan'])->name('dashboard.myPlan');
    Route::get('/point', [BlogDashboardController::class, 'point'])->name('dashboard.point');
    

});

//coupon route
Route::get('/generate-coupon', [CouponGenerateController::class, 'couponUser'])->name('dashboard.coupon');
Route::get('/coupon-users', [CouponGenerateController::class, 'couponUserView'])->name('dashboard.coupon-users');
Route::post('/eligible-coupon-user', [CouponGenerateController::class, 'eligibleCouponUser'])->name('eligible-coupon.user-ajax');
Route::get('/generate-coupon/{id}', [CouponGenerateController::class, 'generateCouponForPoints'])->name('coupons.generate');
Route::post('/redeem-coupon', [CouponGenerateController::class, 'redeemCoupon']);


