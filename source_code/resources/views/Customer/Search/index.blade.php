@extends('layouts.Customer.master')

@section('content')
    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">

                        


                        <div class="sidebar__item">
                            <h4 style="color:#42378C;">{{ __('messages.price_range') }}</h4>
                            <form action="{{ url()->current() }}" method="GET">
                                <!-- Hidden field for preserving the search query -->
                                <input type="hidden" name="query" value="{{ request('query') }}">
                            
                                <div class="price-range1">
                                    <div class="form-group">
                                        <label for="min_price">{{ __('messages.min_price') }}</label>
                                        <input type="text" name="min_price" id="min_price" placeholder="Min"
                                               value="{{ request('min_price') }}" class="form-control input-range" oninput="formatInput(this);">
                                    </div>
                                    <div class="form-group">
                                        <label for="max_price" class="mt-2">{{ __('messages.max_price') }}</label>
                                        <input type="text" name="max_price" id="max_price" placeholder="Max" value="{{ request('max_price') }}" class="form-control input-range" oninput="formatInput(this);">
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">{{ __('messages.apply_filter') }}</button>
                                    <button type="button" class="btn btn-secondary mt-3" onclick="resetFields()" title="{{ __('messages.refresh') }}">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
    
                        <script>
                            function formatInput(input) {
                                let value = input.value;
                                let numericValue = value.replace(/[^0-9]/g, '');
                                let formatted = numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                input.value = formatted;
                            }
    
                            function resetFields() {
                                var url = new URL(window.location.href);
                                url.searchParams.delete('min_price');
                                url.searchParams.delete('max_price');
                                window.location.href = url.toString();
                            }
                        </script>
    
    
    
                        <style>
                            .sidebar__item {
                                padding: 20px;
                                background-color: #f7f7f7;
                                border-radius: 10px;
                                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                                margin-bottom: 20px;
                            }
    
                            .sidebar__item h4 {
                                font-size: 18px;
                                margin-bottom: 15px;
                                font-weight: bold;
                            }
    
                            .price-range1 .form-group {
                                margin-bottom: 15px;
                            }
    
                            .price-range1 input {
                                width: 100%;
                                padding: 10px;
                                border: 1px solid #ddd;
                                border-radius: 5px;
                                font-size: 14px;
                                background-color: #fff;
                                transition: border-color 0.3s ease-in-out;
                            }
    
                            .price-range1 input:focus {
                                border-color: #42378C;
                                outline: none;
                                box-shadow: 0 0 5px rgba(66, 55, 140, 0.3);
                            }
    
                            .price-range1 label {
                                font-size: 14px;
                                font-weight: 600;
                                color: #555;
                                margin-bottom: 5px;
                            }
    
                            .btn-primary {
                                background-color: #42378C;
                                border-color: #42378C;
                                color: #fff;
                                font-weight: bold;
                                padding: 10px 20px;
                                border-radius: 5px;
                                transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
                            }
    
                            .btn-primary:hover {
                                background-color: #2d2777;
                                box-shadow: 0 4px 10px rgba(66, 55, 140, 0.3);
                            }
    
                            .btn-block {
                                width: 100%;
                            }
    
                            /* Add some padding between sections */
                            .sidebar__item + .sidebar__item {
                                margin-top: 20px;
                            }
    
                            .btn-secondary {
                                background-color: #6c757d; /* default secondary color */
                                border-color: #6c757d;
                                color: #fff;
                                font-weight: bold;
                                padding: 10px 20px;
                                border-radius: 5px;
                                transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
                            }
    
                            .btn-secondary:hover {
                                background-color: #5a6268; /* slightly darker shade for hover */
                                box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3); /* add shadow */
                            }
                        </style>

                        <div class="sidebar__item">
                            <h4 style="color:#416bbf;">Kategori</h4>
                            <ul>
                                @foreach ($kategori as $kategoris)
                                    <li><a href="{{ route('shop.category', $kategoris->id) }}">{{ \Illuminate\Support\Str::limit($kategoris->nama, 25, '...') }}</a>
                                    </li>
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
