@extends('layouts.customer.master')
@section('content')

<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(isset($cartItems) && $cartItems->count() > 0)
                <div class="shoping__cart__table">
                    <table>
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">{{ __('messages.product') }}</th>
                                <th class="text-center">{{ __('messages.image') }}</th>
                                <th class="text-center">{{ __('messages.price') }}</th>
                                <th class="text-center">{{ __('messages.quantity') }}</th>
                                <th class="text-center">{{ __('messages.subtotal') }}</th>
                                <th class="text-center">{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($cartItems as $cartItem)
                                @php 
                                    $product = $cartItem->product;
                                    $price = $product->discount_price ?? $product->price;
                                    $subtotal = $price * $cartItem->quantity;
                                    $total += $subtotal;
                                @endphp
                                <tr>
                                    <td class="shoping__cart__item align-middle text-center">
                                        {{ $product->name ?? __('messages.product_name_unavailable') }}
                                    </td>
                                    <td class="shoping__cart__item align-middle text-center">
                                        <img src="{{ asset($product->images->first()->images ?? 'default.png') }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 100px;">
                                    </td>
                                    <td class="shoping__cart__price align-middle text-center">
                                        Rp {{ number_format($price, 0, ',', '.') }}
                                    </td>
                                    <td class="shoping__cart__quantity align-middle text-center">
                                        <input type="number" name="quantity" value="{{ $cartItem->quantity }}" class="form-control quantity" data-id="{{ $cartItem->id }}" data-max="{{ $product->stock }}" min="1" style="width: 70px; padding: 8px; text-align: center; margin: 0 auto; border-radius: 8px; border: 1px solid #ced4da; box-shadow: 0px 2px 5px rgba(0,0,0,0.1);">
                                    </td>
                                    <td class="shoping__cart__total subtotal align-middle text-center" data-id="{{ $cartItem->id }}">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">{{ __('messages.remove') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="shoping__checkout">
                                <h5>{{ __('messages.cart_total') }}</h5>
                                <ul>
                                    <li>{{ __('messages.total') }} <span id="total">Rp {{ number_format($total, 0, ',', '.') }}</span></li>
                                </ul>

                                @if(auth()->user()->userDetail)
                                    <form action="{{ route('cart.checkout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn text-white" style="background: #42378C;">{{ __('messages.proceed_to_checkout') }}</button>
                                    </form>
                                @else
                                    <p class="text-danger">{{ __('messages.complete_personal_data') }}</p>
                                    <a href="{{ route('user.create') }}" class="btn text-white" style="background: #42378C;">{{ __('messages.fill_personal_data') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>

                @else
                <div class="card mb-3 mt-4 shadow rounded border-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 300px;">
                        <h5 class="mb-1">{{ __('messages.cart_empty') }}</h5>
                        <a href="{{ route('shop') }}" class="btn text-white mt-3" style="background-color: #42378C;">{{ __('messages.shop_now') }}</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>






@endsection
