<?php

use App\Http\Controllers\Admin\BigSale\BigsaleController;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\MasterData\KategoriController;
use App\Http\Controllers\Admin\MasterData\SubKategoriController;
use App\Http\Controllers\Admin\MasterData\KomoditasController;
use App\Http\Controllers\Admin\Produk\ProdukController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Slider\SliderController;
use App\Http\Controllers\Costumer\User\UserDetailController;
use App\Http\Controllers\Costumer\Produk\ProdukCostumerController;
use App\Http\Controllers\Admin\BigSale;
use App\Http\Controllers\Admin\MasterData\MateraiController;
use App\Http\Controllers\Admin\MasterData\PPNController;
use App\Http\Controllers\Admin\QnA\QaController;
use App\Http\Controllers\Admin\Transaksi\TransaksiController;
use App\Http\Controllers\Admin\User\UserController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Costumer\Cart\CartController;
use App\Http\Controllers\Costumer\Order\OrderController;
use App\Http\Controllers\Costumer\BigSale\BigSaleCustomerController;
use App\Http\Controllers\Costumer\QnA\QnaController;
use App\Http\Controllers\Costumer\Shop\ShopController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/shop', [App\Http\Controllers\Costumer\Shop\ShopController::class, 'shop'])->name('shop');
Route::get('/shop/category/{id}', [App\Http\Controllers\Costumer\Shop\ShopController::class, 'filterByCategory'])->name('shop.category');
Route::get('/shop/subcategory/{id}', [ShopController::class, 'filterBySubcategory'])->name('shop.subcategory');
Route::get('/shop/price-range', [ShopController::class, 'filterByPriceRange'])->name('shop.priceRange');
Route::get('produk_customer/{id}', [ProdukCostumerController::class, 'userShow'])->name('produk_customer.user.show');
Route::get('/search', [ProdukCostumerController::class, 'search'])->name('produk.search');
Route::get('/product/{id}', [ProdukCostumerController::class, 'userShow'])->name('product.show');
Route::get('/faq', [QnaController::class, 'index'])->name('faq');
Route::get('/shop/rating/{rating}', [ShopController::class, 'filterByRating'])->name('shop.rating');


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
    

    // Cart
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    //checkout
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::patch('/cart/update-quantity/{id}', [CartController::class, 'updateQuantity']);
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/totalPrice', [CartController::class, 'totalPrice'])->name('cart.totalPrice');

    //order
    Route::get('/riwayat-pesanan', [OrderController::class, 'history'])->name('order.history');
    Route::get('/order/detail/{id}', [OrderController::class, 'detail'])->name('order.detail');
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::patch('/order/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::patch('/order/{id}/update-status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
    Route::patch('/order/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel');
    Route::get('/negoisasi/{id}', [OrderController::class, 'negoisasi'])->name('negoisasi');
    Route::get('/order/{id}/transaction-history', [OrderController::class, 'transactionHistory'])->name('order.transaction_history');
    Route::get('/order/{id}/generate-pdf', [OrderController::class, 'generatePdf'])->name('order.generate_pdf');
    Route::post('/order/{id}/upload_bukti_pembayaran', [OrderController::class, 'uploadBuktiPembayaran'])->name('order.upload_bukti_pembayaran');
    Route::post('/order/{id}/review', [OrderController::class, 'submitReview'])->name('order.submitReview');
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.details');
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');





    //Bigsale
    Route::get('/bigsale/now', [BigSaleCustomerController::class, 'index'])->name('bigsale.now.index');
    Route::post('/bigsale/{id}/update-status', [BigSaleCustomerController::class, 'updateStatus'])->name('bigsale.updateStatus');
    Route::get('/category/{id}/discounted', [ShopController::class, 'showDiscountedCategoryProducts'])->name('shop.category.discounted');






    //contract
    Route::get('/order/{id}/contract', [OrderController::class, 'contract'])->name('order.contract');
});



//Admin Routes List
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::group(['middleware' => ['check.big.sale']], function () {
        // Route yang memerlukan pengecekan Big Sale
        Route::resource('produk', ProdukController::class);
    });

    Route::resource('bigsale', BigsaleController::class);
    Route::resource('slider', SliderController::class);
    Route::resource('qas', QaController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('users', UserController::class);


    Route::get('admin/produk/getSubKategori/{kategoriId}', [ProdukController::class, 'getSubKategori']);
    Route::post('/produk/update-status/{id}', [ProdukController::class, 'updateStatus'])->name('produk.update-status');
    Route::put('/transaksi/{id}/updateEdit', [TransaksiController::class, 'updateEdit'])->name('transaksi.updateEdit');

    Route::prefix('admin/masterdata')->name('admin.masterdata.')->group(function () {
        Route::resource('kategori', KategoriController::class);
        Route::resource('subkategori', SubKategoriController::class);
        Route::resource('komoditas', KomoditasController::class)->parameters(['komoditas' => 'komoditas']);
        Route::resource('ppn', PPNController::class);
        Route::resource('materai', MateraiController::class);

    });
});




//switch language
Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');



//akun sosial login
Route::get('/auth/{provider}redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

