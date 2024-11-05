<?php

use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\MasterData\CategoryController;
use App\Http\Controllers\Admin\MasterData\SubCategoryController;
use App\Http\Controllers\Admin\Product\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Slider\SliderController;
use App\Http\Controllers\Costumer\User\UserDetailController;
use App\Http\Controllers\Costumer\Product\ProductCostumerController;
use App\Http\Controllers\Admin\MasterData\MateraiController;
use App\Http\Controllers\Admin\MasterData\PPNController;
use App\Http\Controllers\Admin\MasterData\ShippingServiceController;
use App\Http\Controllers\Admin\Order\OrderHandleAdminController;
use App\Http\Controllers\Admin\Payment\PaymentHandleAdminController;
use App\Http\Controllers\Admin\QnA\QaController;
use App\Http\Controllers\Admin\Transaksi\TransaksiController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Costumer\Cart\CartController;
use App\Http\Controllers\Costumer\Order\OrderHandleCustomerController;
use App\Http\Controllers\Costumer\QnA\QnaController;
use App\Http\Controllers\Costumer\Shop\ShopController;
use App\Http\Controllers\Costumer\Wishlist\WishlistController;
use App\Http\Controllers\LanguageController;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/shop', [App\Http\Controllers\Costumer\Shop\ShopController::class, 'shop'])->name('shop');
Route::get('/shop/category/{id}', [App\Http\Controllers\Costumer\Shop\ShopController::class, 'filterByCategory'])->name('shop.category');
Route::get('/shop/subcategory/{id}', [ShopController::class, 'filterBySubcategory'])->name('shop.subcategory');
Route::get('/shop/price-range', [ShopController::class, 'filterByPriceRange'])->name('shop.priceRange');
Route::get('/product/lab/{id}', [ProductCostumerController::class, 'userShow'])->name('Product_customer.user.show');
Route::get('/search', [ProductCostumerController::class, 'search'])->name('Product.search');
Route::get('/labverse/lab/product/{slug}', [ProductCostumerController::class, 'userShow'])->name('product.show');
Route::get('/faq', [QnaController::class, 'index'])->name('faq');
Route::get('/shop/rating/{rating}', [ShopController::class, 'filterByRating'])->name('shop.rating');
Route::get('/order/{id}/generate-pdf', [OrderHandleAdminController::class, 'generatePdf'])->name('order.generate_pdf');


