<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dari form
        $search = $request->input('search');

        // Query untuk mendapatkan Category yang di-flag 'yes' dan filter jika ada pencarian
        $query = Category::query();

        // Jika ada pencarian, tambahkan filter where untuk name
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Paginate hasil pencarian atau semua Category
        $categories = $query->paginate(10); // Sesuaikan jumlah item per halaman

        return view('admin.masterdata.category.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('admin.masterdata.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.masterdata.category.index')->with('success', 'Category berhasil dibuat.');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.masterdata.category.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.masterdata.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // Validasi name
        $request->validate([
            'name' => 'required|max:255|unique:t_p_category,name,' . $id, // Validasi name harus unik selain untuk kategori ini
        ]);

        // Buat slug dari name
        $slug = Str::slug($request->name);

        // Cari kategori berdasarkan ID dan update dengan slug baru
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.masterdata.category.index')->with('success', 'Category berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.masterdata.category.index')->with('success', 'Category berhasil dihapus.');
    }
}
