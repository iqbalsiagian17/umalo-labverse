<?php

use App\Http\Controllers\Admin\Bigsales\BigSaleAdminHandleController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Costumer\Faq\FaqCustomerController;
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
use App\Http\Controllers\Admin\MasterData\ShippingServiceController;
use App\Http\Controllers\Admin\Order\OrderHandleAdminController;
use App\Http\Controllers\Admin\Payment\PaymentHandleAdminController;
use App\Http\Controllers\Admin\Faq\FaqController;
use App\Http\Controllers\Admin\MasterData\TParameterController;
use App\Http\Controllers\Admin\Transaksi\TransaksiController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Costumer\Cart\CartController;
use App\Http\Controllers\Costumer\Order\OrderHandleCustomerController;
use App\Http\Controllers\Costumer\Payment\PaymentHandleCustomerController;
use App\Http\Controllers\Costumer\Shop\ShopController;
use App\Http\Controllers\Costumer\Wishlist\WishlistController;
use App\Http\Controllers\Costumer\Review\ReviewCustomerController;
use App\Http\Controllers\LanguageController;


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Define each shop route with a unique name
Route::get('/shop', [App\Http\Controllers\Costumer\Shop\ShopController::class, 'shop'])->name('shop');
Route::get('/shop/{category_slug?}', [ShopController::class, 'shop'])->name('shop.category');
Route::get('/shop/{category_slug?}/{subcategory_slug?}', [ShopController::class, 'shop'])->name('shop.subcategory');
Route::get('/shop/rating/{rating}', [ShopController::class, 'filterByRating'])->name('shop.rating');

Route::get('/bigsale/{slug}', [App\Http\Controllers\HomeController::class, 'bigsale'])->name('customer.bigsale.index');