//Normal Users Routes List
Route::middleware(['auth', 'user-access:costumer'])->group(function () {


    //Detail Account
    Route::get('/personal', [UserDetailController::class, 'show'])->name('user.show');
    Route::get('/personal/create', [UserDetailController::class, 'create'])->name('user.create');
    Route::post('/personal', [UserDetailController::class, 'store'])->name('user.store');
    Route::get('/personal/edit', [UserDetailController::class, 'edit'])->name('user.edit');
    Route::put('/personal', [UserDetailController::class, 'update'])->name('user.update');
    Route::post('/user/upload-profile-photo', [UserDetailController::class, 'uploadProfilePhoto'])->name('user.uploadProfilePhoto');
    Route::post('/personal/password', [UserDetailController::class, 'createPassword'])->name('password.store');
    Route::post('/password/change', [UserDetailController::class, 'changePassword'])->name('password.change');
    Route::get('/personal/address/edit', [UserDetailController::class, 'editAddress'])->name('user.editAddress');
    Route::put('/personal/address/update', [UserDetailController::class, 'updateAddress'])->name('user.updateAddress');
    Route::post('/personal/address/toggle/{id}', [UserDetailController::class, 'toggleAddressStatus'])->name('user.toggleAddressStatus');
    Route::get('/personal/address/create', [UserDetailController::class, 'createAddress'])->name('user.createAddress');
    Route::post('/personal/address/store', [UserDetailController::class, 'storeAddress'])->name('user.storeAddress');

    //Favorite
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('/wishlist/remove/{productId}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::post('/wishlist/move-to-cart/{productId}', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');


    Route::get('/customer/cart', [CartController::class, 'index'])->name('cart.show');
    Route::post('/customer/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/customer/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    
    //order
    Route::post('/checkout', [OrderHandleCustomerController::class, 'checkout'])->name('customer.checkout');
    Route::post('/payment/{orderId}', [OrderHandleCustomerController::class, 'submitPaymentProof'])->name('customer.payment.submit');
    Route::get('/order/{orderId}', [OrderHandleCustomerController::class, 'showOrder'])->name('customer.order.show');
    Route::put('/orders/{order}/complete', [OrderHandleCustomerController::class, 'completeOrder'])->name('customer.complete.order');
    Route::post('/orders/{order}/complaint', [OrderHandleCustomerController::class, 'submitComplaint'])->name('customer.complaint.submit');
    Route::put('/orders/{order}/cancel', [OrderHandleCustomerController::class, 'cancelOrder'])->name('customer.order.cancel');
    Route::get('/customer/orders', [OrderHandleCustomerController::class, 'index'])->name('customer.orders.index');

});



//Admin Routes List
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    Route::get('admin/product', [ProductController::class, 'index'])->name('admin.product.index');
    Route::get('admin/product/create', [ProductController::class, 'create'])->name('admin.product.create');
    Route::post('admin/product', [ProductController::class, 'store'])->name('admin.product.store');
    Route::get('admin/product/{product}', [ProductController::class, 'show'])->name('admin.product.show');
    Route::get('admin/product/{product}/edit', [ProductController::class, 'edit'])->name('admin.product.edit');
    Route::put('admin/product/{product}', [ProductController::class, 'update'])->name('admin.product.update');
    Route::delete('admin/product/{product}', [ProductController::class, 'destroy'])->name('admin.product.destroy');
    Route::post('/admin/product/update-status/{product}', [ProductController::class, 'updateStatus'])->name('product.update-status');
    Route::get('get-subcategories/{category_id}', [ProductController::class, 'getSubcategories']);
    });

    Route::resource('slider', SliderController::class);
    Route::resource('qas', QaController::class);
    Route::resource('users', UserController::class);
    Route::put('/users/{id}/password', [UserController::class, 'updatePassword'])->name('users.update.password');


    Route::get('admin/Product/getSubCategory/{CategoryId}', [ProductController::class, 'getSubCategory']);
    Route::post('/Product/update-status/{id}', [ProductController::class, 'updateStatus'])->name('Product.update-status');
    Route::put('/transaksi/{id}/updateEdit', [TransaksiController::class, 'updateEdit'])->name('transaksi.updateEdit');



    Route::get('/admin/orders', [OrderHandleAdminController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{id}', [OrderHandleAdminController::class, 'show'])->name('admin.orders.show');
    Route::put('/admin/orders/{order}/approve', [OrderHandleAdminController::class, 'approveOrder'])->name('admin.orders.approve');
    Route::put('/admin/orders/{order}/packing', [OrderHandleAdminController::class, 'markAsPacking'])->name('admin.mark.packing');
    Route::put('/admin/orders/{order}/shipped', [OrderHandleAdminController::class, 'markAsShipped'])->name('admin.orders.shipped');
    Route::put('admin/orders/{order}/payment', [OrderHandleAdminController::class, 'allowPayment'])->name('customer.orders.payment');


    Route::get('/admin/payments', [PaymentHandleAdminController::class, 'index'])->name('admin.payments.index');
    Route::get('admin/payments/{id}', [PaymentHandleAdminController::class, 'show'])->name('admin.payments.show');
    Route::put('/admin/payments/{payment}/verify', [PaymentHandleAdminController::class, 'verifyPayment'])->name('admin.payments.verify');
    Route::post('admin/payments/{paymentId}/reject', [PaymentHandleAdminController::class, 'rejectPayment'])->name('admin.payments.reject');
    Route::put('/admin/orders/{order}/cancel', [PaymentHandleAdminController::class, 'cancelOrder'])->name('admin.orders.cancel');


    Route::prefix('admin/masterdata')->name('admin.masterdata.')->group(function () {
        Route::resource('Category', CategoryController::class);
        Route::resource('subCategory', SubCategoryController::class);
        Route::resource('ppn', PPNController::class);
        Route::resource('materai', MateraiController::class);

        Route::get('shipping-services', [ShippingServiceController::class, 'index'])->name('shippingservice.index');
        Route::get('shipping-services/create', [ShippingServiceController::class, 'create'])->name('shippingservice.create');
        Route::post('shipping-services', [ShippingServiceController::class, 'store'])->name('shippingservice.store');
        Route::get('shipping-services/{id}/edit', [ShippingServiceController::class, 'edit'])->name('shippingservice.edit');
        Route::put('shipping-services/{id}', [ShippingServiceController::class, 'update'])->name('shippingservice.update');
        Route::delete('shipping-services/{id}', [ShippingServiceController::class, 'destroy'])->name('shippingservice.destroy');
    });




//switch language
Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');



//akun sosial login
Route::get('/auth/{provider}redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

