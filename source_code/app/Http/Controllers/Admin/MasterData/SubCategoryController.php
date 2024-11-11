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

        // Mulai query untuk mendapatkan subCategory dengan relasi Category
        $query = SubCategory::with('Category');

        // Jika ada input pencarian, tambahkan filter where
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhereHas('Category', function($query) use ($search) {
                          $query->where('name', 'like', '%' . $search . '%');
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
            'name' => 'required|max:255|unique:t_p_sub_category,name',
            'category_id' => 'required|exists:t_p_category,id'
        ]);

        // Buat slug dari name
        $slug = Str::slug($request->name);

        // Simpan data subkategori
        SubCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('admin.masterdata.subcategory.index')->with('success', 'Sub Category berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $categories = Category::all();
        return view('admin.masterdata.subcategory.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:t_p_sub_category,name,' . $subcategory->id,
            'category_id' => 'required|exists:t_p_category,id'
        ]);

        // Buat slug dari name
        $slug = Str::slug($request->name);

        // Update data subkategori
        $subcategory->update([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('admin.masterdata.subcategory.index')->with('success', 'Sub Category berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();

        return redirect()->route('admin.masterdata.subcategory.index')->with('success', 'Sub Category berhasil dihapus.');
    }
}
