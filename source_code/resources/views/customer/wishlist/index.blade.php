@extends('layouts.customer.master')
@section('content')

<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-5">
                @if($wishlistItems->isEmpty())
                    <div class="card mb-3 mt-4 shadow rounded border-0 h-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 300px;">
                            <h5 class="mb-1">{{ __('messages.favorites_empty') }}</h5>
                            <a href="{{ route('shop') }}" class="btn text-white mt-3" style="background-color: #42378C;">{{ __('messages.shop_now') }}</a>
                        </div>
                    </div>
                @else
                    <div class="shoping__cart__table">
                            <table class="table table-borderless wishlist-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ __('messages.product') }}</th>
                                        <th class="text-center">{{ __('messages.image') }}</th>
                                        <th class="text-center">{{ __('messages.price') }}</th>
                                        <th class="text-center">{{ __('messages.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wishlistItems as $wishlist)
                                        <tr>
                                            <td class="text-center align-middle">
                                                {{ $wishlist->product->name ?? 'Product Not Found' }}
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($wishlist->product && $wishlist->product->images->isNotEmpty())
                                                    <img src="{{ asset($wishlist->product->images->first()->images) }}" alt="{{ $wishlist->product->name }}" class="wishlist-image">
                                                @else
                                                    <img src="{{ asset('path/to/default-image.png') }}" alt="No Image" class="wishlist-image">
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                Rp {{ number_format($wishlist->product->price ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center align-middle">
                                                <a href="{{ route('Product_customer.user.show', $wishlist->product->slug) }}" class="btn btn-link text-primary" title="{{ __('messages.view_details') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-link text-success move-to-cart" data-product-id="{{ $wishlist->product->id }}" title="{{ __('messages.move_to_cart') }}">
                                                    <i class="fa fa-shopping-cart"></i>
                                                </a>
                                                <a href="#" class="btn btn-link text-danger remove-favorite" data-product-id="{{ $wishlist->product->id }}" title="{{ __('messages.remove') }}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Notification for cart actions -->
<div id="cart-notification" class="cart-notification" style="display: none;">
    <div class="notification-content">
        <div class="notification-icon">&#10003;</div>
        <div class="notification-text">{{ __('messages.added_to_cart') }}</div>
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

    .wishlist-table th, .wishlist-table td {
                                    border-top: none;
                                }
                                
                                .wishlist-table th {
                                    font-weight: normal;
                                    color: #555;
                                    font-size: 14px;
                                    text-transform: uppercase;
                                }
                            
                                .wishlist-table td {
                                    font-size: 14px;
                                    color: #333;
                                }
                            
                                .wishlist-image {
                                    max-width: 80px;
                                    border-radius: 5px;
                                }
                            
                                .btn-link {
                                    padding: 0;
                                    font-size: 16px;
                                }
                            
                                .btn-link i {
                                    transition: color 0.3s ease;
                                }
                            
                                .btn-link.text-primary:hover i {
                                    color: #1e7e34;
                                }
                            
                                .btn-link.text-success:hover i {
                                    color: #155724;
                                }
                            
                                .btn-link.text-danger:hover i {
                                    color: #dc3545;
                                }
</style>

<script>
    $(document).ready(function () {
    // Hapus dari Wishlist
    $('.remove-favorite').click(function (e) {
        e.preventDefault();
        var productId = $(this).data('product-id');

        $.ajax({
            url: "{{ route('wishlist.remove', ':productId') }}".replace(':productId', productId),
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.success) {
                    showNotification(response.message, true); // Tampilkan notifikasi dan reload
                } else {
                    showNotification(response.message); // Tampilkan pesan error
                }
            },
            error: function () {
                showNotification("{{ __('messages.error_occurred') }}");
            }
        });
    });

    // Pindahkan ke Keranjang
    $('.move-to-cart').click(function (e) {
        e.preventDefault();
        var productId = $(this).data('product-id');

        $.ajax({
            url: "{{ route('wishlist.moveToCart', ':productId') }}".replace(':productId', productId),
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.success) {
                    showNotification(response.message, true); // Tampilkan notifikasi dan reload
                } else {
                    showNotification(response.message); // Tampilkan pesan error
                }
            },
            error: function () {
                showNotification("{{ __('messages.error_occurred') }}");
            }
        });
    });

    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, reload = false) {
        $('.notification-text').text(message);
        $('#cart-notification').fadeIn(400).delay(2000).fadeOut(400, function () {
            if (reload) {
                window.location.reload(); // Reload halaman setelah notifikasi selesai
            }
        });
    }
});

</script>


@endsection
