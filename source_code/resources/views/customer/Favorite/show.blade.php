@extends('layouts.customer.master')
@section('content')

<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h4>Favorite</h4>
<hr>
                @if($favorites->isEmpty())
                    <div class="card mb-3 mt-4 shadow rounded border-0 h-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 300px;">
                            <h5 class="mb-1">{{ __('messages.favorites_empty') }}</h5>
                            <a href="{{ route('shop') }}" class="btn text-white mt-3" style="background-color: #42378C;">{{ __('messages.shop_now') }}</a>
                        </div>
                    </div>
                @else
                    <div class="shoping__cart__table">
                        <table>
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">{{ __('messages.product') }}</th>
                                    <th class="text-center">{{ __('messages.image') }}</th>
                                    <th class="text-center">{{ __('messages.price') }}</th>
                                    <th class="text-center">{{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($favorites as $favorite)
                                <tr>
                                    <td class="shoping__cart__item align-middle text-center">
                                        {{ $favorite->nama }}
                                    </td>
                                    <td class="shoping__cart__item align-middle text-center">
                                        @if($favorite->images->isNotEmpty())
                                            <img src="{{ $favorite->images->first()->gambar }}" alt="{{ $favorite->nama }}" class="img-thumbnail" style="max-width: 100px;">
                                        @else
                                            <img src="default-image.png" alt="{{ $favorite->nama }}" class="img-thumbnail" style="max-width: 100px;">
                                        @endif
                                    </td>
                                    <td class="shoping__cart__price align-middle text-center">
                                        Rp {{ number_format($favorite->harga_tayang, 0, ',', '.') }}
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center">
                                            <!-- View Details Icon -->
                                            <a href="{{ route('Product_customer.user.show', $favorite->id) }}" class="btn btn-primary mr-2" title="{{ __('messages.view_details') }}">
                                                <i class="fa fa-eye"></i> <!-- Font Awesome eye icon for view details -->
                                            </a>

                                            <!-- Move to Cart Icon -->
                                            <a href="#" class="btn btn-success mr-2 move-to-cart" data-product-id="{{ $favorite->id }}" title="{{ __('messages.move_to_cart') }}">
                                                <i class="fa fa-shopping-cart"></i> <!-- Font Awesome shopping cart icon for moving to cart -->
                                            </a>

                                            <!-- Remove Icon -->
                                            <a href="#" class="btn btn-danger remove-favorite" data-product-id="{{ $favorite->id }}" title="{{ __('messages.remove') }}">
                                                <i class="fa fa-trash"></i> <!-- Font Awesome trash icon for remove -->
                                            </a>
                                        </div>
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
</style>

<script>
    // Handle removing favorite using AJAX
    $('.remove-favorite').click(function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');

        $.ajax({
            url: "{{ route('favorite.remove', ':id') }}".replace(':id', productId),
            method: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                showNotification('{{ __('messages.removed_from_favorites') }}', true); // Pass 'true' to reload page
            }
        });
    });

    // Handle moving product to cart using AJAX
    $('.move-to-cart').click(function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');

        $.ajax({
            url: "{{ route('favorite.moveToCart', ':id') }}".replace(':id', productId),
            method: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                showNotification('{{ __('messages.added_to_cart') }}', true); // Pass 'true' to reload page
            }
        });
    });

    // Function to show custom notification
    // 'reload' parameter controls whether the page should reload after showing the notification
    function showNotification(message, reload = false) {
        $('.notification-text').text(message);
        $('#cart-notification').fadeIn(400).delay(2000).fadeOut(400, function() {
            if (reload) {
                window.location.reload(); // Reload the page after the notification fades out
            }
        });
    }
</script>


@endsection
