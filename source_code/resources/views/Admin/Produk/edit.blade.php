@extends('layouts.admin.master')

@section('content')

    <!-- Tampilkan semua pesan error di atas form -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">Edit Produk</div>
                    <div class="d-flex align-items-center">
                        <div class="selectgroup w-auto mr-3">
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="arsip" class="selectgroup-input" {{ $produk->status == 'arsip' ? 'checked' : '' }} />
                                <span class="selectgroup-button">Arsip</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="publish" class="selectgroup-input" {{ $produk->status == 'publish' ? 'checked' : '' }} />
                                <span class="selectgroup-button">Publish</span>
                            </label>
                        </div>
                    </div>
                    @if ($errors->has('status'))
                        <small class="text-danger">{{ $errors->first('status') }}</small>
                    @endif
                </div>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <ul class="nav nav-tabs" id="productFormTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="categories-tab" data-toggle="tab" href="#categories" role="tab" aria-controls="categories" aria-selected="false">Categories</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="false">Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="images-tab" data-toggle="tab" href="#images" role="tab" aria-controls="images" aria-selected="false">Produk List</a>
                            </li>
                        </ul>

                    </div>

                    <div class="tab-content" id="productFormContent">
                        <!-- General Information Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="form-group">
                                <label for="nama">Nama Produk:</label>
                                <input type="text" name="nama" class="form-control" value="{{ $produk->nama }}" required>
                                @if ($errors->has('nama'))
                                    <small class="text-danger">{{ $errors->first('nama') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="nego">Bisa Nego:</label>
                                <select name="nego" class="form-control" required>
                                    <option value="tidak" {{ old('nego', $produk->nego) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                    <option value="ya" {{ old('nego', $produk->nego) == 'ya' ? 'selected' : '' }}>Ya</option>
                                </select>
                                @if ($errors->has('nego'))
                                    <small class="text-danger">{{ $errors->first('nego') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="harga_ditampilkan">Harga Ditampilkan:</label>
                                <select name="harga_ditampilkan" class="form-control" required>
                                    <option value="ya" {{ old('harga_ditampilkan', $produk->harga_ditampilkan) == 'ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="tidak" {{ old('harga_ditampilkan', $produk->harga_ditampilkan) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @if ($errors->has('harga_ditampilkan'))
                                    <small class="text-danger">{{ $errors->first('harga_ditampilkan') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="harga_tayang">Harga Produk:</label>
                                <input type="number" step="0.01" name="harga_tayang" class="form-control" value="{{ $produk->harga_tayang }}" required>
                                @if ($errors->has('harga_tayang'))
                                    <small class="text-danger">{{ $errors->first('harga_tayang') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="harga_potongan">Harga Diskon:</label>
                                <input type="number" step="0.01" name="harga_potongan" class="form-control"
                                       value="{{ old('harga_potongan', $produk->harga_potongan) }}" id="harga_potongan" 
                                       {{ old('allow_discount', $produk->harga_potongan ? true : false) ? '' : 'disabled' }}>
                                @if ($errors->has('harga_potongan'))
                                    <small class="text-danger">{{ $errors->first('harga_potongan') }}</small>
                                @endif
                            </div>
                            
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="allow_discount" name="allow_discount"
                                       {{ old('allow_discount', $produk->harga_potongan ? 'checked' : '') ? 'checked' : '' }}>
                                <label class="form-check-label" for="allow_discount">Izinkan Pengisian Harga Diskon</label>
                            </div>
                            
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const allowDiscountCheckbox = document.getElementById('allow_discount');
                                    const hargaDiskonInput = document.getElementById('harga_potongan');
                            
                                    // Toggle the disabled state of the input field based on the checkbox state
                                    allowDiscountCheckbox.addEventListener('change', function() {
                                        if (this.checked) {
                                            hargaDiskonInput.disabled = false;
                                        } else {
                                            hargaDiskonInput.disabled = true;
                                            hargaDiskonInput.value = ''; // Clear the input if unchecked
                                        }
                                    });
                            
                                    // Trigger the change event on page load to set the initial state
                                    allowDiscountCheckbox.dispatchEvent(new Event('change'));
                                });
                            </script>
                            
                            

                            <div class="form-group">
                                <label for="spesifikasi_produk">Spesifikasi Produk:</label>
                                <textarea name="spesifikasi_produk" id="spesifikasi_produk" class="form-control" required>
                                    {{ old('spesifikasi_produk', $produk->spesifikasi_produk) }}
                                </textarea>
                                @if ($errors->has('spesifikasi_produk'))
                                    <small class="text-danger">{{ $errors->first('spesifikasi_produk') }}</small>
                                @endif
                            </div>
                            
                            <script>
                                $(document).ready(function() {
                                    $('#spesifikasi_produk').summernote({
                                        height: 200, // Set tinggi editor
                                        placeholder: 'Masukkan spesifikasi produk...',
                                        toolbar: [
                                            // Sesuaikan toolbar sesuai kebutuhan
                                            ['style', ['style']],
                                            ['font', ['bold', 'italic', 'underline', 'clear']],
                                            ['fontname', ['fontname']],
                                            ['color', ['color']],
                                            ['para', ['ul', 'ol', 'paragraph']],
                                            ['table', ['table']],
                                            ['insert', ['link', 'picture', 'video']],
                                            ['view', ['fullscreen', 'codeview', 'help']],
                                        ]
                                    });
                                });
                            </script>
                            

                            <div class="form-group">
                                <label for="link_ekatalog">Link E-katalog:</label>
                                <input type="text" name="link_ekatalog" class="form-control" value="{{ old('link_ekatalog', $produk->link_ekatalog) }}" required>
                                @if ($errors->has('link_ekatalog'))
                                    <small class="text-danger">{{ $errors->first('link_ekatalog') }}</small>
                                @endif
                            </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="masa_berlaku_produk">Masa Berlaku Produk:</label>
                                    <input type="date" name="masa_berlaku_produk" class="form-control" value="{{ $produk->masa_berlaku_produk }}" required>
                                    @if ($errors->has('masa_berlaku_produk'))
                                        <small class="text-danger">{{ $errors->first('masa_berlaku_produk') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stok">Stok:</label>
                                    <input type="number" name="stok" class="form-control" value="{{ $produk->stok }}" required>
                                    @if ($errors->has('stok'))
                                        <small class="text-danger">{{ $errors->first('stok') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gambar">Gambar Produk:</label>
                            <input type="file" name="gambar[]" id="gambar[]" class="form-control" multiple>
                        
                            <div class="mt-2 d-flex flex-wrap">
                                @foreach($produk->images as $image)
                                    <div class="position-relative" style="margin-right: 10px;">
                                        <img src="{{ asset($image->gambar) }}" alt="Gambar Produk" style="width: 100px; height: 100px;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: 0; right: 0;" onclick="removeImage({{ $image->id }})">Hapus</button>
                                    </div>
                                @endforeach
                            </div>
                        
                            <input type="hidden" name="deleted_images" id="deleted_images">
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>  
                    </div>


                        <!-- Categories Tab -->
                        <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="komoditas_id">Komoditas:</label>
                                        <select name="komoditas_id" class="form-control" required>
                                            @foreach($komoditas as $k)
                                                <option value="{{ $k->id }}" {{ $produk->komoditas_id == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('komoditas_id'))
                                            <small class="text-danger">{{ $errors->first('komoditas_id') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kategori_id">Kategori:</label>
                                        <select name="kategori_id" id="kategori_id" class="form-control" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}" {{ $produk->kategori_id == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('kategori_id'))
                                            <small class="text-danger">{{ $errors->first('kategori_id') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sub_kategori_id">Sub Kategori:</label>
                                        <select name="sub_kategori_id" id="sub_kategori_id" class="form-control" required>
                                            <option value="">Pilih Sub Kategori</option>
                                            <!-- Sub kategori akan dimuat melalui AJAX -->
                                        </select>
                                        @if ($errors->has('sub_kategori_id'))
                                            <small class="text-danger">{{ $errors->first('sub_kategori_id') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Simpan</button>  
                        </div>

                        <!-- Details Tab -->
                        <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_pengukuran">Unit Pengukuran:</label>
                                        <select name="unit_pengukuran" class="form-control">
                                            <option value="set" {{ $produk->unit_pengukuran == 'set' ? 'selected' : '' }}>Set</option>
                                            <option value="paket" {{ $produk->unit_pengukuran == 'paket' ? 'selected' : '' }}>Paket</option>
                                        </select>
                                        @if ($errors->has('unit_pengukuran'))
                                            <small class="text-danger">{{ $errors->first('unit_pengukuran') }}</small>
                                        @endif
                                    </div>
                                </div>
                        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_alat">Jenis Alat:</label>
                                        <input type="text" name="jenis_alat" class="form-control" value="{{ $produk->jenis_alat }}">
                                        @if ($errors->has('jenis_alat'))
                                            <small class="text-danger">{{ $errors->first('jenis_alat') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nilai_tkdn">Nilai TKDN:</label>
                                        <input type="number" step="0.01" name="nilai_tkdn" class="form-control" value="{{ $produk->nilai_tkdn }}">
                                        @if ($errors->has('nilai_tkdn'))
                                            <small class="text-danger">{{ $errors->first('nilai_tkdn') }}</small>
                                        @endif
                                    </div>
                                </div>
                        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="merk">Merk:</label>
                                        <input type="text" name="merk" class="form-control" value="{{ $produk->merk }}">
                                        @if ($errors->has('merk'))
                                            <small class="text-danger">{{ $errors->first('merk') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_produk_penyedia">No Produk Penyedia:</label>
                                        <input type="text" name="no_produk_penyedia" class="form-control" value="{{ $produk->no_produk_penyedia }}">
                                        @if ($errors->has('no_produk_penyedia'))
                                            <small class="text-danger">{{ $errors->first('no_produk_penyedia') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="memiliki_svlk">Memiliki SVLK:</label>
                                        <select name="memiliki_svlk" class="form-control">
                                            <option value="ya" {{ $produk->memiliki_svlk == 'ya' ? 'selected' : '' }}>Ya</option>
                                            <option value="tidak" {{ $produk->memiliki_svlk == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                        @if ($errors->has('memiliki_svlk'))
                                            <small class="text-danger">{{ $errors->first('memiliki_svlk') }}</small>
                                        @endif
                                    </div>
                                </div>
                        
                               
                            </div>
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sni">SNI:</label>
                                        <select name="sni" class="form-control">
                                            <option value="ya" {{ $produk->sni == 'ya' ? 'selected' : '' }}>Ya</option>
                                            <option value="tidak" {{ $produk->sni == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                        @if ($errors->has('sni'))
                                            <small class="text-danger">{{ $errors->first('sni') }}</small>
                                        @endif
                                    </div>
                                </div>
                        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_sni">No SNI:</label>
                                        <input type="text" name="no_sni" class="form-control" value="{{ $produk->no_sni }}">
                                        @if ($errors->has('no_sni'))
                                            <small class="text-danger">{{ $errors->first('no_sni') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode_kbki">Kode KBLI:</label>
                                        <input type="number" name="kode_kbki" class="form-control" value="{{ $produk->kode_kbki }}">
                                        @if ($errors->has('kode_kbki'))
                                            <small class="text-danger">{{ $errors->first('kode_kbki') }}</small>
                                        @endif
                                    </div>
                                </div>
                        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asal_negara">Asal Negara:</label>
                                        <input type="text" name="asal_negara" class="form-control" value="{{ $produk->asal_negara }}">
                                        @if ($errors->has('asal_negara'))
                                            <small class="text-danger">{{ $errors->first('asal_negara') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_produk">Jenis Produk:</label>
                                        <select name="jenis_produk" class="form-control">
                                            <option value="PDN" {{ $produk->jenis_produk == 'PDN' ? 'selected' : '' }}>PDN</option>
                                            <option value="Impor" {{ $produk->jenis_produk == 'Impor' ? 'selected' : '' }}>Impor</option>
                                        </select>
                                        @if ($errors->has('jenis_produk'))
                                            <small class="text-danger">{{ $errors->first('jenis_produk') }}</small>
                                        @endif
                                    </div>
                                </div>
                        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fungsi">Fungsi:</label>
                                        <input type="text" name="fungsi" class="form-control" value="{{ $produk->fungsi }}">
                                        @if ($errors->has('fungsi'))
                                            <small class="text-danger">{{ $errors->first('fungsi') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipe_barang">Tipe Barang:</label>
                                        <input type="text" name="tipe_barang" class="form-control" value="{{ $produk->tipe_barang }}">
                                        @if ($errors->has('tipe_barang'))
                                            <small class="text-danger">{{ $errors->first('tipe_barang') }}</small>
                                        @endif
                                    </div>
                                </div>
                        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="garansi_produk">Garansi Produk:</label>
                                        <input type="text" name="garansi_produk" class="form-control" value="{{ old('garansi_produk', $produk->garansi_produk) }}">
                                        @if ($errors->has('garansi_produk'))
                                            <small class="text-danger">{{ $errors->first('garansi_produk') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Simpan</button>  
                        </div>
                        

                        <!-- Images Tab -->
                        <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images-tab">
                            <!-- Detail Produk List -->
                            <h2 class="mt-5">Detail Produk List</h2>
                            <div id="detail-list-container">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th> 
                                            <th>Nama Produk List</th>
                                            <th>Spesifikasi Produk List</th>
                                            <th>Merk Produk List</th>
                                            <th>Tipe Produk List</th>
                                            <th>Jumlah Produk List</th>
                                            <th>Satuan Produk List</th>
                                            <th>Harga Satuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($produk->produkList as $detail)
                                            <tr class="detail-list">
                                                <td class="numbering">1</td> 
                                                <td><input type="text" name="detail[nama][]" class="form-control" value="{{ $detail->nama }}"></td>
                                                <td><textarea name="detail[spesifikasi][]" class="form-control">{{ $detail->spesifikasi }}</textarea></td>
                                                <td><input type="text" name="detail[merk][]" class="form-control" value="{{ $detail->merk }}"></td>
                                                <td><input type="text" name="detail[tipe][]" class="form-control" value="{{ $detail->tipe }}"></td>
                                                <td><input type="number" name="detail[jumlah][]" class="form-control" value="{{ $detail->jumlah }}"></td>
                                                <td>
                                                    <select name="detail[satuan][]" class="form-control">
                                                        <option value="Set" {{ $detail->satuan == 'Set' ? 'selected' : '' }}>Set</option>
                                                        <option value="Paket" {{ $detail->satuan == 'Paket' ? 'selected' : '' }}>Paket</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" step="0.01" name="detail[harga_satuan][]" class="form-control" value="{{ $detail->harga_satuan }}"></td>
                                                <td><button type="button" class="btn btn-danger remove-detail">Hapus</button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-secondary mt-3" id="add-detail">Tambah Detail</button>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Simpan</button>  
                        </div>
                    </div>

                </div>
            </div>
            <small>*Produk, ketika berhasil ditambahkan, maka status akan otomatis menjadi "Arsip" yang artinya anda perlu merubahnya menjadi "Publish" agar muncul di halaman User</small>
        </div>
    </form>

    <script>
        document.getElementById('add-detail').addEventListener('click', function() {
            var detailListContainer = document.querySelector('#detail-list-container tbody');
            var detailList = document.createElement('tr');
            detailList.classList.add('detail-list');

            detailList.innerHTML = `
                <td class="numbering"></td> <!-- Numbering Cell -->
                <td><input type="text" name="detail[nama][]" class="form-control"></td>
                <td><textarea name="detail[spesifikasi][]" class="form-control"></textarea></td>
                <td><input type="text" name="detail[merk][]" class="form-control"></td>
                <td><input type="text" name="detail[tipe][]" class="form-control"></td>
                <td><input type="number" name="detail[jumlah][]" class="form-control"></td>
                <td>
                    <select name="detail[satuan][]" class="form-control">
                        <option value="Set">Set</option>
                        <option value="Paket">Paket</option>
                    </select>
                </td>
                <td><input type="number" step="0.01" name="detail[harga_satuan][]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger remove-detail">Hapus</button></td>
            `;
            detailListContainer.appendChild(detailList);

            updateNumbering();

            // Add event listener to the newly added remove button
            detailList.querySelector('.remove-detail').addEventListener('click', function() {
                this.closest('tr').remove();
                updateNumbering();
            });
        });

        // Function to update numbering
        function updateNumbering() {
            const rows = document.querySelectorAll('#detail-list-container tbody .detail-list');
            rows.forEach((row, index) => {
                row.querySelector('.numbering').textContent = index + 1;
            });
        }

        // Initial call to set up numbering
        updateNumbering();

        // Add event listener to the initial remove buttons
        document.querySelectorAll('.remove-detail').forEach(function(button) {
            button.addEventListener('click', function() {
                this.closest('tr').remove();
                updateNumbering();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var kategoriSelect = document.getElementById('kategori_id');
            var subKategoriSelect = document.getElementById('sub_kategori_id');

            // Function to load sub categories
            function loadSubKategoris(kategoriId, selectedSubKategoriId = null) {
                subKategoriSelect.innerHTML = '<option value="">Memuat...</option>';

                fetch(`/admin/produk/getSubKategori/${kategoriId}`)
                    .then(response => response.json())
                    .then(data => {
                        subKategoriSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';
                        data.forEach(function(subKategori) {
                            var option = document.createElement('option');
                            option.value = subKategori.id;
                            option.textContent = subKategori.nama;
                            if (subKategori.id == selectedSubKategoriId) {
                                option.selected = true;
                            }
                            subKategoriSelect.appendChild(option);
                        });
                    });
            }

            // Initial load of sub categories based on the selected kategori
            if (kategoriSelect.value) {
                loadSubKategoris(kategoriSelect.value, '{{ $produk->sub_kategori_id }}');
            }

            // Event listener for kategori change
            kategoriSelect.addEventListener('change', function() {
                loadSubKategoris(this.value);
            });
        });
    </script>

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

<style>
    .nav-tabs .nav-link {
        color: #1a2035;
        font-weight: bold;
        border-radius: 0;
        transition: background-color 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        background-color: #f8f9fa;
    }

    .nav-tabs .nav-link.active {
        background-color: #1a2035;
        color: #fff;
    }

    .btn {
        border-radius: 30px;
        padding: 10px 20px;
    }
</style>

<script>
    function removeImage(imageId) {
    let deletedImages = document.getElementById('deleted_images').value;
    if (deletedImages) {
        deletedImages += ',' + imageId;
    } else {
        deletedImages = imageId;
    }
    document.getElementById('deleted_images').value = deletedImages;

    // Remove the image element from the UI
    event.target.parentElement.remove();
}

</script>
@endsection
