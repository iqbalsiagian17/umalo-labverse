<?php

namespace App\Http\Controllers\Costumer\Shop;

use App\Http\Controllers\Controller;
use App\Models\BigSale;
use App\Models\Kategori;
use App\Models\Komoditas;
use App\Models\Produk;
use App\Models\SubKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{

        public function shop(Request $request)
        {
            // Ambil semua kategori dan komoditas untuk ditampilkan di sidebar
            $kategori = Kategori::take(10)->get(); // Retrieve all categories
            $subkategori = SubKategori::get();

            // Ambil parameter sort dan kategori dari request
            $sort = $request->get('sort');
            $kategoriId = $request->get('kategori_id');

            // Query dasar untuk produk yang dipublish
            $query = Produk::with('images')->where('status', 'publish');

            // Filter berdasarkan kategori jika ada
            if ($kategoriId) {
                $query->where('kategori_id', $kategoriId);
            }

            // Sorting berdasarkan parameter yang diberikan
            if ($sort == 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort == 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort == 'price_lowest') {
                $query->orderBy('harga_tayang', 'asc'); // Sort by price lowest to highest
            } elseif ($sort == 'price_highest') {
                $query->orderBy('harga_tayang', 'desc'); // Sort by price highest to lowest
            }


            $produk = $query->paginate(9);

            // Dapatkan produk setelah menerapkan filter dan sorting
            $productCount = $produk->total();

            return view('customer.shop.shop', compact('produk', 'kategori', 'subkategori', 'productCount'));
        }


    public function filterByCategory(Request $request, $id)
    {
        $kategori = Kategori::all(); // Untuk sidebar kategori
        $currentCategory = Kategori::find($id); // Kategori yang dipilih

        if (!$currentCategory) {
            return redirect()->route('shop')->with('error', 'Kategori tidak ditemukan.');
        }

        // Menghitung jumlah produk yang memiliki kategori yang sama
        $productCount = Produk::where('kategori_id', $id)->where('status', 'publish')->count();

        $subkategori = SubKategori::all(); // Untuk sidebar komoditas

        $sort = $request->get('sort');
    
        $minPrice = str_replace('.', '', $request->input('min_price'));
        $maxPrice = str_replace('.', '', $request->input('max_price'));
    
            // Convert to integers
            $minPrice = (int)$minPrice;
            $maxPrice = (int)$maxPrice;


        $query = Produk::where('kategori_id', $id)->where('status', 'publish');
        
        // Menambahkan kondisi filter harga ke query
        if (!empty($minPrice)) {
            $query->where('harga_tayang', '>=', floatval($minPrice));
        }
        if (!empty($maxPrice)) {
            $query->where('harga_tayang', '<=', floatval($maxPrice));
        }

        if ($sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort == 'price_lowest') {
            $query->orderBy('harga_tayang', 'asc'); // Sort by price lowest to highest
        } elseif ($sort == 'price_highest') {
            $query->orderBy('harga_tayang', 'desc'); // Sort by price highest to lowest
        }

        // Selalu dapatkan produk setelah sorting
        $produk = $query->paginate(9);

        // Pastikan untuk mengirimkan variabel $productCount ke view
        return view('customer.shop.kategori', compact('produk', 'kategori', 'currentCategory', 'subkategori', 'productCount'));
    }

    public function filterBySubcategory(Request $request, $id)
    {
        $kategori = Kategori::all(); // Untuk sidebar kategori
        $currentSubcategory = SubKategori::find($id); // Subkategori yang dipilih
    
        if (!$currentSubcategory) {
            return redirect()->route('shop')->with('error', 'Subkategori tidak ditemukan.');
        }
    
        // Menghitung jumlah produk yang memiliki subkategori yang sama
        $productCount = Produk::where('sub_kategori_id', $id)->where('status', 'publish')->count();
    
        $subkategori = SubKategori::all(); // Untuk sidebar komoditas
    
        $sort = $request->get('sort');

        $minPrice = str_replace('.', '', $request->input('min_price'));
        $maxPrice = str_replace('.', '', $request->input('max_price'));
    
            // Convert to integers
            $minPrice = (int)$minPrice;
            $maxPrice = (int)$maxPrice;


        $query = Produk::where('sub_kategori_id', $id)->where('status', 'publish');

        if (!empty($minPrice)) {
            $query->where('harga_tayang', '>=', floatval($minPrice));
        }
        if (!empty($maxPrice)) {
            $query->where('harga_tayang', '<=', floatval($maxPrice));
        }
    
        if ($sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort == 'price_lowest') {
            $query->orderBy('harga_tayang', 'asc'); // Sort by price lowest to highest
        } elseif ($sort == 'price_highest') {
            $query->orderBy('harga_tayang', 'desc'); // Sort by price highest to lowest
        }
    
        // Selalu dapatkan produk setelah sorting
        $produk = $query->paginate(9);
    
        // Pastikan untuk mengirimkan variabel $productCount ke view
        return view('customer.shop.subkategori', compact('produk', 'kategori', 'currentSubcategory', 'subkategori', 'productCount'));
    }
    


    public function showDiscountedCategoryProducts($categoryId)
{
    $komoditas = Komoditas::all();

    // Get the active Big Sale
    $bigSale = BigSale::with('produk')
        ->where('status', true)
        ->whereDate('mulai', '<=', now())
        ->whereDate('berakhir', '>=', now())
        ->first();

    if (!$bigSale) {
        return redirect()->back()->with('error', 'No active Big Sale found.');
    }

    // Filter products by the selected category
    $products = $bigSale->produk->filter(function($product) use ($categoryId) {
        return $product->kategori_id == $categoryId;
    });

    // Get categories related to Big Sale products
    $kategori = Kategori::whereHas('produk', function ($query) use ($bigSale) {
        $query->whereHas('bigSales', function ($query) use ($bigSale) {
            $query->where('big_sale_id', $bigSale->id);
        });
    })->get();

    return view('customer.bigsale.kategori', compact('products', 'kategori', 'bigSale','komoditas'));
}

public function filterByRating($rating)
{
    $subkategori = SubKategori::all(); // Untuk sidebar komoditas
    $kategori = Kategori::all(); // Untuk sidebar kategori

    // Fetch products with the exact average rating specified
    $produk = Produk::whereHas('reviews', function($query) use ($rating) {
        $query->select('produk_id', DB::raw('AVG(rating) as average_rating'))
              ->groupBy('produk_id')
              ->havingRaw('ROUND(AVG(rating), 1) = ?', [$rating]);
    })->with(['reviews' => function($query) {
        $query->select('produk_id', DB::raw('AVG(rating) as average_rating'))->groupBy('produk_id');
    }])->paginate(9); // Paginate the results

    // Get the total count of filtered products
    $productCount = $produk->total();

    return view('customer.shop.shop', compact('produk', 'subkategori', 'kategori', 'productCount'));
}


public function filterByPriceRange(Request $request)
{
    $minPrice = str_replace('.', '', $request->input('min_price'));
    $maxPrice = str_replace('.', '', $request->input('max_price'));

        // Convert to integers
        $minPrice = (int)$minPrice;
        $maxPrice = (int)$maxPrice;
    

    $kategori = Kategori::all();
    $subkategori = SubKategori::all();

    // Start building the query for filtering products by price range
    $query = Produk::with('images')->where('status', 'publish');

    // Apply the price filter if the user has provided min and/or max price
    if ($minPrice) {
        $query->where('harga_tayang', '>=', $minPrice);
    }
    if ($maxPrice) {
        $query->where('harga_tayang', '<=', $maxPrice);
    }

    // Execute the query and paginate the results
    $produk = $query->paginate(9);

    // Count the number of products found
    $productCount = $produk->total();

    return view('customer.shop.shop', compact('produk', 'kategori', 'subkategori', 'productCount'));
}








}
