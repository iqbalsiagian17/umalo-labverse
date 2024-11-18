@extends('layouts.customer.master')@section('content')
<!-- Product Details Section Begin -->

@include('customer.partials.order.order__messages')

<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__item">
                        @if($images->isNotEmpty())
                            <a href="{{ asset($images->first()->images) }}" data-lightbox="product-image" data-title="{{ $product->name }}">
                                <img class="product__details__pic__item--large"
                                    src="{{ asset($images->first()->images) }}" alt="{{ $product->name }}">
                            </a>
                        @else
                            <a href="https://via.placeholder.com/150" data-lightbox="product-image" data-title="{{ $product->name }}">
                                <img src="https://via.placeholder.com/150" class="img-fluid mb-2" alt="{{ $product->name }}">
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

                                // Ukuran images yang ingin diperbesar
                                const zoomFactor = 2;

                                // Hitung posisi zoom relatif terhadap ukuran images
                                const xPercent = (x / rect.width) * 100;
                                const yPercent = (y / rect.height) * 100;

                                image.style.transformOrigin = `${xPercent}% ${yPercent}%`;
                                image.style.transform = `scale(${zoomFactor})`;
                            });

                            imageContainer.addEventListener('mouseleave', function() {
                                // Kembalikan images ke ukuran normal saat mouse keluar
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
                                    <img data-imgbigurl="{{ asset($image->images) }}"
                                        src="{{ asset($image->images) }}" alt="{{ $product->nama }}">
                                @endforeach
                        @endif
                        </div>
                    </div>
                </div>

                <style>
                    /* Efek muted untuk produk tanpa stok */
                    .muted {
                        opacity: 0.5; /* Mengurangi opacity untuk memberi efek muted */
                        pointer-events: none; /* Menonaktifkan klik pada elemen */
                    }
                
                    .muted .product__details__text h3, 
                    .muted .product__details__price, 
                    .muted .primary-btn,
                    .muted .heart-icon {
                        color: #999999; /* Warna abu-abu untuk produk yang tidak tersedia */
                    }
                
                    .muted .primary-btn {
                        background-color: #ddd; /* Warna latar untuk tombol disabled */
                        cursor: not-allowed; /* Mengganti kursor menjadi tanda tidak bisa diklik */
                    }

                    .stock-habis {
                        display: block;
                        margin-top: 10px;
                        font-size: 18px;
                        color: #ff0000; /* Warna merah untuk pesan */
                        font-weight: bold;
                    }
                </style>


                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                    <h3>{{ $product->name }}
                    </h3>
                    <div class="product__details__price">
                        @if ($product->is_price_displayed === 'yes')
                            @php
                                // Determine the final price to display
                                $finalPrice = $product->price;
                                $isBigSaleProduct = isset($activeBigSale) && $activeBigSale->products->contains($product->id);

                                if ($isBigSaleProduct) {
                                    // Apply Big Sale discount
                                    if ($activeBigSale->discount_amount) {
                                        $finalPrice = $product->price - $activeBigSale->discount_amount;
                                    } elseif ($activeBigSale->discount_percentage) {
                                        $finalPrice = $product->price - ($activeBigSale->discount_percentage / 100) * $product->price;
                                    }
                                } elseif ($product->discount_price > 0) {
                                    // If no Big Sale, use product-specific discount price
                                    $finalPrice = $product->discount_price;
                                }

                                // Calculate discount percentage for display purposes
                                $discountPercentage = ($product->price > $finalPrice) ? round((($product->price - $finalPrice) / $product->price) * 100) : null;
                            @endphp

                            {{-- Display Price --}}
                            @if ($isBigSaleProduct)
                                <span style="text-decoration: line-through; color: #ff0000;">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <br>
                                <span style="color: #000000;">
                                    Rp{{ number_format($finalPrice, 0, ',', '.') }}
                                </span>
                                <span style="color: green;">
                                    ({{ $discountPercentage }}% Off - Big Sale!)
                                </span>
                            @elseif ($product->discount_price > 0)
                                <span style="text-decoration: line-through; color: #ff0000;">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <br>
                                <span style="color: #000000;">
                                    Rp{{ number_format($product->discount_price, 0, ',', '.') }}
                                </span>
                                <span style="color: green;">
                                    ({{ $discountPercentage }}% Off)
                                </span>
                            @else
                                <span style="color: #000000;">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            @endif
                        @else
                            {{ __('messages.contact_admin_for_price') }}
                        @endif

                    </div>
                    


                    <div class="product__details__quantity {{ $product->stock == 0 ? 'muted' : '' }}">
                        <div class="quantity">
                            <div class="pro-qty">
                                <span class="dec qtybtn">-</span>
                                <input type="text" id="quantity" value="1" min="1" data-stok="{{ $product->stock }}">
                                <span class="inc qtybtn">+</span>
                            </div>
                        </div>
                    </div>
                    
                @auth
                    <a href="#" class="primary-btn add-to-cart-btn {{ $product->stock == 0 ? 'muted' : '' }}" id="add-to-cart" data-id="{{ $product->id }}" {{ $product->stock == 0 ? 'disabled' : '' }}>
                        {{ $product->stock == 0 ? 'Stock Habis :(' : __('messages.add') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="primary-btn add-to-cart-btn {{ $product->stock == 0 ? 'muted' : '' }}" {{ $product->stock == 0 ? 'disabled' : '' }}>
                        {{ $product->stock == 0 ? 'Stock Habis :(' : __('messages.add') }}
                    </a>
                @endauth
                
                <a href="javascript:void(0)" class="heart-icon" data-product-id="{{ $product->id }}">
                    <i class="fas fa-heart {{ $isFavorite ? 'favorite' : '' }}"></i>
                </a>
                
                <a href="#" class="share-icon" data-bs-toggle="modal" data-bs-target="#shareModal">
                    <i class="fas fa-share-alt"></i>
                </a>
                
                <script>
                    $(document).ready(function() {
                        // Click event only for the heart icon to add to wishlist
                        $('.heart-icon').on('click', function() {
                            var productId = $(this).data('product-id');
                            
                            $.ajax({
                                url: '{{ route('wishlist.add') }}',
                                type: 'POST',
                                data: {
                                    product_id: productId,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    var message = response.success ? response.message : 'Product already in wishlist';
                                    $('#wishlist-message .notification-text').text(message);
                                    $('#wishlist-message').removeClass('d-none').fadeIn();
                
                                    if (response.success) {
                                        $(this).find('i').addClass('favorite');
                                    }
                                }.bind(this),
                                error: function(xhr) {
                                    $('#wishlist-message .notification-text').text('An error occurred while adding the product to your wishlist.');
                                    $('#wishlist-message').removeClass('d-none').fadeIn();
                                }
                            });
                        });
                    });
                </script>
                
                <style>
                    /* Style for both heart and share icons */
                    .heart-icon, .share-icon {
                        color: gray;
                        cursor: pointer;
                    }
                
                    .heart-icon .favorite {
                        color: red; /* Full red color when favorited */
                    }
                
                    /* Hover effect for both icons */
                    .heart-icon:hover i,
                    .share-icon:hover i {
                        color: #ff0000;
                        transition: color 0.3s ease;
                    }
                </style>
                


                    <div class="product__details__subtotal hidden">
                        <b>{{ __('messages.subtotal') }}: </b>
                        <span id="subtotal">Rp{{ number_format($product->discount_price > 0 ? $product->discount_price : $product->price, 0, ',', '.') }}</span>
                        <style>
                            .hidden {
                                display: none;
                            }
                        </style>

                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const price = {{ $product->discount_price > 0 ? $product->discount_price : $product->price }};
                            const quantityInput = document.querySelector('.pro-qty input');
                            const subtotalElement = document.getElementById('subtotal');
                            const incrementButton = document.querySelector('.inc.qtybtn');
                            const decrementButton = document.querySelector('.dec.qtybtn');
                            const subtotalContainer = document.querySelector('.product__details__subtotal');
                            const maxStock = parseInt(quantityInput.getAttribute('data-stok'));
                    
                            const addToCartButton = document.getElementById('add-to-cart');
                            const cartMessage = document.getElementById('cart-message'); // Notification element
                            let reachedMaxNotificationShown = false; // Track if notification was already shown
                    
                            // Only show notification if stock is not zero
                            const shouldShowNotification = maxStock > 0;
                    
                            // Function to show a custom notification
                            function showNotification(message) {
                                cartMessage.querySelector('.notification-text').innerText = message;
                                cartMessage.classList.remove('d-none'); // Show notification
                                setTimeout(() => {
                                    cartMessage.classList.add('d-none'); // Hide after 3 seconds
                                }, 3000);
                            }
                    
                            // Function to update subtotal
                            function updateSubtotal() {
                                let quantity = parseInt(quantityInput.value);
                                if (isNaN(quantity) || quantity < 1) {
                                    quantity = 1;
                                    quantityInput.value = 1;
                                } else if (quantity > maxStock) {
                                    quantity = maxStock;
                                    quantityInput.value = maxStock;
                    
                                    // Show notification only if stock is exceeded and allowed
                                    if (shouldShowNotification && !reachedMaxNotificationShown) {
                                        showNotification('Jumlah yang diminta melebihi stok yang tersedia.');
                                        reachedMaxNotificationShown = true; // Set flag to true
                                    }
                                }
                                const subtotal = price * quantity;
                                subtotalElement.innerHTML = 'Rp' + new Intl.NumberFormat('id-ID').format(subtotal);
                    
                                // Show subtotal container only if quantity is greater than 1
                                if (quantity > 1) {
                                    subtotalContainer.classList.remove('hidden');
                                } else {
                                    subtotalContainer.classList.add('hidden');
                                }
                            }
                    
                            // Function to increment quantity
                            function incrementQuantity() {
                                let quantity = parseInt(quantityInput.value);
                                if (quantity < maxStock) {
                                    quantity += 1;
                                    quantityInput.value = quantity;
                                    updateSubtotal();
                                    reachedMaxNotificationShown = false; // Reset flag if below max stock
                                } else {
                                    // Show notification only if max stock is exceeded and allowed
                                    if (shouldShowNotification && !reachedMaxNotificationShown) {
                                        showNotification('Anda telah mencapai jumlah stok maksimum.');
                                        reachedMaxNotificationShown = true;
                                    }
                                }
                            }
                    
                            // Function to decrement quantity
                            function decrementQuantity() {
                                let quantity = parseInt(quantityInput.value);
                                if (quantity > 1) {
                                    quantity -= 1;
                                    quantityInput.value = quantity;
                                    updateSubtotal();
                                }
                            }
                    
                            // Event listeners for increment and decrement buttons
                            incrementButton.addEventListener('click', function(e) {
                                e.preventDefault();
                                incrementQuantity();
                            });
                    
                            decrementButton.addEventListener('click', function(e) {
                                e.preventDefault();
                                decrementQuantity();
                            });
                    
                            // AJAX Add to Cart
                            addToCartButton.addEventListener('click', function(e) {
                                e.preventDefault();
                                const productId = this.getAttribute('data-id');
                                const quantity = quantityInput.value;
                    
                                fetch("{{ route('cart.add') }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        product_id: productId,
                                        quantity: quantity
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        showNotification('Product added to cart successfully!');
                                    } else if (data.error) {
                                        showNotification(data.error);
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                            });
                    
                            // Initialize subtotal on page load without showing notifications
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
                                                    <img src="{{ asset($images->first()->images) }}" alt="{{ $product->name }}" class="product-image">
                                                @else
                                                    <img src="path_to_default_image.jpg" alt="Default Image" class="product-image">
                                                @endif
                                                <div class="product-info">
                                                    <p class="product-name">{{ $product->name }}</p>
                                                    <p class="product-price">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
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
                                let message = encodeURIComponent('Beli (" ' + '{{ $product->name }}' + '") dengan harga terbaik! ' + url);
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


                    <ul>
                        <li><b>{{ __('messages.stock') }}</b> <span>{{ $product->stock }}</span></li>
                        <li><b>{{ __('messages.category') }}</b> <span>{{ $product->Category ? $product->Category->name : 'N/A' }}</span></li>
                        <li><b>{{ __('messages.sub_category') }}</b> <span>{{ $product->subCategory ? $product->subCategory->name : 'N/A' }}</span></li>
                        <li><b>{{ __('messages.ecatalog') }}</b>
                            <span>
                                @if ($product->e_catalog_link)
                                    @php
                                        $url = $product->e_catalog_link;
                                        if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
                                            $url = 'http://' . $url;
                                        }
                                    @endphp

                                    <a href="{{ $url }}" target="_blank" class="ecatalog-link">{{ Str::limit($product->e_catalog_link, 50) }}</a>
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
                            <a class="nav-link" data-toggle="tab" href="#tabs-1" role="tab"
                               aria-selected="true" id="specifications-tab">{{ __('messages.specifications') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab"
                               aria-selected="false" id="additional-info-tab">{{ __('messages.additional_information') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                               aria-selected="false" id="review-tab">{{ __('messages.review') }}</a>
                        </li>
                    </ul>

                    
                    <div class="tab-content">
                        <div class="tab-pane fade" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>{{ __('messages.product_information') }}</h6>
                                <p>{!! $product->product_specifications !!}</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tabs-2" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <table class="table table-striped">
                                    <tbody>
                                        @if($product->product_type)
                                            <tr>
                                                <th scope="row"><strong>{{ __('messages.product_type') }}:</strong></th>
                                                <td>{{ $product->product_type }}</td>
                                            </tr>
                                        @endif
                                        @if($product->stock)
                                            <tr>
                                                <th scope="row"><strong>Stok:</strong></th>
                                                <td>{{ $product->stock }}</td>
                                            </tr>
                                        @endif
                                        @if($product->product_expiration_date)
                                            <tr>
                                                <th scope="row"><strong>{{ __('messages.stock') }}:</strong></th>
                                                <td>{{ $product->product_expiration_date }}</td>
                                            </tr>
                                        @endif
                                        @if($product->brand)
                                            <tr>
                                                <th scope="row"><strong>Merk:</strong></th>
                                                <td>{{ $product->brand }}</td>
                                            </tr>
                                        @endif
                                        @if($product->provider_product_number)
                                            <tr>
                                                <th scope="row"><strong>No Product Penyedia:</strong></th>
                                                <td>{{ $product->provider_product_number }}</td>
                                            </tr>
                                        @endif
                                        @if($product->measurement_unit)
                                            <tr>
                                                <th scope="row"><strong>Unit Pengukuran:</strong></th>
                                                <td>{{ $product->measurement_unit }}</td>
                                            </tr>
                                        @endif
                                        @if($product->tool_type)
                                            <tr>
                                                <th scope="row"><strong>Jenis Product:</strong></th>
                                                <td>{{ $product->tool_type }}</td>
                                            </tr>
                                        @endif
                                        @if($product->kbki_code)
                                            <tr>
                                                <th scope="row"><strong>Kode KBLI:</strong></th>
                                                <td>{{ $product->kbki_code }}</td>
                                            </tr>
                                        @endif
                                        @if($product->tkdn_value)
                                            <tr>
                                                <th scope="row"><strong>Nilai TKDN:</strong></th>
                                                <td>{{ $product->tkdn_value }}</td>
                                            </tr>
                                        @endif
                                        @if($product->sni_number)
                                            <tr>
                                                <th scope="row"><strong>No SNI:</strong></th>
                                                <td>{{ $product->sni_number }}</td>
                                            </tr>
                                        @endif
                                        @if($product->product_warranty)
                                            <tr>
                                                <th scope="row"><strong>Garansi Product:</strong></th>
                                                <td>{{ $product->product_warranty }}</td>
                                            </tr>
                                        @endif
                                        @if($product->sni)
                                            <tr>
                                                <th scope="row"><strong>SNI:</strong></th>
                                                <td>{{ $product->sni }}</td>
                                            </tr>
                                        @endif
                                        @if($product->function_test)
                                            <tr>
                                                <th scope="row"><strong>Uji Fungsi:</strong></th>
                                                <td>{{ $product->function_test }}</td>
                                            </tr>
                                        @endif
                                        @if($product->has_svlk)
                                            <tr>
                                                <th scope="row"><strong>Memiliki SVLK:</strong></th>
                                                <td>{{ $product->has_svlk }}</td>
                                            </tr>
                                        @endif
                                        @if($product->function)
                                            <tr>
                                                <th scope="row"><strong>Fungsi:</strong></th>
                                                <td>{{ $product->function }}</td>
                                            </tr>
                                        @endif
                                        @if($product->product_specifications)
                                            <tr>
                                                <th scope="row"><strong>Spesifikasi Product:</strong></th>
                                                <td>{!! $product->product_specifications !!}</td>
                                            </tr>
                                        @endif
                                        @if($product->Category && $product->Category->name)
                                            <tr>
                                                <th scope="row"><strong>Category:</strong></th>
                                                <td>{{ $product->Category->name }}</td>
                                            </tr>
                                        @endif
                                        @if($product->subCategory && $product->subCategory->name)
                                            <tr>
                                                <th scope="row"><strong>Sub Category:</strong></th>
                                                <td>{{ $product->subCategory->name }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tabs-3" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Ulasan</h6>
                                <!-- Display existing reviews or a message if no reviews are available -->
                                @if ($product->reviews->isNotEmpty())
                                    @foreach ($product->reviews as $review)
                                        <div class="review-item d-flex align-items-start mb-4 p-3 shadow-sm bg-light rounded">
                                            <div class="review-avatar mr-3">
                                                <img src="{{ $review->user->foto_profile ? asset($review->user->foto_profile) : asset('assets/images/logo.png') }}"
                                                    alt="Avatar" class="rounded-circle border" width="60" height="60" style="object-fit: cover;">
                                            </div>
                                            <div class="review-content">
                                                <h6 class="mb-1 font-weight-bold text-primary">
                                                    {{ $review->user->name }}
                                                    @if ($review->user->userDetail && $review->user->userDetail->perusahaan)
                                                        <small class="text-muted">- {{ $review->user->userDetail->perusahaan }}</small>
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
                                                <small class="text-muted">{{ $review->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">Belum ada ulasan untuk Product ini</p>
                                @endif


                                <!-- Review Form -->
                                <div class="card shadow-sm border-0 mb-4">
                                    <div class="card-header text-white" style="background-color: #416bbf;">
                                        <h5 class="mb-0">Tinggalkan Ulasan</h5>
                                    </div>
                                    <div class="card-body">
                                        @if (!$reviewExists)
                                            @if ($deliveredOrders->isNotEmpty())
                                            <form action="{{ route('review.store', ['productId' => $product->slug]) }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="order_id" value="{{ $deliveredOrders->first()->id }}">
                                                    <input type="hidden" name="product_id" value="{{ $product->slug }}">

                                                    <div class="form-group mb-3">
                                                        <label for="rating">Ulasan Anda</label>
                                                        <textarea class="form-control" id="rating" name="comment" rows="4" placeholder="Tulis ulasan Anda di sini..." required></textarea>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="rating">Rating</label>
                                                        <div class="star-rating d-flex align-items-center" style="justify-content: flex-start;">
                                                            @for ($i = 5; $i >= 1; $i--)
                                                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                                                <label for="star{{ $i }}" class="fa fa-star mx-1" style="margin: 0;"></label>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn text-white w-100" style="background-color: #416bbf;">Kirim Ulasan</button>
                                                </form>
                                            @else
                                                <div class="alert alert-warning">
                                                    Anda belum memiliki pesanan yang diselesaikan untuk produk ini.
                                                </div>
                                            @endif
                                        @else
                                            <div class="alert alert-info">
                                                Anda telah memberikan ulasan untuk produk ini dalam pesanan ini.
                                            </div>
                                        @endif
                                    </div>
                                </div>
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

@if($relatedProducts->count() > 0)
    <section class="featured spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>{{ __('messages.other_products') }} !!</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach ($relatedProducts as $index => $item)
                    @if ($item instanceof \App\Models\Product)
                        @php
                            $imagePath = $item->images->isNotEmpty()
                                ? $item->images->first()->images
                                : 'path/to/default/image.jpg';
                        @endphp
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="featured__item" data-href="{{ route('product.show', $item->slug) }}?source={{ Str::random(10) }}">
                                <div class="featured__item__pic"
                                    style="background-image: url('{{ asset($imagePath) }}'); background-size: cover; background-position: center; border-radius: 10px;">

                                </div>

                                <div class="featured__item__text">
                                    <h6><a href="{{ route('product.show', $product->slug) }}?source={{ Str::random(10) }}">{{ $item->name }}</a></h6>
                                    <h5>
                                        @if ($item->is_price_displayed === 'yes')
                                            <span style="color: #000000;">
                                                Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </span>
                                        @else
                                            {{ __('messages.contact_admin_for_price') }}
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>

                        @if ($index == 3 && $relatedProducts->count() > 4)
                            <div class="col-lg-12 text-center mt-3">
                                <a href="{{ route('shop') }}" class="primary-btn rounded">{{ __('messages.selengkapnya') }}</a>
                            </div>
                            @break
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif



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


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Check localStorage for the active tab
        const activeTab = localStorage.getItem("activeTab");
        if (activeTab) {
            const tabLink = document.querySelector(`a[href="${activeTab}"]`);
            const tabPane = document.querySelector(activeTab);
            
            if (tabLink && tabPane) {
                // Activate the saved tab
                tabLink.classList.add("active");
                tabPane.classList.add("show", "active");
            } else {
                // If not found, activate the first tab as default
                document.querySelector('.nav-link').classList.add("active");
                document.querySelector('.tab-pane').classList.add("show", "active");
            }
        } else {
            // Default to first tab if no tab was saved
            document.querySelector('.nav-link').classList.add("active");
            document.querySelector('.tab-pane').classList.add("show", "active");
        }

        // Save active tab on click
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.addEventListener("click", function() {
                localStorage.setItem("activeTab", this.getAttribute("href"));
            });
        });
    });
</script>










<div id="cart-message" class="cart-notification d-none" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(30, 30, 30, 0.9); color: white; padding: 20px 30px; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5); z-index: 1000; font-size: 18px;">
    <div class="notification-content" style="display: flex; align-items: center; width: 100%;">
        <span class="notification-icon" style="font-size: 30px; margin-right: 15px;">🎉</span>
        <span class="notification-text" style="flex-grow: 1; font-weight: bold;">{{ __('Product.cart_message') }}</span>
        <button onclick="this.parentElement.parentElement.style.display='none'" style="background: transparent; border: none; color: white; cursor: pointer; font-size: 22px; margin-left: 15px;">&times;</button>
    </div>
</div>

<!-- Notification Message -->
<div id="wishlist-message" class="wishlist-notification d-none" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(30, 30, 30, 0.9); color: white; padding: 20px 30px; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5); z-index: 1000; font-size: 18px;">
    <div class="notification-content" style="display: flex; align-items: center; width: 100%;">
        <span class="notification-icon" style="font-size: 30px; margin-right: 15px;">🎉</span>
        <span class="notification-text" style="flex-grow: 1; font-weight: bold;">{{ __('product.wishlist_message') }}</span>
        <button onclick="this.parentElement.parentElement.style.display='none'" style="background: transparent; border: none; color: white; cursor: pointer; font-size: 22px; margin-left: 15px;">&times;</button>
    </div>
</div>

@endsection
