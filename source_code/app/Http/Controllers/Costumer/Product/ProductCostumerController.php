<?php

namespace App\Http\Controllers\Costumer\Product;

use App\Http\Controllers\Controller;
use App\Models\BigSale;
use App\Models\Category;
use App\Models\Komoditas;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PPN;
use App\Models\Review;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;

class ProductCostumerController extends Controller
{
    public function userShow($slug)
    {
        $product = Product::where('slug', $slug)->with('bigSales')->firstOrFail();
        $images = $product->images;

        $activeBigSale = BigSale::where('status', true)
        ->where('start_time', '<=', now())
        ->where('end_time', '>=', now())
        ->whereHas('products', function($query) use ($product) {
            $query->where('t_product.id', $product->id); // Specify 'products.id' to avoid ambiguity
        })
        ->first();

        $averageRating = $product->reviews()->avg('rating');
        $totalRatings = $product->reviews()->count();

        $relatedProducts = Product::where('id', '!=', $product->id)
                        ->where('category_id', $product->category_id)
                        ->has('images')
                        ->limit(5)
                        ->get();

        $isFavorite = false;

        // Retrieve delivered orders that contain this product for the authenticated user
        $deliveredOrders = Order::where('user_id', auth()->id())
            ->where('status', Order::STATUS_DELIVERED)
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->get();

            $reviewExists = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->whereIn('order_id', $deliveredOrders->pluck('id'))
            ->exists();

            return view('customer.product.show', compact('product', 'images', 'averageRating', 'totalRatings', 'relatedProducts', 'isFavorite', 'deliveredOrders', 'reviewExists', 'activeBigSale'));
        }








    public function search(Request $request)
    {
        $query = $request->input('query');
        $sort = $request->get('sort'); // Get the sort parameter from the request

        $subCategory = SubCategory::all();
        $Category = Category::all();

        // Start the query for searching products
        $Product = Product::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
            ->orWhere('brand', 'LIKE', "%{$query}%");
        });

        // Removing dots for price inputs and converting them to integers
        $minPrice = preg_replace('/\D/', '', $request->input('min_price')); // Remove non-digits
        $maxPrice = preg_replace('/\D/', '', $request->input('max_price'));

        if (!empty($minPrice)) {
            $Product->where('price', '>=', (int)$minPrice);
        }
        if (!empty($maxPrice)) {
            $Product->where('price', '<=', (int)$maxPrice);
        }

        // Apply sorting logic
        if ($sort == 'newest') {
            $Product->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $Product->orderBy('created_at', 'asc');
        } elseif ($sort == 'price_lowest') {
            $Product->orderBy('price', 'asc');
        } elseif ($sort == 'price_highest') {
            $Product->orderBy('price', 'desc');
        }

        // Execute the query and paginate the results
        $Product = $Product->paginate(9);

        // Count the number of products found
        $productCount = $Product->total();

        // Return the search results to a view
        return view('customer.search.index', compact('Product', 'query', 'subCategory', 'Category', 'productCount'));
    }







}
