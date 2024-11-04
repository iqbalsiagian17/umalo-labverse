<?php

namespace App\Http\Controllers\Costumer\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Komoditas;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PPN;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;

class ProductCostumerController extends Controller
{
    public function userShow($id)
{
    $Product = Product::with(['images', 'Category', 'subCategory', 'komoditas', 'bigSales', 'reviews.user'])->findOrFail($id);
    $images = $Product->images;
    $ppn = PPN::firstOrFail(); // Assuming there's always at least one PPN record


    $bigSale = $Product->bigSales->first();
    $bigSaleItem = $Product->bigSales()->where('status', 'aktif')->first();
    $averageRating = $Product->reviews()->avg('rating');
    $totalRatings = $Product->reviews()->count();

    $Product = Product::where('id', '!=', $id)
                    ->where(function ($query) use ($Product) {
                        $query->where('komoditas_id', $Product->komoditas_id)
                              ->orWhere('Category_id', $Product->Category_id);
                    })
                    ->has('images')
                    ->limit(5)
                    ->get();

    // Get the latest completed order for this product by the logged-in user
    $order = Order::where('user_id', auth()->id())
                  ->whereHas('orderItems', function ($query) use ($id) {
                      $query->where('Product_id', $id);
                  })
                  ->where('status', 'Selesai')
                  ->latest()
                  ->first();

                  $userId = auth()->id();
                  $isFavorite = DB::table('favorites')
                                  ->where('user_id', $userId)
                                  ->where('Product_id', $id)
                                  ->exists();

    return view('customer.Product.show', compact('Product', 'images', 'Product', 'bigSale','bigSaleItem', 'order','averageRating','totalRatings' ,'isFavorite','ppn'));
}






public function search(Request $request)
{
    $query = $request->input('query');
    $sort = $request->get('sort'); // Get the sort parameter from the request

    $subCategory = SubCategory::all();
    $Category = Category::all();

    // Start the query for searching products
    $Product = Product::where(function ($q) use ($query) {
        $q->where('nama', 'LIKE', "%{$query}%")
          ->orWhere('merk', 'LIKE', "%{$query}%");
    });

    // Removing dots for price inputs and converting them to integers
    $minPrice = preg_replace('/\D/', '', $request->input('min_price')); // Remove non-digits
    $maxPrice = preg_replace('/\D/', '', $request->input('max_price'));

    if (!empty($minPrice)) {
        $Product->where('harga_tayang', '>=', (int)$minPrice);
    }
    if (!empty($maxPrice)) {
        $Product->where('harga_tayang', '<=', (int)$maxPrice);
    }

    // Apply sorting logic
    if ($sort == 'newest') {
        $Product->orderBy('created_at', 'desc');
    } elseif ($sort == 'oldest') {
        $Product->orderBy('created_at', 'asc');
    } elseif ($sort == 'price_lowest') {
        $Product->orderBy('harga_tayang', 'asc');
    } elseif ($sort == 'price_highest') {
        $Product->orderBy('harga_tayang', 'desc');
    }

    // Execute the query and paginate the results
    $Product = $Product->paginate(9);

    // Count the number of products found
    $productCount = $Product->total();

    // Return the search results to a view
    return view('customer.search.index', compact('Product', 'query', 'subCategory', 'Category', 'productCount'));
}







}
