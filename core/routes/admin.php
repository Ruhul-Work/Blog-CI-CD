<?php

use App\Http\Controllers\backend\AuthorsController;
use App\Http\Controllers\backend\BlogController;
use App\Http\Controllers\backend\CampaignController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CouponController;
use App\Http\Controllers\backend\CourierController;
use App\Http\Controllers\backend\HomeCategoryController;
use App\Http\Controllers\backend\MenuController;
use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\ProductSectionController;
use App\Http\Controllers\backend\PublisherController;
use App\Http\Controllers\backend\PurchaseController;
use App\Http\Controllers\backend\Reports\SalesReportController;
use App\Http\Controllers\backend\Reports\StockController;
use App\Http\Controllers\backend\Reports\WalletsReportsController;
use App\Http\Controllers\backend\ReturnController;
use App\Http\Controllers\backend\ReviewController;
use App\Http\Controllers\backend\Setting\CountryCityController;
use App\Http\Controllers\backend\Setting\HomeBannerController;
use App\Http\Controllers\backend\Setting\OptionController;
use App\Http\Controllers\backend\Setting\OrderStatusController;
use App\Http\Controllers\backend\Setting\paymentMethodController;
use App\Http\Controllers\backend\SliderController;
use App\Http\Controllers\backend\SubcategoryController;
use App\Http\Controllers\backend\SubjectController;
use App\Http\Controllers\backend\SubscriptionPackageController;
use App\Http\Controllers\backend\SubscriberController;
use App\Http\Controllers\backend\TagController;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\VariantController;
use App\Http\Controllers\PlaceController;
use Illuminate\Support\Facades\Route;

