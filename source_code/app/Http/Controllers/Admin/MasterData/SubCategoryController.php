<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Support\Str;


class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dari request
        $search = $request->input('search');
    
        // Query untuk mendapatkan subCategory dengan Category, filter berdasarkan flag dan pencarian
        $query = SubCategory::with('Category')->where('flag', 'yes');
    
        // Jika ada input pencarian, tambahkan filter where
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                      ->orWhereHas('Category', function($query) use ($search) {
                          $query->where('nama', 'like', '%' . $search . '%');
                      });
            });
        }
    
        // Paginate hasil pencarian
        $subcategories = $query->paginate(10);
    
        return view('admin.masterdata.subcategory.index', compact('subcategories', 'search'));
    }
    

    public function create()
    {
        $categories = Category::all();
        return view('admin.masterdata.subcategory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:t_p_subcategory,name',
            'category_id' => 'required|exists:t_p_category,id' // Validasi harus ada di tabel kategori
        ]);

        // Buat slug dari name
        $slug = Str::slug($request->name);

        // Simpan data subkategori
        Subcategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id // Simpan relasi ke kategori
        ]);

        return redirect()->route('admin.masterdata.subcategory.index')->with('success', 'Sub Category berhasil ditambahkan.');
    }

    public function edit(SubCategory $subcategories)
    {
        $category = Category::where('flag', 'yes')->get(); // Hanya Category aktif
        return view('admin.masterdata.subcategory.edit', compact('subcategory', 'Category'));
    }

    public function update(Request $request, SubCategory $subcategories)
    {
        $request->validate([
            'name' => 'required|max:255|unique:t_p_subcategory,name,' . $subcategories->id,
            'category_id' => 'required|exists:t_p_category,id' // Validasi harus ada di tabel kategori
        ]);

        // Buat slug dari name
        $slug = Str::slug($request->name);

        // Update data subkategori
        $subcategories->update([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id // Update relasi ke kategori
        ]);

        return redirect()->route('admin.masterdata.subcategory.index')->with('success', 'Sub Category berhasil diperbarui.');
    }

    public function destroy(SubCategory $subcategories)
    {
        $subcategories->delete();

        return redirect()->route('admin.masterdata.subcategory.index')->with('success', 'Sub Category berhasil dihapus.');
    }
}
