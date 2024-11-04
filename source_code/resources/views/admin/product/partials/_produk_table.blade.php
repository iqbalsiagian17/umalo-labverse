@forelse($product as $index => $Product)
<tr>
    <td>{{ $product->firstItem() + $index }}</td>
    <td>
        {{ $Product->nama }} 
        @if($Product->nego === 'ya')
            <span class="badge badge-success">Bisa Nego</span>
        @else
            <span class="badge badge-danger">Tidak Bisa Nego</span>
        @endif
    </td>                
    <td>{{ $Product->stok }}</td>
    <td>{{ formatRupiah($Product->harga_tayang) }}</td>
    <td>{{ $Product->status }}</td>
    <td style="max-width: 200px;">
        @if ($Product->images->isNotEmpty())
            <img src="{{ asset($Product->images->first()->gambar) }}" alt="Gambar Product" class="img-fluid" style="border-radius: initial; width: 100%; height: auto; max-width: 100%; margin-bottom: 10px;">
        @else
            <p>No Image</p>
        @endif
    </td>                
    <td>
        <a href="{{ route('Product.show', $Product->id) }}" class="btn btn-info btn-sm">Lihat</a>
        <a href="{{ route('Product.edit', $Product->id) }}" class="btn btn-warning btn-sm">Edit</a>
        <form action="{{ route('Product.destroy', $Product->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus Product ini?')">Hapus</button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center">No products found.</td>
</tr>
@endforelse