// Other routes
Route::get('/product/lab/{id}', [ProductCostumerController::class, 'userShow'])->name('Product_customer.user.show');
Route::get('/search', [ProductCostumerController::class, 'search'])->name('Product.search');
Route::get('/labverse/lab/product/{slug}', [ProductCostumerController::class, 'userShow'])->name('product.show');
Route::get('/customer/faq', [FaqCustomerController::class, 'index'])->name('customer.faq');
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
    Route::get('/personal/address/edit/{id}', [UserDetailController::class, 'editAddress'])->name('user.editAddress');
    Route::put('/personal/address/update/{id}', [UserDetailController::class, 'updateAddress'])->name('user.updateAddress');
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
    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');

    
    //order
    Route::post('/checkout', [OrderHandleCustomerController::class, 'checkout'])->name('customer.checkout');
    Route::post('/payment/{orderId}', [PaymentHandleCustomerController::class, 'submitPaymentProof'])->name('customer.payment.submit');
    Route::get('/order/{orderId}', [OrderHandleCustomerController::class, 'showOrder'])->name('customer.order.show');
    Route::put('/orders/{order}/complete', [OrderHandleCustomerController::class, 'completeOrder'])->name('customer.complete.order');
    Route::post('/orders/{order}/complaint', [OrderHandleCustomerController::class, 'submitComplaint'])->name('customer.complaint.submit');
    Route::put('/orders/{order}/cancel', [OrderHandleCustomerController::class, 'cancelOrder'])->name('customer.order.cancel');
    Route::get('/customer/orders', [OrderHandleCustomerController::class, 'index'])->name('customer.orders.index');


    //
    Route::get('/product/{productId}/review', [ReviewCustomerController::class, 'createReview'])->name('review.create');
    Route::post('/product/{productId}/review', [ReviewCustomerController::class, 'storeReview'])->name('review.store');


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

    Route::resource('slider', SliderController::class);
    Route::resource('faq', FaqController::class);
    Route::resource('users', UserController::class);
    Route::put('/users/{id}/password', [UserController::class, 'updatePassword'])->name('users.update.password');


    Route::get('/admin/bigsale/index', [BigSaleAdminHandleController::class, 'index'])->name('admin.bigsales.index');
    Route::get('/admin/bigsale/create', [BigSaleAdminHandleController::class, 'create'])->name('admin.bigsales.create');
    Route::post('/admin/bigsale/store', [BigSaleAdminHandleController::class, 'store'])->name('admin.bigsales.store');
    Route::get('/admin/bigsale/{bigSale}', [BigSaleAdminHandleController::class, 'show'])->name('admin.bigsales.show');
    Route::get('/admin/bigsale/{bigSale}/edit', [BigSaleAdminHandleController::class, 'edit'])->name('admin.bigsales.edit');
    Route::put('/admin/bigsale/update/{bigSale}', [BigSaleAdminHandleController::class, 'update'])->name('admin.bigsales.update');
    Route::delete('/admin/bigsale/{bigSale}', [BigSaleAdminHandleController::class, 'destroy'])->name('admin.bigsales.destroy');



    Route::get('admin/Product/getSubCategory/{CategoryId}', [ProductController::class, 'getSubCategory']);
    Route::post('/Product/update-status/{id}', [ProductController::class, 'updateStatus'])->name('Product.update-status');
    Route::put('/transaksi/{id}/updateEdit', [TransaksiController::class, 'updateEdit'])->name('transaksi.updateEdit');



    Route::get('/admin/orders', [OrderHandleAdminController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{id}', [OrderHandleAdminController::class, 'show'])->name('admin.orders.show');
    Route::put('/admin/orders/{order}/approve', [OrderHandleAdminController::class, 'approveOrder'])->name('admin.orders.approve');
    Route::put('/admin/orders/{order}/packing', [OrderHandleAdminController::class, 'markAsPacking'])->name('admin.mark.packing');
    Route::put('/admin/orders/{order}/shipped', [OrderHandleAdminController::class, 'markAsShipped'])->name('admin.orders.shipped');
    Route::put('admin/orders/{order}/payment', [OrderHandleAdminController::class, 'allowPayment'])->name('customer.orders.payment');
    Route::put('/admin/orders/{order}/cancel', [OrderHandleAdminController::class, 'cancelOrder'])->name('admin.orders.cancel');

    Route::put('/admin/orders/{order}/start-negotiation', [OrderHandleAdminController::class, 'startNegotiation'])->name('admin.orders.startNegotiation');
    Route::put('/admin/orders/{order}/approve-negotiation', [OrderHandleAdminController::class, 'approveNegotiation'])->name('admin.orders.approveNegotiation');
    Route::put('/admin/orders/{order}/reject-negotiation', [OrderHandleAdminController::class, 'rejectNegotiation'])->name('admin.orders.rejectNegotiation');
    Route::put('/admin/orders/{order}/finalize-negotiation', [OrderHandleAdminController::class, 'finalizeNegotiation'])->name('admin.orders.finalizeNegotiation');



    Route::get('/admin/payments', [PaymentHandleAdminController::class, 'index'])->name('admin.payments.index');
    Route::get('admin/payments/{id}', [PaymentHandleAdminController::class, 'show'])->name('admin.payments.show');
    Route::put('/admin/payments/{payment}/verify', [PaymentHandleAdminController::class, 'verifyPayment'])->name('admin.payments.verify');
    Route::post('admin/payments/{paymentId}/reject', [PaymentHandleAdminController::class, 'rejectPayment'])->name('admin.payments.reject');


    Route::prefix('admin/masterdata')->name('admin.masterdata.')->group(function () {
        Route::get('category', [CategoryController::class, 'index'])->name('category.index');
        Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('category', [CategoryController::class, 'store'])->name('category.store');
        Route::get('category/{category}', [CategoryController::class, 'show'])->name('category.show');
        Route::get('category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
        Route::put('category/{category}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
        
        Route::get('subcategory', [SubCategoryController::class, 'index'])->name('subcategory.index');
        Route::get('subcategory/create', [SubCategoryController::class, 'create'])->name('subcategory.create');
        Route::post('subcategory', [SubCategoryController::class, 'store'])->name('subcategory.store');
        Route::get('subcategory/{id}', [SubCategoryController::class, 'show'])->name('subcategory.show');
        Route::get('subcategory/{id}/edit', [SubCategoryController::class, 'edit'])->name('subcategory.edit');
        Route::put('subcategory/{id}', [SubCategoryController::class, 'update'])->name('subcategory.update');
        Route::delete('subcategory/{id}', [SubCategoryController::class, 'destroy'])->name('subcategory.destroy');
        
        // Routes untuk TParameterController
        Route::get('parameter', [TParameterController::class, 'index'])->name('parameter.index');
        Route::get('parameter/create', [TParameterController::class, 'create'])->name('parameter.create');
        Route::post('parameter', [TParameterController::class, 'store'])->name('parameter.store');
        Route::get('parameter/{id}', [TParameterController::class, 'show'])->name('parameter.show');
        Route::get('parameter/{id}/edit', [TParameterController::class, 'edit'])->name('parameter.edit');
        Route::put('parameter/{id}', [TParameterController::class, 'update'])->name('parameter.update');
        Route::delete('parameter/{id}', [TParameterController::class, 'destroy'])->name('parameter.destroy');

        Route::get('shipping-services', [ShippingServiceController::class, 'index'])->name('shippingservice.index');
        Route::get('shipping-services/create', [ShippingServiceController::class, 'create'])->name('shippingservice.create');
        Route::post('shipping-services', [ShippingServiceController::class, 'store'])->name('shippingservice.store');
        Route::get('shipping-services/{id}/edit', [ShippingServiceController::class, 'edit'])->name('shippingservice.edit');
        Route::put('shipping-services/{id}', [ShippingServiceController::class, 'update'])->name('shippingservice.update');
        Route::delete('shipping-services/{id}', [ShippingServiceController::class, 'destroy'])->name('shippingservice.destroy');
        
    });


});


//switch language
Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');



//akun sosial login
Route::get('/auth/{provider}redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

