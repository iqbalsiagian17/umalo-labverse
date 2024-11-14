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

    <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data" onsubmit="removeFormatAndSubmit(this)">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Product</div>
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
                                <a class="nav-link" id="product-list-tab" data-bs-toggle="tab" href="#product-list" role="tab" aria-controls="product-list" aria-selected="false">Product List</a>
                            </li>
                        </ul>

                    </div>

                    <div class="tab-content" id="productFormContent">
                        <!-- General Information Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="form-group">
                                <label for="name">Nama Product:</label>
                                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                @if ($errors->has('name'))
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="is_price_displayed">Harga Ditampilkan:</label>
                                <select name="is_price_displayed" class="form-control" required>
                                    <option value="yes" {{ old('is_price_displayed', $product->is_price_displayed) == 'yes' ? 'selected' : '' }}>Ya</option>
                                    <option value="no" {{ old('is_price_displayed', $product->is_price_displayed) == 'no' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @if ($errors->has('is_price_displayed'))
                                    <small class="text-danger">{{ $errors->first('is_price_displayed') }}</small>
                                @endif
                            </div>
                            
                            
                            <div class="form-group"> <!-- Fixed typo here -->
                                <label for="status">Status Produk:</label>
                                <select name="status" class="form-control" required>
                                    <option value="publish" {{ old('status', $product->status) == 'publish' ? 'selected' : '' }}>publish</option>
                                    <option value="archive" {{ old('status', $product->status) == 'archive' ? 'selected' : '' }}>archive</option>
                                </select>
                                @if ($errors->has('status'))
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                @endif  
                            </div>

                            <div class="form-group">
                                <label for="negotiable">Status Negosiasi:</label>
                                <select name="negotiable" class="form-control" required>
                                    <option value="yes" {{ old('negotiable', $product->negotiable) == 'yes' ? 'selected' : '' }}>Ya</option>
                                    <option value="no" {{ old('negotiable', $product->negotiable) == 'no' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @if ($errors->has('negotiable'))
                                    <small class="text-danger">{{ $errors->first('negotiable') }}</small>
                                @endif
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label" for="price"><span class="text-danger">*</span> Harga Produk:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="price" class="form-control" id="price" placeholder="Harga Produk" 
                                                   value="{{ old('price', number_format($product->price, 0, '', '.')) }}" oninput="formatNumber(this)" required>
                                        </div>
                                        @if ($errors->has('price'))
                                            <small class="text-danger">{{ $errors->first('price') }}</small>
                                        @endif
                                    </div>
                                </div>
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label" for="discount_price">Harga Diskon:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="discount_price" class="form-control" id="discount_price" placeholder="Harga Diskon" 
                                                   value="{{ old('discount_price', number_format($product->discount_price, 0, '', '.')) }}" 
                                                   oninput="formatNumber(this)" {{ old('allow_discount', $product->discount_price ? true : false) ? '' : 'disabled' }}>
                                        </div>
                                        @if ($errors->has('discount_price'))
                                            <small class="text-danger">{{ $errors->first('discount_price') }}</small>
                                        @endif
                                        <small class="form-text text-muted">Jika Anda ingin mematikan harga potongan, cukup ubah jadi 0,00.</small>
                                    </div>
                            
                                    <!-- Checkbox for allowing discount input -->
                                    <div class="form-group form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="allow_discount" name="allow_discount"
                                               {{ old('allow_discount', $product->discount_price ? true : false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_discount">Izinkan Pengisian Harga Diskon</label>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const allowDiscountCheckbox = document.getElementById('allow_discount');
                                        const discountPriceInput = document.getElementById('discount_price');
                                        const priceInput = document.getElementById('price');
                                
                                        // Initialize format on page load
                                        formatNumber(priceInput);
                                        if (discountPriceInput) formatNumber(discountPriceInput);
                                
                                        // Toggle the discount price input based on checkbox state
                                        function toggleDiscountPriceInput() {
                                            discountPriceInput.disabled = !allowDiscountCheckbox.checked;
                                            if (!allowDiscountCheckbox.checked) {
                                                discountPriceInput.value = ''; // Clear the input if unchecked
                                            }
                                        }
                                
                                        // Initialize the state on page load
                                        toggleDiscountPriceInput();
                                
                                        // Add event listener to handle changes on the checkbox
                                        allowDiscountCheckbox.addEventListener('change', toggleDiscountPriceInput);
                                    });
                                
                                    function formatNumber(input) {
                                        // Remove any character that is not a digit
                                        let value = input.value.replace(/[^0-9]/g, '');
                                        
                                        // Add dot every 3 digits
                                        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                    }
                                
                                    // Function to remove dots before form submission
                                    function removeFormatAndSubmit(form) {
                                        const priceInput = document.getElementById('price');
                                        const discountPriceInput = document.getElementById('discount_price');
                                
                                        // Remove all dots to submit as plain numeric value
                                        priceInput.value = priceInput.value.replace(/\./g, '');
                                        if (discountPriceInput) {
                                            discountPriceInput.value = discountPriceInput.value.replace(/\./g, '');
                                        }
                                
                                        // Submit the form
                                        form.submit();
                                    }
                                </script>


                            </div>
                            
                            <div class="form-group">
                                <label for="product_specifications">Spesifikasi Product:</label>
                                <textarea name="product_specifications" id="product_specifications" class="form-control" required>
                                    {{ old('product_specifications', $product->product_specifications) }}
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
                                <label for="e_catalog_link">Link E-katalog:</label>
                                <input type="text" name="e_catalog_link" class="form-control" value="{{ old('e_catalog_link', $product->e_catalog_link) }}" required>
                                @if ($errors->has('e_catalog_link'))
                                    <small class="text-danger">{{ $errors->first('e_catalog_link') }}</small>
                                @endif
                            </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_expiration_date">Masa Berlaku Product:</label>
                                    <input type="date" name="product_expiration_date" class="form-control" value="{{ $product->product_expiration_date }}" required>
                                    @if ($errors->has('product_expiration_date'))
                                        <small class="text-danger">{{ $errors->first('product_expiration_date') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock">stock:</label>
                                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                                    @if ($errors->has('stock'))
                                        <small class="text-danger">{{ $errors->first('stock') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category:</label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">Pilih Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('category_id'))
                                        <small class="text-danger">{{ $errors->first('category_id') }}</small>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subcategory_id">Sub Category:</label>
                                    <select name="subcategory_id" id="subcategory_id" class="form-control" required>
                                        <option value="">Pilih Sub Category</option>
                                        @foreach($categories as $category)
                                            @foreach($category->subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}" data-category-id="{{ $category->id }}" 
                                                    {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                                    {{ $subcategory->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    
                                    @if ($errors->has('subcategory_id'))
                                        <small class="text-danger">{{ $errors->first('subcategory_id') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        
                        

                        <div class="form-group">
                            <label for="images">Images Product:</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple>
                        
                            <div class="mt-2 d-flex flex-wrap">
                                @foreach($product->images as $image)
                                    <div id="image-{{ $image->id }}" class="position-relative" style="margin-right: 10px;">
                                        <img src="{{ asset($image->images) }}" alt="Images Product" style="width: 100px; height: 100px;">
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

                            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                        </div>

                        <!-- Details Tab -->
                        <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="measurement_unit">Unit Pengukuran:</label>
                                        <select name="measurement_unit" class="form-control">
                                            <option value="set" {{ $product->measurement_unit == 'set' ? 'selected' : '' }}>Set</option>
                                            <option value="Package" {{ $product->measurement_unit == 'Package' ? 'selected' : '' }}>Paket</option>
                                        </select>
                                        @if ($errors->has('measurement_unit'))
                                            <small class="text-danger">{{ $errors->first('measurement_unit') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kbki_code">Kode KBLI:</label>
                                        <input type="number" name="kbki_code" class="form-control" value="{{ $product->kbki_code }}">
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
                                        <input type="number" step="0.01" name="tkdn_value" class="form-control" value="{{ $product->tkdn_value }}">
                                        @if ($errors->has('tkdn_value'))
                                            <small class="text-danger">{{ $errors->first('tkdn_value') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="brand">Merk:</label>
                                        <input type="text" name="brand" class="form-control" value="{{ $product->brand }}">
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
                                            <option value="yes" {{ $product->sni == 'yes' ? 'selected' : '' }}>Ya</option>
                                            <option value="no" {{ $product->sni == 'no' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                        @if ($errors->has('sni'))
                                            <small class="text-danger">{{ $errors->first('sni') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="provider_product_number">No Product Penyedia:</label>
                                        <input type="text" name="provider_product_number" class="form-control" value="{{ $product->provider_product_number }}">
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
                                        <input type="text" name="sni_number" class="form-control" value="{{ $product->sni_number }}">
                                        @if ($errors->has('sni_number'))
                                            <small class="text-danger">{{ $errors->first('sni_number') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="has_svlk">Memiliki SVLK:</label>
                                        <select name="has_svlk" class="form-control">
                                            <option value="yes" {{ $product->has_svlk == 'yes' ? 'selected' : '' }}>Ya</option>
                                            <option value="no" {{ $product->has_svlk == 'no' ? 'selected' : '' }}>Tidak</option>
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
                                            <option value="PDN" {{ $product->product_type == 'PDN' ? 'selected' : '' }}>PDN</option>
                                            <option value="Import" {{ $product->product_type == 'Import' ? 'selected' : '' }}>Impor</option>
                                        </select>
                                        @if ($errors->has('product_type'))
                                            <small class="text-danger">{{ $errors->first('product_type') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="function">Fungsi:</label>
                                        <input type="text" name="function" class="form-control" value="{{ $product->function }}">
                                        @if ($errors->has('function'))
                                            <small class="text-danger">{{ $errors->first('function') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tool_type">Tipe Barang:</label>
                                        <input type="text" name="tool_type" class="form-control" value="{{ $product->tool_type }}">
                                        @if ($errors->has('tool_type'))
                                            <small class="text-danger">{{ $errors->first('tool_type') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_warranty">Garansi Product:</label>
                                        <input type="text" name="product_warranty" class="form-control" value="{{ old('product_warranty', $product->product_warranty) }}">
                                        @if ($errors->has('product_warranty'))
                                            <small class="text-danger">{{ $errors->first('product_warranty') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                        </div>


                        <!-- Images Tab -->
                        <div class="tab-pane fade" id="product-list" role="tabpanel" aria-labelledby="product-list-tab">
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
                                        @foreach($product->productLists ?? [] as $detail)
                                        <tr class="detail-list">
                                                <td class="numbering">1</td>
                                                <td><input type="text" name="detail[name][]" class="form-control" value="{{ $detail->name }}"></td>
                                                <td><textarea name="detail[specifications][]" class="form-control">{{ $detail->specifications }}</textarea></td>
                                                <td><input type="text" name="detail[brand][]" class="form-control" value="{{ $detail->brand }}"></td>
                                                <td><input type="text" name="detail[type][]" class="form-control" value="{{ $detail->type }}"></td>
                                                <td><input type="number" name="detail[quantity][]" class="form-control" value="{{ $detail->quantity }}"></td>
                                                <td>
                                                    <select name="detail[unit][]" class="form-control">
                                                        <option value="Set" {{ $detail->unit == 'Set' ? 'selected' : '' }}>Set</option>
                                                        <option value="Paket" {{ $detail->unit == 'Paket' ? 'selected' : '' }}>Paket</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" step="0.01" name="detail[unit_price][]" class="form-control" value="{{ $detail->harga_satuan }}"></td>
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
            <small>*Product, ketika berhasil ditambahkan, maka status akan otomatis menjadi "Arsip" yang artinya anda perlu merubahnya menjadi "Publish" agar muncul di halaman User</small>
        </div>
    </form>

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

        // Add event listener to the initial remove buttons
        document.querySelectorAll('.remove-detail').forEach(function(button) {
            button.addEventListener('click', function() {
                this.closest('tr').remove();
                updateNumbering();
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
        // Get the hidden input field where we store deleted image IDs
        let deletedImagesInput = document.getElementById('deleted_images');
        
        // If deletedImagesInput already has some values, add a comma separator
        if (deletedImagesInput.value) {
            deletedImagesInput.value += ',' + imageId;
        } else {
            deletedImagesInput.value = imageId;
        }

        // Remove the image element from the UI
        const imageDiv = document.getElementById(`image-${imageId}`);
        if (imageDiv) {
            imageDiv.remove();
        }
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('category_id');
        const subcategorySelect = document.getElementById('subcategory_id');

        function filterSubcategories() {
            const selectedCategoryId = categorySelect.value;
            
            Array.from(subcategorySelect.options).forEach(option => {
                if (option.getAttribute('data-category-id') === selectedCategoryId || option.value === "") {
                    option.style.display = 'block'; // Show options that match the selected category or the default option
                } else {
                    option.style.display = 'none'; // Hide options that do not match
                }
            });

            // Clear the subcategory selection if the selected option is not visible
            if (!Array.from(subcategorySelect.options).some(option => option.value === subcategorySelect.value && option.style.display === 'block')) {
                subcategorySelect.value = '';
            }
        }

        // Initial filter on page load based on the current category selection
        filterSubcategories();

        // Filter subcategories whenever the category selection changes
        categorySelect.addEventListener('change', filterSubcategories);
    });
</script>
@endsection
