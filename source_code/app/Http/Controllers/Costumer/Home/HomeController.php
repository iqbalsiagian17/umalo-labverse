<?php

namespace App\Http\Controllers\Costumer\Home;

use App\Http\Controllers\Controller;
use App\Models\BigSale;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Update the status of expired Big Sales
        $this->updateBigSaleStatus();
    
        $Product = Product::with('images')
            ->where('status', 'publish')
            ->get();
    
        $slider = Slider::all();
    
        $bigSale = BigSale::with('Product')
            ->where('status', 'aktif')
            ->whereDate('mulai', '<=', now())
            ->whereDate('berakhir', '>=', now())
            ->first();
    
        $Category = Category::take(10)->get(); // Retrieve all categories
    
        return view('home', compact('Product', 'bigSale', 'slider', 'Category'));
    }

    
}
