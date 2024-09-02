@extends('layouts.customer.master')

@section('content')
    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4 style="color: #42378C;">{{ __('messages.komoditas') }}</h4>
                            <ul>
                                @foreach ($komoditas as $komoditasi)
                                    <li><a href="#">{{ $komoditasi->nama }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="sidebar__item">
                            <h4 style="color:#42378C;">{{ __('messages.kategori') }}</h4>
                            <ul>
                                @foreach ($kategori as $kategoris)
                                    <li><a href="{{ route('shop.category', $kategoris->id) }}">{{ \Illuminate\Support\Str::limit($kategoris->nama, 25, '...') }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="sidebar__item">
                            <h4 style="color:#42378C;">{{ __('Rating') }}</h4>
                            <ul>
                                @for ($i = 5; $i >= 1; $i--)
                                    <li>
                                        <a href="{{ route('shop.rating', $i) }}" class="rating-link">
                                            @for ($j = 1; $j <= $i; $j++)
                                                <i class="fa fa-star star-colored"></i>
                                            @endfor
                                        </a>
                                    </li>
                                @endfor
                            </ul>
                            <style>
                                .star-colored {
                                    color: #ffc107; /* Bootstrap yellow color for stars */
                                    margin-right: 2px; /* Optional: space between stars */
                                }
                            
                                .rating-link {
                                    text-decoration: none; /* Remove underline from links */
                                    color: #333; /* Default text color */
                                }
                            
                                .rating-link:hover .star-colored {
                                    color: #ff9800; /* Darker yellow on hover */
                                }
                            
                                .rating-link:hover {
                                    color: #000; /* Darker text color on hover */
                                }
                            </style>
                                                        
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-9 col-md-7">

                    <div class="filter__item">
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <span>{{ __('messages.sort_by') }}</span>
                                    <select id="sort-by" onchange="sortProducts()">
                                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>{{ __('messages.default') }}</option>
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('messages.newest') }}</option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('messages.oldest') }}</option>
                                    </select>
                                </div>
                            </div>

                            <script>
                                function sortProducts() {
                                    var sortBy = document.getElementById('sort-by').value;
                                    var url = new URL(window.location.href);
                                    url.searchParams.set('sort', sortBy);
                                    window.location.href = url.toString();
                                }

                                // Optional: Menyimpan pilihan sebelumnya setelah reload
                                document.addEventListener('DOMContentLoaded', function() {
                                    var urlParams = new URLSearchParams(window.location.search);
                                    var sortBy = urlParams.get('sort') || 'default';
                                    document.getElementById('sort-by').value = sortBy;
                                });
                            </script>

                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <h6><span>{{ $productCount }}</span> {{ __('messages.produk_ditemukan') }}</h6>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-3">
                                <div class="filter__option">
                                    <span class="icon_grid-2x2"></span>
                                    <span class="icon_ul"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($produk as $product)
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    @php
                                        $imagePath = $product->images->isNotEmpty()
                                            ? $product->images->first()->gambar
                                            : 'path/to/default/image.jpg';
                                    @endphp
                                    <div class="product__item__pic"
                                        style="background-image: url('{{ asset($imagePath) }}');">
                                        @if ($product->nego === 'ya')
                                            <span class="nego-badge">{{ __('messages.bisa_nego') }}</span>
                                        @endif
                                        <ul class="product__item__pic__hover">
                                            <li><a href="{{ route('produk_customer.user.show', $product->id) }}"><i
                                                        class="fa fa-info-circle"></i></a></li>
                                            @auth
                                                <!-- Jika pengguna sudah login -->
                                                <li><a href="#" class="add-to-cart-btn" data-id="{{ $product->id }}"><i
                                                            class="fa fa-shopping-cart"></i></a></li>
                                            @else
                                                <!-- Jika pengguna belum login -->
                                                <li><a href="{{ route('login') }}"><i class="fa fa-shopping-cart"></i></a></li>
                                            @endauth
                                        </ul>
                                    </div>
                                    <div class="product__item__text">
                                        <h6><a href="{{ route('produk_customer.user.show', $product->id) }}">{{ \Illuminate\Support\Str::limit($product->nama, 20, '...') }}</a>
                                        </h6>
                                        <h5>Rp{{ number_format($product->harga_tayang, 2) }}</h5>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="product__pagination text-center">
                        <!-- Pagination Elements -->
                        @for ($i = 1; $i <= $produk->lastPage(); $i++)
                            @if ($i == $produk->currentPage())
                                <span class="">{{ $i }}</span>
                            @else
                                <a href="{{ $produk->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

    <style>
        /* Hide the categories list by default */
        #categoriesList {
            display: none;
        }
    </style>

    <script>
        document.getElementById('toggleCategories').addEventListener('click', function() {
            var categoriesList = document.getElementById('categoriesList');

            if (categoriesList.style.display === 'block' || categoriesList.style.display === 'block') {
                categoriesList.style.display = 'none';
            } else {
                categoriesList.style.display = 'none';
            }
        });
    </script>

    <!-- Notifikasi (Hidden by Default) -->
    <div id="cart-notification" class="cart-notification" style="display: none;">
        <div class="notification-content">
            <div class="notification-icon">&#10003;</div>
            <div class="notification-text">{{ __('messages.added_to_cart') }}</div>
        </div>
    </div>

    <!-- CSS untuk Notifikasi -->
    <style>
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

    <!-- AJAX untuk Add to Cart -->
    <script>
        document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior
                var productId = this.dataset.id;
                var token = '{{ csrf_token() }}';

                fetch('{{ route('cart.add', '') }}/' + productId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            quantity: 1 // Always add 1 quantity
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
                            // Show the notification
                            var notification = document.getElementById('cart-notification');
                            notification.style.display = 'flex';
                            setTimeout(() => {
                                notification.style.display = 'none';
                            }, 3000); // Hide the notification after 3 seconds
                        } else {
                            alert('Failed to add product to cart: ' + (data.message ||
                                'Unknown error.'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('There was an error adding the product to the cart.');
                    });
            });
        });
    </script>
@endsection
