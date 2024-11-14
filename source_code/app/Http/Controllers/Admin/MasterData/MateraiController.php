<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Materai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MateraiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materai = Materai::all();
        return view('admin.masterdata.materai.index', compact('materai'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.masterdata.materai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Mengambil file yang diunggah
            $image = $request->file('image');
            $slug = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
            $newImageName = time() . '_' . $slug . '.' . $image->getClientOriginalExtension();

            // Pindahkan gambar ke direktori yang diinginkan
            $image->move(public_path('uploads/materai/'), $newImageName);

            // Path gambar yang akan disimpan di database
            $imagePath = 'uploads/materai/' . $newImageName;
        }

        // Membuat slider dengan data yang diberikan
        Materai::create([
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.masterdata.materai.index')->with('success', 'Materai created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Materai $materai)
    {
        return view('admin.masterdata.materai.show', compact('materai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materai $materai)
    {
        return view('admin.masterdata.materai.edit', compact('materai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materai $materai)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:20483',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($materai->image) {
                Storage::disk('public')->delete($materai->image);
            }

            // Upload new image
            $image = $request->file('image')->store('materai', 'public');
            $materai->update(['image' => $image]);
        }

        return redirect()->route('admin.masterdata.materai.index')
            ->with('success', 'Materai updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materai $materai)
    {
        if ($materai->image) {
            Storage::disk('public')->delete($materai->image);
        }

        $materai->delete();

        return redirect()->route('admin.masterdata.materai.index')
            ->with('success', 'Materai deleted successfully.');
    }
}
