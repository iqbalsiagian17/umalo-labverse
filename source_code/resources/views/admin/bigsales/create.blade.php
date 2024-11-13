@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Create Big Sale</h2>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form with Tabs -->
                <form action="{{ route('admin.bigsales.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General Info</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="products-tab" data-bs-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="false">Select Products</a>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="myTabContent">

                        <!-- First Tab: General Info -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Banner -->
                            <div class="mb-3">
                                <label for="banner" class="form-label">Banner</label>
                                <input type="file" name="banner" id="banner" class="form-control @error('banner') is-invalid @enderror">
                                @error('banner')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Modal Image -->
                            <div class="mb-3">
                                <label for="modal_image" class="form-label">Modal Image</label>
                                <input type="file" name="modal_image" id="modal_image" class="form-control @error('modal_image') is-invalid @enderror">
                                @error('modal_image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Start Time -->
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input type="datetime-local" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div class="mb-3">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="datetime-local" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Discount Amount -->
                            <div class="mb-3">
                                <label for="discount_amount" class="form-label">Discount Amount</label>
                                <input type="text" name="discount_amount" id="discount_amount" 
                                    class="form-control @error('discount_amount') is-invalid @enderror" 
                                    value="{{ old('discount_amount') }}" oninput="formatAndToggleFields('amount')" onblur="convertToNumeric()">
                                @error('discount_amount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    Jika diisi, aplikasi akan memberikan potongan harga tetap (disama ratakan) pada setiap produk yang dipilih.
                                </small>
                            </div>

                            <!-- Discount Percentage -->
                            <div class="mb-3">
                                <label for="discount_percentage" class="form-label">Discount Percentage</label>
                                <input type="number" name="discount_percentage" id="discount_percentage" 
                                    class="form-control @error('discount_percentage') is-invalid @enderror" 
                                    value="{{ old('discount_percentage') }}" step="0.01" min="1" max="99" 
                                    oninput="validatePercentageInput(this)" oninput="formatAndToggleFields('percentage')">
                                @error('discount_percentage')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    Jika diisi, aplikasi akan memberikan potongan harga berdasarkan persentase dari harga asli produk. 
                                    Hanya 1-99 persen yang diperbolehkan.
                                </small>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="status" id="status_active" class="form-check-input" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                        <label for="status_active" class="form-check-label">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="status" id="status_non_active" class="form-check-input" value="0" {{ old('status', false) === false ? 'checked' : '' }}>
                                        <label for="status_non_active" class="form-check-label">Non-Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Second Tab: Select Products -->
                        <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                            <div class="mb-3">
                                <label class="form-label">Select Products </label>
                                <div class="row">
                                    @foreach($products as $product)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input @error('products') is-invalid @enderror" 
                                                    type="checkbox" 
                                                    name="products[]" 
                                                    id="product-{{ $product->id }}" 
                                                    value="{{ $product->id }}"
                                                    {{ in_array($product->id, old('products', [])) ? 'checked' : '' }}>
                                                
                                                <label class="form-check-label" for="product-{{ $product->id }}">
                                                    <!-- Display the product image -->
                                                    @if($product->images->isNotEmpty())
                                                        <img src="{{ asset($product->images->first()->images) }}" 
                                                             alt="{{ $product->name }}" 
                                                             style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                                    @else
                                                        <img src="{{ asset('storage/images/default.jpg') }}" 
                                                             alt="default image" 
                                                             style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                                    @endif
                                                    <p>{{ \Illuminate\Support\Str::limit($product->name, 25) }}</p>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                    @error('products')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create Big Sale</button>
                        <a href="{{ route('admin.bigsales.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Activate Bootstrap Tabs for page load
    document.addEventListener("DOMContentLoaded", function () {
        var myTab = new bootstrap.Tab(document.querySelector('#myTab a.active'));
        myTab.show();
    });
</script>

<script>
    function formatDiscountAmount() {
        const input = document.getElementById('discount_amount');
        let value = input.value.replace(/\./g, ''); // Remove any existing dots
        if (value) {
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Add dots every 3 digits
        }
    }

    function convertToNumeric() {
        const input = document.getElementById('discount_amount');
        input.value = input.value.replace(/\./g, ''); // Remove dots to convert to a plain numeric value
    }
</script>


<script>
function formatAndToggleFields(field) {
    const discountAmount = document.getElementById('discount_amount');
    const discountPercentage = document.getElementById('discount_percentage');

    if (field === 'amount') {
        // Format discount amount with dot separators
        let value = discountAmount.value.replace(/\./g, '');
        if (value) {
            discountAmount.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        discountPercentage.disabled = discountAmount.value !== ''; // Disable percentage if amount is filled
    } else if (field === 'percentage') {
        discountAmount.disabled = discountPercentage.value !== ''; // Disable amount if percentage is filled
    }
}

function convertToNumeric() {
    const discountAmount = document.getElementById('discount_amount');
    // Convert discount amount to numeric by removing dots
    discountAmount.value = discountAmount.value.replace(/\./g, '');
}

// Function to ensure the percentage stays within the allowed range
function validatePercentageInput(input) {
    let value = parseFloat(input.value);
    if (value < 1) {
        input.value = 1; // Set to minimum value if less than 1
    } else if (value > 99) {
        input.value = 99; // Set to maximum value if greater than 99
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Get the current date and time in YYYY-MM-DDTHH:mm format
    let now = new Date();
    let year = now.getFullYear();
    let month = ("0" + (now.getMonth() + 1)).slice(-2); // Add leading zero for month
    let day = ("0" + now.getDate()).slice(-2); // Add leading zero for day
    let hours = ("0" + now.getHours()).slice(-2); // Add leading zero for hours
    let minutes = ("0" + now.getMinutes()).slice(-2); // Add leading zero for minutes

    // Format current date and time for input field
    let currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

    // Set the min attribute for both start and end time input fields
    document.getElementById('end_time').setAttribute('min', currentDateTime);
});


</script>
@endsection
