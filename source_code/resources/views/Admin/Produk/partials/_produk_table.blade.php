@forelse($produks as $index => $produk)
<tr>
    <td>{{ $produks->firstItem() + $index }}</td>
    <td>
        {{ $produk->nama }} 
        @if($produk->nego === 'ya')
            <span class="badge badge-success">Bisa Nego</span>
        @else
            <span class="badge badge-danger">Tidak Bisa Nego</span>
        @endif
    </td>                
    <td>{{ $produk->stok }}</td>
    <td>{{ formatRupiah($produk->harga_tayang) }}</td>
    <td>{{ $produk->status }}</td>
    <td style="max-width: 200px;">
        @if ($produk->images->isNotEmpty())
            <img src="{{ asset($produk->images->first()->gambar) }}" alt="Gambar Produk" class="img-fluid" style="border-radius: initial; width: 100%; height: auto; max-width: 100%; margin-bottom: 10px;">
        @else
            <p>No Image</p>
        @endif
    </td>                
    <td>
        <a href="{{ route('produk.show', $produk->id) }}" class="btn btn-info btn-sm">Lihat</a>
        <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning btn-sm">Edit</a>
        <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center">No products found.</td>
</tr>
@endforelse
