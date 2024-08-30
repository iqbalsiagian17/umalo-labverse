@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- First Card: Product Information -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    <h2>Spesifikasi Produk</h2>
                    @if($produk->nego === 'ya')
                        <span class="badge badge-success">Bisa Nego</span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Status:</label>
                    <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                            <input type="radio" name="status" value="arsip" class="selectgroup-input" {{ $produk->status == 'arsip' ? 'checked' : '' }} />
                            <span class="selectgroup-button">Arsip</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="status" value="publish" class="selectgroup-input" {{ $produk->status == 'publish' ? 'checked' : '' }} />
                            <span class="selectgroup-button">Publish</span>
                        </label>
                    </div>
                    @if ($errors->has('status'))
                        <small class="text-danger">{{ $errors->first('status') }}</small>
                    @endif
                </div>
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
                            <td>{{ $produk->nama ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Harga Tayang</td>
                            <td>{{ $produk->harga_tayang ? 'Rp ' . number_format($produk->harga_tayang, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Harga Diskon</td>
                            <td>{{ $produk->harga_potongan ? 'Rp ' . number_format($produk->harga_potongan, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Link E-katalog</td>
                            <td>{{ $produk->link_ekatalog ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tipe Barang</td>
                            <td>{{ $produk->tipe_barang ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Stok</td>
                            <td>{{ $produk->stok ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Masa Berlaku Produk</td>
                            <td>{{ $produk->masa_berlaku_produk ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Merk</td>
                            <td>{{ $produk->merk ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>No Produk Penyedia</td>
                            <td>{{ $produk->no_produk_penyedia ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Unit Pengukuran</td>
                            <td>{{ $produk->unit_pengukuran ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Produk</td>
                            <td>{{ $produk->jenis_produk ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Kode KBKI</td>
                            <td>{{ $produk->kode_kbki ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Asal Negara</td>
                            <td>{{ $produk->asal_negara ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nilai TKDN</td>
                            <td>{{ $produk->nilai_tkdn ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>No SNI</td>
                            <td>{{ $produk->no_sni ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Garansi Produk</td>
                            <td>{{ $produk->garansi_produk ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Uji Fungsi</td>
                            <td>{{ $produk->uji_fungsi ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>SNI</td>
                            <td>{{ $produk->sni ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Memiliki SVLK</td>
                            <td>{{ $produk->memiliki_svlk ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Alat</td>
                            <td>{{ $produk->jenis_alat ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Fungsi</td>
                            <td>{{ $produk->fungsi ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Spesifikasi Produk</td>
                            <td>{{ $produk->spesifikasi_produk ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Komoditas</td>
                            <td>{{ $produk->komoditas->nama ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Kategori</td>
                            <td>{{ $produk->kategori->nama ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Sub Kategori</td>
                            <td>{{ $produk->subkategori->nama ?: '-' }}</td>
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
                <div class="card-title"><h2>Gambar Produk</h2></div>
            </div>
            <div class="card-body">
                @if($produk->images && $produk->images->isNotEmpty())
                    <div class="row">
                        @foreach ($produk->images as $image)
                            <div class="col-md-6 mb-3">
                                <img src="{{ asset($image->gambar) }}" alt="Gambar Produk" class="img-fluid rounded shadow-sm">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No Image Available</p>
                @endif
            </div>
        </div>

        <!-- Third Card: Detail Produk List -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title"><h2>Detail Produk List</h2></div>
            </div>
            <div class="card-body">
                @if($produk->produkList && $produk->produkList->isNotEmpty())
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
                            @foreach ($produk->produkList as $detail)
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
                    <p>No Detail Produk List Available</p>
                @endif
            </div>
            
        </div>
        
    </div>


    </div>
    <a href="{{ route('produk.index') }}" class="btn btn-primary">Kembali</a>

</div>


<script>
    document.querySelectorAll('input[name="status"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var status = this.value;
            var productId = "{{ $produk->id }}";  // Pass the product ID to use in the request

            fetch(`/produk/update-status/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({status: status})
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Status berhasil diperbarui');
                } else {
                    alert('Gagal memperbarui status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui status');
            });
        });
    });
</script>

@endsection
