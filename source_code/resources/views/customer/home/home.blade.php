@extends('layouts.customer.master')

@section('content')

@include('customer.partials.home.welcome__messages')




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
                                            <h2 class="text-white">{{ $sliders->description }}</h2>
                                            <a href="{{ $sliders->url }}" class="primary-btn rounded mt-5 ">{{ $sliders->button }}</a>
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
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if($bigSales && $bigSales->products->isNotEmpty())
                        <section class="exclusive-deal-area">
                            <div class="container-fluid">
                                <div class="row justify-content-center align-items-center rounded"
                                     style="background: url('{{ asset($bigSales->banner) }}') no-repeat center center/cover; position: relative; border-radius: 10px;">
                                    <!-- Transparent overlay -->
                                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); border-radius: 10px;">
                                    </div>
                                    <div class="col-lg-6 no-padding exclusive-left" style="position: relative; z-index: 1;">
                                        <div class="row clock_sec clockdiv" id="clockdiv">
                                            <div class="col-lg-12 text-center">
                                                <br><br>
                                                <h2 style="color: #ffffff; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">{{ $bigSales->title }}</h2>
                                                <br>
                                            </div>
                                            <div class="col-lg-12 text-center">
                                                <div class="row clock-wrap" style="gap: 10px">
                                                    <!-- Countdown section with smooth animation and rounded styles -->
                                                    <div class="col clockinner1 clockinner" style="border-radius: 20px; background-color: rgba(255, 255, 255, 0.9); padding: 10px; animation: pulse 1s infinite;">
                                                        <h1 id="days" class="days" style="color: black;">00</h1>
                                                        <span class="smalltext" style="color: black;">{{ __('messages.days') }}</span>
                                                    </div>
                                                    <div class="col clockinner clockinner1" style="border-radius: 20px; background-color: rgba(255, 255, 255, 0.9); padding: 10px; animation: pulse 1s infinite;">
                                                        <h1 id="hours" class="hours" style="color: black;">00</h1>
                                                        <span class="smalltext" style="color: black;">{{ __('messages.hours') }}</span>
                                                    </div>
                                                    <div class="col clockinner clockinner1" style="border-radius: 20px; background-color: rgba(255, 255, 255, 0.9); padding: 10px; animation: pulse 1s infinite;">
                                                        <h1 id="minutes" class="minutes" style="color: black;">00</h1>
                                                        <span class="smalltext" style="color: black;">{{ __('messages.minutes') }}</span>
                                                    </div>
                                                    <div class="col clockinner clockinner1" style="border-radius: 20px; background-color: rgba(255, 255, 255, 0.9); padding: 10px; animation: pulse 1s infinite;">
                                                        <h1 id="seconds" class="seconds" style="color: black;">00</h1>
                                                        <span class="smalltext" style="color: black;">{{ __('messages.seconds') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br><br>
                                        <!-- Enhanced button with hover effect -->
                                        <a href="{{ route('customer.bigsale.index', ['slug' => $bigSales->slug ?? 'default-slug']) }}" class="primary-btn text-center"
                                           style="color: black; background-color: rgba(255, 255, 255, 0.9); padding: 10px 20px; border-radius: 5px; display: block; width: fit-content; margin: 0 auto; transition: background-color 0.3s ease;">
                                            {{ __('messages.shop_now') }}
                                        </a>
                                        <br><br>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <script>
                            const bigSaleEndTime = new Date("{{ date('Y-m-d\TH:i:s', strtotime($bigSales->end_time)) }}").getTime();
    
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
                        <style>
                            /* Button hover effect */
                            .primary-btn:hover {
                                background-color: rgba(255, 255, 255, 1);
                            }
    
                            /* Smooth pulse animation for countdown */
                            @keyframes pulse {
                                0% { transform: scale(1); }
                                50% { transform: scale(1.05); }
                                100% { transform: scale(1); }
                            }
                        </style>
                    @endif
                </div>
            </div>
        </div>
    </section>
    

    <section class="hero-section-bigsale">
        <div class="container-bigsale">
            <div class="row-bigsale">
                <div class="col-bigsale">
                    @if($bigSales && $bigSales->modal_image)
                    <!-- Modal with Image -->
                    <div class="modal fade" id="bigsaleModal" tabindex="-1" aria-labelledby="bigsaleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-bigsale">
                            <div class="modal-content modal-content-bigsale border-0">
                                <div class="modal-body modal-body-bigsale p-0 position-relative">
                                    <!-- Link the image to the Big Sale page -->
                                    <a href="{{ route('customer.bigsale.index', ['slug' => $bigSales->slug]) }}">
                                        <img src="{{ asset($bigSales->modal_image) }}" alt="Big Sale Banner" class="img-fluid img-bigsale">
                                    </a>
                                    <button type="button" class="btn-close btn-close-bigsale" data-bs-dismiss="modal" aria-label="Close">✕</button>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <!-- Bootstrap 5 JS Modal activation script -->
                    <script type="text/javascript">
                        document.addEventListener('DOMContentLoaded', function () {
                            var bigsaleModal = new bootstrap.Modal(document.getElementById('bigsaleModal'));
                            bigsaleModal.show();
                        });
                    </script>
                    @endif
                </div>
            </div>
        </div>
    </section>
    
    <style>
        /* Hero Section Custom Styling */
        .hero-section-bigsale .container-bigsale {
            width: 100%;
            margin: 0 auto;
        }
    
        .hero-section-bigsale .row-bigsale {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    
        .hero-section-bigsale .col-bigsale {
            position: relative;
        }
    
        /* Custom Modal Content Styling */
        .modal-content-bigsale {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            background-color: transparent; /* Removes background */
        }
    
        .modal-body-bigsale {
            padding: 0;
        }
    
        /* Modal Image Styling */
        .img-bigsale {
            width: 100%;
            height: auto; /* Maintain aspect ratio */
            object-fit: cover; /* Preserve proportions */
            border-radius: 10px; /* Rounded corners */
        }
    
        /* Close Button Styling */
        .btn-close-bigsale {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
            color: #fff; /* White icon color */
            border-radius: 50%;
            border: none;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
    
        .btn-close-bigsale:hover {
            background-color: rgba(255, 0, 0, 0.7); /* Red hover effect */
            color: #fff;
        }
    
        /* Modal Dialog Styling */
        .modal-dialog-bigsale {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
    
        
        
    
    
    
    


    @if($product->isNotEmpty()) <!-- Pastikan ini adalah koleksi -->
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
                @foreach ($product as $item)
                    @php
                        // Pastikan cek apakah images memiliki data
                        $imagePath = optional($item->images->first())->images ?? 'assets/dummy/produck1.png';
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
                                        @php
                                            $discount_percentage = (($item->price - $item->discount_price) / $item->price) * 100;
                                        @endphp
                                        <span style="text-decoration: line-through; color:darkgray">
                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                        <span style="color:red;">
                                            ({{ round($discount_percentage) }}% Off)
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
                    <a href="{{ route('shop') }}" class="btn btn-primary">{{ __('messages.selengkapnya') }}</a>
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
