<body>
    <!-- Page Preloder -->
    {{--   <div id="preloder">
      <div class="loader"></div>
  </div> --}}

    <!-- Humberger Begin -->
    <div class="humberger__menu__overlay"></div>
    <div class="humberger__menu__wrapper">
        <div class="humberger__menu__logo">
            <a href="#"><img src="{{ asset('assets/images/logo-nobg.png') }}" alt=""></a>
        </div>
        <div class="humberger__menu__cart">
            <ul>
                @if (Auth::check())
                <li>
                    <a href="{{ route('cart.view') }}"><i class="fa fa-shopping-cart"></i></a>
                </li>
            @endif
            </ul>
        </div>
        <div class="humberger__menu__widget">
            <div class="header__top__right__language">
                <img id="language-flag" 
                        src="{{ app()->getLocale() == 'en' 
                                ? asset('kaiadmin-lite-1.2.0/assets/img/flags/england.png') 
                                : asset('kaiadmin-lite-1.2.0/assets/img/flags/id.png') }}"
                        alt="{{ app()->getLocale() == 'en' ? 'English' : 'Indonesia' }}" 
                        data-lang="{{ app()->getLocale() }}">
                    <div id="language-text">
                        @if(app()->getLocale() == 'id')
                            Bahasa
                        @else
                            English
                        @endif
                    </div>

                <span class="arrow_carrot-down"></span>
                <ul>
                    <li><a href="{{ route('lang.switch', 'id') }}">Indonesia</a></li>
                    <li><a href="{{ route('lang.switch', 'en') }}">English</a></li>
                </ul>
            </div>
            <div class="header__top__right__auth">
                @if (Auth::check())
                    <div class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" style="text-decoration: none; color: inherit;">
                            <img src="{{ Auth::user()->foto_profile ? asset(Auth::user()->foto_profile) : asset('assets/images/logo-nobg.png') }}" 
                                 alt="Avatar"
                                 style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; margin-right: 8px; border: 2px solid #ccc;">
                            {{ Str::limit(explode(' ', Auth::user()->name)[0], 10) }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('user.show') }}">
                                {{ __('messages.settings') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('order.history') }}">
                                {{ __('messages.purchase') }}                                        
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('messages.logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"><i class="fa fa-user"></i> Login</a>
                @endif
            </div>
            
        </div>
        <nav class="humberger__menu__nav mobile-menu">
            <ul>
                <li class="active"><a href="/">Home</a></li>
                <li><a href="/shop">Shop</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>
        <div class="header__top__right__social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-pinterest-p"></i></a>
        </div>
        <div class="humberger__menu__contact">
            <ul>
                <li><i class="fa fa-envelope"></i> info@labtek.id</li>
                <li>Level-Up Your Output With <LABTEK></LABTEK></li>
            </ul>
        </div>
    </div>
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-envelope"></i>info@labtek.id</li>
                                <li>Level-Up Your Output With LABTEK</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__language">
                                <img id="language-flag" 
                                        src="{{ app()->getLocale() == 'en' 
                                                ? asset('kaiadmin-lite-1.2.0/assets/img/flags/england.png') 
                                                : asset('kaiadmin-lite-1.2.0/assets/img/flags/id.png') }}"
                                        alt="{{ app()->getLocale() == 'en' ? 'English' : 'Indonesia' }}" 
                                        data-lang="{{ app()->getLocale() }}">
                                    <div id="language-text">
                                        @if(app()->getLocale() == 'id')
                                            Bahasa
                                        @else
                                            English
                                        @endif
                                    </div>

                                <span class="arrow_carrot-down"></span>
                                <ul>
                                    <li><a href="{{ route('lang.switch', 'id') }}">Indonesia</a></li>
                                    <li><a href="{{ route('lang.switch', 'en') }}">English</a></li>
                                </ul>
                            </div>
                            <div class="header__top__right__auth">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="container-fluid shadow">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <div class="header__logo text-center mb-3">
                        <a href="/"><img src="{{ asset('assets/images/logo-nobg.png') }}" alt="" style="width: 100%; height: 100px;"></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero__search mb-3">
                        <div class="hero__search__form">
                            <form action="{{ route('produk.search') }}" method="GET">
                                <input type="text" name="query" placeholder="{{ __('messages.search_product') }}" value="{{ request('query') }}">
                                <button type="submit" class="site-btn rounded">{{ __('messages.search') }}</button>
                            </form>
                        </div>
                    </div>
                </div>                
                <div class="col-lg-3">
                    <div class="header__cart mb-3">
                        <ul>
                                @if (Auth::check())
                                    <li>
                                        <a href="{{ route('cart.view') }}">
                                            <i class="fa fa-shopping-cart"></i>
                                            <span class="notification">{{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}</span>
                                        </a>
                                    </li>
                                @endif
                                @guest
                                <li>
                                    @if (Route::has('login'))
                                        <a class="site-btn rounded" href="{{ route('login') }}">
                                            {{ __('messages.login') }}
                                        </a>
                                    @endif
                                </li>
                                
                            @else
                            <li>
                                <div class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" style="text-decoration: none; color: inherit;">
                                        <!-- Avatar Gambar -->
                                    @if (Auth::check())
                                        <img src="{{ Auth::user()->foto_profile ? asset(Auth::user()->foto_profile) : asset('assets/images/logo.png') }}" 
                                             alt="Avatar"
                                             style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-right: 8px; border: 2px solid #ccc;">
                                    @else
                                        <!-- Tampilkan alternatif jika pengguna belum login -->
                                    @endif
                                    
                                    {{ Str::limit(explode(' ', Auth::user()->name)[0], 10) }}
                                </a>
                            
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('user.show') }}">
                                            {{ __('messages.settings') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('order.history') }}">
                                            {{ __('messages.purchase') }}                                        
                                        </a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('messages.logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </li>
                            
                            
                            @endguest
                        </ul>
                    </div>
                </div>
                
                <div class="humberger__open">
                    <i class="fa fa-bars"></i>
                </div>
            </div>
        </div>
        </div>
    </div>
    </header>







<!-- Bottom Navbar -->
<!-- Bottom Navbar -->
<nav class="navbar navbar-dark bg-white navbar-expand fixed-bottom d-md-none d-lg-none d-xl-none p-0 shadow">
    <ul class="navbar-nav nav-justified w-100 shadow">
        <li class="nav-item">
            <a href="/" class="nav-link text-center text-dark">
                <div class="icon-circle">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-house" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                        <path fill-rule="evenodd"
                            d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
                    </svg>
                </div>
                <span class="small d-block">{{ __('messages.home') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-center text-dark">
                <div class="icon-circle">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z" />
                        <path fill-rule="evenodd"
                            d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
                    </svg>
                </div>
                <span class="small d-block">{{ __('messages.search') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('order.history') }}" class="nav-link text-center text-dark">
                <div class="icon-circle">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-bag" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 0 0-1 1v1H3.5A1.5 1.5 0 0 0 2 5.5v8A1.5 1.5 0 0 0 3.5 15h9A1.5 1.5 0 0 0 14 13.5v-8A1.5 1.5 0 0 0 12.5 4H11V3a1 1 0 0 0-1-1H6zm4 2H6V3h4v1zm-8 2h12v8H2V6zm3-1v1h6V5H5z" />
                    </svg>
                </div>
                <span class="small d-block">{{ __('messages.purchase') }}</span>
            </a>
        </li>
        <li class="nav-item dropup">
            <a href="#" class="nav-link text-center text-dark" role="button" id="dropdownMenuProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                <div class="icon-circle">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-person" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                    </svg>
                </div>
                <span class="small d-block">Profile</span>
            </a>
            <!-- Dropup menu for profile -->
            <div class="dropdown-menu" aria-labelledby="dropdownMenuProfile">
                @auth
                    <a class="dropdown-item" href="/personal">{{ __('messages.settings') }}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('messages.logout') }}</a>
                @else
                    <a class="dropdown-item" href="{{ route('login') }}">{{ __('messages.login') }}</a>
                @endauth
            </div>
        </li>
    </ul>
</nav>

<style>
.icon-circle {
    width: 50px;
    height: 50px;
    background-color: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 5px;
}
</style>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var header = document.querySelector('.container-fluid.shadow');
            var placeholder = document.createElement('div');
            placeholder.className = 'header__placeholder';
        
            // Insert the placeholder before the header
            header.parentNode.insertBefore(placeholder, header);
        
            var headerOffset = header.offsetTop;
        
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > headerOffset) {
                    header.classList.add('header__fixed');
                    header.classList.add('header__shrink'); // Tambahkan kelas shrink saat scroll
                    placeholder.style.display = 'block';
                } else {
                    header.classList.remove('header__fixed');
                    header.classList.remove('header__shrink'); // Hapus kelas shrink saat tidak scroll
                    placeholder.style.display = 'none';
                }
            });
        });
    </script>
    
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const productId = this.dataset.productId;
            const url = `/cart/add/${productId}`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    quantity: 1 // or the quantity from an input field
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the cart notification span
                    const cartNotification = document.querySelector('.notification');
                    cartNotification.textContent = data.totalQuantity;
                } else {
                    alert(data.message);
                }
            });
        });
    });
});

    </script>
    

    <style>
    .header__fixed {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.header__placeholder {
    height: 120px; /* Adjust the height to match the height of the fixed header */
    display: none; /* Hidden by default */
}

/* Media Query for Mobile Mode */
@media (max-width: 767px) {
    .header__fixed {
        position: static;
        box-shadow: none;
    }

    .header__placeholder {
        display: none; /* Optionally, ensure the placeholder is hidden on mobile */
    }
}


    </style>
    <!-- Header Section End -->
