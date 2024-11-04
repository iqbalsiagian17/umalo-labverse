<?php

namespace App\Http\Controllers\Costumer\BigSale;

use App\Http\Controllers\Controller;
use App\Models\BigSale;
use App\Models\Category;
use App\Models\Komoditas;
use App\Models\Product;
use Illuminate\Http\Request;

class BigSaleCustomerController extends Controller
{
    public function index(Request $request)
{
    $bigSale = BigSale::with('Product')
        ->where('status', true)
        ->whereDate('mulai', '<=', now())
        ->whereDate('berakhir', '>=', now())
        ->first();

    // If there is an active Big Sale, get the products
    $products = $bigSale ? $bigSale->Product : collect();

    $komoditas = Komoditas::all();

    $Category = Category::whereHas('Product', function ($query) use ($bigSale) {
        $query->whereHas('bigSales', function ($query) use ($bigSale) {
            $query->where('big_sale_id', $bigSale->id);
        });
    })->get();

    $sort = $request->get('sort');
    $CategoryId = $request->get('Category_id');

    // Filter Product Big Sale berdasarkan Category jika ada
    if ($CategoryId) {
        $products = $products->where('Category_id', $CategoryId);
    }

    // Sorting Product Big Sale berdasarkan parameter yang diberikan
    if ($sort == 'newest') {
        $products = $products->sortByDesc('created_at');
    } elseif ($sort == 'oldest') {
        $products = $products->sortBy('created_at');
    }

    // Dapatkan jumlah Product setelah menerapkan filter dan sorting
    $productCount = $products->count();

    return view('customer.bigsale.index', compact('bigSale','products', 'komoditas', 'Category', 'productCount'));
}

public function updateStatus($id)
{
    try {
        $bigSale = BigSale::findOrFail($id);
        $bigSale->update(['status' => 'tidak aktif']);

        return response()->json(['message' => 'Status updated successfully']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to update status'], 500);
    }
}

public function showBigSaleCategories()
{
    $bigSale = BigSale::active()->first(); // Assuming 'active' is a scope or method that returns the active Big Sale

    if (!$bigSale) {
        return redirect()->back()->with('error', 'No active Big Sale found.');
    }

    $Category = Category::whereHas('products', function ($query) use ($bigSale) {
        $query->whereHas('bigSales', function ($query) use ($bigSale) {
            $query->where('big_sale_id', $bigSale->id);
        });
    })->get();

    $products = Product::whereHas('bigSales', function ($query) use ($bigSale) {
        $query->where('big_sale_id', $bigSale->id);
    })->get();

    return view('shop.index', compact('Category', 'products', 'bigSale'));
}



}
