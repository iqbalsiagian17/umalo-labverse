<?php

namespace App\Http\Controllers\Admin\BigSale;

use App\Http\Controllers\Controller;
use App\Models\BigSale;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class BigsaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bigSales = BigSale::with('Product')->orderBy('created_at', 'asc')->paginate(10);
        return view('admin.bigsale.index', compact('bigSales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $categories = Category::all();

        return view('admin.bigsale.create', compact('products','categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'mulai' => 'required|date',
            'berakhir' => 'required|date|after:mulai', // Pastikan berakhir setelah mulai
            'status' => 'required|in:aktif,tidak aktif',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Image is required during creation
        ]);

            // Cek apakah ada BigSale lain yang berlangsung selama periode yang sama
            $existingBigSale = BigSale::where(function($query) use ($request) {
                $query->where('mulai', '<=', $request->berakhir)
                    ->where('berakhir', '>=', $request->mulai);
            })->where('status', 'aktif')->exists();

            if ($existingBigSale) {
                return redirect()->back()->withErrors('Sudah ada Big Sale aktif di periode waktu yang dipilih.');
            }

        $image = $request->file('image');
        $slug = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
        $imageName = time() . '_' . $slug . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/bigsale/'), $imageName);

        $bigSale = BigSale::create([
            'judul' => $request->input('judul'),
            'mulai' => $request->input('mulai'),
            'berakhir' => $request->input('berakhir'),
            'status' => $request->input('status'),
            'image' => 'uploads/bigsale/' . $imageName,
        ]);

        // Attach products if any
        if ($request->has('products')) {
            foreach ($request->products as $product_id => $value) {
                $Product = Product::findOrFail($product_id);
                $harga_diskon = $request->input("harga_diskon.{$product_id}");

                if ($harga_diskon) {
                    $bigSale->Product()->attach($product_id, ['harga_diskon' => $harga_diskon]);
                }

                // Update nego status to "tidak" if it was "ya"
                if ($Product->nego === 'ya') {
                    $Product->update(['nego' => 'tidak']);
                }
            }
        }

        return redirect()->route('bigsale.index')->with('success', 'Big Sale created successfully.');
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bigSale = BigSale::with('Product')->findOrFail($id);
        return view('admin.bigsale.show', compact('bigSale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bigSale = BigSale::findOrFail($id);
        $bigSale->mulai = \Carbon\Carbon::parse($bigSale->mulai);
        $bigSale->berakhir = \Carbon\Carbon::parse($bigSale->berakhir);
        $products = Product::all();
        $categories = Category::all();
        return view('admin.bigsale.edit', compact('bigSale', 'products','categories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul' => 'required|string',
            'mulai' => 'required|date',
            'berakhir' => 'required|date|after:mulai', // Pastikan berakhir setelah mulai
            'status' => 'required|in:aktif,tidak aktif',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bigSale = BigSale::findOrFail($id);

         // Cek apakah ada BigSale lain yang berlangsung selama periode yang sama, kecuali yang sedang diupdate
        $existingBigSale = BigSale::where(function($query) use ($request) {
            $query->where('mulai', '<=', $request->berakhir)
                ->where('berakhir', '>=', $request->mulai);
        })->where('status', 'aktif')
        ->where('id', '!=', $id) // Mengecualikan BigSale yang sedang diupdate
        ->exists();

        if ($existingBigSale) {
            return redirect()->back()->withErrors('Sudah ada Big Sale aktif di periode waktu yang dipilih.');
        }

        $data = $request->only('judul', 'mulai', 'berakhir', 'status');

        if ($request->hasFile('image')) {
            if ($bigSale->image && file_exists(public_path($bigSale->image))) {
                @unlink(public_path($bigSale->image));
            }

            $image = $request->file('image');
            $slug = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
            $newImageName = time() . '_' . $slug . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/bigsale/'), $newImageName);

            $data['image'] = 'uploads/bigsale/' . $newImageName;
        }

        $bigSale->update($data);

        // Synchronize the products with their discounts
        $products = [];
        if ($request->has('products')) {
            foreach ($request->products as $product_id => $value) {
                $harga_diskon = $request->input("harga_diskon.{$product_id}");
                if ($harga_diskon) {
                    $products[$product_id] = ['harga_diskon' => $harga_diskon];
                }
            }
        }

        // Sync the products with the Big Sale
        $bigSale->Product()->sync($products);

        return redirect()->route('bigsale.index')->with('success', 'Big Sale updated successfully.');
    }









    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bigSale = BigSale::findOrFail($id);
        $bigSale->delete();
        return redirect()->route('bigsale.index');
    }
}
