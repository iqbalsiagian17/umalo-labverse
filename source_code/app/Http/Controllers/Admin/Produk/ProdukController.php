<?php

namespace App\Http\Controllers\Admin\Produk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Komoditas;
use App\Models\SubKategori;
use App\Models\Kategori;
use App\Models\ProdukImage;
use App\Models\ProdukList;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Produk::query();
    
        if ($request->filled('search')) {
            $search = $request->input('search');
    
            // Order by exact match first, then by closest match
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orderByRaw("CASE 
                                    WHEN nama LIKE ? THEN 1 
                                    WHEN nama LIKE ? THEN 2 
                                    ELSE 3 
                                END", ["$search", "$search%"]);
        }
    
        $produks = $query->orderBy('created_at', 'asc')->paginate(5);
    
        if ($request->ajax()) {
            return view('admin.produk.partials._produk_table', compact('produks'))->render();
        }
    
        return view('admin.produk.index', compact('produks'));
    }
    
    
    



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $komoditas = Komoditas::all();
        $subKategoris = SubKategori::all();
        $kategoris = Kategori::all();
        return view('admin.produk.create', compact('komoditas', 'subKategoris', 'kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'nama' => 'required',
        'tipe_barang' => 'nullable',
        'stok' => 'required|integer',
        'masa_berlaku_produk' => 'required|date',
        'merk' => 'nullable',
        'no_produk_penyedia' => 'nullable',
        'unit_pengukuran' => 'nullable',
        'jenis_produk' => 'nullable',
        'kode_kbli' => 'nullable|integer',
        'asal_negara' => 'nullable',
        'nilai_tkdn' => 'nullable|numeric',
        'no_sni' => 'nullable',
        'garansi_produk' => 'nullable',
        'uji_fungsi' => 'nullable',
        'sni' => 'nullable',
        'memiliki_svlk' => 'nullable',
        'jenis_alat' => 'nullable',
        'fungsi' => 'nullable',
        'spesifikasi_produk' => 'required',
        'nego' => 'required',
        'harga_ditampilkan' => 'required',
        'harga_tayang' => 'required|numeric',
        'komoditas_id' => 'required|exists:komoditas,id',
        'kategori_id' => 'required|exists:kategori,id',
        'sub_kategori_id' => 'required|exists:sub_kategori,id',
        'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:15000',
    ]);

    $produk = new Produk;
    $produk->fill($request->all());
    $produk->save();

    if ($request->hasFile('gambar')) {
        foreach ($request->file('gambar') as $imgProduk) {
            $slug = Str::slug(pathinfo($imgProduk->getClientOriginalName(), PATHINFO_FILENAME));
            $newImageName = time() . '_' . $slug . '.' . $imgProduk->getClientOriginalExtension();
            $imgProduk->move('uploads/produk/', $newImageName);

            $produkImage = new ProdukImage;
            $produkImage->produk_id = $produk->id;
            $produkImage->gambar = 'uploads/produk/' . $newImageName;
            $produkImage->save();
        }
    }

    $details = $request->input('detail');
    if ($details && isset($details['nama'])) {
        foreach ($details['nama'] as $key => $value) {
            $data2 = [
                'produk_id' => $produk->id,
                'nama' => $details['nama'][$key] ?? null,
                'spesifikasi' => $details['spesifikasi'][$key] ?? null,
                'merk' => $details['merk'][$key] ?? null,
                'tipe' => $details['tipe'][$key] ?? null,
                'jumlah' => $details['jumlah'][$key] ?? null,
                'satuan' => $details['satuan'][$key] ?? null,
                'harga_satuan' => $details['harga_satuan'][$key] ?? null,
            ];
            ProdukList::create($data2);
        }
    }

    return redirect()->route('produk.index')->with('success', 'Produk created successfully.');
}

    


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::with('produkList', 'komoditas', 'kategori', 'subkategori', 'images')->findOrFail($id);
        return view('admin.produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(string $id)
    {
        $produk = Produk::find($id);
        $komoditas = Komoditas::all();
        $subKategoris = SubKategori::all();
        $kategoris = Kategori::all();
        $images = ProdukImage::where('produk_id', $id)->get(); 
        return view('admin.produk.edit', compact('produk', 'komoditas', 'subKategoris', 'kategoris','images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'nama' => 'required',
            'tipe_barang' => 'nullable',
            'stok' => 'required|integer',
            'masa_berlaku_produk' => 'nullable|date',
            'merk' => 'nullable',
            'no_produk_penyedia' => 'nullable',
            'unit_pengukuran' => 'nullable',
            'jenis_produk' => 'nullable',
            'kode_kbki' => 'nullable|integer',
            'asal_negara' => 'nullable',
            'nilai_tkdn' => 'nullable|numeric',
            'no_sni' => 'nullable',
            'garansi_produk' => 'nullable',
            'uji_fungsi' => 'nullable',
            'sni' => 'nullable',
            'memiliki_svlk' => 'nullable',
            'jenis_alat' => 'nullable',
            'fungsi' => 'nullable',
            'spesifikasi_produk' => 'required',
            'harga_ditampilkan' => 'required',
            'nego' => 'required',
            'harga_tayang' => 'required|numeric',
            'harga_potongan' => 'nullable|numeric|min:0',
            'komoditas_id' => 'required|exists:komoditas,id',
            'kategori_id' => 'required|exists:kategori,id',
            'sub_kategori_id' => 'required|exists:sub_kategori,id',
            'gambar.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:15000',
        ]);
    
        // Check if the 'allow_discount' checkbox is checked
        if (!$request->has('allow_discount')) {
            // If not checked, remove 'harga_diskon' from the validated data
            unset($validatedData['harga_potongan']);
        }
    
        // Find the product by ID and update with the validated data
        $produk = Produk::findOrFail($id);
        $produk->update($validatedData);
    
        // Handle image uploads
        if ($request->deleted_images) {
            $deletedImages = explode(',', $request->deleted_images);
            foreach ($deletedImages as $imageId) {
                $image = ProdukImage::find($imageId);
                if ($image) {
                    // Hapus file gambar dari storage
                    if (file_exists(public_path($image->gambar))) {
                        unlink(public_path($image->gambar));
                    }
                    // Hapus record gambar dari database
                    $image->delete();
                }
            }
        }
    
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $imgProduk) {
                $slug = Str::slug(pathinfo($imgProduk->getClientOriginalName(), PATHINFO_FILENAME));
                $newImageName = time() . '_' . $slug . '.' . $imgProduk->getClientOriginalExtension();
                $imgProduk->move('uploads/produk/', $newImageName);
    
                $produkImage = new ProdukImage;
                $produkImage->produk_id = $produk->id;
                $produkImage->gambar = 'uploads/produk/' . $newImageName;
                $produkImage->save();
            }
        }
    
        // Handle detail updates
        if ($request->input('detail')) {
            ProdukList::where('produk_id', $produk->id)->delete();
            foreach ($request->input('detail.nama') as $key => $value) {
                $data2 = [
                    'produk_id' => $produk->id,
                    'nama' => $request->input('detail.nama')[$key],
                    'spesifikasi' => $request->input('detail.spesifikasi')[$key],
                    'merk' => $request->input('detail.merk')[$key],
                    'tipe' => $request->input('detail.tipe')[$key],
                    'jumlah' => $request->input('detail.jumlah')[$key],
                    'satuan' => $request->input('detail.satuan')[$key],
                    'harga_satuan' => $request->input('detail.harga_satuan')[$key],
                ];
                ProdukList::create($data2);
            }
        }
    
        return redirect()->route('produk.index')->with('success', 'Produk updated successfully.');
    }
    



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the product by its ID
        $produk = Produk::findOrFail($id);
    
        // Delete associated images
        $images = ProdukImage::where('produk_id', $produk->id)->get();
        foreach ($images as $image) {
            if (file_exists(public_path($image->gambar))) {
                unlink(public_path($image->gambar));
            }
            $image->delete();
        }
    
        // Delete associated details
        ProdukList::where('produk_id', $produk->id)->delete();
    
        // Delete the product
        $produk->delete();
    
        // Redirect back with a success message
        return redirect()->route('produk.index')->with('success', 'Produk deleted successfully.');
    }
    

    public function getSubKategori($kategoriId)
    {
        $subKategoris = SubKategori::where('kategori_id', $kategoriId)->get();
        return response()->json($subKategoris);
    }

    public function updateStatus(Request $request, $id)
    {
        $produk = Produk::find($id);
        $produk->status = $request->status;
        $produk->save();

        return response()->json(['success' => true]);
    }

}
