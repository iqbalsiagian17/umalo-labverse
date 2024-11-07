<?php

namespace App\Http\Controllers;

use App\Models\BigSale;
use App\Models\Category;
use App\Models\Komoditas;
use App\Models\Order;
use App\Models\Product;
use App\Models\Slider;
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

        // Menghitung jumlah kunjungan ke halaman home hari ini oleh pengguna biasa
        $visitorCountToday = Visit::whereDate('visited_at', Carbon::today())->count();

        // Statistik kunjungan berdasarkan interval waktu (misalnya per jam dalam sehari)
        $hourlyVisits = Visit::select(DB::raw('HOUR(visited_at) as hour'), DB::raw('count(*) as visits'))
            ->whereDate('visited_at', Carbon::today())
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item['hour'] => $item['visits']];
            });

        // Hitung durasi kunjungan individu per pengguna hari ini
        $visitDurations = Visit::whereDate('visited_at', Carbon::today())
            ->select(DB::raw('TIMESTAMPDIFF(SECOND, MIN(visited_at), MAX(visited_at)) as duration'))
            ->groupBy('user_id')
            ->pluck('duration');

        // Hitung rata-rata durasi kunjungan hari ini
        $averageVisitTimeToday = $visitDurations->avg();

        // Mengirim variabel ke view
        return view('admin.dashboard.dashboard', compact('customerCount', 'orderCount', 'orderNotFinishCount', 'visitorCountToday', 'hourlyVisits', 'averageVisitTimeToday', 'totalSales'));
    }

}
