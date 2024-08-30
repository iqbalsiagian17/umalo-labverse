@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title"><h1>Produk</h1></div>
                <form id="search-form" action="{{ route('produk.index') }}" method="GET" class="d-flex w-50">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search Produk..." value="{{ request()->input('search') }}">
                    <button type="submit" class="btn btn-primary ml-2 d-none">Cari</button>
                </form>
                <a href="{{ route('produk.create') }}" class="btn btn-primary">Tambah Produk</a>
            </div>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="card-body">
                <div class="row">
                    <table class="table table-striped table-responsive table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Stok</th>
                                <th>Harga Tayang</th>
                                <th>Status</th>
                                <th style="width: 200px">Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="produk-table-body">
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
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $produks->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var query = $(this).val();
            $.ajax({
                url: "{{ route('produk.index') }}",
                type: "GET",
                data: { search: query },
                success: function(data) {
                    $('#produk-table-body').html(data);
                }
            });
        });
    });
</script>
@endsection