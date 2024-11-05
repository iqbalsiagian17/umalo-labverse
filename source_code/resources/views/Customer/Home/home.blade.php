@extends('layouts.customer.master')
@section('content')
    <!-- Hero Section Begin -->
    <section class="hero mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div id="heroCarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @if ($slider->isEmpty())
                                <!-- If no sliders are available, show a default image -->
                                <div class="carousel-item active">
                                    <div class="hero__item set-bg rounded"
                                        data-setbg="{{ asset('assets/images/slider.jpg') }}">
                                        <div class="hero__text">
                                            <span></span>
                                            <h2 class="text-white">{{ __('messages.slider_title') }}</h2><br>
                                            @php
                                            $text = __('messages.slider_desc');
                                            $formattedText = wordwrap($text, 100, "<br>\n", true);
                                            $truncatedText = strlen($formattedText) > 40 ? substr($formattedText, 0, 40) . '...' : $formattedText;
                                            @endphp
                                            <p class="text-white full-text" style="border-radius: 30px;">{!! $formattedText !!}</p>
                                            <p class="text-white truncated-text" style="border-radius: 30px; display: none;">{!! $truncatedText !!}</p>
                                        <a href="{{ route('shop') }}" class="primary-btn rounded">{{ __('messages.shop_now') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @foreach ($slider as $index => $sliders)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="hero__item set-bg rounded" data-setbg="{{ asset( $sliders->image) }}">
                                        <div class="hero__text p-5">
                                            <h2 class="text-white">{{ $sliders->deskripsi }}</h2>
                                            <a href="{{ $sliders->url }}" class="primary-btn rounded mt-5 ">{{ $sliders->tombol }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif

                            
                        </div>
                        <style>
                            .carousel-inner .hero__text h2 {
                                font-size: 48px; /* Default size */
                            }

                            .carousel-inner .hero__text p {
                                font-size: 18px; /* Default size */
                            }

                            .carousel-inner .primary-btn {
                                font-size: 16px; /* Default size */
                            }

                            /* Media Query for smaller devices (e.g., mobile phones) */
                            @media (max-width: 767px) {
                                .full-text {
                                    display: none; /* Hide full text on mobile */
                                }

                                .truncated-text {
                                    display: block; /* Show truncated text on mobile */
                                }

                                .carousel-inner .hero__text h2 {
                                    font-size: 28px; /* Smaller font size for mobile */
                                }

                                .carousel-inner .primary-btn {
                                    font-size: 14px; /* Smaller font size for mobile */
                                }
                            }



                            /* Media Query for medium-sized devices (e.g., tablets) */
                            @media (min-width: 768px) and (max-width: 991px) {
                                .carousel-inner .hero__text h2 {
                                    font-size: 36px; /* Medium font size */
                                }

                                .carousel-inner .hero__text p {
                                    font-size: 16px; /* Medium font size */
                                }

                                .carousel-inner .primary-btn {
                                    font-size: 15px; /* Medium font size */
                                }
                            }
                        </style>


                        @if ($slider->count() > 1)
                            <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
    </section>
    <!-- Hero Section End -->
    {{-- <section class="hero">
        <div class="container">
                <div class="row">
                        <div class="col-lg-12">
                            @if ($bigSale)
                                <section class="exclusive-deal-area">
                                    <div class="container-fluid">
                                        <div class="row justify-content-center align-items-center rounded"
                                            style="background: url('{{ asset($bigSale->image) }}') no-repeat center center/cover; position: relative;">
                                            <div
                                                style="position: absxolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7);">
                                            </div>
                                            <div class="col-lg-6 no-padding exclusive-left" style="position: relative; z-index: 1;">
                                                <div class="row clock_sec clockdiv" id="clockdiv">
                                                    <div class="col-lg-12 text-center"><br><br>
                                                        <h2 style="color: white;">{{ $bigSale->judul }}</h2><br>
                                                    </div>
                                                    <div class="col-lg-12 text-center">
                                                        <div class="row clock-wrap" style="gap: 10px">
                                                            <div class="col clockinner1 clockinner"
                                                                style="border-radius: 20px; background-color: rgb(255, 255, 255);">
                                                                <h1 id="days" class="days" style="color: black;">00</h1>
                                                                <span class="smalltext" style="color: black;">{{ __('messages.days') }}</span>
                                                            </div>
                                                            <div class="col clockinner clockinner1"
                                                                style="border-radius: 20px; background-color: rgb(255, 255, 255);">
                                                                <h1 id="hours" class="hours" style="color: black;">00</h1>
                                                                <span class="smalltext" style="color: black;">{{ __('messages.hours') }}</span>
                                                            </div>
                                                            <div class="col clockinner clockinner1"
                                                                style="border-radius: 20px; background-color: rgb(255, 255, 255);">
                                                                <h1 id="minutes" class="minutes" style="color: black;">00</h1>
                                                                <span class="smalltext" style="color: black;">{{ __('messages.minutes') }}</span>
                                                            </div>
                                                            <div class="col clockinner clockinner1"
                                                                style="border-radius: 20px; background-color: rgb(255, 255, 255);">
                                                                <h1 id="seconds" class="seconds" style="color: black;">00</h1>
                                                                <span class="smalltext" style="color: black;">{{ __('messages.seconds') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><br><br>
                                                <a href="{{ route('bigsale.now.index') }}" class="primary-btn text-center"
                                                    style="color: black; background-color: rgba(255, 255, 255); padding: 10px 20px; border-radius: 5px; display: block; width: fit-content; margin: 0 auto;">{{ __('messages.shop_now') }}</a><br><br>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- Exclusive deal end -->

                                <!-- Product list start -->
                                <div class="row featured__filter mt-5" id="MixItUpD27635">
                                    @foreach ($bigSale->Product as $product)
                                        @php
                                            $imagePath = $product->images->isNotEmpty()
                                                ? $product->images->first()->image
                                                : 'path/to/default/image.jpg';
                                        @endphp
                                        <div class="col-lg-3 col-md-4 col-sm-6 mix oranges fresh-meat">
                                            <div class="featured__item">
                                                <div class="featured__item__pic"
                                                    style="position: relative; background-image: url('{{ asset($imagePath) }}'); background-size: cover; background-position: center; border-radius: 10px;">
                                                    <ul class="featured__item__pic__hover">
                                                        <li><a href="{{ route('Product_customer.user.show', $product->id) }}?source={{ Str::random(10) }}"><i
                                                                    class="fa fa-info-circle"></i></a></li>

                                                        @auth
                                                            <!-- Jika pengguna sudah login -->
                                                            <li><a href="#" class="add-to-cart-btn"
                                                                    data-id="{{ $product->id }}"><i
                                                                        class="fa fa-shopping-cart"></i></a></li>
                                                        @else
                                                            <!-- Jika pengguna belum login -->
                                                            <li><a href="{{ route('login') }}"><i
                                                                        class="fa fa-shopping-cart"></i></a></li>
                                                        @endauth
                                                    </ul>
                                                </div>

                                                <div class="featured__item__text">
                                                    <h6><a
                                                            href="{{ route('Product_customer.user.show', $product->id) }}?source={{ Str::random(10) }}">{{ $product->name }}</a>
                                                    </h6>
                                                    <h5>
                                                        <span style="text-decoration: line-through; color: #ff0000;">
                                                            Rp{{ number_format($product->price, 0, ',', '.') }}
                                                        </span>
                                                        <br>
                                                        Rp{{ number_format($product->pivot->harga_diskon, 0, ',', '.') }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                                <hr>
                                <!-- Product list end -->

                                <script>
                                    function startCountdown(endTime) {
                                        let statusUpdated = false; // Add a flag to prevent multiple updates

                                        function updateCountdown() {
                                            const now = new Date().getTime();
                                            const distance = endTime - now;

                                            if (distance < 0 && !statusUpdated) {
                                                clearInterval(countdownInterval);
                                                document.getElementById('days').textContent = '00';
                                                document.getElementById('hours').textContent = '00';
                                                document.getElementById('minutes').textContent = '00';
                                                document.getElementById('seconds').textContent = '00';
                                                updateBigSaleStatus();
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

                                        function updateBigSaleStatus() {
                                            if (!statusUpdated) {
                                                statusUpdated = true; // Prevent further status updates
                                                fetch('{{ route('bigsale.updateStatus', $bigSale->id) }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    },
                                                    body: JSON.stringify({
                                                        status: 'tidak aktif'
                                                    })
                                                }).then(response => {
                                                    if (response.ok) {
                                                        console.log('Big Sale status updated to tidak aktif.');
                                                        location.reload(); // Reload the page only if status update is successful
                                                    } else {
                                                        console.error('Failed to update Big Sale status.');
                                                        statusUpdated = false; // Allow retry if update failed
                                                    }
                                                }).catch(error => {
                                                    console.error('Error:', error);
                                                    statusUpdated = false; // Allow retry if there's an error
                                                });
                                            }
                                        }

                                        const countdownInterval = setInterval(updateCountdown, 1000);
                                        updateCountdown();
                                    }

                                    const bigSaleEndTime = new Date("{{ date('Y-m-d\TH:i:s', strtotime($bigSale->berakhir)) }}").getTime();
                                    startCountdown(bigSaleEndTime);

                                </script>
                            @else
                            @endif
                        </div>
                </div>
        </div>
    </section> --}}


    @if($product->isNotEmpty())
    <section class="featured spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>{{ __('messages.Product_terbaru') }}</h2>
                    </div>
                </div>
            </div>

            <div class="row featured__filter" id="MixItUpD27635">
                @foreach ($product as $index => $item)
                @php
                $imagePath = $item->images->isNotEmpty()
                        ? $item->images->first()->images
                        : 'assets/dummy/produck1.png'; // Gambar default jika tidak ada gambar
                @endphp
                    <div class="col-lg-3 col-md-4 col-sm-6 mix oranges fresh-meat">
                        <div class="featured__item" data-href="{{ route('product.show', $item->slug) }}?source={{ Str::random(10) }}">
                            <div class="featured__item__pic"
                                style="background-image: url('{{ asset($imagePath) }}'); background-size: cover; background-position: center; border-radius: 10px;">
                                @if ($item->discount_price)
                                    <span class="nego-badge bg-danger">{{ __('Diskon!!') }}</span>
                                @endif
                            </div>

                            <div class="featured__item__text">
                                <h6><a href="{{ route('product.show', $item->slug) }}?source={{ Str::random(10) }}">{{ $item->name }}</a></h6>
                                <h5>
                                    @if ($item->discount_price)
                                                <span style="text-decoration: line-through; color:red;">
                                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                                </span>
                                                <br>
                                                <span>
                                                    Rp{{ number_format($item->discount_price, 0, ',', '.') }}
                                                </span>
                                            @else
                                                @if ($item->is_price_displayed === 'yes')
                                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                                @else
                                                    {{ __('messages.hubungi_admin') }}
                                                @endif
                                            @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-12 text-center mt-3">
                    <a href="{{ route('shop') }}" class="primary-btn rounded">{{ __('messages.selengkapnya') }}</a>
                </div>

            </div>
        </div>
    </section>
@endif




  
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
