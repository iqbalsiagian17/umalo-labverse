@extends('layouts.Admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title"><h1>Product</h1></div>
                <form id="search-form" action="{{ route('Product.index') }}" method="GET" class="d-flex w-50">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search Product..." value="{{ request()->input('search') }}">
                    <button type="submit" class="btn btn-primary ml-2 d-none">Cari</button>
                </form>
                <a href="{{ route('Product.create') }}" class="btn btn-primary">Tambah Product</a>
            </div>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">

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
                        <tbody id="Product-table-body">
                            @forelse($product as $index => $Product)
                            <tr>
                                <td>{{ $product->firstItem() + $index }}</td>
                                <td>
                                    {{ $Product->name }}
                                </td>
                                <td>{{ $Product->stock }}</td>
                                <td>{{ formatRupiah($Product->price) }}</td>
                                <td>{{ $Product->status }}</td>
                                <td style="max-width: 200px;">
                                    @if ($Product->images->isNotEmpty())
                                        <img src="{{ asset($Product->images->first()->images) }}" alt="Gambar Product" class="img-fluid" style="border-radius: initial; width: 100%; height: auto; max-width: 100%; margin-bottom: 10px;">
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
                                        <button type="button" class="btn btn-danger btn-sm delete-button">Hapus</button>
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
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $product->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
        // SweetAlert confirmation for deletion
        $('.delete-button').on('click', function(e) {
            e.preventDefault(); // Prevent form submission

            var form = $(this).closest('form'); // Get the form
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form if confirmed
                }
            });
        });

        // AJAX search functionality
        $('#search').on('keyup', function() {
            var query = $(this).val();
            $.ajax({
                url: "{{ route('Product.index') }}",
                type: "GET",
                data: { search: query },
                success: function(data) {
                    $('#Product-table-body').html(data);
                }
            });
        });
    });
</script>
@endsection
