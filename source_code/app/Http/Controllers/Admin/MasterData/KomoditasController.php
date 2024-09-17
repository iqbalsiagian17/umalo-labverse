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
        return view('Admin.MasterData.Komoditas.index', compact('komoditas'));
    }

    public function create()
    {
        return view('Admin.MasterData.Komoditas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Komoditas::create($request->all());

        return redirect()->route('Admin.MasterData.Komoditas.index')->with('success', 'Komoditas berhasil dibuat.');
    }

    public function show(Komoditas $komoditas)
    {
        return view('Admin.MasterData.Komoditas.show', compact('komoditas'));
    }

    public function edit(Komoditas $komoditas)
    {

        return view('Admin.MasterData.Komoditas.edit', compact('komoditas'));
    }


    public function update(Request $request, Komoditas $komoditas)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $komoditas->update($request->all());

        return redirect()->route('Admin.MasterData.Komoditas.index')->with('success', 'Komoditas berhasil diperbarui.');
    }

    public function destroy(Komoditas $komoditas)
    {
        $komoditas->update(['flag' => 'no']);

        return redirect()->route('Admin.MasterData.Komoditas.index')->with('success', 'Komoditas berhasil dihapus.');
    }
}
