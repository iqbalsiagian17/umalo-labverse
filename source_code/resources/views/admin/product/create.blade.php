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
                <div class="card-title">Form Product</div>
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
                            <a class="nav-link" id="Product_list-tab" data-toggle="tab" href="#Product_lists" role="tab" aria-controls="Product_lists" aria-selected="false">Product List</a>
                        </li>
                    </ul>

                    <!-- Navigation Buttons -->
                    <div class="ml-auto">
                        <button type="button" class="btn btn-light" id="prevBtn"><i class="fas fa-arrow-left"></i> Back</button>
                        <button type="button" class="btn btn-light" id="nextBtn">Next <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" onsubmit="removeFormatAndSubmit(this)">
                    @csrf 
                
                    <div class="tab-content" id="productFormContent">
                        <!-- General Information Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="form-group">
                                <label for="name"><span class="text-danger">*</span> Nama Product: </label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                                @if ($errors->has('name'))
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="is_price_displayed"> <span class="text-danger">*</span> Harga Ditampilkan:</label>
                                <select name="is_price_displayed" id="is_price_displayed" class="form-control" required>
                                    <option value="" disabled {{ old('is_price_displayed') ? '' : 'selected' }}>Pilih opsi</option>
                                    <option value="yes" {{ old('is_price_displayed') == 'yes' ? 'selected' : '' }}>Ya</option>
                                    <option value="no" {{ old('is_price_displayed') == 'no' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @if ($errors->has('is_price_displayed'))
                                    <small class="text-danger">{{ $errors->first('is_price_displayed') }}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 col-form-label" for="price"><span class="text-danger">*</span> Harga Produk:</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" name="price" class="form-control" id="price" placeholder="Harga Produk" value="{{ old('price') }}" oninput="formatNumber(this)" />
                                    </div>
                                    @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="product_specifications"><span class="text-danger">*</span> Spesifikasi Product:</label>
                                <textarea name="product_specifications" id="product_specifications" class="form-control" required>{{ old('product_specifications') }}
                                    <div>
                                        <ol>
                                            <li></li>
                                        </ol>
                                    </div>
                                </textarea>
                                @if ($errors->has('product_specifications'))
                                    <small class="text-danger">{{ $errors->first('product_specifications') }}</small>
                                @endif
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('#product_specifications').summernote({
                                        height: 200, // Set tinggi editor
                                        placeholder: 'Masukkan spesifikasi Product...',
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
                                <label for="e_catalog_link"><span class="text-danger">*</span> Link E-katalog:</label>
                                <input type="text" name="e_catalog_link" id="e_catalog_link" class="form-control" value="{{ old('e_catalog_link') }}" required>
                                @if ($errors->has('e_catalog_link'))
                                    <small class="text-danger">{{ $errors->first('e_catalog_link') }}</small>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_expiration_date"><span class="text-danger">*</span> Masa Berlaku Product:</label>
                                        <input type="date" name="product_expiration_date" id="product_expiration_date" class="form-control" value="{{ old('product_expiration_date') }}" required>
                                        @if ($errors->has('product_expiration_date'))
                                            <small class="text-danger">{{ $errors->first('product_expiration_date') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stock"><span class="text-danger">*</span> Stok:</label>
                                        <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock') }}" required>
                                        @if ($errors->has('stock'))
                                            <small class="text-danger">{{ $errors->first('stock') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id"><span class="text-danger">*</span> Category:</label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            <option value="">Pilih Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('category_id'))
                                            <small class="text-danger">{{ $errors->first('category_id') }}</small>
                                        @endif
                                    </div>
                                </div>
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subcategory_id"><span class="text-danger">*</span> Sub Category:</label>
                                        <select name="subcategory_id" class="form-control" id="subcategory_id">
                                            <option value="">Pilih Sub kategori Produk</option>
                                            <!-- Menyimpan subkategori jika ada nilai old -->
                                            @if(old('category_id'))
                                                @foreach($subcategories as $subcategory)
                                                    @if($subcategory->category_id == old('category_id'))
                                                        <option value="{{ $subcategory->id }}" {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                                            {{ $subcategory->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('subcategory_id'))
                                            <small class="text-danger">{{ $errors->first('subcategory_id') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <label for="images"><span class="text-danger">*</span> Gambar Product:</label>
                                <input type="file" name="images[]" id="images[]" class="form-control" multiple required>
                                @if ($errors->has('images.*'))
                                    <small class="text-danger">{{ $errors->first('images.*') }}</small>
                                @endif
                            </div>
                        <button type="submit" id="saveButton" class="btn btn-primary mt-3" style="display: none;">Simpan</button>
                        </div>

                        <!-- Details Tab -->
                        <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="measurement_unit">Unit Pengukuran:</label>
                                        <select name="measurement_unit" class="form-control">
                                            <option value="" disabled {{ old('measurement_unit') ? '' : 'selected' }}>Pilih unit pengukuran</option>
                                            <option value="set" {{ old('measurement_unit') == 'set' ? 'selected' : '' }}>Set</option>
                                            <option value="Package" {{ old('measurement_unit') == 'Package' ? 'selected' : '' }}>Paket</option>
                                        </select>
                                        @if ($errors->has('measurement_unit'))
                                            <small class="text-danger">{{ $errors->first('measurement_unit') }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kbki_code">Kode KBKI:</label>
                                        <input type="number" name="kbki_code" class="form-control" value="{{ old('kbki_code') }}">
                                        @if ($errors->has('kbki_code'))
                                            <small class="text-danger">{{ $errors->first('kbki_code') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tkdn_value">Nilai TKDN:</label>
                                        <input type="number" step="0.01" name="tkdn_value" class="form-control" value="{{ old('tkdn_value') }}">
                                        @if ($errors->has('tkdn_value'))
                                            <small class="text-danger">{{ $errors->first('tkdn_value') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="brand">Merk:</label>
                                        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
                                        @if ($errors->has('brand'))
                                            <small class="text-danger">{{ $errors->first('brand') }}</small>
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
                                            <option value="yes" {{ old('sni') == 'yes' ? 'selected' : '' }}>Ya</option>
                                            <option value="no" {{ old('sni') == 'no' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                        @if ($errors->has('sni'))
                                            <small class="text-danger">{{ $errors->first('sni') }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="provider_product_number">No Product Penyedia:</label>
                                        <input type="text" name="provider_product_number" class="form-control" value="{{ old('provider_product_number') }}">
                                        @if ($errors->has('provider_product_number'))
                                            <small class="text-danger">{{ $errors->first('provider_product_number') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sni_number">No SNI:</label>
                                        <input type="text" name="sni_number" class="form-control" value="{{ old('sni_number') }}">
                                        @if ($errors->has('sni_number'))
                                            <small class="text-danger">{{ $errors->first('sni_number') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="has_svlk">Memiliki SVLK:</label>
                                        <select name="has_svlk" class="form-control">
                                            <option value="" disabled {{ old('has_svlk') ? '' : 'selected' }}>Pilih status SVLK</option>
                                            <option value="yes" {{ old('has_svlk') == 'yes' ? 'selected' : '' }}>Ya</option>
                                            <option value="no" {{ old('has_svlk') == 'no' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                        @if ($errors->has('has_svlk'))
                                            <small class="text-danger">{{ $errors->first('has_svlk') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_type">Jenis Product:</label>
                                        <select name="product_type" class="form-control">
                                            <option value="" disabled {{ old('product_type') ? '' : 'selected' }}>Pilih jenis Product</option>
                                            <option value="PDN" {{ old('product_type') == 'PDN' ? 'selected' : '' }}>PDN</option>
                                            <option value="Import" {{ old('product_type') == 'Import' ? 'selected' : '' }}>Impor</option>
                                        </select>
                                        @if ($errors->has('product_type'))
                                            <small class="text-danger">{{ $errors->first('product_type') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="function">Fungsi:</label>
                                        <input type="text" name="function" class="form-control" value="{{ old('function') }}">
                                        @if ($errors->has('function'))
                                            <small class="text-danger">{{ $errors->first('function') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tool_type">Tipe Barang:</label>
                                    <input type="text" name="tool_type" class="form-control" value="{{ old('tool_type') }}">
                                    @if ($errors->has('tool_type'))
                                        <small class="text-danger">{{ $errors->first('tool_type') }}</small>
                                    @endif
                                </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_warranty">Garansi Product:</label>
                                        <input type="text" name="product_warranty" class="form-control" value="{{ old('product_warranty') }}">
                                        @if ($errors->has('product_warranty'))
                                            <small class="text-danger">{{ $errors->first('product_warranty') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Images Tab -->
                        <div class="tab-pane fade" id="Product_lists" role="tabpanel" aria-labelledby="Product_list-tab">
                            <!-- Detail Product List -->
                            <h2 class="mt-5">Detail Product List</h2>
                            <div id="detail-list-container">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Product List</th>
                                            <th>Spesifikasi Product List</th>
                                            <th>Merk Product List</th>
                                            <th>Tipe Product List</th>
                                            <th>Jumlah Product List</th>
                                            <th>Satuan Product List</th>
                                            <th>Harga Satuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="detail-list">
                                            <td class="numbering">1</td>
                                            <td><input type="text" name="detail[name][]" class="form-control"></td>
                                            <td><textarea name="detail[specifications][]" class="form-control"></textarea></td>
                                            <td><input type="text" name="detail[brand][]" class="form-control"></td>
                                            <td><input type="text" name="detail[type][]" class="form-control"></td>
                                            <td><input type="number" name="detail[quantity][]" class="form-control"></td>
                                            <td>
                                                <select name="detail[unit][]" class="form-control">
                                                    <option value="Set">Set</option>
                                                    <option value="Paket">Paket</option>
                                                </select>
                                            </td>
                                            <td><input type="number" step="0.01" name="detail[unit_price][]" class="form-control"></td>
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
        <small>*Product, ketika berhasil ditambahkan, maka status akan otomatis menjadi "Arsip" yang artinya anda perlu merubahnya menjadi "Publish" agar muncul di halaman User</small>
        <small>*General Information adalah hal yang wajib di isi jika ingin memasukkan data Product, dan untuk "details" dan "Product List" dapat di isi belakangan.</small>
    </div>

    <script>
        document.getElementById('add-detail').addEventListener('click', function() {
            var detailListContainer = document.querySelector('#detail-list-container tbody');
            var detailList = document.createElement('tr');
            detailList.classList.add('detail-list');

            detailList.innerHTML = `
                <td class="numbering"></td> <!-- Numbering Cell -->
                <td><input type="text" name="detail[name][]" class="form-control"></td>
                <td><textarea name="detail[specifications][]" class="form-control"></textarea></td>
                <td><input type="text" name="detail[brand][]" class="form-control"></td>
                <td><input type="text" name="detail[type][]" class="form-control"></td>
                <td><input type="number" name="detail[quantity][]" class="form-control"></td>
                <td>
                    <select name="detail[unit][]" class="form-control">
                        <option value="Set">Set</option>
                        <option value="Paket">Paket</option>
                    </select>
                </td>
                <td><input type="number" step="0.01" name="detail[unit_price][]" class="form-control"></td>
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
    $(document).ready(function () {
        function loadSubcategories(categoryId, selectedSubcategoryId = null) {
            if (categoryId) {
                $.ajax({
                    url: '{{ url("get-subcategories") }}/' + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#subcategory_id').empty();
                        if (data.length > 0) {
                            $('#subcategory_id').append('<option value="">Pilih Sub kategori Produk</option>');
                            $.each(data, function (key, value) {
                                $('#subcategory_id').append('<option value="' + value.id + '"' + (selectedSubcategoryId == value.id ? ' selected' : '') + '>' + value.name + '</option>');
                            });
                        } else {
                            $('#subcategory_id').append('<option value="" disabled>Kategori ini belum memiliki subkategori</option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    }
                });
            } else {
                $('#subcategory_id').empty();
                $('#subcategory_id').append('<option value="">Pilih Sub kategori Produk</option>');
            }
        }

        // Panggil fungsi loadSubcategories saat kategori berubah
        $('#category_id').on('change', function () {
            var categoryId = $(this).val();
            loadSubcategories(categoryId);
        });

        // Cek jika ada nilai old untuk category_id dan subcategory_id
        @if(old('category_id'))
            loadSubcategories('{{ old('category_id') }}', '{{ old('subcategory_id') }}');
        @endif
    });
</script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = ['general', 'details', 'Product_lists'];
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
    const requiredFields = ['name', 'price', 'category_id', 'subcategory_id','images[]'];
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

<script>
    function formatNumber(input) {
        // Menghapus karakter selain angka
        let value = input.value.replace(/[^0-9]/g, '');
        
        // Menambahkan titik setiap 3 angka
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Fungsi untuk menghapus format titik sebelum submit
    function removeFormatAndSubmit(form) {
        const priceInput = document.getElementById('price');

        // Menghapus semua titik untuk mengembalikan ke format numerik
        priceInput.value = priceInput.value.replace(/\./g, '');

        // Kirim form
        form.submit();
    }
</script>



@endsection
