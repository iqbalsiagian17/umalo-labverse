<?php

namespace App\Http\Controllers;

use App\Models\BigSale;
use App\Models\Kategori;
use App\Models\Komoditas;
use App\Models\Order;
use App\Models\Produk;
use App\Models\Slider;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    // Update the status of expired Big Sales
    $this->updateBigSaleStatus();

    // Retrieve Big Sales that are within the time frame but may be inactive
    $activeOrUpcomingBigSales = BigSale::with('produk')
        ->whereDate('mulai', '<=', now())
        ->whereDate('berakhir', '>=', now())
        ->get();

    // Collect product IDs from active or valid Big Sales
    $bigSaleProductIds = collect();
    foreach ($activeOrUpcomingBigSales as $bigSale) {
        if ($bigSale->status === 'aktif') {
            $bigSaleProductIds = $bigSaleProductIds->merge($bigSale->produk->pluck('id'));
        }
    }
    $bigSaleProductIds = $bigSaleProductIds->toArray();

    // Retrieve products that are either not in any active Big Sale or in an inactive Big Sale
    $produk = Produk::with('images')
    ->where('status', 'publish')
    ->where(function ($query) use ($bigSaleProductIds) {
        $query->whereNotIn('id', $bigSaleProductIds)
              ->orWhereHas('bigSales', function ($query) {
                  $query->where('status', '!=', 'aktif')
                        ->whereDate('mulai', '<=', now())
                        ->whereDate('berakhir', '>=', now());
              });
    })
    ->orderBy('created_at', 'desc') // Order by latest products
    ->take(9) // Limit to the top 8 products
    ->get();


    $slider = Slider::all();

    $bigSale = $activeOrUpcomingBigSales->where('status', 'aktif')->first();

    $topSellingProducts = Produk::with('images')
    ->select('produk.id', 'produk.nama', 'produk.harga_tayang', 'produk.nego', 'produk.harga_ditampilkan', DB::raw('SUM(order_items.jumlah) as total_sold'))
    ->join('order_items', 'produk.id', '=', 'order_items.produk_id')
    ->groupBy('produk.id', 'produk.nama', 'produk.harga_tayang', 'produk.nego', 'produk.harga_ditampilkan')
    ->orderBy('total_sold', 'desc')
    ->where('produk.status', 'publish')
    ->whereDoesntHave('bigSales', function ($query) {
        $query->where('status', 'aktif');
    })
    ->take(4) // Limit to the top 4 best-sellers
    ->get();

    $user = User::find(auth()->id());
    $pendingOrders = $user ? $user->orders()->where('status', 'Diterima')->whereNull('bukti_pembayaran')->get() : collect();

    return view('customer.home.home', compact('produk', 'bigSale', 'slider','topSellingProducts','pendingOrders' ));
}


    
    public function filterByCategory($id)
{
    $category = Kategori::find($id);
    $products = Produk::where('kategori_id', $id)->where('status', 'publish')->get(); // Sesuaikan dengan struktur tabel Anda
    return view('shop.index', compact('products', 'category'));
}

    


private function updateBigSaleStatus()
{
    $currentDateTime = now(); // Get the current date and time

    // Update all active Big Sales that have passed their end time
    BigSale::where('status', 'aktif')
        ->where('berakhir', '<=', $currentDateTime)
        ->update(['status' => 'tidak aktif']);
}



    

    public function dashboard()
    {
        // Menghitung jumlah pelanggan
        $customerCount = User::where('role', 'customer')->count();
    
        // Menghitung jumlah pesanan
        $orderCount = Order::where('status', 'selesai')->count();

        $orderNotFinishCount = Order::where('status', '!=', 'selesai')->count();


        $totalSales = Order::where('status', 'selesai')->sum('harga_total');
    
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
