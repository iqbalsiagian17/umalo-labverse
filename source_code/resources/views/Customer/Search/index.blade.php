@extends('layouts.customer.master')

@section('content')
    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4 style="color: #416bbf;">Komoditas</h4>
                            <ul>
                                @foreach ($komoditas as $komoditasi)
                                    <li><a href="#">{{ $komoditasi->nama }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="sidebar__item">
                            <h4 style="color:#416bbf;">Kategori</h4>
                            <ul>
                                @foreach ($kategori as $kategoris)
                                    <li><a href="{{ route('shop.category', $kategoris->id) }}">{{ \Illuminate\Support\Str::limit($kategoris->nama, 25, '...') }}</a>
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
                                    <span>Sort By</span>
                                    <select id="sort-by" onchange="sortProducts()">
                                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Default
                                        </option>
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru
                                        </option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama
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

                                document.addEventListener('DOMContentLoaded', function() {
                                    var urlParams = new URLSearchParams(window.location.search);
                                    var sortBy = urlParams.get('sort') || 'default';
                                    document.getElementById('sort-by').value = sortBy;
                                });
                            </script>

                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <h6><span> {{ $productCount }} </span> Produk Ditemukan</h6>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-3">
                                <div class="filter__option">
                                    <h6>Produk Ditemukan dari : <span style="font-size: 1em;">{{ $query }}</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @forelse ($produk as $product)
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
                                            <span class="nego-badge">Bisa Nego</span>
                                        @endif
                                        <ul class="product__item__pic__hover">
                                            <li><a href="{{ route('produk_customer.user.show', $product->id) }}"><i
                                                        class="fa fa-info-circle"></i></a></li>
                                            @auth
                                                <li><a href="#" class="add-to-cart-btn" data-id="{{ $product->id }}"><i
                                                            class="fa fa-shopping-cart"></i></a></li>
                                            @else
                                                <li><a href="{{ route('login') }}"><i class="fa fa-shopping-cart"></i></a></li>
                                            @endauth
                                        </ul>
                                    </div>
                                    <div class="product__item__text">
                                        <h6><a href="{{ route('produk_customer.user.show', $product->id) }}">{{ \Illuminate\Support\Str::limit($product->nama, 20, '...') }}</a></h6>
                                        <h5>Rp{{ number_format($product->harga_tayang, 2) }}</h5>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-lg-12">
                                <p class="text-center">No products found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

    <!-- Optional Notification for Add to Cart -->
    <div id="cart-notification" class="cart-notification" style="display: none;">
        <div class="notification-content">
            <div class="notification-icon">&#10003;</div>
            <div class="notification-text">Produk telah ditambahkan ke keranjang belanja</div>
        </div>
    </div>

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

    <script>
        document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                var productId = this.dataset.id;
                var token = '{{ csrf_token() }}';

                fetch('{{ route('cart.add', '') }}/' + productId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            quantity: 1
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
                            var notification = document.getElementById('cart-notification');
                            notification.style.display = 'flex';
                            setTimeout(() => {
                                notification.style.display = 'none';
                            }, 3000);
                        } else {
                            alert('Failed to add product to cart: ' + (data.message || 'Unknown error.'));
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
