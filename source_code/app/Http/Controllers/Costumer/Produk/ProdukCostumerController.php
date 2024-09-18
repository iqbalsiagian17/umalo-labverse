<?php

namespace App\Http\Controllers\Costumer\Produk;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Komoditas;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\SubKategori;
use Illuminate\Support\Facades\DB;

class ProdukCostumerController extends Controller
{
    public function userShow($id)
{
    $produk = Produk::with(['images', 'kategori', 'subKategori', 'komoditas', 'bigSales', 'reviews.user'])->findOrFail($id);
    $images = $produk->images;

    $bigSale = $produk->bigSales->first();
    $bigSaleItem = $produk->bigSales()->where('status', 'aktif')->first();
    $averageRating = $produk->reviews()->avg('rating');
    $totalRatings = $produk->reviews()->count();

    $produK = Produk::where('id', '!=', $id)
                    ->where(function ($query) use ($produk) {
                        $query->where('komoditas_id', $produk->komoditas_id)
                              ->orWhere('kategori_id', $produk->kategori_id);
                    })
                    ->has('images')
                    ->limit(5)
                    ->get();

    // Get the latest completed order for this product by the logged-in user
    $order = Order::where('user_id', auth()->id())
                  ->whereHas('orderItems', function ($query) use ($id) {
                      $query->where('produk_id', $id);
                  })
                  ->where('status', 'Selesai')
                  ->latest()
                  ->first();

                  $userId = auth()->id();
                  $isFavorite = DB::table('favorites')
                                  ->where('user_id', $userId)
                                  ->where('produk_id', $id)
                                  ->exists();

    return view('Customer.Produk.show', compact('produk', 'images', 'produK', 'bigSale','bigSaleItem', 'order','averageRating','totalRatings' ,'isFavorite'));
}






public function search(Request $request)
{
    $query = $request->input('query');
    $sort = $request->get('sort'); // Get the sort parameter from the request

    $subkategori = SubKategori::all();
    $kategori = Kategori::all();

    // Start the query for searching products
    $produk = Produk::where(function ($q) use ($query) {
        $q->where('nama', 'LIKE', "%{$query}%")
          ->orWhere('merk', 'LIKE', "%{$query}%");
    });

    // Removing dots for price inputs and converting them to integers
    $minPrice = preg_replace('/\D/', '', $request->input('min_price')); // Remove non-digits
    $maxPrice = preg_replace('/\D/', '', $request->input('max_price'));

    if (!empty($minPrice)) {
        $produk->where('harga_tayang', '>=', (int)$minPrice);
    }
    if (!empty($maxPrice)) {
        $produk->where('harga_tayang', '<=', (int)$maxPrice);
    }

    // Apply sorting logic
    if ($sort == 'newest') {
        $produk->orderBy('created_at', 'desc');
    } elseif ($sort == 'oldest') {
        $produk->orderBy('created_at', 'asc');
    } elseif ($sort == 'price_lowest') {
        $produk->orderBy('harga_tayang', 'asc');
    } elseif ($sort == 'price_highest') {
        $produk->orderBy('harga_tayang', 'desc');
    }

    // Execute the query and paginate the results
    $produk = $produk->paginate(9);

    // Count the number of products found
    $productCount = $produk->total();

    // Return the search results to a view
    return view('Customer.Search.index', compact('produk', 'query', 'subkategori', 'kategori', 'productCount'));
}







}
