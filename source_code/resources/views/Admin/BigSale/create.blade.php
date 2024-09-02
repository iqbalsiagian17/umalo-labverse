@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Membuat Big Sale</h2>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('bigsale.store') }}" method="POST" enctype="multipart/form-data" id="bigsaleForm">
                    @csrf

                    <!-- Step 1: Judul, Mulai, Berakhir, Status, Image -->
                    <div id="step1">
                        <div class="form-group mb-3">
                            <label for="judul"><span class="text-danger">*</span> Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required>
                            @if ($errors->has('judul'))
                                <small class="text-danger">{{ $errors->first('judul') }}</small>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="mulai"><span class="text-danger">*</span> Mulai</label>
                            <input type="datetime-local" class="form-control" id="mulai" name="mulai" value="{{ old('mulai') }}" required>
                            @if ($errors->has('mulai'))
                                <small class="text-danger">{{ $errors->first('mulai') }}</small>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="berakhir"><span class="text-danger">*</span> Berakhir</label>
                            <input type="datetime-local" class="form-control" id="berakhir" name="berakhir" value="{{ old('berakhir') }}" required>
                            @if ($errors->has('berakhir'))
                                <small class="text-danger">{{ $errors->first('berakhir') }}</small>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="status"><span class="text-danger">*</span> Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @if ($errors->has('status'))
                                <small class="text-danger">{{ $errors->first('status') }}</small>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="image" class="form-label"><span class="text-danger">*</span> Gambar:</label>
                            <input type="file" class="form-control" id="image" name="image">
                            @if ($errors->has('image'))
                                <small class="text-danger">{{ $errors->first('image') }}</small>
                            @endif
                        </div>

                        <!-- Next Button -->
                        <button type="button" class="btn btn-primary" id="nextStep">Next</button>
                    </div>

                    <!-- Step 2: Diskon Persen, Produk, Filter -->
                    <div id="step2" style="display: none;">
                        <div class="form-group mb-3">
                            <label for="diskon_persen"><span class="text-danger">*</span> Diskon Persen</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="diskon_persen" name="diskon_persen" value="{{ old('diskon_persen') }}" placeholder="Masukkan Persentase Diskon">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @if ($errors->has('diskon_persen'))
                                <small class="text-danger">{{ $errors->first('diskon_persen') }}</small>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="searchInput">Cari Produk</label>
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari produk...">
                        </div>

                        <div class="form-group mb-3">
                            <label for="categoryFilter">Filter Berdasarkan Kategori</label>
                            <select id="categoryFilter" class="form-control">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="products"><span class="text-danger">*</span> Produk</label>
                            <div class="row" id="productsList">
                                @foreach($products as $product)
                                    <div class="col-md-2 col-sm-4 mb-4 product-item" data-category="{{ $product->kategori_id }}">
                                        <div class="card h-100">
                                            <img src="{{ asset($product->images->first()->gambar ?? 'path/to/default/image.jpg') }}" class="card-img-top" alt="{{ $product->nama }}" style="height: 100px; object-fit: cover; border-radius: 10px;">
                                            <div class="card-body p-2">
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" class="form-check-input" id="product-{{ $product->id }}" name="products[{{ $product->id }}]" value="{{ $product->id }}" {{ old('products.'.$product->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label ml-2" for="product-{{ $product->id }}" style="word-wrap: break-word; white-space: normal;">
                                                        {{ $product->nama }}
                                                    </label>
                                                </div>
                                                <input type="text" disabled class="form-control mt-2 harga-diskon" data-harga-tayang="{{ $product->harga_tayang }}" name="harga_diskon[{{ $product->id }}]" placeholder="Harga Diskon" value="{{ old('harga_diskon.'.$product->id) }}">
                                                @if ($errors->has('harga_diskon.'.$product->id))
                                                    <small class="text-danger">{{ $errors->first('harga_diskon.'.$product->id) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Back and Submit Buttons -->
                        <button type="button" class="btn btn-secondary" id="prevStep">Back</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('nextStep').addEventListener('click', function() {
        document.getElementById('step1').style.display = 'none';
        document.getElementById('step2').style.display = 'block';
    });

    document.getElementById('prevStep').addEventListener('click', function() {
        document.getElementById('step2').style.display = 'none';
        document.getElementById('step1').style.display = 'block';
    });

    // Mengaktifkan/menonaktifkan input harga diskon berdasarkan checkbox
    document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            let hargaDiskonInput = this.closest('.product-item').querySelector('.harga-diskon');
            if (this.checked) {
                hargaDiskonInput.removeAttribute('disabled');
            } else {
                hargaDiskonInput.setAttribute('disabled', 'disabled');
                hargaDiskonInput.value = '';
            }
        });
    });

    // Pencarian dan filter kategori
    function filterProducts() {
        let filterText = document.getElementById('searchInput').value.toLowerCase();
        let selectedCategory = document.getElementById('categoryFilter').value;
        let productItems = document.querySelectorAll('#productsList .product-item');

        productItems.forEach(function(item) {
            let productName = item.querySelector('.form-check-label').textContent.toLowerCase();
            let productCategory = item.getAttribute('data-category');

            let matchesName = productName.includes(filterText);
            let matchesCategory = selectedCategory === '' || selectedCategory === productCategory;

            if (matchesName && matchesCategory) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', filterProducts);
    document.getElementById('categoryFilter').addEventListener('change', filterProducts);

    document.getElementById('diskon_persen').addEventListener('input', function() {
        var diskonPersen = this.value;
        var hargaDiskonFields = document.querySelectorAll('.harga-diskon');

        hargaDiskonFields.forEach(function(field) {
            var hargaTayang = parseFloat(field.getAttribute('data-harga-tayang'));
            if (diskonPersen) {
                var hargaDiskon = hargaTayang - (hargaTayang * (diskonPersen / 100));
                field.value = hargaDiskon.toFixed(2);
            } else {
                field.value = '';
            }
        });
    });
</script>

@endsection
