<?php

namespace App\Http\Controllers;

use App\Models\BigSale;
use App\Models\Category;
use App\Models\Komoditas;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Slider;
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

        $slider = Slider::all();
    
        $user = User::find(auth()->id());

        $orders = Order::where('user_id', Auth::id())->get();

        $product = Product::with('images')
        ->where('status', 'publish')
        ->orderBy('created_at', 'desc')
        ->take(8)
        ->get();
    
        return view('customer.home.home', compact( 'slider' , 'product', 'user', 'orders'));
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
        $orderCount = Order::where('status', 'selesai')->count();

        $orderNotFinishCount = Order::where('status', '!=', 'selesai')->count();

        $totalSales = Order::where('status', 'selesai')->sum('total');

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

}
