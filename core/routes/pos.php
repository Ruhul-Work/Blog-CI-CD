<?php
use App\Http\Controllers\backend\PosController;
use App\Http\Controllers\backend\SubscriptionOrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('backend')->middleware(['auth', 'admin', 'HasAccess'])->group(function () {

    Route::group(['prefix' => 'pos'], function () {

        Route::get('/', [PosController::class, 'index'])->name('pos.index');
        Route::post('/cart/store', [PosController::class, 'orderStore'])->name('pos.cart.store');
        Route::post('/cart/update/{id}', [PosController::class, 'orderUpdate'])->name('pos.orders.update');

        Route::post('/search/product', [PosController::class, 'searchProduct'])->name('pos.search.product');
        Route::get('search/customer', [PosController::class, 'searchCustomer'])->name('pos.search.customer');
        Route::get('get/customer/info', [PosController::class, 'getCustomerInfo'])->name('pos.customer.inf');
        Route::post('/customer/store', [PosController::class, 'storeCustomer'])->name('pos.customers.store');
        Route::post('/payment/method/store', [PosController::class, 'paymentMethodStore'])->name('pos.method.store');
        Route::post('/remove/payment/method', [PosController::class, 'removePaymentMethod'])->name('pos.method.remove');
        Route::post('/apply-coupon', [PosController::class, 'applyCoupon'])->name('apply.coupon');

    });

// Subscription Order Routes
    Route::prefix('subscription-orders')->group(function () {
        Route::get('/', [SubscriptionOrderController::class, 'index'])->name('subscription-orders.index');
        Route::get('/create', [SubscriptionOrderController::class, 'create'])->name('subscription-orders.create');

        Route::post('/search/package', [SubscriptionOrderController::class, 'searchPackage'])->name('subscription-orders.search.package');
        Route::post('/store', [SubscriptionOrderController::class, 'store'])->name('subscription-orders.store');
        Route::post('/userstore', [SubscriptionOrderController::class, 'userstore'])->name('subscription-orders.user.store');
        Route::get('/searchUser', [SubscriptionOrderController::class, 'searchUser'])->name('subscription-orders.user.search');
        Route::get('/getUserInfo', [SubscriptionOrderController::class, 'getUserInfo'])->name('subscription-orders.user.info');
        Route::get('/payment-methods-list', [SubscriptionOrderController::class, 'methodsList'])->name('subscription-orders.payment-methods.list');
        Route::post('/subscription-orders/payment/store', [SubscriptionOrderController::class, 'storePaymentMethod'])->name('subscription-orders.payment.store');
        Route::post('/save-payment', [SubscriptionOrderController::class, 'savePaymentToSession'])->name('subscription-orders.payment.save');
        Route::post('/remove-payment', [SubscriptionOrderController::class, 'removePaymentFromSession'])->name('subscription-orders.payment.remove');
        Route::get('/index-ajax', [SubscriptionOrderController::class, 'indexAjax'])->name('subscription-orders.index.ajax');
        Route::post('/ajax-index', [SubscriptionOrderController::class, 'ajaxIndex'])->name('subscription-orders.ajax.index');
        Route::delete('/subscription-orders/{id}', [SubscriptionOrderController::class, 'destroy'])
            ->name('subscription-orders.destroy');

        Route::get('/subscription-orders/{id}', [SubscriptionOrderController::class, 'show'])->name('subscription-orders.show');
        Route::get('/subscription-orders/{id}/destroy', [SubscriptionOrderController::class, 'destroy'])->name('subscription-orders.destroy');
        Route::get('/subscription-orders/destroy-all', [SubscriptionOrderController::class, 'destroyAll'])->name('subscription-orders.destroy.all');

        Route::get('/edit/{id}', [SubscriptionOrderController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SubscriptionOrderController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [SubscriptionOrderController::class, 'destroy'])->name('destroy');
        Route::post('/delete-all', [SubscriptionOrderController::class, 'destroyAll'])->name('destroyAll');
        Route::post('/update-status', [SubscriptionOrderController::class, 'updateStatus'])->name('updateStatus');
    });

});
