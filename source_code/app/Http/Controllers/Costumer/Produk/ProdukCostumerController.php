<?php

namespace App\Http\Controllers\Costumer\Produk;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Komoditas;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\Order;


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
    
    return view('customer.produk.show', compact('produk', 'images', 'produK', 'bigSale','bigSaleItem', 'order','averageRating','totalRatings'));
}
    
    
    
    
    

public function search(Request $request)
{
    $query = $request->input('query');

    $komoditas = Komoditas::all();
    $kategori = Kategori::all();

    // Search products by name, specifications, or brand
    $produk = Produk::where('nama', 'LIKE', "%{$query}%")
        ->orderByRaw("CASE WHEN nama LIKE ? THEN 1 ELSE 2 END", ["%{$query}%"])
        ->orWhere('merk', 'LIKE', "%{$query}%")
        ->get();

    // Count the number of products found
    $productCount = $produk->count();

    // Return the search results to a view
    return view('customer.search.index', compact('produk', 'query', 'komoditas', 'kategori', 'productCount'));
}


    
    
}