Route::prefix('backend')->middleware(['auth', 'admin', 'HasAccess'])->group(function () {

//------New Route start --->
    // Blog routes
    Route::group(['prefix' => 'blogs'], function () {
        Route::get('/', [BlogController::class, 'index'])->name('blogs.index');
        Route::post('/ajax', [BlogController::class, 'ajaxIndex'])->name('blogs.ajax.index');
        Route::get('/create', [BlogController::class, 'create'])->name('blogs.create');
        Route::post('/store', [BlogController::class, 'store'])->name('blogs.store');
        Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('blogs.edit');
        Route::get('/show/{id}', [BlogController::class, 'show'])->name('blogs.show');
        Route::post('/update/{id}', [BlogController::class, 'update'])->name('blogs.update');
        Route::get('/delete/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy');
        Route::get('/delete-all', [BlogController::class, 'destroyAll'])->name('blogs.destroyAll');
        Route::post('/blogs/update-field', [BlogController::class, 'updateField'])->name('blogs.updateField');

    });
    // tag routes
    Route::group(['prefix' => 'tags'], function () {
        Route::get('/', [TagController::class, 'index'])->name('tags.index');
        Route::post('/ajax', [TagController::class, 'ajaxIndex'])->name('tags.ajax.index');
        Route::get('/create', [TagController::class, 'create'])->name('tags.create');
        Route::post('/store', [TagController::class, 'store'])->name('tags.store');
        Route::get('/edit/{id}', [TagController::class, 'edit'])->name('tags.edit');
        Route::get('/show/{id}', [TagController::class, 'show'])->name('tags.show');
        Route::post('/update/{id}', [TagController::class, 'update'])->name('tags.update');
        Route::get('/delete/{id}', [TagController::class, 'destroy'])->name('tags.destroy');
        Route::get('/all-delete', [TagController::class, 'destroyAll'])->name('tags.destroy.all');

    });

    // subscription packages routes
    Route::prefix('subscription-packages')->name('subscription-packages.')->group(function () {
        Route::get('/', [SubscriptionPackageController::class, 'index'])->name('index');
        Route::post('/ajaxtable', [SubscriptionPackageController::class, 'ajaxIndex'])->name('ajax.index');
        Route::get('/create', [SubscriptionPackageController::class, 'create'])->name('create');
        Route::post('/store', [SubscriptionPackageController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SubscriptionPackageController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SubscriptionPackageController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SubscriptionPackageController::class, 'destroy'])->name('destroy');
        Route::get('/delete-all', [SubscriptionPackageController::class, 'destroyAll'])->name('destroyAll');
        Route::post('/update-status', [SubscriptionPackageController::class, 'updateStatus'])->name('updateStatus');
    });
    // subscriber routes
    Route::prefix('subscriber')->group(function () {
        Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscriber.list');
        Route::post('/subscribers-ajax', [SubscriberController::class, 'ajaxIndex'])->name('subscribers.ajax.index');

    });
    // allcustomer routes
    Route::prefix('allcustomer')->group(function () {
        Route::get('/allcustomer', [SubscriberController::class, 'customerIndex'])->name('allcustomer.list');
        Route::post('/allcustomer-ajax', [SubscriberController::class, 'allcustomerajaxIndex'])->name('allcustomer.ajax.index');

    });

//------new route end--->

    Route::group(['prefix' => 'authors'], function () {
        // Route for listing authors
        Route::get('/', [AuthorsController::class, 'index'])->name('authors.index');
        // Route for displaying author creation form
        Route::get('/create', [AuthorsController::class, 'create'])->name('authors.create');
        // Route for storing a new author
        Route::post('/', [AuthorsController::class, 'store'])->name('authors.store');
        // Route for AJAX datatable functionality
        Route::post('/ajax-datatable', [AuthorsController::class, 'ajaxIndex'])->name('authors.ajax');
        // Route for deleting a single author
        Route::get('/delete/{id}', [AuthorsController::class, 'destroy'])->name('authors.destroy');
        // Route for displaying author edit form
        Route::get('/edit/{id}', [AuthorsController::class, 'edit'])->name('authors.edit');
        // Route for updating an author's details
        Route::post('/edit', [AuthorsController::class, 'editStore'])->name('authors.update');
        // Route for updating author status (e.g., active or inactive)
        Route::post('/status', [AuthorsController::class, 'updateStatus'])->name('author.updateStatus');
        // Route for deleting a single author (alternative to the delete route with ID in URL)

        // Route for deleting multiple authors at once
        Route::get('/all-delete', [AuthorsController::class, 'destroyAll'])->name('authors.all.delete');
        Route::get('/search', [AuthorsController::class, 'authorSearch'])->name('authors.search');
    });

    // all publishers routes
    Route::group(['prefix' => 'publishers'], function () {
        // Route for listing publisher
        Route::get('/', [PublisherController::class, 'index'])->name('publishers.index');
        // Route for view form
        Route::get('/create', [PublisherController::class, 'create'])->name('publishers.create');
        // Route for storing a new publisher
        Route::post('/', [PublisherController::class, 'store'])->name('publishers.store');
        // Route for AJAX data table
        Route::post('/ajax-datatable', [PublisherController::class, 'ajaxIndex'])->name('publishers.ajax');
        // Route for deleting a publisher
        Route::get('/delete/{id}', [PublisherController::class, 'destroy'])->name('publishers.destroy');
        // Route for editing a publisher
        Route::get('/edit/{id}', [PublisherController::class, 'edit'])->name('publishers.edit');
        // Route for updating a publisher
        Route::post('/edit', [PublisherController::class, 'editStore'])->name('publishers.update');
        // Route for updating publisher status
        Route::post('/status', [PublisherController::class, 'updateStatus'])->name('publishers.updateStatus');
        // Route for deleting multiple publisher at once
        Route::get('/all-delete', [PublisherController::class, 'destroyAll'])->name('publishers.all.delete');

        Route::get('/search', [PublisherController::class, 'publisherSearch'])->name('publishers.search');
    });

    Route::group(['prefix' => 'sliders'], function () {
        // Route for listing publishers
        Route::get('/', [SliderController::class, 'index'])->name('sliders.index');

        Route::get('/sub', [SliderController::class, 'Subindex'])->name('sliders.sub-index');
        // Route for view form
        Route::get('/create', [SliderController::class, 'create'])->name('sliders.create');
        // Route for storing a new publisher
        Route::post('/', [SliderController::class, 'store'])->name('sliders.store');
        // Route for AJAX data table
        Route::post('/ajax-datatable', [SliderController::class, 'ajaxIndex'])->name('sliders.ajax');
        // Route for deleting a publisher
        Route::get('/delete/{id}', [SliderController::class, 'destroy'])->name('sliders.destroy');
        // Route for editing a publisher
        Route::get('/edit/{id}', [SliderController::class, 'edit'])->name('sliders.edit');
        // Route for updating a publisher
        Route::post('/edit', [SliderController::class, 'editStore'])->name('sliders.update');
        // Route for updating publisher status
        Route::post('/status', [SliderController::class, 'updateStatus'])->name('sliders.updateStatus');
        // Route for deleting multiple publisher at once
        Route::get('/all-delete', [SliderController::class, 'destroyAll'])->name('sliders.all.delete');
    });
    // all couriers routes
    Route::group(['prefix' => 'couriers'], function () {
        // Route for listing couriers
        Route::get('/', [CourierController::class, 'index'])->name('couriers.index');
        // Route for creating a new courier
        Route::get('/create', [CourierController::class, 'create'])->name('couriers.create');
        // Route for storing a new courier
        Route::post('/', [CourierController::class, 'store'])->name('couriers.store');
        // Route for AJAX data table
        Route::post('/ajax-datatable', [CourierController::class, 'ajaxIndex'])->name('couriers.ajax');
        // Route for deleting a courier
        Route::get('/delete/{id}', [CourierController::class, 'destroy'])->name('couriers.destroy');
        // Route for editing a courier
        Route::get('/edit/{id}', [CourierController::class, 'edit'])->name('couriers.edit');
        // Route for updating a courier
        Route::post('/edit', [CourierController::class, 'editStore'])->name('couriers.update');
        // Route for updating courier status
        Route::post('/status', [CourierController::class, 'updateStatus'])->name('couriers.updateStatus');
        // Route for deleting multiple couriers at once
        Route::get('/all-delete', [CourierController::class, 'destroyAll'])->name('couriers.all.delete');
    });
    Route::group(['prefix' => 'homebanner'], function () {
        // Route for listing couriers
        Route::get('/', [HomeBannerController::class, 'index'])->name('homecart1.index');

        Route::get('/banner', [HomeBannerController::class, 'index2'])->name('homecart1.index2');
        // Route for creating a new courier
        Route::get('/create', [HomeBannerController::class, 'create'])->name('homecart1.create');

        Route::post('/ajax-datatable', [HomeBannerController::class, 'ajaxIndex'])->name('homecart1.ajax');
        // Route for deleting a courier
        Route::get('/delete/{id}', [HomeBannerController::class, 'destroy'])->name('homecart1.destroy');
        // Route for editing a courier
        Route::get('/edit/{id}', [HomeBannerController::class, 'edit'])->name('homecart1.edit');
        // Route for updating a courier
        Route::post('/edit', [HomeBannerController::class, 'editStore'])->name('homecart1.update');
        // Route for updating courier status
        Route::post('/status', [HomeBannerController::class, 'updateStatus'])->name('homecart1.updateStatus');
        // Route for deleting multiple couriers at once
        Route::get('/all-delete', [HomeBannerController::class, 'destroyAll'])->name('homecart1.all.delete');
    });
    Route::group(['prefix' => 'campaigns'], function () {
        // Route for listing campaigns
        Route::get('/', [CampaignController::class, 'index'])->name('campaigns.index');
        // Route for creating a new campaign
        Route::get('/create', [CampaignController::class, 'create'])->name('campaigns.create');
        // Route for storing a new campaign
        Route::post('/', [CampaignController::class, 'store'])->name('campaigns.store');
        // Route for AJAX data table
        Route::post('/ajax-datatable', [CampaignController::class, 'ajaxIndex'])->name('campaigns.ajax');
        // Route for deleting a campaign
        Route::get('/delete/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
        // Route for editing a campaign
        Route::get('/edit/{id}', [CampaignController::class, 'edit'])->name('campaigns.edit');
        // Route for updating a campaign
        Route::post('/edit', [CampaignController::class, 'editStore'])->name('campaigns.update');
        // Route for updating campaign status
        Route::post('/status', [CampaignController::class, 'updateStatus'])->name('campaigns.updateStatus');
        // Route for deleting multiple campaigns at once
        Route::get('/all-delete', [CampaignController::class, 'destroyAll'])->name('campaigns.all.delete');
        Route::post('/is-featured', [CampaignController::class, 'updateIsFeatured'])->name('campaigns-is-featured');
        // search products
        Route::get('/get-products', [CampaignController::class, 'getProducts'])->name('campaigns.get-products');
        Route::get('/products/{id}', [CampaignController::class, 'campaignProducts'])->name('campaigns.products');
        Route::post('/products-ajax', [CampaignController::class, 'campaignProductsAjax'])->name('campaigns-product.ajax');
        Route::post('/products-create', [CampaignController::class, 'campaignCreate'])->name('campaigns.all.create');
        Route::get('/products-view/{id}', [CampaignController::class, 'campaignProductView'])->name('campaigns.products.view');
        Route::post('/products-view/ajax', [CampaignController::class, 'campaignProductViewAjax'])->name('campaigns-product-view.ajax');
        Route::get('/add/new-product/{id}', [CampaignController::class, 'campaignnewProductAdd'])->name('campaigns.new.product-add');
        Route::post('/products-view-edit/ajax', [CampaignController::class, 'campaignProductViewAjaxEdit'])->name('campaigns-product-edit.ajax');
        Route::get('/products-single-delete/{id}', [CampaignController::class, 'singleDesteroyProduct'])->name('campaigns-product-single.delete');
    });
    //order status setting
    Route::group(['prefix' => 'order-statuses'], function () {
        // Route for listing order statuses
        Route::get('/', [OrderStatusController::class, 'index'])->name('orderstatuses.index');
        // Route for displaying order status creation form
        Route::get('/create', [OrderStatusController::class, 'create'])->name('orderstatuses.create');
        // Route for storing a new order status
        Route::post('/', [OrderStatusController::class, 'store'])->name('orderstatuses.store');
        // Route for AJAX datatable functionality
        Route::post('/ajax-datatable', [OrderStatusController::class, 'ajaxIndex'])->name('orderstatuses.ajax');
        // Route for displaying order status edit form
        Route::post('/edit', [OrderStatusController::class, 'edit'])->name('orderstatuses.edit');
        // Route::post('/edit', [OrderStatusController::class, 'editStore'])->name('orderstatuses.update');
        Route::post('/update', [OrderStatusController::class, 'editStore'])->name('orderstatuses.updateStatus');
        Route::post('/status', [OrderStatusController::class, 'updateStatus'])->name('orderstatuses.status');
        // Route for deleting a single order status (alternative to the delete route with ID in URL)
        Route::get('/delete/{id}', [OrderStatusController::class, 'destroy'])->name('orderstatuses.destroy');
        // Route for deleting multiple order statuses at once
        Route::get('/all-delete', [OrderStatusController::class, 'destroyAll'])->name('orderstatuses.all.delete');
    });
    //payment methods routes
    Route::group(['prefix' => 'payment-method'], function () {
        // Route for listing order statuses
        Route::get('/', [paymentMethodController::class, 'index'])->name('paymentmethod.index');
        // Route for displaying order status creation form
        Route::get('/create', [paymentMethodController::class, 'create'])->name('paymentmethod.create');
        // Route for storing a new order status
        Route::post('/', [paymentMethodController::class, 'store'])->name('paymentmethod.store');
        // Route for AJAX datatable functionality
        Route::post('/ajax-datatable', [paymentMethodController::class, 'ajaxIndex'])->name('paymentmethod.ajax');
        // Route for deleting a single order status
        Route::get('/delete/{id}', [paymentMethodController::class, 'destroy'])->name('paymentmethod.destroy');
        // Route for displaying order status edit form
        Route::get('/edit/{id}', [paymentMethodController::class, 'edit'])->name('paymentmethod.edit');
        Route::post('/update', [paymentMethodController::class, 'editStore'])->name('paymentmethod.update');
        // Route for updating order status status (e.g., active or inactive)
        Route::post('/status', [paymentMethodController::class, 'updateStatus'])->name('paymentmethod.updateStatus');
        // Route for deleting a single order status (alternative to the delete route with ID in URL)
        Route::get('/delete/{id}', [paymentMethodController::class, 'destroy'])->name('paymentmethod.destroy');
        // Route for deleting multiple order statuses at once
        Route::get('/all-delete', [paymentMethodController::class, 'destroyAll'])->name('paymentmethod.all.delete');
    });
    // all coupons routes
    Route::group(['prefix' => 'coupons'], function () {
        // Route for listing coupons
        Route::get('/', [CouponController::class, 'index'])->name('coupons.index');
        // Route for creating a new coupon
        Route::get('/create', [CouponController::class, 'create'])->name('coupons.create');
        // Route for storing a new coupon
        Route::post('/', [CouponController::class, 'store'])->name('coupons.store');
        // Route for AJAX data table
        Route::post('/ajax-datatable', [CouponController::class, 'ajaxIndex'])->name('coupons.ajax');
        // Route for deleting a coupon
        Route::get('/delete/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');
        // Route for editing a coupon
        Route::get('/edit/{id}', [CouponController::class, 'edit'])->name('coupons.edit');
        // Route for updating a coupon
        Route::post('/edit', [CouponController::class, 'editStore'])->name('coupons.update');
        // Route for updating coupon status
        Route::post('/status', [CouponController::class, 'updateStatus'])->name('coupons.updateStatus');
        // Route for deleting multiple coupons at once
        Route::get('/all-delete', [CouponController::class, 'destroyAll'])->name('coupons.all.delete');

        Route::get('/product-add/{id}', [CouponController::class, 'addCouponProduct'])->name('coupons.product.add');

        Route::post('/product-add', [CouponController::class, 'CouponProductStore'])->name('coupons.product.store');

        Route::get('/product-view/{id}', [CouponController::class, 'CouponProductView'])->name('coupons.product.view');

        Route::post('/product-ajax', [CouponController::class, 'singleCouponProductsAjax'])->name('coupons.product.ajax');

        Route::get('/product-edit/{id}', [CouponController::class, 'singleCouponProductsEdit'])->name('coupons.new.product-add');

        Route::get('/product-delete/{id}', [CouponController::class, 'singleDesteroyProduct'])->name('coupons.product-destroy');

        Route::post('/change-status', [CouponController::class, 'isValidFirtsOrder'])->name('coupons.chnage-valid-status');
    });

    Route::group(['prefix' => 'sections'], function () {

        Route::get('/', [ProductSectionController::class, 'index'])->name('sections.index');
        Route::post('/ajax', [ProductSectionController::class, 'ajaxIndex'])->name('sections.ajax.index');
        // Route::get('/create', [ProductSectionController::class, 'create'])->name('sections.create');
        Route::post('/store', [ProductSectionController::class, 'store'])->name('sections.store');
        Route::post('/ajax', [ProductSectionController::class, 'ajaxIndex'])->name('sections.products.ajax');
        Route::post('/edit', [ProductSectionController::class, 'edit'])->name('sections.edit');
        Route::get('/show/{id}', [ProductSectionController::class, 'show'])->name('sections.show');
        Route::post('/store-update', [ProductSectionController::class, 'update'])->name('sections.update');
        Route::get('/delete/{id}', [ProductSectionController::class, 'destroy'])->name('sections.destroy');
        Route::get('/delete/all', [ProductSectionController::class, 'destroyAll'])->name('sections.destroy.all');
        Route::post('/update-status', [ProductSectionController::class, 'updateStatus'])->name('sections.updateStatus');
        // section product module
        Route::get('/create/{id}', [ProductSectionController::class, 'create'])->name('sections.create');
        Route::post('/product-store', [ProductSectionController::class, 'storeSectionProduct'])->name('sections.product.store');
        Route::get('/product-show/{id}', [ProductSectionController::class, 'singleSectionProducts'])->name('section.products.show');
        Route::post('single/product-ajax/{id}', [ProductSectionController::class, 'singleSectionProductsAjax'])->name('section-product-view.ajax');
        // for editing purposes
        Route::get('/add-products/{id}', [ProductSectionController::class, 'addNewProduct'])->name('sections.product.new.create');
        //single section product delete
        Route::get('/delete-single-product/{id}', [ProductSectionController::class, 'singleDestroy'])->name('sections-product-single.delete');
        Route::post('/sorting', [ProductSectionController::class, 'updateSortOrder'])->name('sections.sorting');

        Route::post('/product-sorting', [ProductSectionController::class, 'productSort'])->name('sections.product-sorting');
    });

    //jalal start

    // Route::group(['prefix' => 'customers'], function () {
    //     Route::get('/customer', [UserController::class, 'customerIndex'])->name('users.customer.index');
    //     Route::post('/customer/ajax', [UserController::class, 'customerAjaxIndex'])->name('users.customer.ajax');

    //     Route::get('/shop', [UserController::class, 'shopIndex'])->name('users.shop.index');
    //     Route::post('/shop/ajax', [UserController::class, 'shopAjaxIndex'])->name('users.shop.ajax');

    //     Route::get('/create', [UserController::class, 'create'])->name('users.create');
    //     Route::post('/store', [UserController::class, 'store'])->name('users.store');
    //     Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    //     Route::post('/update', [UserController::class, 'update'])->name('users.update');
    //     Route::get('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    //     Route::get('/all-delete', [UserController::class, 'destroyAll'])->name('users.destroy.all');
    //     Route::post('/update-status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
    //     Route::post('/make-admin', [UserController::class, 'makeAdmin'])->name('users.makeAdmin');
    // });

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/customer', [UserController::class, 'customerIndex'])->name('users.customer.index');

        Route::post('/customer/ajax', [UserController::class, 'customerAjaxIndex'])->name('users.customer.ajax');

        Route::get('/shop', [UserController::class, 'shopIndex'])->name('users.shop.index');
        Route::post('/shop-store', [UserController::class, 'storeShop'])->name('shop.store');

        Route::post('/shop/ajax', [UserController::class, 'shopAjaxIndex'])->name('users.shop.ajax');

        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');

        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::get('/{id}/shop-edit', [UserController::class, 'shopEdit'])->name('shop.edit');

        Route::post('/update', [UserController::class, 'update'])->name('users.update');

        Route::post('/shop-update', [UserController::class, 'shopUpdate'])->name('shop.update');

        Route::get('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/all-delete', [UserController::class, 'destroyAll'])->name('users.destroy.all');
        Route::post('/update-status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
        Route::post('/make-admin', [UserController::class, 'makeAdmin'])->name('users.makeAdmin');
    });

    // Category routes
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/ajax', [CategoryController::class, 'ajaxIndex'])->name('categories.ajax.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
        // Route::get('/show/{id}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/edit/{id}/', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('/all-delete', [CategoryController::class, 'destroyAll'])->name('categories.destroy.all');
        Route::post('/update-status', [CategoryController::class, 'updateStatus'])->name('categories.updateStatus');
        Route::post('/update-ismenu', [CategoryController::class, 'updateIsmenu'])->name('categories.updateIsmenu');
        Route::get('/search', [CategoryController::class, 'categorySearch'])->name('categories.search');
    });

    // Subcategory routes
    Route::group(['prefix' => 'subcategories'], function () {
        Route::get('/', [SubcategoryController::class, 'index'])->name('subcategories.index');
        Route::post('/ajax', [SubcategoryController::class, 'ajaxIndex'])->name('subcategories.ajax.index');
        Route::get('/create', [SubcategoryController::class, 'create'])->name('subcategories.create');
        Route::post('/store', [SubcategoryController::class, 'store'])->name('subcategories.store');
        Route::get('/edit/{id}', [SubcategoryController::class, 'edit'])->name('subcategories.edit');
        Route::post('/update/{id}', [SubcategoryController::class, 'update'])->name('subcategories.update');
        Route::get('/delete/{id}', [SubcategoryController::class, 'destroy'])->name('subcategories.destroy');
        Route::get('/all-delete', [SubcategoryController::class, 'destroyAll'])->name('subcategories.destroy.all');
        Route::post('/update-status', [SubcategoryController::class, 'updateStatus'])->name('subcategories.updateStatus');
        Route::post('/fetch-subcategories', [SubcategoryController::class, 'fetchSubcategories'])->name('subcategories.fetch');
    });

    // Subject routes
    Route::group(['prefix' => 'subjects'], function () {
        Route::get('/', [SubjectController::class, 'index'])->name('subjects.index');
        Route::post('/ajax', [SubjectController::class, 'ajaxIndex'])->name('subjects.ajax.index');
        Route::get('/create', [SubjectController::class, 'create'])->name('subjects.create');
        Route::post('/store', [SubjectController::class, 'store'])->name('subjects.store');
        Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('subjects.edit');
        Route::post('/update/{id}', [SubjectController::class, 'update'])->name('subjects.update');
        Route::get('/delete/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
        Route::get('/all-delete', [SubjectController::class, 'destroyAll'])->name('subjects.destroy.all');
        Route::post('/update-status', [SubjectController::class, 'updateStatus'])->name('subjects.updateStatus');
        Route::get('/search', [SubjectController::class, 'subjectSearch'])->name('subjects.search');
    });

    // Product routes
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::post('/ajax', [ProductController::class, 'ajaxIndex'])->name('products.ajax.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');

        Route::post('/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
        Route::get('/show/{id}', [ProductController::class, 'show'])->name('products.show');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('products.update');

        Route::get('/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/all-delete', [ProductController::class, 'destroyAll'])->name('products.destroy.all');
        Route::post('/update-status', [ProductController::class, 'updateStatus'])->name('products.updateStatus');
        Route::get('/stock/status', [ProductController::class, 'updateStockStatus'])->name('products.stock.status');
        Route::get('/discount/multiple', [ProductController::class, 'productDiscountMultiple'])->name("products.discount.multiple");
        // bundle
        Route::get('/bundle/create', [ProductController::class, 'bundleCreate'])->name('products.bundle.create');
        Route::post('/bundle/store', [ProductController::class, 'bundleStore'])->name('products.bundle.store');
        Route::post('bundle/update/{id}', [ProductController::class, 'bundleUpdate'])->name('products.bundle.update');
        Route::post('bundle/delete', [ProductController::class, 'deleteBundleItem'])->name('products.bundle.destroy');

        //            variation
        Route::get('/variation/create/{id}', [ProductController::class, 'variationCreate'])->name('products.variation.create');
        Route::post('/variation/store/{id}', [ProductController::class, 'variationStore'])->name('products.variation.store');
        Route::post('variation/delete', [ProductController::class, 'deleteVariationItem'])->name('products.variation.destroy');
        Route::get('/{productId}/variation/{variationId}/edit', [ProductController::class, 'variationEdit'])->name('products.variation.edit');
        Route::post('/{productId}/variation/{variationId}/update', [ProductController::class, 'variationUpdate'])->name('products.variation.update');

        Route::get('/search', [ProductController::class, 'productSearch'])->name('products.search');
        Route::get('/getProduct/details', [ProductController::class, 'getProductDetails'])->name('products.get.details');

        Route::get('/create-pages/{slug}', [ProductController::class, 'addPages'])->name('products.add-pages');
        Route::post('/upload-images', [ProductController::class, 'uploadPages'])->name('products.pages-upload');
        Route::get('pages/{id}', [ProductController::class, 'destroyPage'])->name('product_pages.destroy');

        Route::get('pages-all-delete/{id}', [ProductController::class, 'destroyAllPage'])->name('product_pages.all.destroy');

    });

    //pending
    Route::group(['prefix' => 'variants'], function () {
        Route::get('/', [VariantController::class, 'index'])->name('variants.index');
        Route::post('/ajax', [VariantController::class, 'ajaxIndex'])->name('variants.ajax.index');
        Route::get('/create', [VariantController::class, 'create'])->name('variants.create');
        Route::post('/store', [VariantController::class, 'store'])->name('variants.store');
        Route::get('/edit/{id}', [VariantController::class, 'edit'])->name('variants.edit');
        Route::post('/update', [VariantController::class, 'update'])->name('variants.update');
        Route::get('/delete/{id}', [VariantController::class, 'destroy'])->name('variants.destroy');
        Route::get('/all-delete', [VariantController::class, 'destroyAll'])->name('variants.destroy.all');
        Route::post('/update-status', [VariantController::class, 'updateStatus'])->name('variants.updateStatus');
        Route::get('/search', [VariantController::class, 'variantSearch'])->name('variants.search');
    });

    Route::group(['prefix' => 'orders'], function () {

        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/ajax', [OrderController::class, 'ajaxIndex'])->name('orders.ajax.index');
        // Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
        // Route::post('/store', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('orders.edit');
        Route::get('/show/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/delete/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('/all-delete', [OrderController::class, 'destroyAll'])->name('orders.destroy.all');

        Route::get('/update-status', [OrderController::class, 'updateOrderStatus'])->name('orders.updateStatus');
        Route::get('/payment/status', [OrderController::class, 'updatePaymentStatus'])->name('orders.payment.status');

        Route::get('/courier', [OrderController::class, 'orderCourier'])->name('orders.courier');

        Route::get('/invoice/{id}', [OrderController::class, 'orderInvoice'])->name('orders.invoice');

        Route::get('/transactions/{id}', [OrderController::class, 'transactionsShow'])->name('orders.transactions.show');
        Route::post('/transactions/destroy/{id}', [OrderController::class, 'transactionsDestroy'])->name('orders.transactions.destroy');
        Route::post('/transactions/store/{id}', [OrderController::class, 'transactionsStore'])->name('orders.transactions.store');

        Route::get('/shipping/{id}', [OrderController::class, 'orderShippingEdit'])->name('orders.shipping.edit');
        Route::post('/shipping/update/{id}', [OrderController::class, 'orderShippingUpdate'])->name('orders.shipping.update');

        Route::get('/pending', [OrderController::class, 'pendingIndex'])->name('orders.pending');
        Route::post('pending/get', [OrderController::class, 'ajaxGetPending'])->name('orders.pending.get');
        Route::get('/paid', [OrderController::class, 'paidIndex'])->name('orders.paid');
        Route::post('paid/get', [OrderController::class, 'ajaxGetPaid'])->name('orders.paid.get');
        Route::get('/unpaid', [OrderController::class, 'unPaidIndex'])->name('orders.unpaid');
        Route::post('/unpaid/get', [OrderController::class, 'ajaxGetUnpaid'])->name('orders.unpaid.get');

    });

    Route::group(['prefix' => 'purchases'], function () {

        Route::get('/', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::post('/ajax', [PurchaseController::class, 'ajaxIndex'])->name('purchases.ajax.index');
        Route::get('/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/store', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::get('/edit/{id}', [PurchaseController::class, 'edit'])->name('purchases.edit');
        Route::post('/update/{id}', [PurchaseController::class, 'update'])->name('purchases.update');
        Route::get('/show/{id}', [PurchaseController::class, 'show'])->name('purchases.show');
        Route::get('/delete/{id}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
        Route::get('/all-delete', [PurchaseController::class, 'destroyAll'])->name('purchases.destroy.all');
        Route::get('/payment/status', [PurchaseController::class, 'updatePaymentStatus'])->name('purchases.payment.status');
        Route::get('/courier', [PurchaseController::class, 'purchaseCourier'])->name('purchases.courier');
        Route::get('/invoice/{id}', [PurchaseController::class, 'purchaseInvoice'])->name('purchases.invoice');
        Route::get('/transactions/{id}', [PurchaseController::class, 'transactionsShow'])->name('purchases.transactions.show');
        Route::post('/transactions/destroy/{id}', [PurchaseController::class, 'transactionsDestroy'])->name('purchases.transactions.destroy');
        Route::post('/transactions/store/{id}', [PurchaseController::class, 'transactionsStore'])->name('purchases.transactions.store');
        Route::post('/search/product', [PurchaseController::class, 'searchProduct'])->name('purchases.search.product');
    });

    // menus module routes
    Route::group(['prefix' => 'menu'], function () {
        Route::get('/', [MenuController::class, 'index'])->name('menus.index');
        Route::post('/ajax', [MenuController::class, 'ajaxIndex'])->name('menus.ajax');
        Route::get('/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('/store', [MenuController::class, 'store'])->name('menus.store');
        Route::post('/submenu-store', [MenuController::class, 'storeSubMenu'])->name('menus.store-submenu');
        Route::post('/childmenu-store', [MenuController::class, 'storeChildMenu'])->name('menus.store-childmenu');
        Route::post('/sorting', [MenuController::class, 'menuSorting'])->name('menus.sorting');
        Route::get('/edit/{id}', [MenuController::class, 'edit'])->name('menus.edit');
        Route::get('/show/{id}', [MenuController::class, 'show'])->name('menus.show');
        Route::post('/update', [MenuController::class, 'update'])->name('menus.update');
        Route::get('/delete/{id}', [MenuController::class, 'menuDestroy'])->name('menus.destroy');
        Route::get('/delete/all', [MenuController::class, 'destroyAll'])->name('menus.destroy.all');
        Route::post('/update-status', [MenuController::class, 'updateStatus'])->name('menus.updateStatus');
        Route::get('/search-routes', [MenuController::class, 'searchRoutes'])->name('menus.search-routes');

        // Mega menu routes
        Route::group(['prefix' => 'mega-menu'], function () {
            Route::get('/{id}', [MenuController::class, 'megaMenu'])->name('menus.mega.add');
            Route::post('/store', [MenuController::class, 'megaMenuStore'])->name('menus.store-megamenu');
            Route::post('/create', [MenuController::class, 'menusMegamenuCreate'])->name('menus.megamenu.create');
            Route::get('/show/{id}', [MenuController::class, 'menusMegaView'])->name('menus.mega.view');
            Route::post('/ajax', [MenuController::class, 'megaMenuAjax'])->name('menus.megamenu.ajax');
            Route::post('/mega-ajax', [MenuController::class, 'megaMenuViewAjax'])->name('menus.mega-menu.ajax-view');
            Route::post('/sorting', [MenuController::class, 'menuMegaMenusorting'])->name('menu.mega-menu-sorting');
            Route::get('/destroy/{id}', [MenuController::class, 'megaMenuDestroy'])->name('menu.mega-menu-delete');
            Route::get('/single-delete/{id}', [MenuController::class, 'megaMenuwithMenu'])->name('menus.single-menu-mega.delete');
        });
        // Sub menu routes
        Route::group(['prefix' => 'sub-menu'], function () {
            Route::get('/{id}', [MenuController::class, 'subMenu'])->name('menus.submenu.add');
            // menu with submenu store
            Route::post('/store', [MenuController::class, 'subMenuStore'])->name('menus.submenu.create');
            Route::get('/view/{id}', [MenuController::class, 'menuSubmenuView'])->name('menus.submenu.view');
            Route::post('/show-ajax', [MenuController::class, 'menuSubmenuAjax'])->name('menus.submenu.ajax-view');
            Route::post('/sorting', [MenuController::class, 'menuSubmenusorting'])->name('menu.sub-menu-sorting');
            Route::post('/ajax', [MenuController::class, 'subMenuAjax'])->name('menus.submenu.ajax');
            Route::get('/delete/{id}', [MenuController::class, 'subMenuDestroy'])->name('menus.submenu.destroy');
            Route::get('/single-delete/{id}', [MenuController::class, 'subMenuwithMenu'])->name('menus.submenu-menu.destroy');
        });
    });

    // home sctions modules all routes
    Route::group(['prefix' => 'home-sections'], function () {
        Route::get('/', [HomeCategoryController::class, 'index'])->name('home-category.index');
        Route::group(['prefix' => 'category'], function () {
            Route::get('/add/{id}', [HomeCategoryController::class, 'homeCategorySingle'])->name('home-category.add');
            Route::get('/single/view/{id}', [HomeCategoryController::class, 'homeCategoryView'])->name('home-category.view');
            Route::post('/single/view-ajax', [HomeCategoryController::class, 'homeCategoryAjax'])->name('home-category.ajax');
            Route::post('/sorting', [HomeCategoryController::class, 'homeCategorySorting'])->name('home-category.sorting');
            Route::post('/store', [HomeCategoryController::class, 'homeCategoryStore'])->name('home-category.store');
            Route::get('/show-all/{id}', [HomeCategoryController::class, 'showAllCategory'])->name('home-category.view-all');
            Route::post('/ajax', [HomeCategoryController::class, 'addCategoryAjax'])->name('home-category.category-ajax');
            Route::get('/single-destroy/{id}', [HomeCategoryController::class, 'singleDestroy'])->name('home-category.single-destroy-category');
        });
        // authors section routes
        Route::group(['prefix' => 'authors'], function () {
            Route::get('/add/{id}', [HomeCategoryController::class, 'Authors'])->name('home-category.author-add');
            Route::post('/ajax', [HomeCategoryController::class, 'authorsAjax'])->name('home-category.author-ajax');
            Route::post('/view-ajax', [HomeCategoryController::class, 'authorsViewAjax'])->name('home-category.author-view-ajax');
            Route::post('/store', [HomeCategoryController::class, 'authorStore'])->name('home-category.author-store');
            Route::get('/view/{id}', [HomeCategoryController::class, 'homeAuthorView'])->name('home-category.author-view');
            Route::post('/sorting', [HomeCategoryController::class, 'homeAuthorSorting'])->name('home-category.author-sorting');
            Route::get('/single-delete/{id}', [HomeCategoryController::class, 'authorSingleDestroy'])->name('home-category.author-delete');
        });
        // publishers section routes
        Route::group(['prefix' => 'publisher'], function () {

            Route::get('/add/{id}', [HomeCategoryController::class, 'Publishers'])->name('home-category.publisher-add');
            Route::get('/all/{id}', [HomeCategoryController::class, 'publisherView'])->name('home-category.publisher-view');
            Route::post('/sorting', [HomeCategoryController::class, 'homePublisherSorting'])->name('home-category.publisher-sorting');
            Route::post('/view-ajax', [HomeCategoryController::class, 'publisherViewAjax'])->name('home-category.publisher-view-ajax');
            Route::post('/store', [HomeCategoryController::class, 'publisherStore'])->name('home-category.publisher-store');
            Route::post('/ajax', [HomeCategoryController::class, 'publisherAjax'])->name('home-category.publisher-ajax');
            Route::get('/single-delete/{id}', [HomeCategoryController::class, 'publisherSingleDestroy'])->name('home-category.publisher-delete');
        });
        // reviews section routes
        Route::group(['prefix' => 'reviews'], function () {
            Route::get('/add/{id}', [HomeCategoryController::class, 'review'])->name('home-category.review-add');
            Route::post('/ajax', [HomeCategoryController::class, 'reviewsAjax'])->name('home-category.review-ajax');
            Route::post('/store', [HomeCategoryController::class, 'reviewallStore'])->name('home-category.review-store');
            Route::post('/sorting', [HomeCategoryController::class, 'reviewSorting'])->name('home-category.review-sorting');
            Route::get('/view/{id}', [HomeCategoryController::class, 'allReviews'])->name('home-category.review-view');
            Route::post('/all-ajax', [HomeCategoryController::class, 'reviewAllAjax'])->name('home-category.review-all-ajax');
            Route::get('/delete/{id}', [HomeCategoryController::class, 'reviewSingleDestroy'])->name('home-category.review-delete');
            // Route::get('/', [HomeCategoryController::class, 'review'])->name('home-category.review');
        });
    });
    // review module routes
    Route::group(['prefix' => 'reviews'], function () {
        // Route for listing authors
        Route::get('/', [ReviewController::class, 'index'])->name('review.index');
        // Route for displaying author creation form
        Route::get('/create', [ReviewController::class, 'create'])->name('review.create');
        // Route for storing a new author
        Route::post('/', [ReviewController::class, 'store'])->name('review.store');
        // Route for AJAX datatable functionality
        Route::post('/ajax-datatable', [ReviewController::class, 'ajaxIndex'])->name('review.ajax');
        // Route for deleting a single author
        Route::get('/delete/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');
        // Route for displaying author edit form
        Route::get('/edit/{id}', [ReviewController::class, 'edit'])->name('review.edit');
        // Route for updating an author's details
        Route::post('/edit', [ReviewController::class, 'editStore'])->name('review.update');
        // Route for updating author status (e.g., active or inactive)
        Route::post('/status', [ReviewController::class, 'updateStatus'])->name('review.updateStatus');
        // Route for deleting a single author (alternative to the delete route with ID in URL)
        Route::get('/delete/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');
        // Route for deleting multiple authors at once
        Route::get('/all-delete', [ReviewController::class, 'destroyAll'])->name('review.all.delete');
        Route::get('/search', [ReviewController::class, 'authorSearch'])->name('review.search');
    });
    //returns modules routes
    Route::group(['prefix' => 'returns'], function () {
        // Route for listing authors
        Route::get('/', [ReturnController::class, 'index'])->name('returns.index');
        // Route for displaying author creation form
        Route::get('/create', [ReturnController::class, 'create'])->name('returns.create');
        // Route for storing a new author
        Route::post('/ajax', [ReturnController::class, 'ajaxIndex'])->name('returns-product.ajax');
        Route::post('/', [ReturnController::class, 'store'])->name('returns.store');
        Route::get('/show/{id}', [ReturnController::class, 'show'])->name('returns.show');
        Route::get('/delete/{id}', [ReturnController::class, 'destroy'])->name('returns.destroy');
        Route::get('/edit/{id}', [ReturnController::class, 'edit'])->name('returns.edit');
        // Route for updating an author's details
        Route::post('/edit-store/{id}', [ReturnController::class, 'editStore'])->name('returns.update');
        // Route for updating author status (e.g., active or inactive)
        Route::post('/status', [ReturnController::class, 'updateStatus'])->name('returns.updateStatus');
        // Route for deleting a single author (alternative to the delete route with ID in URL)
        Route::get('/delete/{id}', [ReturnController::class, 'destroy'])->name('returns.destroy');
        // Route for deleting multiple authors at once
        Route::get('/all-delete', [ReturnController::class, 'destroyAll'])->name('returns.all.delete');
        Route::get('/invoice/{id}', [ReturnController::class, 'returnsInvoice'])->name('returns.invoice');
        // Route::post('/transactions/store/{id}', [ReturnController::class, 'transactionsStore'])->name('returns.transactions.show');
        Route::get('/transactions/{id}', [ReturnController::class, 'transactionsShow'])->name('returns.transactions.show');
        Route::post('/transactions/{id}', [ReturnController::class, 'transactionsStore'])->name('returns.transactions.store');
        Route::get('/transactions-destroy/{id}', [ReturnController::class, 'transactionsDestroy'])->name('returns.transactions.destroy');
        Route::post('/transactions-refund', [ReturnController::class, 'wallettMethodStore'])->name('returns.refund');
        // Route::post('/transactions-store', [ReturnController::class, 'returnMethodStore'])->name('returns.method.store');
        Route::post('/transactions-store', [ReturnController::class, 'paymentMethodStore'])->name('returns.method.store');
        Route::get('/payment/status', [ReturnController::class, 'updatePaymentStatus'])->name('returns.payment.status');
        Route::get('/search', [ReturnController::class, 'authorSearch'])->name('returns.search');
        Route::get('/customer-search', [ReturnController::class, 'searchCustomer'])->name('returns.customer-search');
        Route::post('/store', [ReturnController::class, 'store'])->name('returns.store');
        Route::post('/remove/payment/method', [ReturnController::class, 'removePaymentMethod'])->name('returns.method.remove');
        Route::post('/remove/payment/wallet', [ReturnController::class, 'removeBTNWallet'])->name('returns.method.remove.wallet');
    });

    Route::group(['prefix' => 'settings'], function () {

        Route::get('/', [OptionController::class, 'index'])->name('settings.index');
        Route::group(['prefix' => 'basic'], function () {
            Route::get('/', [OptionController::class, 'basic'])->name('settings.basic');
        });
        Route::group(['prefix' => 'website'], function () {
            Route::get('/', [OptionController::class, 'website'])->name('settings.website');
            Route::post('/store', [OptionController::class, 'websiteStore'])->name('settings.website.store');
        });
        Route::group(['prefix' => 'social'], function () {
            Route::get('/', [OptionController::class, 'social'])->name('settings.social');
            Route::post('/store', [OptionController::class, 'socialStore'])->name('settings.social.store');
        });
        Route::group(['prefix' => 'core'], function () {
            Route::get('/', [OptionController::class, 'websiteCore'])->name('settings.core');
            Route::post('/store', [OptionController::class, 'websiteCoreStore'])->name('settings.core.store');
        });

        Route::group(['prefix' => 'order'], function () {
            Route::get('/', [OptionController::class, 'order'])->name('settings.order');
            Route::post('/store', [OptionController::class, 'orderSettingStore'])->name('settings.order.store');
        });

        Route::group(['prefix' => 'email'], function () {

            Route::get('/', [OptionController::class, 'email'])->name('settings.email');

            Route::post('/store', [OptionController::class, 'emailSettingStore'])->name('settings.email.store');
        });

        Route::get('/general', [OptionController::class, 'general'])->name('settings.general');

        Route::get('/others', [OptionController::class, 'others'])->name('settings.others');
    });

    Route::group(['prefix' => 'reports'], function () {
        // stocks reports
        Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::post('/ajax-stocks', [StockController::class, 'stockAjax'])->name('stocks.ajax');

        //sales reports
        Route::get('/', [SalesReportController::class, 'salesReport'])->name('sales.report');
        Route::get('/sales', [SalesReportController::class, 'DailySales'])->name('sales.daily');
        Route::post('/ajax-sales', [SalesReportController::class, 'dailySalesAjax'])->name('sales.daily-report-ajax');
        Route::get('/sales-monthly', [SalesReportController::class, 'salesMonthly'])->name('sales.monthly');
        Route::post('/ajax-sales-monthly', [SalesReportController::class, 'monthlySalesAjax'])->name('sales.monthly-ajax');
        Route::get('/sales-yearly', [SalesReportController::class, 'salesYearly'])->name('sales.yearly');
        Route::post('/ajax-sales-yearly', [SalesReportController::class, 'yearlySalesAjax'])->name('sales.yearly-ajax');
        // sales payments report
        Route::get('/sales-payments', [SalesReportController::class, 'salesByPayments'])->name('sales.payments');
        Route::post('/ajax-sales-payments', [SalesReportController::class, 'salesByPaymentsAjax'])->name('sales.payments-ajax');

        // courier reports
        Route::get('/courier', [SalesReportController::class, 'courierReport'])->name('reports.couriers');
        Route::post('/courier-reports-ajax', [SalesReportController::class, 'courierReportAjax'])->name('couriers.reports-ajax');

        //wallets reports
        Route::get('/wallets', [WalletsReportsController::class, 'wallets'])->name('wallets.index');
        Route::post('/wallets-ajax', [WalletsReportsController::class, 'walletsAjax'])->name('wallets.ajax');
    });

    //country city upazila
    Route::group(['prefix' => 'country'], function () {
        // Route list for country
        Route::get('/', [CountryCityController::class, 'index'])->name('country.index');
        Route::get('/city', [CountryCityController::class, 'city'])->name('country.city');
        Route::get('/city/upazila', [CountryCityController::class, 'upazila'])->name('country.city.upazila');
    });

});

Route::get('/stocks/update', [StockController::class, 'triggerStockUpdate'])->name('trigger.stocks.update');

Route::post('/places/cities', [PlaceController::class, 'getCitiesByCountry'])->name('places.cities');
Route::post('/places/upazilas', [PlaceController::class, 'getUpazilasByCity'])->name('places.upazilas');
Route::post('/places/unions', [PlaceController::class, 'getUnionsByUpazila'])->name('places.unions');
