@extends('layouts.customer.master')
@section('content')
    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">

                        <div class="sidebar__item">
                            <h4 style="color:#42378C;">{{ __('messages.price_range') }}</h4>
                            <form action="{{ request()->url() }}" method="GET">
                                <div class="price-range1">
                                    <div class="form-group">
                                        <label for="min_price">{{ __('messages.min_price') }}</label>
                                        <input type="text" name="min_price" id="min_price" placeholder="Min"
                                               value="{{ request('min_price') }}" class="form-control input-range" oninput="formatInput(this);">
                                    </div>
                                    <div class="form-group">
                                        <label for="max_price" class="mt-2">{{ __('messages.max_price') }}</label>
                                        <input type="text" name="max_price" id="max_price" placeholder="Max"
                                               value="{{ request('max_price') }}" class="form-control input-range" oninput="formatInput(this);">
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
                                document.getElementById('min_price').value = '';
                                document.getElementById('max_price').value = '';
                                
                                // Reload the page without price parameters
                                const url = new URL(window.location.href);
                                url.searchParams.delete('min_price');
                                url.searchParams.delete('max_price');
                                window.location.href = '{{ route('shop') }}';
                            }
                        </script>
                        


                        <div class="sidebar__item">
                            <h4 style="color:#42378C;">{{ __('messages.Category') }}</h4>
                            <ul>
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ route('shop', ['category_slug' => $category->slug]) }}"
                                        class="{{ request('category_slug') == $category->slug ? 'active' : '' }}">
                                            {{ \Illuminate\Support\Str::limit($category->name, 25, '...') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="sidebar__item">
                            <h4 style="color:#42378C;">{{ __('messages.subCategory') }}</h4>
                            <ul>
                                @foreach ($subcategories as $subCategory)
                                    <li>
                                        <a href="{{ route('shop', ['category_slug' => $subCategory->category->slug, 'subcategory_slug' => $subCategory->slug]) }}"
                                           class="{{ request()->segment(3) == $subCategory->slug ? 'active' : '' }}">
                                            {{ \Illuminate\Support\Str::limit($subCategory->name, 40, '...') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        


                        {{-- <div class="sidebar__item">
                            <h4 style="color:#42378C;">{{ __('Rating') }}</h4>
                            <ul>
                                @for ($i = 5; $i >= 1; $i--)
                                    <li>
                                        <a href="{{ route('shop.rating', $i) }}" class="rating-link {{ request('rating') == $i ? 'active' : '' }}">
                                            @for ($j = 1; $j <= $i; $j++)
                                                <i class="fa fa-star star-colored"></i>
                                            @endfor
                                        </a>
                                    </li>
                                @endfor
                            </ul>
                        </div> --}}
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
                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <h6><span>{{ $productCount }}</span> {{ __('messages.Product_ditemukan') }}</h6>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-3">
                                <div class="filter__option">
                                    <span class="icon_grid-2x2" id="grid-view"></span>
                                    <span class="icon_ul" id="list-view"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($pageMessage)
                    <div class="page-message-container d-flex align-items-center justify-content-between bg-light p-3 rounded mb-4">
                        <span class="page-message-text">{{ $pageMessage }}</span>
                        <a href="{{ route('shop') }}" class="btn btn-secondary btn-sm refresh-button" title="{{ __('Refresh Page') }}">
                            <i class="fas fa-sync-alt"></i> {{ __('Refresh') }}
                        </a>
                    </div>
                    @endif
                    
                    
                    
                    <div class="row" id="product-list">
                        @if($products->isEmpty())
                            <div class="col-12 text-center">
                                <p class="alert alert-warning mt-4">Produk tidak ditemukan dalam kategori ini.</p>
                            </div>
                        @else
                            @foreach ($products as $product)
                                <div class="col-lg-4 col-md-6 col-sm-6 product__item-container">
                                    <div class="product__item" data-href="{{ route('product.show', $product->slug) }}?source={{ Str::random(10) }}">
                                        @php
                                            $imagePath = $product->images->isNotEmpty()
                                                ? $product->images->first()->images
                                                : 'path/to/default/image.jpg';
                                        @endphp
                                        <div class="product__item__pic" style="background-image: url('{{ asset($imagePath) }}');">
                                        </div>
                                        <div class="product__item__text">
                                            <h6>
                                                <a href="{{ route('product.show', $product->slug) }}?source={{ Str::random(10) }}">
                                                    {{ \Illuminate\Support\Str::limit($product->name, 20, '...') }}
                                                </a>
                                            </h6>
                                            <h5>
                                                @if ($product->discount_price)
                                                    @php
                                                        $discount_percentage = (($product->price - $product->discount_price) / $product->price) * 100;
                                                    @endphp
                                                    <span style="text-decoration: line-through; color:darkgray">
                                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                                    </span>
                                                    <span style="color:red;">
                                                        ({{ round($discount_percentage) }}% Off)
                                                    </span>
                                                    <br>
                                                    <span>
                                                        Rp{{ number_format($product->discount_price, 0, ',', '.') }}
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
                        @endif
                    </div>
                    
                    <div class="product__pagination text-center">
                        @for ($i = 1; $i <= $products->lastPage(); $i++)
                            @if ($i == $products->currentPage())
                                <span class="">{{ $i }}</span>
                            @else
                                <a href="{{ $products->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->


    <div id="notification" style="display: none; position: fixed; top: 20px; right: 20px; background-color: #f44336; color: white; padding: 10px; border-radius: 5px; z-index: 1000;">
        Not recommended: This view mode is still under development.
    </div>
@endsection
