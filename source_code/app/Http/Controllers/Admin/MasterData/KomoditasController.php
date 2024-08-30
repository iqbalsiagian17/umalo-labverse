<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Komoditas;
use Illuminate\Http\Request;

class KomoditasController extends Controller
{
    public function index()
    {
        $komoditas = Komoditas::where('flag', 'yes')->get();
        return view('admin.masterdata.komoditas.index', compact('komoditas'));
    }

    public function create()
    {
        return view('admin.masterdata.komoditas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Komoditas::create($request->all());

        return redirect()->route('admin.masterdata.komoditas.index')->with('success', 'Komoditas berhasil dibuat.');
    }

    public function show(Komoditas $komoditas)
    {
        return view('admin.masterdata.komoditas.show', compact('komoditas'));
    }

    public function edit(Komoditas $komoditas)
    {
        
        return view('admin.masterdata.komoditas.edit', compact('komoditas'));
    }
    

    public function update(Request $request, Komoditas $komoditas)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $komoditas->update($request->all());

        return redirect()->route('admin.masterdata.komoditas.index')->with('success', 'Komoditas berhasil diperbarui.');
    }

    public function destroy(Komoditas $komoditas)
    {
        $komoditas->update(['flag' => 'no']);

        return redirect()->route('admin.masterdata.komoditas.index')->with('success', 'Komoditas berhasil dihapus.');
    }
}
