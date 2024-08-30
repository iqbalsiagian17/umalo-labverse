@extends('layouts.customer.master')

@section('content')
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4 style="color: #416bbf;">{{ __('messages.komoditas') }}</h4>
                        <ul>
                            @foreach ($komoditas as $komoditasi)
                                <li><a href="#">{{ $komoditasi->nama }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="sidebar__item">
                        <h4 style="color:#416bbf;">{{ __('messages.kategori') }}</h4>
                        <ul>
                            @foreach ($kategori as $kategoris)
                                <li>
                                    <a href="{{ route('shop.category.discounted', $kategoris->id) }}">
                                        {{ $kategoris->nama }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                
                <!-- Countdown Timer Begin -->
                @if($bigSale)
                <div class="countdown__timer">
                    <div class="col-lg-12 text-center">
                        <div class="row clock-wrap" style="gap: 10px">
                            <div class="col clockinner1 clockinner"
                                style="border-radius: 20px; background-color: rgb(224, 224, 224);">
                                <h1 id="days" class="days" style="color: black;">00</h1>
                                <span class="smalltext" style="color: black;">{{ __('messages.days') }}</span>
                            </div>
                            <div class="col clockinner1 clockinner"
                                style="border-radius: 20px; background-color: rgb(224, 224, 224);">
                                <h1 id="hours" class="hours" style="color: black;">00</h1>
                                <span class="smalltext" style="color: black;">{{ __('messages.hours') }}</span>
                            </div>
                            <div class="col clockinner1 clockinner"
                                style="border-radius: 20px; background-color: rgb(224, 224, 224);">
                                <h1 id="minutes" class="minutes" style="color: black;">00</h1>
                                <span class="smalltext" style="color: black;">{{ __('messages.minutes') }}</span>
                            </div>
                            <div class="col clockinner1 clockinner"
                                style="border-radius: 20px; background-color: rgb(224, 224, 224);">
                                <h1 id="seconds" class="seconds" style="color: black;">00</h1>
                                <span class="smalltext" style="color: black;">{{ __('messages.seconds') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Countdown Timer End -->

                <div class="filter__item">
                    <div class="row">
                        <!-- Sort and Filter Section -->
                        <!-- ... -->
                    </div>
                </div>

                <div class="row">
                    @foreach($products as $product)
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                @php
                                    $imagePath = $product->images->isNotEmpty()
                                        ? $product->images->first()->gambar
                                        : 'path/to/default/image.jpg';
                                @endphp
                                <div class="product__item__pic"
                                    style="background-image: url('{{ asset($imagePath) }}') ; border-radius: 10px;">
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
                                    <h6><a href="{{ route('produk_customer.user.show', $product->id) }}">{{ \Illuminate\Support\Str::limit($product->nama, 30, '...') }}</a>
                                    </h6>
                                    <span style="text-decoration: line-through; color: #ff0000;">
                                        <b>Rp{{ number_format($product->harga_tayang, 0, ',', '.') }}</b>
                                    </span>
                                    <br>
                                    <h5>Rp{{ number_format($product->pivot->harga_diskon, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Notification for adding to cart -->
<div id="cart-notification" class="cart-notification" style="display: none;">
    <i class="fa fa-check notification-icon"></i>
    <span class="notification-text">{{ __('messages.added_to_cart') }}</span>
</div>

<!-- CSS for Notification -->
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
    .countdown__timer {
        margin-bottom: 20px;
    }
    .clockinner1 {
        display: inline-block;
        font-size: 30px;
        margin: 0 10px;
    }
</style>

<!-- AJAX for Add to Cart -->
<script>
    document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var productId = this.dataset.id;
            var token = '{{ csrf_token() }}';

            fetch('{{ route('cart.add', '') }}/' + productId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    quantity: 1 // Default quantity, you can customize this if needed
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Display notification
                    var notification = document.getElementById('cart-notification');
                    notification.style.display = 'flex';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 3000);  // Notification disappears after 3 seconds
                } else {
                    alert('Failed to add product to cart: ' + (data.message || 'Unknown error.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to add product to cart!');
            });
        });
    });

    // Countdown Timer Script
    @if($bigSale)
    function startCountdown(endTime) {
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(countdownInterval);
                document.getElementById('days').textContent = '00';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }

        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown();
    }

    const bigSaleEndTime = new Date("{{ date('Y-m-d\TH:i:s', strtotime($bigSale->berakhir)) }}").getTime();
    startCountdown(bigSaleEndTime);
    @endif
</script>
@endsection
