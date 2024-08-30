<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubKategori;
use App\Models\Kategori;

class SubKategoriController extends Controller
{
    public function index()
    {
        $subkategoris = SubKategori::with('kategori')->where('flag', 'yes')->get(); // Filter berdasarkan flag
        return view('admin.masterdata.subkategori.index', compact('subkategoris'));
    }

    public function create()
    {
        $kategori = Kategori::where('flag', 'yes')->get(); // Hanya kategori aktif
        return view('admin.masterdata.subkategori.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        SubKategori::create($request->all());

        return redirect()->route('admin.masterdata.subkategori.index')->with('success', 'Sub Kategori berhasil ditambahkan.');
    }

    public function edit(SubKategori $subkategori)
    {
        $kategori = Kategori::where('flag', 'yes')->get(); // Hanya kategori aktif
        return view('admin.masterdata.subkategori.edit', compact('subkategori', 'kategori'));
    }

    public function update(Request $request, SubKategori $subkategori)
    {
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        $subkategori->update($request->all());

        return redirect()->route('admin.masterdata.subkategori.index')->with('success', 'Sub Kategori berhasil diperbarui.');
    }

    public function destroy(SubKategori $subkategori)
    {
        $subkategori->update(['flag' => 'no']); // Set flag menjadi 'no' bukannya dihapus

        return redirect()->route('admin.masterdata.subkategori.index')->with('success', 'Sub Kategori berhasil dihapus.');
    }
}
