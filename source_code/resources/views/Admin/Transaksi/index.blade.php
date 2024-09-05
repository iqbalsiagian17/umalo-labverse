@extends('layouts.admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><h1>Transaksi</h1></div>
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
                                <th>ID Transaksi</th>
                                <th>User</th>
                                <th>Harga Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="produk-table-body">
                            @foreach($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td> 
                                <td>
                                    @if($order->harga_setelah_nego)
                                        <span style="text-decoration: line-through;">Rp {{ number_format($order->harga_total, 0, ',', '.') }}</span><br>
                                        <span>Rp {{ number_format($order->harga_setelah_nego, 0, ',', '.') }}</span>
                                    @else
                                        Rp {{ number_format($order->harga_total, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td>{{ $order->status }}</td>
                                <td>
                                    <a href="{{ route('transaksi.show', $order->id) }}" class="btn btn-info">Lihat</a>
                                    <a href="{{ route('transaksi.edit', $order->id) }}" class="btn btn-warning">Edit</a>
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
