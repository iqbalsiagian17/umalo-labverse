<?php

namespace App\Http\Controllers\Costumer\Home;

use App\Http\Controllers\Controller;
use App\Models\BigSale;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Update the status of expired Big Sales
        $this->updateBigSaleStatus();
    
        $produk = Produk::with('images')
            ->where('status', 'publish')
            ->get();
    
        $slider = Slider::all();
    
        $bigSale = BigSale::with('produk')
            ->where('status', 'aktif')
            ->whereDate('mulai', '<=', now())
            ->whereDate('berakhir', '>=', now())
            ->first();
    
        $kategori = Kategori::take(10)->get(); // Retrieve all categories
    
        return view('home', compact('produk', 'bigSale', 'slider', 'kategori'));
    }

    
}
