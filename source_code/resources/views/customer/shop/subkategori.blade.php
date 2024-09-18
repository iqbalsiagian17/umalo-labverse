@extends('layouts.Customer.master')

@section('content')

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">

                        <div class="sidebar__item">
                        <h4 style="color:#42378C;">{{ __('messages.kategori') }}</h4>
                        <ul>
                            <li><a href="{{ url('/shop') }}">{{ __('messages.all_kategori') }}</a></li> <!-- Default link to shop -->
                            @foreach ($kategori as $kategoris)
                                <li><a href="{{ route('shop.category', $kategoris->id) }}">{{ \Illuminate\Support\Str::limit($kategoris->nama, 25, '...') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="sidebar__item">
                        <h4 style="color:#42378C;">{{ __('messages.subkategori') }}</h4>
                        <ul>
                            @foreach ($subkategori as $subKategori)
                                <li><a href="{{ route('shop.subcategory', $subKategori->id) }}">{{ \Illuminate\Support\Str::limit($subKategori->nama, 40, '...') }}</a>
                                </li>
                            @endforeach
                        </ul>
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
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                            {{ __('messages.newest') }}
                                        </option>
                                        <option value="oldest" {{ request('sort') == 'oldest' || !request('sort') ? 'selected' : '' }}>
                                            {{ __('messages.oldest') }}
                                        </option>
                                        <option value="price_lowest" {{ request('sort') == 'price_lowest' ? 'selected' : '' }}>
                                            {{ __('messages.price_lowest') }}
                                        </option>
                                        <option value="price_highest" {{ request('sort') == 'price_highest' ? 'selected' : '' }}>
                                            {{ __('messages.price_highest') }}
                                        </option>
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
                            </script>

                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <h6><span>{{ $productCount }}</span> {{ __('messages.produk_ditemukan') }}</h6>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-3">
                                <div class="filter__option">
                                    @if (isset($currentSubcategory))
                                        <span style="font-size: 10px;">{{ __('messages.subkategori') }}: <b>{{ $currentSubcategory->nama }}</b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="filter__item">
                        <div class="row">
                            @forelse($produk as $product)
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        @php
                                            $imagePath = $product->images->isNotEmpty()
                                                ? $product->images->first()->gambar
                                                : 'path/to/default/image.jpg';
                                        @endphp
                                        <div class="product__item__pic"
                                            style="background-image: url('{{ asset($imagePath) }}');">
                                            <ul class="product__item__pic__hover">
                                                <li><a href="{{ route('produk_customer.user.show', $product->id) }}"><i
                                                            class="fa fa-info-circle"></i></a></li>
                                                @auth
                                                    <!-- Jika pengguna sudah login -->
                                                    <li><a href="#" class="add-to-cart-btn"
                                                            data-id="{{ $product->id }}"><i
                                                                class="fa fa-shopping-cart"></i></a></li>
                                                @else
                                                    <!-- Jika pengguna belum login -->
                                                    <li><a href="{{ route('login') }}"><i class="fa fa-shopping-cart"></i></a>
                                                    </li>
                                                @endauth
                                            </ul>
                                        </div>
                                        <div class="product__item__text">
                                            <h6><a
                                                    href="{{ route('produk_customer.user.show', $product->id) }}">{{ \Illuminate\Support\Str::limit($product->nama, 20, '...') }}</a>
                                            </h6>
                                            <h5>Rp{{ number_format($product->harga_tayang, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <p>{{ __('messages.tidak_ada_produk') }}</p>
                            @endforelse
                        </div>
                    @else
                        <!-- Section for All Products -->

                        <div class="row" id="product-list">
                            @foreach ($produk as $product)
                                <div class="col-lg-4 col-md-6 col-sm-6 product__item-container">
                                    <div class="product__item" data-href="{{ route('produk_customer.user.show', $product->id) }}">
                                        @php
                                            $imagePath = $product->images->isNotEmpty()
                                                ? $product->images->first()->gambar
                                                : 'path/to/default/image.jpg';
                                        @endphp
                                        <div class="product__item__pic"
                                            style="background-image: url('{{ asset($imagePath) }}');">
                                            <ul class="product__item__pic__hover">
                                                <li>
                                                    <a href="{{ route('produk_customer.user.show', $product->id) }}">
                                                        <i class="fa fa-info-circle"></i>
                                                    </a>
                                                </li>
                                                @auth
                                                    <li>
                                                        <a href="#" class="add-to-cart-btn" data-id="{{ $product->id }}">
                                                            <i class="fa fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a href="{{ route('login') }}">
                                                            <i class="fa fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                @endauth
                                            </ul>
                                        </div>
                                        <div class="product__item__text">
                                            <h6>
                                                <a href="{{ route('produk_customer.user.show', $product->id) }}">
                                                    {{ \Illuminate\Support\Str::limit($product->nama, 20, '...') }}
                                                </a>
                                            </h6>
                                            <h5>Rp{{ number_format($product->harga_tayang, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif


                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Get all product items
                                const productItems = document.querySelectorAll('.product__item');

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

                        <style>
                            /* Change cursor to pointer for the whole product item */
                            .product__item {
                                cursor: pointer;
                            }

                            /* Ensure buttons like Add to Cart and Info Icon are not affected by the card click */
                            .product__item__pic__hover li a {
                                z-index: 2;
                                position: relative;
                            }
                        </style>

                        <div class="product__pagination text-center">
                            <!-- Pagination Elements -->
                            @if ($produk->lastPage() > 1)
                                @for ($i = 1; $i <= $produk->lastPage(); $i++)
                                    @if ($i == $produk->currentPage())
                                        <span class="">{{ $i }}</span>
                                    @else
                                        <a href="{{ $produk->url($i) }}">{{ $i }}</a>
                                    @endif
                                @endfor
                            @endif

                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- Product Section End -->

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

    <!-- AJAX for Add to Cart -->
    <script>
        document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default action

                var productId = this.dataset.id;
                var token = '{{ csrf_token() }}';

                fetch('{{ route('cart.add', '') }}/' + productId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            quantity: 1 // Add exactly 1 quantity
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

@endsection