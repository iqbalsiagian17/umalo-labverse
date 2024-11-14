@extends('layouts.admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1 class="card-title mb-0">Transaksi</h1>
                <form action="{{ route('transaksi.index') }}" method="GET" class="d-flex align-items-center">
                    <div class="input-group me-2">
                        <input type="text" name="search_name" class="form-control" placeholder="Cari Nama User" value="{{ request('search_name') }}">
                    </div>
                    <div class="input-group me-2">
                        <input type="text" name="search_invoice" class="form-control" placeholder="Cari Nomor Invoice" value="{{ request('search_invoice') }}">
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Cari</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </form>
            </div>
            
            

            <!-- Notifikasi jika hasil pencarian kosong -->
            @if(session('no_results'))
            <div class="alert alert-warning mt-3">
                {{ session('no_results') }}
            </div>
            @endif

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Invoice</th>
                                <th>User</th>
                                <th>Harga Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="Product-table-body">
                            @foreach($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $order->id }}</td>
                                <td>
                                    <a href="{{ route('order.generate_pdf', ['id' => $order->id]) }}" target="_blank">
                                        {{ $order->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    @if($order->harga_setelah_nego)
                                        <span>Rp {{ number_format($order->harga_setelah_nego, 0, ',', '.') }}</span>
                                    @else
                                        Rp {{ number_format($order->harga_total, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td>{{ $order->status }}</td>
                                <td>
                                    <a href="{{ route('transaksi.show', $order->id) }}" class="btn btn-info">Lihat</a>
                                    <form action="{{ route('transaksi.destroy', $order->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger delete-button">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                    <!-- Link Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
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
    });
</script>

@endsection
