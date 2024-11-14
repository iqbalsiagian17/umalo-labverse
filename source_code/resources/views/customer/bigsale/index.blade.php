@extends('layouts.customer.master')
@section('content')
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4 style="color:#42378C;">{{ __('messages.Category') }}</h4>
                        <ul>
                            @foreach ($categories as $category)
                                <li>
                                    <a href="{{ route('customer.bigsale.index', ['slug' => $bigSales->slug, 'category' => $category->id]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    

                </div>
            </div>
            <div class="col-lg-9 col-md-7">

                <!-- Countdown Timer Begin -->
                @if($bigSales)
                    <div class="countdown__timer">
                        <div class="col-lg-12 text-center">
                            <!-- Big Sale Title -->
                            <h2 class="bigsale-title" style="color: #444; margin-bottom: 20px; font-weight: bold;">
                                {{ $bigSales->title }}
                            </h2>
                            
                            <!-- Countdown Timer -->
                            <div class="row clock-wrap justify-content-center" style="gap: 10px;">
                                <!-- Days Box -->
                                <div class="col clockinner1 clockinner" style="border-radius: 10px; background-color: #f5f5f5; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    <h1 id="days" class="days" style="color: #333; font-size: 1.8em; font-weight: bold;">00</h1>
                                    <span class="smalltext" style="color: #666;">{{ __('messages.days') }}</span>
                                </div>
                                <!-- Hours Box -->
                                <div class="col clockinner clockinner1" style="border-radius: 10px; background-color: #f5f5f5; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    <h1 id="hours" class="hours" style="color: #333; font-size: 1.8em; font-weight: bold;">00</h1>
                                    <span class="smalltext" style="color: #666;">{{ __('messages.hours') }}</span>
                                </div>
                                <!-- Minutes Box -->
                                <div class="col clockinner clockinner1" style="border-radius: 10px; background-color: #f5f5f5; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    <h1 id="minutes" class="minutes" style="color: #333; font-size: 1.8em; font-weight: bold;">00</h1>
                                    <span class="smalltext" style="color: #666;">{{ __('messages.minutes') }}</span>
                                </div>
                                <!-- Seconds Box -->
                                <div class="col clockinner clockinner1" style="border-radius: 10px; background-color: #f5f5f5; padding: 15px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    <h1 id="seconds" class="seconds" style="color: #333; font-size: 1.8em; font-weight: bold;">00</h1>
                                    <span class="smalltext" style="color: #666;">{{ __('messages.seconds') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            const bigSaleEndTime = new Date("{{ date('Y-m-d\\TH:i:s', strtotime($bigSales->end_time)) }}").getTime();
                            
                            function startCountdown(endTime) {
                                const countdownInterval = setInterval(function() {
                                    const now = new Date().getTime();
                                    const distance = endTime - now;

                                    if (distance < 0) {
                                        clearInterval(countdownInterval);
                                        document.getElementById("days").innerHTML = "00";
                                        document.getElementById("hours").innerHTML = "00";
                                        document.getElementById("minutes").innerHTML = "00";
                                        document.getElementById("seconds").innerHTML = "00";
                                    } else {
                                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                        document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
                                        document.getElementById("hours").innerHTML = hours < 10 ? "0" + hours : hours;
                                        document.getElementById("minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
                                        document.getElementById("seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;
                                    }
                                }, 1000);
                            }

                            startCountdown(bigSaleEndTime);
                        </script>
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
                    @foreach($bigSales->products as $product)
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item" data-href="{{ route('product.show', $product->slug) }}?source={{ Str::random(10) }}">
                                @php
                                    $imagePath = $product->images->isNotEmpty()
                                        ? $product->images->first()->images
                                        : 'path/to/default/image.jpg';
                
                                    // Calculate the Big Sale discounted price if applicable
                                    $finalPrice = $product->price;
                                    if ($bigSales->discount_amount) {
                                        // Apply a fixed discount amount
                                        $finalPrice -= $bigSales->discount_amount;
                                    } elseif ($bigSales->discount_percentage) {
                                        // Apply a percentage discount
                                        $finalPrice -= ($bigSales->discount_percentage / 100) * $product->price;
                                    }
                
                                    // Calculate the discount percentage for display purposes
                                    $discountPercentage = ($product->price - $finalPrice) / $product->price * 100;
                                @endphp
                
                                <div class="featured__item__pic"
                                    style="background-image: url('{{ asset($imagePath) }}'); background-size: cover; background-position: center; border-radius: 10px;">
                                    @if ($bigSales->discount_percentage || $bigSales->discount_amount)
                                        <span class="nego-badge bg-danger">{{ __('Diskon!!') }}</span>
                                    @endif
                                </div>
                
                                <div class="featured__item__text">
                                    <h6><a href="{{ route('product.show', $product->slug) }}?source={{ Str::random(10) }}">{{ $product->name }}</a></h6>
                                    <h5>
                                        @if ($bigSales->discount_percentage || $bigSales->discount_amount)
                                            <span style="text-decoration: line-through; color:darkgray">
                                                Rp{{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                            <span style="color:red;">
                                                ({{ round($discountPercentage) }}% Off)
                                            </span>
                                            <br>
                                            <span>
                                                Rp{{ number_format($finalPrice, 0, ',', '.') }}
                                            </span>
                                        @else
                                            @if ($product->is_price_displayed === 'yes')
                                                Rp{{ number_format($product->price, 0, ',', '.') }}
                                            @else
                                                {{ __('messages.hubungi_admin') }}
                                            @endif
                                        @endif
                                    </h5>
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
    @if($bigSales)
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

    const bigSaleEndTime = new Date("{{ date('Y-m-d\TH:i:s', strtotime($bigSales->berakhir)) }}").getTime();
    startCountdown(bigSaleEndTime);
    @endif
</script>
@endsection
