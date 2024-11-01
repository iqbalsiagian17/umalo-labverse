@extends('layouts.Admin.master')

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- First Card: Product Information -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    <h2>Spesifikasi Product</h2>
                    @if($Product->nego === 'ya')
                        <span class="badge badge-success">Bisa Nego</span>
                    @endif
                </div>
                 <!-- Start Form for Updating Status -->
                 <form id="statusForm" action="{{ route('Product.update-status', $Product->id) }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="form-group">
                        <label class="form-label">Status:</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="arsip" class="selectgroup-input"
                                    {{ $Product->status == 'arsip' ? 'checked' : '' }}
                                    onchange="confirmStatusChange('arsip')" />
                                <span class="selectgroup-button">Arsip</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="publish" class="selectgroup-input"
                                    {{ $Product->status == 'publish' ? 'checked' : '' }}
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
                            <td>{{ $Product->nama ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Harga Tayang</td>
                            <td>{{ $Product->harga_tayang ? 'Rp ' . number_format($Product->harga_tayang, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Harga Diskon</td>
                            <td>{{ $Product->harga_potongan ? 'Rp ' . number_format($Product->harga_potongan, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Link E-katalog</td>
                            <td>{{ $Product->link_ekatalog ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tipe Barang</td>
                            <td>{{ $Product->tipe_barang ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Stok</td>
                            <td>{{ $Product->stok ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Masa Berlaku Product</td>
                            <td>{{ $Product->masa_berlaku_Product ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Merk</td>
                            <td>{{ $Product->merk ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>No Product Penyedia</td>
                            <td>{{ $Product->no_Product_penyedia ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Unit Pengukuran</td>
                            <td>{{ $Product->unit_pengukuran ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Product</td>
                            <td>{{ $Product->jenis_Product ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Kode KBKI</td>
                            <td>{{ $Product->kode_kbki ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Asal Negara</td>
                            <td>{{ $Product->asal_negara ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nilai TKDN</td>
                            <td>{{ $Product->nilai_tkdn ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>No SNI</td>
                            <td>{{ $Product->no_sni ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Garansi Product</td>
                            <td>{{ $Product->garansi_Product ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Uji Fungsi</td>
                            <td>{{ $Product->uji_fungsi ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>SNI</td>
                            <td>{{ $Product->sni ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Memiliki SVLK</td>
                            <td>{{ $Product->memiliki_svlk ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Alat</td>
                            <td>{{ $Product->jenis_alat ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Fungsi</td>
                            <td>{{ $Product->fungsi ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Spesifikasi Product</td>
                            <td>{!! $Product->spesifikasi_Product ?: '-' !!}</td>
                        </tr>
                        <tr>
                            <td>Komoditas</td>
                            <td>{{ $Product->komoditas->nama ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>{{ $Product->Category->nama ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Sub Category</td>
                            <td>{{ $Product->subCategory->nama ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Second Card: Product Images -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title"><h2>Gambar Product</h2></div>
            </div>
            <div class="card-body">
                @if($Product->images && $Product->images->isNotEmpty())
                    <div class="row">
                        @foreach ($Product->images as $image)
                            <div class="col-md-6 mb-3">
                                <img src="{{ asset($image->gambar) }}" alt="Gambar Product" class="img-fluid rounded shadow-sm">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No Image Available</p>
                @endif
            </div>
        </div>

        <!-- Third Card: Detail Product List -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title"><h2>Detail Product List</h2></div>
            </div>
            <div class="card-body">
                @if($Product->ProductList && $Product->ProductList->isNotEmpty())
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
                            @foreach ($Product->ProductList as $detail)
                                <tr>
                                    <td>{{ $detail->nama }}</td>
                                    <td>{{ $detail->spesifikasi }}</td>
                                    <td>{{ $detail->merk }}</td>
                                    <td>{{ $detail->tipe }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>{{ $detail->satuan }}</td>
                                    <td>{{ 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.') }}</td>
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
    <a href="{{ route('Product.index') }}" class="btn btn-primary">Kembali</a>

</div>


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
