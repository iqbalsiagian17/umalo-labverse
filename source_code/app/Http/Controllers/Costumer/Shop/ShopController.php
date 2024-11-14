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

    public function shop(Request $request, $categorySlug = null, $subcategorySlug = null, $rating = null)
{
    // Retrieve categories and subcategories for the sidebar
    $categories = Category::take(10)->get();
    $subcategories = SubCategory::get();

    // Retrieve sorting, price range, rating filter, and search query parameters
    $sort = $request->get('sort');
    $minPrice = str_replace('.', '', $request->get('min_price'));
    $maxPrice = str_replace('.', '', $request->get('max_price'));
    $queryParam = $request->get('query'); // Retrieve the search query

    // Initialize message variables
    $pageMessage = '';
    $categoryName = null;
    $subcategoryName = null;

    // Find an active Big Sale
    $activeBigSale = BigSale::where('status', true)
        ->where('start_time', '<=', now())
        ->where('end_time', '>=', now())
        ->first();

    // Base query for published products
    $query = Product::with('images')->where('status', 'publish');

    // Exclude products in the active Big Sale
    if ($activeBigSale) {
        $query->whereDoesntHave('bigSales', function ($q) use ($activeBigSale) {
            $q->where('t_bigsales.id', $activeBigSale->id); // Use 'big_sales.id' to avoid ambiguity
        });
    }
    

    // Filter by category if category slug is provided
    if ($categorySlug) {
        $category = Category::where('slug', $categorySlug)->first();
        if ($category) {
            $query->where('category_id', $category->id);
            $categoryName = $category->name;
        }
    }

    // Filter by subcategory if subcategory slug is provided
    if ($subcategorySlug) {
        $subcategory = SubCategory::where('slug', $subcategorySlug)->first();
        if ($subcategory) {
            $query->where('subcategory_id', $subcategory->id);
            $subcategoryName = $subcategory->name;
        }
    }

    // Apply search filtering if query is provided
    if ($queryParam) {
        $query->where(function ($q) use ($queryParam) {
            $q->where('name', 'like', "%{$queryParam}%")
              ->orWhere('product_specifications', 'like', "%{$queryParam}%");
        });
    }

    // Determine the message based on filters applied
    if ($queryParam) {
        $pageMessage = "Kamu sedang berada di halaman shop dengan keyword \"$queryParam\"";
    } elseif ($categoryName && $subcategoryName) {
        $pageMessage = "Kamu sedang berada di halaman shop dengan kategori \"$categoryName\" dan subkategori \"$subcategoryName\"";
    } elseif ($categoryName) {
        $pageMessage = "Kamu sedang berada di halaman shop dengan kategori \"$categoryName\"";
    } elseif ($minPrice || $maxPrice || $rating) {
        $priceMessage = '';

        if ($minPrice && $maxPrice) {
            $priceMessage = "harga dari Rp" . number_format($minPrice, 0, ',', '.') . " sampai Rp" . number_format($maxPrice, 0, ',', '.');
        } elseif ($minPrice) {
            $priceMessage = "harga mulai dari Rp" . number_format($minPrice, 0, ',', '.');
        } elseif ($maxPrice) {
            $priceMessage = "harga hingga Rp" . number_format($maxPrice, 0, ',', '.');
        }

        $ratingMessage = $rating ? "dan rating $rating" : '';
        $pageMessage = "Kamu sedang berada di halaman shop dengan filter $priceMessage $ratingMessage";
    }

    // Apply price filtering
    if ($minPrice) {
        $query->where('price', '>=', (int)$minPrice);
    }
    if ($maxPrice) {
        $query->where('price', '<=', (int)$maxPrice);
    }

    // Apply rating filter
    if ($rating) {
        $query->whereHas('reviews', function ($q) use ($rating) {
            $q->select(DB::raw('AVG(rating) as avg_rating'))
              ->groupBy('product_id')
              ->having('avg_rating', '=', $rating);
        });
    }

    // Apply sorting based on the selected option
    if ($sort == 'newest') {
        $query->orderBy('created_at', 'desc');
    } elseif ($sort == 'oldest') {
        $query->orderBy('created_at', 'asc');
    } elseif ($sort == 'price_lowest') {
        $query->orderBy('price', 'asc');
    } elseif ($sort == 'price_highest') {
        $query->orderBy('price', 'desc');
    }

    // Paginate products
    $products = $query->paginate(9);

    // Total count of products after filtering and sorting
    $productCount = $products->total();

    // Return view with all parameters for correct display
    return view('customer.shop.shop', compact('products', 'categories', 'subcategories', 'productCount', 'categorySlug', 'subcategorySlug', 'rating', 'queryParam', 'pageMessage','activeBigSale'));
}




        


    

}
