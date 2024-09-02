@extends('layouts.admin.master')

@section('content')

    <!-- Display all errors at the top of the form -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Produk</div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs" id="productFormTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="false">Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="produk_list-tab" data-toggle="tab" href="#produk_lists" role="tab" aria-controls="produk_lists" aria-selected="false">Produk List</a>
                        </li>
                    </ul>
                
                    <!-- Navigation Buttons -->
                    <div class="ml-auto">
                        <button type="button" class="btn btn-light" id="prevBtn"><i class="fas fa-arrow-left"></i> Back</button>
                        <button type="button" class="btn btn-light" id="nextBtn">Next <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
    
                <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
    
                    <div class="tab-content" id="productFormContent">
                        <!-- General Information Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="form-group">
                                <label for="nama"><span class="text-danger">*</span> Nama Produk: </label>
                                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" required>
                                @if ($errors->has('nama'))
                                    <small class="text-danger">{{ $errors->first('nama') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="nego"><span class="text-danger">*</span> Bisa Nego:</label>
                                <select name="nego" id="nego" class="form-control" required>
                                    <option value="" disabled {{ old('nego') ? '' : 'selected' }}>Pilih opsi</option>
                                    <option value="ya" {{ old('nego') == 'ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="tidak" {{ old('nego') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @if ($errors->has('nego'))
                                    <small class="text-danger">{{ $errors->first('nego') }}</small>
                                @endif
                            </div>                            
                            <div class="form-group">
                                <label for="harga_ditampilkan"> <span class="text-danger">*</span> Harga Ditampilkan:</label>
                                <select name="harga_ditampilkan" id="harga_ditampilkan" class="form-control" required>
                                    <option value="" disabled {{ old('harga_ditampilkan') ? '' : 'selected' }}>Pilih opsi</option>
                                    <option value="ya" {{ old('harga_ditampilkan') == 'ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="tidak" {{ old('harga_ditampilkan') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @if ($errors->has('harga_ditampilkan'))
                                    <small class="text-danger">{{ $errors->first('harga_ditampilkan') }}</small>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <label for="harga_tayang"><span class="text-danger">*</span> Harga Produk:</label>
                                <input type="number" step="0.01" name="harga_tayang" id="harga_tayang" class="form-control" value="{{ old('harga_tayang') }}" required>
                                @if ($errors->has('harga_tayang'))
                                    <small class="text-danger">{{ $errors->first('harga_tayang') }}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="spesifikasi_produk"><span class="text-danger">*</span> Spesifikasi Produk:</label>
                                <textarea name="spesifikasi_produk" id="spesifikasi_produk" class="form-control" required>{{ old('spesifikasi_produk') }}
                                    <div>
                                        <ol>
                                            <li></li>
                                        </ol>
                                    </div>
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
                                            // Anda bisa menyesuaikan toolbar sesuai kebutuhan
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
                                <label for="link_ekatalog"><span class="text-danger">*</span> Link E-katalog:</label>
                                <input type="text" name="link_ekatalog" id="link_ekatalog" class="form-control" value="{{ old('link_ekatalog') }}" required>
                                @if ($errors->has('link_ekatalog'))
                                    <small class="text-danger">{{ $errors->first('link_ekatalog') }}</small>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="masa_berlaku_produk"><span class="text-danger">*</span> Masa Berlaku Produk:</label>
                                        <input type="date" name="masa_berlaku_produk" id="masa_berlaku_produk" class="form-control" value="{{ old('masa_berlaku_produk') }}" required>
                                        @if ($errors->has('masa_berlaku_produk'))
                                            <small class="text-danger">{{ $errors->first('masa_berlaku_produk') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stok"><span class="text-danger">*</span> Stok:</label>
                                        <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok') }}" required>
                                        @if ($errors->has('stok'))
                                            <small class="text-danger">{{ $errors->first('stok') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="komoditas_id"><span class="text-danger">*</span> Komoditas:</label>
                                        <select name="komoditas_id" class="form-control" id="komoditas_id" required>
                                            @foreach($komoditas as $k)
                                                <option value="{{ $k->id }}" {{ old('komoditas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('komoditas_id'))
                                            <small class="text-danger">{{ $errors->first('komoditas_id') }}</small>
                                        @endif
                                    </div>
                                </div>
    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kategori_id"><span class="text-danger">*</span> Kategori:</label>
                                        <select name="kategori_id" id="kategori_id" class="form-control" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('kategori_id'))
                                            <small class="text-danger">{{ $errors->first('kategori_id') }}</small>
                                        @endif
                                    </div>
                                </div>
    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sub_kategori_id"><span class="text-danger">*</span> Sub Kategori:</label>
                                        <select name="sub_kategori_id" id="sub_kategori_id" class="form-control" required>
                                            <option value="">Pilih Sub Kategori</option>
                                        </select>
                                        @if ($errors->has('sub_kategori_id'))
                                            <small class="text-danger">{{ $errors->first('sub_kategori_id') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="gambar"><span class="text-danger">*</span> Gambar Produk:</label>
                                <input type="file" name="gambar[]" id="gambar[]" class="form-control" multiple required>
                                @if ($errors->has('gambar.*'))
                                    <small class="text-danger">{{ $errors->first('gambar.*') }}</small>
                                @endif
                            </div>
                        <button type="submit" id="saveButton" class="btn btn-primary mt-3" style="display: none;">Simpan</button>
                        </div>
    
                        <!-- Details Tab -->
                        <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_pengukuran">Unit Pengukuran:</label>
                                        <select name="unit_pengukuran" class="form-control">
                                            <option value="" disabled {{ old('unit_pengukuran') ? '' : 'selected' }}>Pilih unit pengukuran</option>
                                            <option value="set" {{ old('unit_pengukuran') == 'set' ? 'selected' : '' }}>Set</option>
                                            <option value="paket" {{ old('unit_pengukuran') == 'paket' ? 'selected' : '' }}>Paket</option>
                                        </select>
                                        @if ($errors->has('unit_pengukuran'))
                                            <small class="text-danger">{{ $errors->first('unit_pengukuran') }}</small>
                                        @endif
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_alat">Jenis Alat:</label>
                                        <input type="text" name="jenis_alat" class="form-control" value="{{ old('jenis_alat') }}">
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
                                        <input type="number" step="0.01" name="nilai_tkdn" class="form-control" value="{{ old('nilai_tkdn') }}">
                                        @if ($errors->has('nilai_tkdn'))
                                            <small class="text-danger">{{ $errors->first('nilai_tkdn') }}</small>
                                        @endif
                                    </div>
                                </div>
    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="merk">Merk:</label>
                                        <input type="text" name="merk" class="form-control" value="{{ old('merk') }}">
                                        @if ($errors->has('merk'))
                                            <small class="text-danger">{{ $errors->first('merk') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sni">SNI:</label>
                                        <select name="sni" class="form-control">
                                            <option value="" disabled {{ old('sni') ? '' : 'selected' }}>Pilih status SNI</option>
                                            <option value="ya" {{ old('sni') == 'ya' ? 'selected' : '' }}>Ya</option>
                                            <option value="tidak" {{ old('sni') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                        @if ($errors->has('sni'))
                                            <small class="text-danger">{{ $errors->first('sni') }}</small>
                                        @endif
                                    </div>
                                </div>                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_produk_penyedia">No Produk Penyedia:</label>
                                        <input type="text" name="no_produk_penyedia" class="form-control" value="{{ old('no_produk_penyedia') }}">
                                        @if ($errors->has('no_produk_penyedia'))
                                            <small class="text-danger">{{ $errors->first('no_produk_penyedia') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_sni">No SNI:</label>
                                        <input type="text" name="no_sni" class="form-control" value="{{ old('no_sni') }}">
                                        @if ($errors->has('no_sni'))
                                            <small class="text-danger">{{ $errors->first('no_sni') }}</small>
                                        @endif
                                    </div>
                                </div>                                
    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="memiliki_svlk">Memiliki SVLK:</label>
                                        <select name="memiliki_svlk" class="form-control">
                                            <option value="" disabled {{ old('memiliki_svlk') ? '' : 'selected' }}>Pilih status SVLK</option>
                                            <option value="ya" {{ old('memiliki_svlk') == 'ya' ? 'selected' : '' }}>Ya</option>
                                            <option value="tidak" {{ old('memiliki_svlk') == 'tidak' ? 'selected' : '' }}>Tidak</option>
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
                                        <label for="kode_kbki">Kode KBLI:</label>
                                        <input type="number" name="kode_kbki" class="form-control" value="{{ old('kode_kbki') }}">
                                        @if ($errors->has('kode_kbki'))
                                            <small class="text-danger">{{ $errors->first('kode_kbki') }}</small>
                                        @endif
                                    </div>
                                </div>
    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asal_negara">Asal Negara:</label>
                                        <input type="text" name="asal_negara" class="form-control" value="{{ old('asal_negara') }}">
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
                                            <option value="" disabled {{ old('jenis_produk') ? '' : 'selected' }}>Pilih jenis produk</option>
                                            <option value="PDN" {{ old('jenis_produk') == 'PDN' ? 'selected' : '' }}>PDN</option>
                                            <option value="Impor" {{ old('jenis_produk') == 'Impor' ? 'selected' : '' }}>Impor</option>
                                        </select>
                                        @if ($errors->has('jenis_produk'))
                                            <small class="text-danger">{{ $errors->first('jenis_produk') }}</small>
                                        @endif
                                    </div>                                    
                                </div>
    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fungsi">Fungsi:</label>
                                        <input type="text" name="fungsi" class="form-control" value="{{ old('fungsi') }}">
                                        @if ($errors->has('fungsi'))
                                            <small class="text-danger">{{ $errors->first('fungsi') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipe_barang">Tipe Barang:</label>
                                    <input type="text" name="tipe_barang" class="form-control" value="{{ old('tipe_barang') }}">
                                    @if ($errors->has('tipe_barang'))
                                        <small class="text-danger">{{ $errors->first('tipe_barang') }}</small>
                                    @endif
                                </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="garansi_produk">Garansi Produk:</label>
                                        <input type="text" name="garansi_produk" class="form-control" value="{{ old('garansi_produk') }}">
                                        @if ($errors->has('garansi_produk'))
                                            <small class="text-danger">{{ $errors->first('garansi_produk') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Images Tab -->
                        <div class="tab-pane fade" id="produk_lists" role="tabpanel" aria-labelledby="produk_list-tab">
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
                                        <tr class="detail-list">
                                            <td class="numbering">1</td>
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
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-secondary mt-3" id="add-detail">Tambah Detail</button>
                            </div>
                            <button type="submit" id="saveButton" class="btn btn-primary mt-3" >Simpan</button>
                        </div>
                    </div>
    
                </form>
            </div>
        </div>
        <small>*Produk, ketika berhasil ditambahkan, maka status akan otomatis menjadi "Arsip" yang artinya anda perlu merubahnya menjadi "Publish" agar muncul di halaman User</small>
        <small>*General Information adalah hal yang wajib di isi jika ingin memasukkan data produk, dan untuk "details" dan "Produk List" dapat di isi belakangan.</small>
    </div>

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
        
        // Add event listener to the initial remove button
        document.querySelectorAll('.remove-detail').forEach(function(button) {
            button.addEventListener('click', function() {
                this.closest('tr').remove();
                updateNumbering();
            });
        });
        </script>

    <script>
        document.getElementById('kategori_id').addEventListener('change', function() {
            var kategoriId = this.value;
            var subKategoriSelect = document.getElementById('sub_kategori_id');

            subKategoriSelect.innerHTML = '<option value="">Memuat...</option>';

            fetch(`/admin/produk/getSubKategori/${kategoriId}`)
                .then(response => response.json())
                .then(data => {
                    subKategoriSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';
                    data.forEach(function(subKategori) {
                        var option = document.createElement('option');
                        option.value = subKategori.id;
                        option.textContent = subKategori.nama;
                        subKategoriSelect.appendChild(option);
                    });
                });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = ['general', 'details', 'produk_lists'];
        let currentTabIndex = 0;

        // Function to update the tabs based on the current index
        function updateTabs(index) {
            if (index >= 0 && index < tabs.length) {
                document.querySelector(`#${tabs[currentTabIndex]}-tab`).classList.remove('active');
                document.querySelector(`#${tabs[currentTabIndex]}`).classList.remove('show', 'active');

                document.querySelector(`#${tabs[index]}-tab`).classList.add('active');
                document.querySelector(`#${tabs[index]}`).classList.add('show', 'active');

                currentTabIndex = index;
            }
        }

        // Back button event listener
        document.getElementById('prevBtn').addEventListener('click', function() {
            if (currentTabIndex > 0) {
                updateTabs(currentTabIndex - 1);
            }
        });

        // Next button event listener
        document.getElementById('nextBtn').addEventListener('click', function() {
            if (currentTabIndex < tabs.length - 1) {
                updateTabs(currentTabIndex + 1);
            }
        });

        // Initial setup
        updateTabs(currentTabIndex);
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
    document.addEventListener('DOMContentLoaded', function () {
    const requiredFields = ['nama', 'harga_ditampilkan', 'komoditas_id', 'kategori_id', 'sub_kategori_id','gambar[]'];
    const saveButton = document.getElementById('saveButton');

    requiredFields.forEach(field => {
        document.getElementById(field).addEventListener('input', checkForm);
    });

    function checkForm() {
        let allFilled = true;

        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value) {
                allFilled = false;
            }
        });

        if (allFilled) {
            saveButton.style.display = 'block';
        } else {
            saveButton.style.display = 'none';
        }
    }
});

</script>

@endsection
