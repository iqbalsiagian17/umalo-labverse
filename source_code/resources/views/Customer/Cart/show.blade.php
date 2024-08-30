@extends('layouts.customer.master')

@section('content')

<!-- Breadcrumb Section Begin -->
{{-- <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/images/cart.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>{{ __('messages.shopping_cart') }}</h2>
                    <div class="breadcrumb__option">
                        <a href="/">{{ __('messages.home') }}</a>
                        <span>{{ __('messages.shopping_cart') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}
<!-- Breadcrumb Section End -->

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

        @if(isset($cart) && count($cart) > 0)
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
                    @foreach($cart as $id => $details)
                    <tr>
                        <td class="shoping__cart__item align-middle text-center ">
                            {{ $details['name'] ?? __('messages.product_name_unavailable') }}

                            @if (isset($details['harga_potongan']) && $details['harga_potongan'] > 0)
                                <span class="badge bg-danger text-white ml-2">{{ __('Diskon') }}</span>
                            @endif
                                                    </td>
                        <td class="shoping__cart__item align-middle text-center">
                            <img src="{{ asset($details['image'] ?? 'default.png') }}" alt="{{ $details['name'] ?? __('messages.product_image') }}" class="img-thumbnail" style="max-width: 100px;">
                        </td>
                        <td class="shoping__cart__price align-middle text-center">
                            @if (isset($details['harga_potongan']) && $details['harga_potongan'] > 0)
                                Rp {{ number_format($details['harga_potongan'], 0, ',', '.') }}
                            @else
                                Rp {{ number_format($details['harga_tayang'] ?? 0, 0, ',', '.') }}
                            @endif
                        </td>                        
                                                <td class="shoping__cart__quantity align-middle text-center">
                                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" class="form-control quantity" data-id="{{ $id }}" min="1" style="width: 70px; padding: 8px; text-align: center; margin: 0 auto; border-radius: 8px; border: 1px solid #ced4da; box-shadow: 0px 2px 5px rgba(0,0,0,0.1);">
                                                </td>
                        
                        <td class="shoping__cart__total subtotal align-middle text-center" data-id="{{ $id }}">
                            @php
                                $price = isset($details['harga_potongan']) && $details['harga_potongan'] > 0 ? $details['harga_potongan'] : ($details['harga_tayang'] ?? 0);
                                $subtotal = $price * $details['quantity'];
                            @endphp
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </td>
                        
                                                <td>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ __('messages.remove') }}</button>
                            </form>
                        </td>
                    </tr>
                    
                        @php $total += $subtotal; @endphp
                    @endforeach
                </tbody>
            </table>

            <div class="row">
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>{{ __('messages.cart_total') }}</h5>
                        <ul>
                            <li>{{ __('messages.total') }} <span id="total">Rp {{ number_format($total, 0, ',', '.') }}</li>
                        </ul>
    
                        @if(auth()->user()->userDetail)
                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="primary-btn">{{ __('messages.proceed_to_checkout') }}</button>
                            </form>
                        @else
                            <p class="text-danger">{{ __('messages.complete_personal_data') }}</p>
                            <a href="{{ route('user.create') }}" class="btn btn-primary">{{ __('messages.fill_personal_data') }}</a>
                        @endif
                    </div>
                </div>
            </div>

        @else
        <div class="card mb-3 mt-4 shadow rounded border-0 h-100">
            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 300px;">
                <h5 class="mb-1">{{ __('messages.cart_empty') }}</h5>
                <a href="/" class="btn btn-primary mt-3">{{ __('messages.shop_now') }}</a>
            </div>
        </div>
        @endif
    </div>
    </div>
    <div class="alert alert-warning">
        {{ __('Untuk menghindari potensi kesalahan pada sistem, disarankan agar produk diskon, produk Big Sale, dan produk reguler dipisahkan dalam keranjang yang berbeda.') }}
    </div>
        </div>
    </div>
</div>
</section>

<script>
    document.querySelectorAll('.quantity').forEach(function(input) {
        input.addEventListener('input', function() {
            var id = this.dataset.id;
            var quantity = parseInt(this.value);

            fetch('/cart/update-quantity/' + id, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: quantity })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      var harga = parseFloat(this.closest('tr').querySelector('.shoping__cart__price').innerText.replace(/[^0-9,-]+/g,"").replace(',', '.'));
                      var subtotalElement = this.closest('tr').querySelector('.subtotal');
                      var subtotal = quantity * harga;
                      subtotalElement.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
                      updateTotal();
                  } else {
                      alert('{{ __('messages.quantity_exceeds_stock') }}');
                      this.value = this.getAttribute('max');  // Reset quantity to max stock
                  }
              });
        });
    });

    function updateTotal() {
        var total = 0;
        document.querySelectorAll('.subtotal').forEach(function(subtotalElement) {
            var subtotal = parseFloat(subtotalElement.innerText.replace(/[^0-9,-]+/g,"").replace(',', '.'));
            total += subtotal;
        });
        document.getElementById('total').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>

@endsection
