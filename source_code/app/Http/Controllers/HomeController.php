<?php

namespace App\Http\Controllers;

use App\Models\BigSale;
use App\Models\Category;
use App\Models\Komoditas;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\TParameter;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('dashboard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // Ambil data slider, user, dan order terkait user yang sedang login
        $slider = Slider::all();
        $user = User::find(auth()->id());
        $orders = Order::where('user_id', Auth::id())->get();

        // Ambil Big Sale aktif
        $bigSales = BigSale::where('status', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->with('products.images') // Memuat produk dan gambar yang terkait dengan Big Sale
            ->first();

        // Ambil ID dari produk yang termasuk dalam Big Sale aktif (jika ada)
        $bigSaleProductIds = $bigSales ? $bigSales->products->pluck('id')->toArray() : [];

        // Ambil produk yang tidak termasuk dalam Big Sale aktif
        $product = Product::with('images')
            ->where('status', 'publish')
            ->whereNotIn('id', $bigSaleProductIds) // Kecualikan produk yang ada dalam Big Sale
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan produk terbaru
            ->take(8) // Batas produk yang diambil sebanyak 8
            ->get();

            

        // Kirim data ke view
        return view('customer.home.home', compact('slider', 'product', 'user', 'orders', 'bigSales'));
    }





    public function filterByCategory($id)
{
    $category = Category::find($id);
    $products = Product::where('Category_id', $id)->where('status', 'publish')->get(); // Sesuaikan dengan struktur tabel Anda
    return view('shop.index', compact('products', 'category'));
}




/* private function updateBigSaleStatus()
{
    $currentDateTime = now(); // Get the current date and time

    // Update all active Big Sales that have passed their end time
    BigSale::where('status', 'aktif')
        ->where('berakhir', '<=', $currentDateTime)
        ->update(['status' => 'tidak aktif']);
} */





    public function dashboard()
    {
        // Menghitung jumlah pelanggan
        $customerCount = User::where('role', 'customer')->count();

        // Menghitung jumlah pesanan
        $orderCount = Order::where('status', 'delivered')->count();

        $orderNotFinishCount = Order::where('status', '!=', 'delivered')->count();

        $totalSales = Order::where('status', 'delivered')->sum('total');

        $payments = Payment::orderBy('created_at', 'desc')->paginate(10);

        $orders = Order::orderBy('created_at', 'desc')->paginate(10);

        $parameterExists = TParameter::exists(); // Checks if there are any records
        
        if (!$parameterExists) {
            session()->flash('warning', 'Data Parameter Kosong, Silahkan Isi Data Parameter Terlebih Dahulu Dikarenakan Berkatian Dengan Halaman Ecommerce dan Transaksi Pembayaran ');
        }
        
        $waitingApprovalOrders = Order::where('status', Order::STATUS_WAITING_APPROVAL)->get();
        $processingOrders = Order::where('status', Order::STATUS_PROCESSING)->get();
        $pendingPaymentOrders = Order::where('status', Order::STATUS_APPROVED)->get();
        $confirmPaymentOrders = Order::where('status', Order::STATUS_CONFIRMED)->get();

        
        // Mengirim variabel ke view
        return view('admin.dashboard.dashboard', compact('customerCount', 'orderCount', 'orderNotFinishCount', 'payments', 'totalSales', 'orders' ,'parameterExists','waitingApprovalOrders', 'processingOrders', 'pendingPaymentOrders', 'confirmPaymentOrders'));
    }


    public function bigsale($slug)
    {
        $bigSales = BigSale::where('slug', $slug)
            ->where('status', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->with(['products' => function ($query) {
                // Only apply the category filter if 'category' is present in the request
                if (request()->has('category') && request()->category) {
                    $query->where('category_id', request()->category);
                }
            }, 'products.images']) // Load images for products
            ->first();
    
        if (!$bigSales) {
            abort(404, 'Big Sale not found or inactive.');
        }
    
        // Retrieve categories for the sidebar
        $categories = Category::all();
    
        // Pass the Big Sale, filtered (or all) products, and categories to the view
        return view('customer.bigsale.index', compact('bigSales', 'categories'));
    }
    


}
