@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- First Card: Product Information -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    <h2>Spesifikasi Product</h2>
                </div>
                 <!-- Start Form for Updating Status -->
                 <form id="statusForm" action="{{ route('product.update-status', $product->id) }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="form-group">
                        <label class="form-label">Status:</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="archive" class="selectgroup-input"
                                    {{ $product->status == 'archive' ? 'checked' : '' }}
                                    onchange="confirmStatusChange('archive')" />
                                <span class="selectgroup-button">Arsip</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="publish" class="selectgroup-input"
                                    {{ $product->status == 'publish' ? 'checked' : '' }}
                                    onchange="confirmStatusChange('publish')" />
                                <span class="selectgroup-button">Publish</span>
                            </label>
                        </div>
                        @if ($errors->has('status'))
                            <small class="text-danger">{{ $errors->first('status') }}</small>
                        @endif
                    </div>
                </form>
                <!-- End Form for Updating Status -->
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Bagian</th>
                            <th scope="col">Informasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td>{{ $product->name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Harga Tayang</td>
                            <td>{{ $product->price ? 'Rp ' . number_format($product->price, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Harga Diskon</td>
                            <td>{{ $product->discount_price ? 'Rp ' . number_format($product->discount_price, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Link E-katalog</td>
                            <td>{{ $product->e_catalog_link ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Stok</td>
                            <td>{{ $product->stock ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Masa Berlaku product</td>
                            <td>{{ $product->product_expiration_date ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Merk</td>
                            <td>{{ $product->brand ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>No product Penyedia</td>
                            <td>{{ $product->provider_product_number ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Unit Pengukuran</td>
                            <td>{{ $product->measurement_unit ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis product</td>
                            <td>{{ $product->product_type ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Kode KBKI</td>
                            <td>{{ $product->kbki_code ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nilai TKDN</td>
                            <td>{{ $product->tkdn_value ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>No SNI</td>
                            <td>{{ $product->sni_number ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Garansi product</td>
                            <td>{{ $product->product_warranty ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Uji Fungsi</td>
                            <td>{{ $product->function_test ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>SNI</td>
                            <td>{{ $product->sni ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Memiliki SVLK</td>
                            <td>{{ $product->has_svlk ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Alat</td>
                            <td>{{ $product->tool_type ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Fungsi</td>
                            <td>{{ $product->function ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Spesifikasi product</td>
                            <td>{!! $product->product_specifications ?: '-' !!}</td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>{{ $product->Category->name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Sub Category</td>
                            <td>{{ $product->subCategory->name ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Second Card: product Images -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title"><h2>Gambar product</h2></div>
            </div>
            <div class="card-body">
                @if($product->images && $product->images->isNotEmpty())
                    <div class="row">
                        @foreach ($product->images as $image)
                            <div class="col-md-6 mb-3">
                                <img src="{{ asset($image->images) }}" alt="Gambar product" class="img-fluid rounded shadow-sm">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No Image Available</p>
                @endif
            </div>
        </div>

        <!-- Third Card: Detail product List -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title"><h2>Detail product List</h2></div>
            </div>
            <div class="card-body">
                @if($product->ProductList && $product->ProductList->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Spesifikasi</th>
                                <th>Merk</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->ProductList as $detail)
                                <tr>
                                    <td>{{ $detail->name }}</td>
                                    <td>{{ $detail->specifications }}</td>
                                    <td>{{ $detail->brand }}</td>
                                    <td>{{ $detail->type }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ $detail->unit }}</td>
                                    <td>{{ 'Rp ' . number_format($detail->unit_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No Detail Product List Available</p>
                @endif
            </div>

        </div>

    </div>


    </div>
    <a href="{{ route('admin.product.index') }}" class="btn btn-primary">Kembali</a>



<script>
    function confirmStatusChange(status) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to change the status to ${status}.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('input[name="status"][value="' + status + '"]').checked = true;
                document.getElementById('statusForm').submit();
            } else {
                document.querySelector('input[name="status"][value="' + status + '"]').checked = false;
            }
        });
    }
</script>

@endsection
