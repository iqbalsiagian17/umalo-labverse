<?php

namespace App\Http\Controllers\Admin\MasterData;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dari form
        $search = $request->input('search');
    
        // Query untuk mendapatkan kategori yang di-flag 'yes' dan filter jika ada pencarian
        $query = Kategori::where('flag', 'yes');
    
        // Jika ada pencarian, tambahkan filter where untuk nama
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }
    
        // Paginate hasil pencarian atau semua kategori
        $kategoris = $query->paginate(10); // Sesuaikan jumlah item per halaman
    
        return view('admin.masterdata.kategori.index', compact('kategoris', 'search'));
    }
    

    public function create()
    {
        return view('admin.masterdata.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Kategori::create($request->all());

        return redirect()->route('admin.masterdata.kategori.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function show(Kategori $kategori)
    {
        return view('admin.masterdata.kkategori.show', compact('kategori'));
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.masterdata.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori->update($request->all());

        return redirect()->route('admin.masterdata.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->update(['flag' => 'no']);

        return redirect()->route('admin.masterdata.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
