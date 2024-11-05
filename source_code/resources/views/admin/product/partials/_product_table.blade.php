@forelse($product as $index => $item)
<tr>
    <td>{{ $product->firstItem() + $index }}</td>
    <td>{{ $item->name }}</td>
    <td>{{ $item->stock }}</td>
    <td>{{ formatRupiah($item->price) }}</td>
    <td>{{ $item->status }}</td>
    <td style="max-width: 200px;">
        @if ($item->images->isNotEmpty())
            <img src="{{ asset($item->images->first()->images) }}" alt="Gambar Product" class="img-fluid" style="border-radius: initial; width: 100%; height: auto; max-width: 100%; margin-bottom: 10px;">
        @else
            <p>No Image</p>
        @endif
    </td>
    <td>
        <a href="{{ route('admin.product.show', ['product' => $item->id]) }}" class="btn btn-info btn-sm">Lihat</a>
        <a href="{{ route('admin.product.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
        <form action="{{ route('admin.product.destroy', $item->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger btn-sm delete-button">Hapus</button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center">No products found.</td>
</tr>
@endforelse
