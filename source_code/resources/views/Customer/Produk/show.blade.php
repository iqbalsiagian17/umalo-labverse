@extends('layouts.Customer.master')
@section('content')
<!-- Product Details Section Begin -->
<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__item">
                        @if($images->isNotEmpty())
                            <a href="{{ asset($images->first()->gambar) }}" data-lightbox="product-image" data-title="{{ $produk->nama }}">
                                <img class="product__details__pic__item--large"
                                    src="{{ asset($images->first()->gambar) }}" alt="{{ $produk->nama }}">
                            </a>
                        @else
                            <a href="https://via.placeholder.com/150" data-lightbox="product-image" data-title="{{ $produk->nama }}">
                                <img src="https://via.placeholder.com/150" class="img-fluid mb-2" alt="{{ $produk->nama }}">
                            </a>
                        @endif
                    </div>


                        <script>
                            const imageContainer = document.querySelector('.product__details__pic__item');
                            const image = imageContainer.querySelector('img');

                            imageContainer.addEventListener('mousemove', function(e) {
                                const rect = imageContainer.getBoundingClientRect();
                                const x = e.clientX - rect.left; // Posisi X mouse
                                const y = e.clientY - rect.top; // Posisi Y mouse

                                // Ukuran gambar yang ingin diperbesar
                                const zoomFactor = 2;

                                // Hitung posisi zoom relatif terhadap ukuran gambar
                                const xPercent = (x / rect.width) * 100;
                                const yPercent = (y / rect.height) * 100;

                                image.style.transformOrigin = `${xPercent}% ${yPercent}%`;
                                image.style.transform = `scale(${zoomFactor})`;
                            });

                            imageContainer.addEventListener('mouseleave', function() {
                                // Kembalikan gambar ke ukuran normal saat mouse keluar
                                image.style.transform = 'scale(1)';
                            });
                        </script>

                        <style>
                        .product__details__pic__item {
                            overflow: hidden;
                            position: relative;
                        }

                        .product__details__pic__item img {
                            transition: transform 0.5s ease, transform-origin 0.5s ease;
                            width: 100%;
                            display: block;
                        }
                        </style>

                        <div class="product__details__pic__slider owl-carousel">
                            @if($images->isNotEmpty())
                                @foreach ($images as $image)
                                    <img data-imgbigurl="{{ asset($image->gambar) }}"
                                        src="{{ asset($image->gambar) }}" alt="{{ $produk->nama }}">
                                @endforeach
                        @endif
                        </div>
                    </div>
                </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h3>{{ $produk->nama }}
                        <button type="button" class="btn primary-btn" data-bs-toggle="modal" data-bs-target="#shareModal" style="width: 45px; height: 45px; border-radius: 50%; padding: 0;">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </h3>
                    <div class="product__details__price">
                        <div class="product__details__price">
                            @if ($produk->harga_ditampilkan === 'ya')
                            @if ($produk->harga_potongan > 0)
                                <span style="text-decoration: line-through; color: #ff0000;">
                                    Rp{{ number_format($produk->harga_tayang, 0, ',', '.') }}
                                </span>
                                <br>
                                <span style="color: #000000;">
                                    Rp{{ number_format($produk->harga_potongan, 0, ',', '.') }}
                                </span>
                            @elseif ($bigSaleItem && $bigSaleItem->status === 'aktif')
                                <span style="text-decoration: line-through; color: #ff0000;">
                                    Rp{{ number_format($produk->harga_tayang, 0, ',', '.') }}
                                </span>
                                <br>
                                <span style="color: #000000;">
                                    Rp{{ number_format($bigSaleItem->pivot->harga_diskon, 0, ',', '.') }}
                                </span>
                            @else
                                <span style="color: #000000;">
                                    Rp{{ number_format($produk->harga_tayang, 0, ',', '.') }}
                                </span>
                            @endif
                            <i class="fa fa-exclamation-circle icon-above" data-toggle="tooltip" title="Harga yang tertera belum termasuk Pajak Pertambahan Nilai (PPN). Apabila PPN dikenakan, total harga akan menjadi Rp{{ number_format($produk->harga_tayang * (1 + $ppn->ppn / 100), 0, ',', '.') }}"></i>

                            <script>
                                $(function () {
                                    $('[data-toggle="tooltip"]').tooltip()
                                })
                            </script>

                            <style>
                                .icon-above {
                                    color: black;       /* Set the icon color to black */
                                    font-size: 12px;    /* Set the font size to make the icon smaller */
                                    vertical-align: super; /* Position the icon above the baseline of the text */
                                }
                            </style>
                        @else
                            {{ __('messages.contact_admin_for_price') }}
                        @endif


                        </div>


                    </div>


                    <div class="product__details__quantity">
                        <div class="quantity">
                            <div class="pro-qty">
                                <span class="dec qtybtn">-</span>
                                <input type="text" value="1" min="1" data-stok="{{ $produk->stok }}">
                                <span class="inc qtybtn">+</span>
                            </div>
                        </div>
                    </div>


                    @auth
                    <a href="#" class="primary-btn add-to-cart-btn" data-id="{{ $produk->id }}">{{ __('messages.add') }}</a>
                    @else
                    <a href="{{ route('login') }}" class="primary-btn add-to-cart-btn">{{ __('messages.add') }}</a>
                    @endauth
                    <a href="#" class="heart-icon" data-product-id="{{ $produk->id }}">
                        <i class="fas fa-heart {{ $isFavorite ? 'favorite' : '' }}"></i>
                    </a>

                        <style>
                        .heart-icon {
                            color: gray;
                            cursor: pointer;
                        }

                        .heart-icon .icon_heart_alt {
                            color: gray;
                            transition: color 0.3s ease; /* Smooth transition */
                        }

                        .heart-icon .favorite {
                            color: red; /* Warna merah penuh ketika menjadi favorit */
                        }

                        .heart-icon:hover .icon_heart_alt {
                            color: #ff0000; /* Hover effect */
                        }

                        </style>


                    <script>
                        $(document).on('click', '.heart-icon', function(e) {
                            e.preventDefault();
                            var productId = $(this).data('product-id'); // Get the product ID from the data attribute

                            $.ajax({
                                url: "{{ route('favorite.toggle', ':id') }}".replace(':id', productId), // Route to toggle favorite
                                method: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}"  // Pass the CSRF token for security
                                },
                                success: function(response) {
                                    showNotification(response.message, false); // Show custom notification without page reload
                                },
                                error: function(xhr) {
                                    showNotification('Something went wrong!', false); // Handle errors with custom notification
                                }
                            });
                        });

                        // Function to show custom notification
                        function showNotification(message, reload = false) {
                            $('.notification-text').text(message);
                            $('#favorite-notification').fadeIn(400).delay(2000).fadeOut(400, function() {
                                if (reload) {
                                    window.location.reload(); // Reload the page if needed
                                }
                            });
                        }
                    </script>



                    <div class="product__details__subtotal hidden">
                        <b>{{ __('messages.subtotal') }}: </b>
                        <span id="subtotal">Rp{{ number_format($produk->harga_potongan > 0 ? $produk->harga_potongan : ($bigSaleItem && $bigSaleItem->status === 'aktif' ? $bigSaleItem->pivot->harga_diskon : $produk->harga_tayang), 0, ',', '.') }}</span>
                        <style>
                            .hidden {
                                display: none;
                            }
                        </style>

                    </div>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                    const price = {{ $produk->harga_potongan > 0 ? $produk->harga_potongan : ($bigSaleItem && $bigSaleItem->status === 'aktif' ? $bigSaleItem->pivot->harga_diskon : $produk->harga_tayang) }};
                    const quantityInput = document.querySelector('.pro-qty input');
                    const subtotalElement = document.getElementById('subtotal');
                    const incrementButton = document.querySelector('.inc.qtybtn');
                    const decrementButton = document.querySelector('.dec.qtybtn');
                    const subtotalContainer = document.querySelector('.product__details__subtotal');
                    const maxStock = parseInt(quantityInput.getAttribute('data-stok')); // Dapatkan stok maksimum

                    // Fungsi untuk memperbarui subtotal
                    function updateSubtotal() {
                        let quantity = parseInt(quantityInput.value);
                        if (isNaN(quantity) || quantity < 1) {
                            quantity = 1;  // Pastikan quantity minimal 1
                            quantityInput.value = 1;
                        } else if (quantity > maxStock) {
                            quantity = maxStock; // Jangan biarkan quantity melebihi stok
                            quantityInput.value = maxStock;
                            alert('Jumlah yang diminta melebihi stok yang tersedia.');
                        }
                        const subtotal = price * quantity;
                        subtotalElement.innerHTML = 'Rp' + new Intl.NumberFormat('id-ID').format(subtotal);
                    }

                    // Fungsi untuk menampilkan subtotal setelah tombol diklik
                    function showSubtotal() {
                        subtotalContainer.classList.remove('hidden');
                    }

                    // Fungsi untuk mengubah nilai quantity
                    function incrementQuantity() {
                        let quantity = parseInt(quantityInput.value);
                        if (isNaN(quantity) || quantity < 1) {
                            quantity = 1;
                        } else if (quantity < maxStock) {
                            quantity += 1;
                        } else {
                            alert('Anda telah mencapai jumlah stok maksimum.');
                            return; // Hentikan increment jika sudah mencapai stok maksimal
                        }
                        quantityInput.value = quantity;
                        updateSubtotal();
                        showSubtotal(); // Tampilkan subtotal
                    }

                    function decrementQuantity() {
                        let quantity = parseInt(quantityInput.value);
                        if (!isNaN(quantity) && quantity > 1) {
                            quantity -= 1;
                        } else {
                            quantity = 1;  // Pastikan minimal 1
                        }
                        quantityInput.value = quantity;
                        updateSubtotal();
                        showSubtotal(); // Tampilkan subtotal
                    }

                    // Pastikan event listener hanya ditambahkan sekali
                    incrementButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        incrementQuantity();
                    });

                    decrementButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        decrementQuantity();
                    });

                    // Inisialisasi subtotal saat halaman dimuat
                    updateSubtotal();
                });

                        </script>

                            <!-- Modal -->
                            <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="shareModalLabel">Level-Up Your Output With Labverse</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Example product card -->
                                            <div class="product-card">
                                                @if($images->isNotEmpty())
                                                    <img src="{{ asset($images->first()->gambar) }}" alt="{{ $produk->nama }}" class="product-image">
                                                @else
                                                    <img src="path_to_default_image.jpg" alt="Default Image" class="product-image">
                                                @endif
                                                <div class="product-info">
                                                    <p class="product-name">{{ $produk->nama }}</p>
                                                    <p class="product-price">Rp{{ number_format($produk->harga_tayang, 0, ',', '.') }}</p>
                                                </div>
                                            </div>


                                            <p class="share-prompt">Mau bagikan lewat mana?</p>
                                            <hr>
                                            <div class="share-buttons">
                                                <!-- Social buttons -->
                                                <button type="button" class="btn share-button whatsapp-btn" data-platform="WhatsApp" onclick="share('WhatsApp')">
                                                    <i class="fab fa-whatsapp"></i>
                                                </button>
                                                <button type="button" class="btn share-button telegram-btn" data-platform="Telegram" onclick="share('Telegram')">
                                                    <i class="fab fa-telegram-plane"></i>
                                                </button>
                                                <button type="button" class="btn share-button facebook-btn" data-platform="Facebook" onclick="share('Facebook')" disabled>
                                                    <i class="fab fa-facebook-f"></i>
                                                </button>
                                                <button type="button" class="btn share-button twitter-btn" data-platform="Twitter" onclick="share('Twitter')">
                                                    <i class="fab fa-twitter"></i>
                                                </button>
                                                <button type="button" class="btn share-button instagram-btn" data-platform="Instagram" onclick="share('Instagram')" disabled>
                                                    <i class="fab fa-instagram"></i>
                                                </button>
                                                <button type="button" class="btn share-button copy-btn" onclick="copyURL()">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>



                    <style>
                        .modal-content {
                            padding: 20px;
                            border-radius: 10px;
                            background-color: #fff;
                            color: #333;
                        }

                        .modal-header {
                            border-bottom: 2px solid #dee2e6;
                            padding-bottom: 15px;
                        }

                        .modal-title {
                            font-size: 20px;
                            color: #0056b3; /* Contoh warna */
                        }

                        .btn-close {
                            color: #000;
                        }

                        .product-card {
                            display: flex;
                            align-items: center; /* Menjaga agar item tetap di tengah secara vertikal */
                            margin-bottom: 10px; /* Jarak antara card dan elemen lainnya */
                            padding: 10px; /* Padding dalam card */
                        }

                        .product-image {
                            width: 80px; /* Ukuran gambar disesuaikan */
                            height: auto; /* Menjaga rasio aspek */
                            margin-right: 15px; /* Jarak antara gambar dan teks */
                        }

                        .product-info {
                            flex-grow: 1;
                        }

                        .product-name {
                            font-weight: bold;
                            font-size: 16px; /* Sesuaikan sesuai kebutuhan */
                            margin-bottom: 5px; /* Jarak antara nama dan harga */
                        }

                        .product-price {
                            font-size: 14px; /* Sesuaikan sesuai kebutuhan */
                            color: #555; /* Warna teks harga */
                        }


                        .product-link {
                            color: #007bff;
                            font-size: 16px;
                        }

                        .share-buttons {
                            display: flex;
                            justify-content: space-around;
                            padding-top: 10px; /* Memberi padding atas */
                        }

                        .share-button {
                            width: 50px; /* Ukuran button yang lebih besar */
                            height: 50px;
                            border-radius: 50%;
                            background-color: #f8f9fa;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border: none;
                            margin-right: 5px; /* Jarak antar button */
                        }

                        .share-button i {
                            font-size: 24px; /* Ikon yang lebih besar */
                            color: #495057;
                        }

                        .share-button:hover {
                            background-color: #e2e6ea;
                            color: #0056b3; /* Efek hover */
                        }

                    </style>




                        <script>
                            function share(platform) {
                                let url = "{{ url()->current() }}";
                                let message = encodeURIComponent('Beli (" ' + '{{ $produk->nama }}' + '") dengan harga terbaik! ' + url);
                                let shareUrl;

                                switch (platform) {
                                    case 'WhatsApp':
                                        shareUrl = `https://api.whatsapp.com/send?text=${message}`;
                                        break;
                                    case 'Telegram':
                                        shareUrl = `https://telegram.me/share/url?url=${url}&text=${message}`;
                                        break;
                                    case 'Twitter':
                                        shareUrl = `https://twitter.com/intent/tweet?text=${message}&url=${url}`;
                                        break;
                                    case 'Instagram':
                                        alert('Instagram tidak mendukung langsung berbagi URL.');
                                        break;
                                    case 'Facebook':
                                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                                        break;
                                }

                                if (platform !== 'Instagram') window.open(shareUrl, '_blank');
                            }

                            function copyURL() {
                                let url = "{{ url()->current() }}";
                                navigator.clipboard.writeText(url).then(function() {
                                    alert('URL telah disalin!');
                                }, function(err) {
                                    console.error('Gagal menyalin URL: ', err);
                                });
                            }
                        </script>




                    @if ($bigSale && $bigSale->status === 'aktif')
                    <span class="nego-badge">Big Sale</span>
                    @endif

                    <ul>
                        <li><b>{{ __('messages.stock') }}</b> <span>{{ $produk->stok }}</span></li>
                        <li><b>{{ __('messages.commodity') }}</b> <span>{{ $produk->komoditas ? $produk->komoditas->nama : 'N/A' }}</span></li>
                        <li><b>{{ __('messages.category') }}</b> <span>{{ $produk->kategori ? $produk->kategori->nama : 'N/A' }}</span></li>
                        <li><b>{{ __('messages.sub_category') }}</b> <span>{{ $produk->subKategori ? $produk->subKategori->nama : 'N/A' }}</span></li>
                        <li><b>{{ __('messages.ecatalog') }}</b>
                            <span>
                                @if ($produk->link_ekatalog)
                                    @php
                                        $url = $produk->link_ekatalog;
                                        if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
                                            $url = 'http://' . $url;
                                        }
                                    @endphp

                                    <a href="{{ $url }}" target="_blank" class="ecatalog-link">{{ Str::limit($produk->link_ekatalog, 50) }}</a>
                                @else
                                    N/A
                                @endif
                            </span>
                        </li>

                        <style>
                            .ecatalog-link {
                                color: #007bff;
                                text-decoration: none;
                                transition: color 0.3s ease, text-decoration 0.3s ease;
                            }

                            .ecatalog-link:hover {
                                color: #ff0000;
                                text-decoration: underline;
                            }

                        </style>


                        <li><b>{{ __('messages.average_rating') }}</b>
                            <span>
                                @if ($averageRating && $totalRatings)
                                    <!-- Tampilkan rata-rata rating -->
                                    {{ number_format($averageRating, 1) }} / 5
                                    ({{ $totalRatings }} {{ __('messages.people') }})

                                    <!-- Tampilkan bintang berdasarkan rata-rata rating -->
                                    <span class="ml-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $averageRating)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif ($i > $averageRating && $i < $averageRating + 1)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </span>
                                @else
                                    Belum Ada Penilaian
                                @endif
                            </span>
                        </li>


                    </ul>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                               aria-selected="true">{{ __('messages.specifications') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab"
                               aria-selected="false">{{ __('messages.additional_information') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                               aria-selected="false">{{ __('messages.review') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>{{ __('messages.product_information') }}</h6>
                                <p>{!! $produk->spesifikasi_produk !!}</p>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <table class="table table-striped">
                                    <tbody>
                                        @if($produk->tipe_barang)
                                            <tr>
                                                <th scope="row"><strong>{{ __('messages.product_type') }}:</strong></th>
                                                <td>{{ $produk->tipe_barang }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->stok)
                                            <tr>
                                                <th scope="row"><strong>Stok:</strong></th>
                                                <td>{{ $produk->stok }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->masa_berlaku_produk)
                                            <tr>
                                                <th scope="row"><strong>{{ __('messages.stock') }}:</strong></th>
                                                <td>{{ $produk->masa_berlaku_produk }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->merk)
                                            <tr>
                                                <th scope="row"><strong>Merk:</strong></th>
                                                <td>{{ $produk->merk }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->no_produk_penyedia)
                                            <tr>
                                                <th scope="row"><strong>No Produk Penyedia:</strong></th>
                                                <td>{{ $produk->no_produk_penyedia }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->unit_pengukuran)
                                            <tr>
                                                <th scope="row"><strong>Unit Pengukuran:</strong></th>
                                                <td>{{ $produk->unit_pengukuran }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->jenis_produk)
                                            <tr>
                                                <th scope="row"><strong>Jenis Produk:</strong></th>
                                                <td>{{ $produk->jenis_produk }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->kode_kbli)
                                            <tr>
                                                <th scope="row"><strong>Kode KBLI:</strong></th>
                                                <td>{{ $produk->kode_kbli }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->nilai_tkdn)
                                            <tr>
                                                <th scope="row"><strong>Nilai TKDN:</strong></th>
                                                <td>{{ $produk->nilai_tkdn }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->no_sni)
                                            <tr>
                                                <th scope="row"><strong>No SNI:</strong></th>
                                                <td>{{ $produk->no_sni }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->asal_negara)
                                            <tr>
                                                <th scope="row"><strong>Asal Negara:</strong></th>
                                                <td>{{ $produk->asal_negara }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->garansi_produk)
                                            <tr>
                                                <th scope="row"><strong>Garansi Produk:</strong></th>
                                                <td>{{ $produk->garansi_produk }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->sni)
                                            <tr>
                                                <th scope="row"><strong>SNI:</strong></th>
                                                <td>{{ $produk->sni }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->uji_fungsi)
                                            <tr>
                                                <th scope="row"><strong>Uji Fungsi:</strong></th>
                                                <td>{{ $produk->uji_fungsi }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->memiliki_svlk)
                                            <tr>
                                                <th scope="row"><strong>Memiliki SVLK:</strong></th>
                                                <td>{{ $produk->memiliki_svlk }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->jenis_alat)
                                            <tr>
                                                <th scope="row"><strong>Jenis Alat:</strong></th>
                                                <td>{{ $produk->jenis_alat }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->fungsi)
                                            <tr>
                                                <th scope="row"><strong>Fungsi:</strong></th>
                                                <td>{{ $produk->fungsi }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->spesifikasi_produk)
                                            <tr>
                                                <th scope="row"><strong>Spesifikasi Produk:</strong></th>
                                                <td>{{ $produk->spesifikasi_produk }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->ramah_lingkungan !== null)
                                            <tr>
                                                <th scope="row"><strong>Ramah Lingkungan:</strong></th>
                                                <td>{{ $produk->ramah_lingkungan ? 'Ya' : 'Tidak' }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->kategori && $produk->kategori->nama)
                                            <tr>
                                                <th scope="row"><strong>Kategori:</strong></th>
                                                <td>{{ $produk->kategori->nama }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->subKategori && $produk->subKategori->nama)
                                            <tr>
                                                <th scope="row"><strong>Sub Kategori:</strong></th>
                                                <td>{{ $produk->subKategori->nama }}</td>
                                            </tr>
                                        @endif
                                        @if($produk->komoditas && $produk->komoditas->nama)
                                            <tr>
                                                <th scope="row"><strong>Komoditas:</strong></th>
                                                <td>{{ $produk->komoditas->nama }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Ulasan</h6>

                                <!-- Display existing reviews or a message if no reviews are available -->
                                @if ($produk->reviews->isNotEmpty())
                                    @foreach ($produk->reviews as $review)
                                        <div
                                            class="review-item d-flex align-items-start mb-4 p-3 shadow-sm bg-light rounded">
                                            <div class="review-avatar mr-3">
                                                <img src="{{ $review->user->foto_profile ? asset($review->user->foto_profile) : asset('assets/images/logo.png') }}"
                                                    alt="Avatar" class="rounded-circle border" width="60"
                                                    height="60" style="object-fit: cover;">
                                            </div>
                                            <div class="review-content">
                                                <h6 class="mb-1 font-weight-bold text-primary">
                                                    {{ $review->user->name }}
                                                    @if ($review->user->userDetail && $review->user->userDetail->perusahaan)
                                                        <small class="text-muted">-
                                                            {{ $review->user->userDetail->perusahaan }}</small>
                                                    @endif
                                                </h6>
                                                <div class="mb-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <p class="text-secondary mb-2">{{ $review->content }}</p>

                                               <!-- Review Images -->
                                            @if (is_array($decodedImages = json_decode($review->images, true)) && !empty($decodedImages))
                                                <div class="review-images mb-3">
                                                    @foreach ($decodedImages as $imageIndex => $image)
                                                        <a href="#" data-toggle="modal" data-target="#imageModal{{ $review->id }}-{{ $imageIndex }}">
                                                            <img src="{{ asset('storage/' . $image) }}" alt="Review Image" class="img-thumbnail mr-2 mb-2" width="100" height="100" style="object-fit: cover;">
                                                        </a>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="imageModal{{ $review->id }}-{{ $imageIndex }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $review->id }}-{{ $imageIndex }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <img src="{{ asset('storage/' . $image) }}" class="img-fluid" alt="Review Image">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                                <!-- Review Videos -->
                                                @if (is_array($decodedVideos = json_decode($review->videos, true)) && !empty($decodedVideos))
                                                    <div class="review-videos mb-3">
                                                        @foreach ($decodedVideos as $video)
                                                            <video width="40%" height="auto" controls class="mb-2">
                                                                <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        @endforeach
                                                    </div>
                                                @endif


                                                <small
                                                    class="text-muted">{{ $review->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">Belum ada ulasan untuk produk ini</p>
                                @endif

                                <!-- Review Form -->
                                @if ($order && $order->status === 'Selesai')
                                    @if ($produk->reviews->where('user_id', auth()->id())->isEmpty())
                                        <div class="card shadow-sm border-0 mb-4">
                                            <div class="card-header text-white" style="background-color: #416bbf;">
                                                <h5 class="mb-0">Tinggalkan Ulasan</h5>
                                            </div>
                                            <div class="card-body">
                                                <form action="{{ route('order.submitReview', $order->id) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group mb-3">
                                                        <label for="review">Ulasan Anda</label>
                                                        <textarea class="form-control" id="review" name="review" rows="4"
                                                            placeholder="Tulis ulasan Anda di sini..." required></textarea>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="rating">Rating</label>
                                                        <div class="star-rating d-flex align-items-center"
                                                            style="justify-content: flex-start;">
                                                            @for ($i = 5; $i >= 1; $i--)
                                                                <input type="radio" id="star{{ $i }}"
                                                                    name="rating" value="{{ $i }}"
                                                                    required>
                                                                <label for="star{{ $i }}"
                                                                    class="fa fa-star mx-1"
                                                                    style="margin: 0;"></label>
                                                            @endfor
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="review_images">Upload Foto (Opsional)</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="review_images[]"
                                                                id="review_images" class="custom-file-input"
                                                                accept="image/*" multiple>
                                                            <label class="custom-file-label" for="review_images">Pilih
                                                                file</label>
                                                        </div>
                                                        <small class="form-text text-muted">Maksimal ukuran file 2MB
                                                            per gambar.</small>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="review_videos">Upload Video (Opsional)</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="review_videos[]"
                                                                id="review_videos" class="custom-file-input"
                                                                accept="video/*" multiple>
                                                            <label class="custom-file-label" for="review_videos">Pilih
                                                                file</label>
                                                        </div>
                                                        <small class="form-text text-muted">Maksimal ukuran file 10MB
                                                            per video.</small>
                                                    </div>
                                                    <button type="submit" class="btn text-white w-100"
                                                        style="background-color: #416bbf;">Kirim Ulasan</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            Anda telah memberikan ulasan untuk produk ini.
                                        </div>
                                    @endif
                                @endif
                                    <style>
                                        /* Style for the star rating */
                                        .star-rating input[type="radio"] {
                                            display: none;
                                        }

                                        .star-rating label {
                                            font-size: 24px;
                                            color: #ddd;
                                            cursor: pointer;
                                            transition: color 0.2s;
                                        }

                                        .star-rating input[type="radio"]:checked ~ label {
                                            color: #ffc107;
                                        }

                                        .star-rating label:hover,
                                        .star-rating label:hover ~ label {
                                            color: #ffc107;
                                        }

                                        /* Style for the form fields */
                                        .form-group label {
                                            font-weight: 600;
                                            color: #333;
                                        }

                                        .custom-file-label {
                                            white-space: nowrap;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        }

                                        /* Style for the card and buttons */
                                        .card-header {
                                            font-size: 18px;
                                            font-weight: bold;
                                        }

                                        .btn-primary {
                                            background-color: #007bff;
                                            border-color: #007bff;
                                            transition: background-color 0.3s, border-color 0.3s;
                                        }

                                        .btn-primary:hover {
                                            background-color: #0056b3;
                                            border-color: #004085;
                                        }
                                        </style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Details Section End -->


    @if($produK->isNotEmpty())
        <section class="featured spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2>{{ __('messages.other_products') }} !!</h2>
                        </div>
                    </div>
                </div>

                <div class="row featured__filter" id="MixItUpD27635">
                    @foreach ($produK as $index => $item)
                        @php
                            $imagePath = $item->images->isNotEmpty()
                                ? $item->images->first()->gambar
                                : 'path/to/default/image.jpg';
                            $bigSaleItem = $item->bigSales->first(); // Ambil Big Sale pertama jika ada
                        @endphp
                        <div class="col-lg-3 col-md-4 col-sm-6 mix oranges fresh-meat">
                            <div class="featured__item" data-href="{{ route('produk_customer.user.show', $item->id) }}">
                                <div class="featured__item__pic"
                                    style="background-image: url('{{ asset($imagePath) }}'); background-size: cover; background-position: center; border-radius: 10px;">
                                @if ($bigSaleItem && $bigSaleItem->status === 'aktif')
                                    <span class="nego-badge badge-primary">Big Sale</span>
                                @endif

                                    <ul class="featured__item__pic__hover">
                                        <li><a href="{{ route('produk_customer.user.show', $item->id) }}"><i
                                                    class="fa fa-info-circle"></i></a></li>

                                        @auth
                                            <!-- Jika pengguna sudah login -->
                                            <li><a href="#" class="add-to-cart-btn" data-id="{{ $item->id }}"><i
                                                        class="fa fa-shopping-cart"></i></a></li>
                                        @else
                                            <!-- Jika pengguna belum login -->
                                            <li><a href="{{ route('login') }}"><i class="fa fa-shopping-cart"></i></a></li>
                                        @endauth
                                    </ul>
                                </div>

                                <div class="featured__item__text">
                                    <h6><a href="#">{{ $item->nama }}</a></h6>
                                    <h5>
                                        @if ($item->harga_ditampilkan === 'ya')
                                            @if($bigSaleItem && $bigSaleItem->status === 'aktif')
                                                <span style="text-decoration: line-through; color: #ff0000;">
                                                    Rp{{ number_format($item->harga_tayang, 0, ',', '.') }}
                                                </span>
                                                <br>
                                                <span style="color: #000000;">
                                                    Rp{{ number_format($bigSaleItem->pivot->harga_diskon, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span style="color: #000000;">
                                                    Rp{{ number_format($item->harga_tayang, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        @else
                                        {{ __('messages.contact_admin_for_price') }}
                                        @endif
                                    </h5>


                                </div>
                            </div>
                        </div>

                        @if ($index == 3 && $produK->count() > 4)
                            <div class="col-lg-12 text-center mt-3">
                                <a href="{{ route('shop') }}" class="primary-btn rounded">{{ __('messages.selengkapnya') }}</a>
                            </div>
                            @break
                        @endif

                    @endforeach
                </div>
            </div>
        </section>
    @endif



    <!-- Notifikasi (Hidden by Default) -->
    <div id="cart-notification" class="cart-notification" style="display: none;">
        <div class="notification-content">
            <div class="notification-icon">&#10003;</div>
        <div class="notification-text">{{ __('messages.added_to_cart') }}</div>
        </div>
    </div>

    <div id="favorite-notification" class="cart-notification" style="display: none;">
        <div class="notification-content">
            <div class="notification-icon">&#10003;</div> <!-- Change icon as needed -->
            <div class="notification-text"></div>
        </div>
    </div>


        <!-- CSS untuk Notifikasi -->
        <style>
        .star-rating {
                direction: rtl;
                font-size: 2rem;
                display: flex;
                justify-content: flex-start;
            }

            .star-rating input[type="radio"] {
                display: none;
            }

            .star-rating label {
                color: #ccc;
                cursor: pointer;
            }

            .star-rating input[type="radio"]:checked ~ label {
                color: #ffc700;
            }

            .star-rating label:hover,
            .star-rating label:hover ~ label {
                color: #ffc700;
            }

            .cart-notification {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 20px 30px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5);
                z-index: 1000;
            }

            .notification-icon {
                font-size: 30px;
                margin-right: 15px;
            }

            .notification-text {
                font-size: 18px;
            }
        </style>

        <!-- AJAX for Add to Cart -->
        <script>
            document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default action

                    var isAuthenticated = '{{ Auth::check() }}';

                    if (!isAuthenticated) {
                        // Redirect to login page if the user is not authenticated
                        window.location.href = '{{ route('login') }}';
                        return;
                    }

                    var productId = this.dataset.id;
                    var token = '{{ csrf_token() }}';
                    var quantity = document.querySelector('.pro-qty input').value; // Ambil nilai quantity dari input

                    fetch('{{ route('cart.add', '') }}/' + productId, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                quantity: quantity // Gunakan nilai quantity yang diambil dari input
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Display notification or update cart count
                                var notification = document.getElementById('cart-notification');
                                notification.style.display = 'flex';
                                setTimeout(() => {
                                    notification.style.display = 'none';
                                }, 3000); // Notification disappears after 3 seconds
                            } else {
                                alert('Failed to add product to cart: ' + (data.message ||
                                    'Unknown error.'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while adding the product to the cart.');
                        });
                });
            });
            </script>

            <script>
                document.querySelectorAll('.star-rating label').forEach(function(label) {
                label.addEventListener('mouseover', function() {
                this.classList.add('hover');
                this.previousElementSibling?.classList.add('hover');
                this.previousElementSibling?.previousElementSibling?.classList.add('hover');
                this.previousElementSibling?.previousElementSibling?.previousElementSibling?.classList.add('hover');
                this.previousElementSibling?.previousElementSibling?.previousElementSibling?.previousElementSibling?.classList.add('hover');
            });
                label.addEventListener('mouseout', function() {
                this.classList.remove('hover');
                this.previousElementSibling?.classList.remove('hover');
                this.previousElementSibling?.previousElementSibling?.classList.remove('hover');
                this.previousElementSibling?.previousElementSibling?.previousElementSibling?.classList.remove('hover');
                this.previousElementSibling?.previousElementSibling?.previousElementSibling?.previousElementSibling?.classList.remove('hover');
            });
        });

    </script>


<style>
    /* Gaya pointer untuk elemen .featured__item */
    .featured__item {
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all product items
        const productItems = document.querySelectorAll('.featured__item');

        // Add a click event listener to each product card
        productItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Check if the clicked element is not one of the interactive elements
                if (!e.target.closest('a') && !e.target.closest('li')) {
                    // If not, redirect to the product's detail page
                    window.location.href = this.getAttribute('data-href');
                }
            });
        });
    });
</script>
@endsection
