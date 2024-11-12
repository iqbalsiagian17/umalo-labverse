<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Komoditas;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductList;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->input('search');

            // Order by exact match first, then by closest match
            $query->where('name', 'like', '%' . $search . '%')
                ->orderByRaw("CASE
                                    WHEN name LIKE ? THEN 1
                                    WHEN name LIKE ? THEN 2
                                    ELSE 3
                                END", ["$search", "$search%"]);
        }

        $product = $query->orderBy('created_at', 'asc')->paginate(5);

        if ($request->ajax()) {
            return view('admin.product.partials._product_table', compact('product'))->render();
        }

        return view('admin.product.index', compact('product'));
    }


    public function create()
    {
        $categories = Category::all(); 
        $subcategories = Subcategory::all(); 
        return view('admin.product.create', compact('subcategories', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // Validate the request data
    $request->validate([
        'name' => 'required',
        'tipe_barang' => 'nullable',
        'stock' => 'required|integer',
        'product_expiration_date' => 'required|date',
        'brand' => 'nullable',
        'provider_product_number' => 'nullable',
        'measurement_unit' => 'nullable',
        'product_type' => 'nullable',
        'kbki_code' => 'nullable|integer',
        'tkdn_value' => 'nullable',
        'sni_number' => 'nullable|numeric',
        'no_sni' => 'nullable',
        'product_warranty' => 'nullable',
        'function_test' => 'nullable',
        'sni' => 'nullable',
        'has_svlk' => 'nullable',
        'tool_type' => 'nullable',
        'function' => 'nullable',
        'product_specifications' => 'required',
        'is_price_displayed' => 'required',
        'price' => 'required|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0|lt:price|required_if:is_discount,1', // Harga diskon hanya wajib jika is_discount true
        'e_catalog_link'=> 'nullable',
        'category_id' => 'required|exists:t_p_category,id',
        'subcategory_id' => 'required|exists:t_p_sub_category,id',
        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:15000',
    ]);

    $slug = Str::slug($request->name);

    $product = Product::create([
        'name' => $request->name,
        'slug' => $slug,
        'stock' => $request->stock,
        'product_expiration_date' => $request->product_expiration_date,
        'brand' => $request->brand,
        'provider_product_number' => $request->provider_product_number,
        'measurement_unit' => $request->measurement_unit,
        'product_type' => $request->product_type,
        'kbki_code' => $request->kbki_code,
        'tkdn_value' => $request->tkdn_value,
        'sni_number' => $request->sni_number,
        'product_warranty' => $request->product_warranty,
        'sni' => $request->sni,
        'function_test' => $request->function_test,
        'has_svlk' => $request->has_svlk,
        'tool_type' => $request->tool_type,
        'function' => $request->function,
        'product_specifications' => $request->product_specifications,
        'status' => 'archive',
        'is_price_displayed' => $request->is_price_displayed,
        'price' => $request->price,
        'discount_price' => $request->is_discount ? $request->discount_price : null,  
        'e_catalog_link' => $request->e_catalog_link,
        'category_id' => $request->category_id,
        'subcategory_id' => $request->subcategory_id,
    ]);


    $product->fill($request->all());
    $product->save();

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imgproduct) {
            $slug = Str::slug(pathinfo($imgproduct->getClientOriginalName(), PATHINFO_FILENAME));
            $newImageName = time() . '_' . $slug . '.' . $imgproduct->getClientOriginalExtension();
            $imgproduct->move('uploads/product/', $newImageName);

            $productImage = new productImage;
            $productImage->product_id = $product->id;
            $productImage->images = 'uploads/product/' . $newImageName;
            $productImage->save();
        }
    }

    $details = $request->input('detail');
    if ($details && isset($details['name'])) {
        foreach ($details['name'] as $key => $value) {
            $data2 = [
                'product_id' => $product->id,
                'name' => $details['name'][$key] ?? null,
                'specifications' => $details['specifications'][$key] ?? null,
                'brand' => $details['brand'][$key] ?? null,
                'type' => $details['type'][$key] ?? null,
                'quantity' => $details['quantity'][$key] ?? null,
                'unit' => $details['unit'][$key] ?? null,
                'unit_price' => $details['unit_price'][$key] ?? null,
            ];
            ProductList::create($data2);
        }
    }

    return redirect()->route('admin.product.index')->with('success', 'Product created successfully.');
}




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['images', 'videos', 'productList'])->find($id);
        
        if (!$product) {
            abort(404, 'Product not found');
        }
        
        return view('admin.product.show', compact('product'));
    }
    
    
    

    /**
     * Show the form for editing the specified resource.
     */

     public function edit($id)
    {
        $product = Product::findOrFail($id); // Fetch product by ID
        $categories = Category::with('subcategories')->get(); // Eager load subcategories within each category
        $subcategories = Subcategory::all(); // Fetch all subcategories independently
        $product->load('images', 'videos'); // Load related images and videos

        return view('admin.product.edit', compact('product', 'categories', 'subcategories'));
    }

     
     

     public function update(Request $request, $id)
     {
        $product = Product::findOrFail($id); // Fetch product by ID

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer',
            'product_expiration_date' => 'required|date',
            'brand' => 'nullable|string|max:255',
            'provider_product_number' => 'nullable|string|max:255',
            'measurement_unit' => 'nullable|string|max:255',
            'product_type' => 'nullable|string|max:255',
            'kbki_code' => 'nullable|integer',
            'tkdn_value' => 'nullable|numeric',
            'sni_number' => 'nullable|numeric',
            'product_warranty' => 'nullable|string|max:255',
            'function_test' => 'nullable|string|max:255',
            'sni' => 'nullable|string|max:255',
            'has_svlk' => 'nullable',
            'status' => 'required',
            'tool_type' => 'nullable|string|max:255',
            'function' => 'nullable|string|max:255',
            'product_specifications' => 'required|string',
            'is_price_displayed' => 'required|in:yes,no',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price|required_if:allow_discount,1',
            'e_catalog_link' => 'nullable',
            'negotiable' => 'required|in:yes,no',
            'category_id' => 'required|exists:t_p_category,id',
            'subcategory_id' => 'required|exists:t_p_sub_category,id',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:15000',
        ]);

        // Generate slug for the product
        $slug = Str::slug($request->name);

        // Update product attributes
        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'stock' => $request->stock,
            'product_expiration_date' => $request->product_expiration_date,
            'brand' => $request->brand,
            'provider_product_number' => $request->provider_product_number,
            'measurement_unit' => $request->measurement_unit,
            'product_type' => $request->product_type,
            'kbki_code' => $request->kbki_code,
            'tkdn_value' => $request->tkdn_value,
            'sni_number' => $request->sni_number,
            'product_warranty' => $request->product_warranty,
            'sni' => $request->sni,
            'function_test' => $request->function_test,
            'has_svlk' => $request->has_svlk,
            'tool_type' => $request->tool_type,
            'function' => $request->function,
            'status' => $request->status,
            'negotiable' => $request->negotiable,
            'product_specifications' => $request->product_specifications,
            'is_price_displayed' => $request->is_price_displayed,
            'price' => str_replace('.', '', $request->price), // Remove formatting before saving
            'discount_price' => $request->allow_discount ? str_replace('.', '', $request->discount_price) : null,
            'e_catalog_link' => $request->e_catalog_link,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
        ]);

        // Update images if new ones are uploaded
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imgproduct) {
                $slug = Str::slug(pathinfo($imgproduct->getClientOriginalName(), PATHINFO_FILENAME));
                $newImageName = time() . '_' . $slug . '.' . $imgproduct->getClientOriginalExtension();
                $imgproduct->move('uploads/product/', $newImageName);
        
                // Save new image and associate with product
                $productImage = new ProductImage;
                $productImage->product_id = $product->id;
                $productImage->images = 'uploads/product/' . $newImageName;
                $productImage->save();
            }
        }
        

        if ($request->filled('deleted_images')) {
            $deletedImageIds = explode(',', $request->input('deleted_images'));
    
            // Find and delete images from storage
            $imagesToDelete = ProductImage::whereIn('id', $deletedImageIds)->get();
            foreach ($imagesToDelete as $image) {
                if (file_exists(public_path($image->images))) {
                    unlink(public_path($image->images)); // Delete from storage
                }
                $image->delete(); // Delete from database
            }
        }

        // Update product details in ProductList
        $details = $request->input('detail');
        if ($details && isset($details['name'])) {
            // Clear existing product details
            ProductList::where('product_id', $product->id)->delete();

            // Add updated product details
            foreach ($details['name'] as $key => $value) {
                ProductList::create([
                    'product_id' => $product->id,
                    'name' => $details['name'][$key] ?? null,
                    'specifications' => $details['specifications'][$key] ?? null,
                    'brand' => $details['brand'][$key] ?? null,
                    'type' => $details['type'][$key] ?? null,
                    'quantity' => $details['quantity'][$key] ?? null,
                    'unit' => $details['unit'][$key] ?? null,
                    'unit_price' => $details['unit_price'][$key] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        // Find the product by its ID
        $Product = Product::findOrFail($id);

        // Delete associated images
        $images = ProductImage::where('product_id', $Product->id)->get();
        foreach ($images as $image) {
            if (file_exists(public_path($image->images))) {
                unlink(public_path($image->images));
            }
            $image->delete();
        }

        // Delete associated details
        ProductList::where('product_id', $Product->id)->delete();

        // Delete the product
        $Product->delete();

        // Redirect back with a success message
        return redirect()->route('admin.product.index')->with('success', 'Product deleted successfully.');
    }


    public function getSubcategories($categoryId)
    {
        // Ambil subkategori yang terhubung dengan kategori yang dipilih
        $subcategories = Subcategory::where('category_id', $categoryId)->get();

        // Kembalikan data subkategori dalam bentuk JSON
        return response()->json($subcategories);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:archive,publish',
        ]);

        try {
            $Product = Product::findOrFail($id);
            $Product->status = $request->input('status');
            $Product->save();

            return redirect()->route('admin.product.show', $Product->id)->with('success', 'Status berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('admin.product.show', $Product->id)->with('error', 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage());
        }
    }


}
