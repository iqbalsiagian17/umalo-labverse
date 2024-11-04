<?php

namespace App\Http\Controllers\Costumer\Shop;

use App\Http\Controllers\Controller;
use App\Models\BigSale;
use App\Models\Category;
use App\Models\Komoditas;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{

        public function shop(Request $request)
        {
            // Ambil semua Category dan komoditas untuk ditampilkan di sidebar
            $categories = Category::take(10)->get(); // Retrieve all categories
            $subcategories = SubCategory::get();

            // Ambil parameter sort dan Category dari request
            $sort = $request->get('sort');
            $CategoryId = $request->get('category_id');

            // Query dasar untuk Product yang dipublish
            $query = Product::with('images')->where('status', 'publish');

            // Filter berdasarkan Category jika ada
            if ($CategoryId) {
                $query->where('category_id', $CategoryId);
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


            $Product = $query->paginate(9);

            // Dapatkan Product setelah menerapkan filter dan sorting
            $productCount = $Product->total();

            return view('customer.shop.shop', compact('Product', 'categories', 'subcategories', 'productCount'));
        }


    public function filterByCategory(Request $request, $id)
    {
        $Category = Category::all(); // Untuk sidebar Category
        $currentCategory = Category::find($id); // Category yang dipilih

        if (!$currentCategory) {
            return redirect()->route('shop')->with('error', 'Category tidak ditemukan.');
        }

        // Menghitung jumlah Product yang memiliki Category yang sama
        $productCount = Product::where('category_id', $id)->where('status', 'publish')->count();

        $subCategory = SubCategory::all(); // Untuk sidebar komoditas

        $sort = $request->get('sort');
    
        $minPrice = str_replace('.', '', $request->input('min_price'));
        $maxPrice = str_replace('.', '', $request->input('max_price'));
    
            // Convert to integers
            $minPrice = (int)$minPrice;
            $maxPrice = (int)$maxPrice;


        $query = Product::where('category_id', $id)->where('status', 'publish');
        
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

        // Selalu dapatkan Product setelah sorting
        $Product = $query->paginate(9);

        // Pastikan untuk mengirimkan variabel $productCount ke view
        return view('customer.shop.Category', compact('Product', 'Category', 'currentCategory', 'subCategory', 'productCount'));
    }

    public function filterBySubcategory(Request $request, $id)
    {
        $Category = Category::all(); // Untuk sidebar Category
        $currentSubcategory = SubCategory::find($id); // SubCategory yang dipilih
    
        if (!$currentSubcategory) {
            return redirect()->route('shop')->with('error', 'SubCategory tidak ditemukan.');
        }
    
        // Menghitung jumlah Product yang memiliki subCategory yang sama
        $productCount = Product::where('sub_category_id', $id)->where('status', 'publish')->count();
    
        $subCategory = SubCategory::all(); // Untuk sidebar komoditas
    
        $sort = $request->get('sort');

        $minPrice = str_replace('.', '', $request->input('min_price'));
        $maxPrice = str_replace('.', '', $request->input('max_price'));
    
            // Convert to integers
            $minPrice = (int)$minPrice;
            $maxPrice = (int)$maxPrice;


        $query = Product::where('sub_category_id', $id)->where('status', 'publish');

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
    
        // Selalu dapatkan Product setelah sorting
        $Product = $query->paginate(9);
    
        // Pastikan untuk mengirimkan variabel $productCount ke view
        return view('customer.shop.subCategory', compact('Product', 'Category', 'currentSubcategory', 'subCategory', 'productCount'));
    }
    


    public function showDiscountedCategoryProducts($categoryId)
{
    $komoditas = Komoditas::all();

    // Get the active Big Sale
    $bigSale = BigSale::with('Product')
        ->where('status', true)
        ->whereDate('mulai', '<=', now())
        ->whereDate('berakhir', '>=', now())
        ->first();

    if (!$bigSale) {
        return redirect()->back()->with('error', 'No active Big Sale found.');
    }

    // Filter products by the selected category
    $products = $bigSale->Product->filter(function($product) use ($categoryId) {
        return $product->category_id == $categoryId;
    });

    // Get categories related to Big Sale products
    $Category = Category::whereHas('Product', function ($query) use ($bigSale) {
        $query->whereHas('bigSales', function ($query) use ($bigSale) {
            $query->where('big_sale_id', $bigSale->id);
        });
    })->get();

    return view('customer.bigsale.Category', compact('products', 'Category', 'bigSale','komoditas'));
}

public function filterByRating($rating)
{
    $subCategory = SubCategory::all(); // Untuk sidebar komoditas
    $Category = Category::all(); // Untuk sidebar Category

    // Fetch products with the exact average rating specified
    $Product = Product::whereHas('reviews', function($query) use ($rating) {
        $query->select('Product_id', DB::raw('AVG(rating) as average_rating'))
              ->groupBy('Product_id')
              ->havingRaw('ROUND(AVG(rating), 1) = ?', [$rating]);
    })->with(['reviews' => function($query) {
        $query->select('Product_id', DB::raw('AVG(rating) as average_rating'))->groupBy('Product_id');
    }])->paginate(9); // Paginate the results

    // Get the total count of filtered products
    $productCount = $Product->total();

    return view('customer.shop.shop', compact('Product', 'subCategory', 'Category', 'productCount'));
}


public function filterByPriceRange(Request $request)
{
    $minPrice = str_replace('.', '', $request->input('min_price'));
    $maxPrice = str_replace('.', '', $request->input('max_price'));

        // Convert to integers
        $minPrice = (int)$minPrice;
        $maxPrice = (int)$maxPrice;
    

    $Category = Category::all();
    $subCategory = SubCategory::all();

    // Start building the query for filtering products by price range
    $query = Product::with('images')->where('status', 'publish');

    // Apply the price filter if the user has provided min and/or max price
    if ($minPrice) {
        $query->where('harga_tayang', '>=', $minPrice);
    }
    if ($maxPrice) {
        $query->where('harga_tayang', '<=', $maxPrice);
    }

    // Execute the query and paginate the results
    $Product = $query->paginate(9);

    // Count the number of products found
    $productCount = $Product->total();

    return view('customer.shop.shop', compact('Product', 'Category', 'subCategory', 'productCount'));
}








}
