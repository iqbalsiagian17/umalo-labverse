@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Edit Big Sale</h2>
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
                <form action="{{ route('admin.bigsales.update', $bigSale->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $bigSale->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Banner -->
                            <div class="mb-3">
                                <label for="banner" class="form-label">Banner</label>
                                
                                <!-- Display the current banner image if it exists -->
                                @if($bigSale->banner)
                                    <div class="mb-2">
                                        <img src="{{ asset($bigSale->banner) }}" alt="Current Banner" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                @endif
                            
                                <!-- File input for uploading a new banner -->
                                <input type="file" name="banner" id="banner" class="form-control @error('banner') is-invalid @enderror">
                                @error('banner')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Modal Image -->
                            <div class="mb-3">
                                <label for="modal_image" class="form-label">Modal Image</label>

                                <!-- Display the current modal image if it exists -->
                                @if($bigSale->modal_image)
                                    <div class="mb-2">
                                        <img src="{{ asset($bigSale->modal_image) }}" alt="Current Modal Image" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                @endif

                                <!-- File input for uploading a new modal image -->
                                <input type="file" name="modal_image" id="modal_image" class="form-control @error('modal_image') is-invalid @enderror">
                                @error('modal_image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Start Time -->
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input type="datetime-local" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                                    value="{{ old('start_time', \Carbon\Carbon::parse($bigSale->start_time)->format('Y-m-d\TH:i')) }}" required>
                                @error('start_time')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div class="mb-3">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="datetime-local" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" 
                                    value="{{ old('end_time', \Carbon\Carbon::parse($bigSale->end_time)->format('Y-m-d\TH:i')) }}" required>
                                @error('end_time')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Discount Amount -->
                            <div class="mb-3">
                                <label for="discount_amount" class="form-label">Discount Amount</label>
                                <div class="input-group">
                                    <input type="text" name="discount_amount" id="discount_amount" 
                                        class="form-control @error('discount_amount') is-invalid @enderror" 
                                        value="{{ $bigSale->discount_amount ? number_format($bigSale->discount_amount, 0, ',', '.') : '' }}" 
                                        oninput="formatAndToggleFields('amount')" onblur="convertToNumeric()" onfocus="removeFormatting()">
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearField('discount_amount')">Clear</button>
                                </div>
                                @error('discount_amount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    Fixed discount on each selected product.
                                </small>
                            </div>

                            <!-- Discount Percentage -->
                            <div class="mb-3">
                                <label for="discount_percentage" class="form-label">Discount Percentage</label>
                                <div class="input-group">
                                    <input type="number" name="discount_percentage" id="discount_percentage" 
                                        class="form-control @error('discount_percentage') is-invalid @enderror" 
                                        value="{{ old('discount_percentage', $bigSale->discount_percentage ?? '') }}" 
                                        step="0.01" min="1" max="99" 
                                        oninput="validatePercentageInput(this)" oninput="formatAndToggleFields('percentage')">
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearField('discount_percentage')">Clear</button>
                                </div>
                                @error('discount_percentage')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    Percentage discount on each selected product (1-99% allowed).
                                </small>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="status" id="status_active" class="form-check-input" value="1" {{ old('status', $bigSale->status) ? 'checked' : '' }}>
                                        <label for="status_active" class="form-check-label">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="status" id="status_non_active" class="form-check-input" value="0" {{ old('status', $bigSale->status) === false ? 'checked' : '' }}>
                                        <label for="status_non_active" class="form-check-label">Non-Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Second Tab: Select Products -->
                        <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                            <div class="mb-3">
                                <label class="form-label">Select Products</label>
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
                                                    {{ in_array($product->id, old('products', $bigSale->products->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                
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
                        <button type="submit" class="btn btn-primary">Update Big Sale</button>
                        <a href="{{ route('admin.bigsales.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        formatAndToggleFields('initial');
    });

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

    function removeFormatting() {
        const input = document.getElementById('discount_amount');
        input.value = input.value.replace(/\./g, ''); // Remove dots when field is focused
    }

    function formatAndToggleFields(field) {
        const discountAmount = document.getElementById('discount_amount');
        const discountPercentage = document.getElementById('discount_percentage');
        
        if (field === 'amount' || field === 'initial') {
            let value = discountAmount.value.replace(/\./g, '');
            if (value) {
                discountAmount.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format with dots for readability
            }
            discountPercentage.disabled = discountAmount.value !== ''; // Disable percentage if amount is filled
        }
        
        if (field === 'percentage' || field === 'initial') {
            discountAmount.disabled = discountPercentage.value !== ''; // Disable amount if percentage is filled
        }
    }

    function clearField(fieldId) {
        const field = document.getElementById(fieldId);
        field.value = '';
        formatAndToggleFields(fieldId === 'discount_amount' ? 'amount' : 'percentage');
    }

    // Function to remove formatting when the input is focused
    function removeFormatting(input) {
        input.value = input.value.replace(",", "."); // Ensure the input uses '.' as decimal separator
    }

    // Function to validate percentage input to stay between 1 and 99
    function validatePercentageInput(input) {
        let value = parseFloat(input.value);

        if (value < 1) {
            input.value = 1; // Set to minimum value if less than 1
        } else if (value > 99) {
            input.value = 99; // Set to maximum value if greater than 99
        }
    }

    // Function to format the percentage input to remove trailing decimals when not needed
    function formatPercentage(input) {
        let value = input.value;

        // Check if the value is a whole number and remove any decimal part
        if (value.includes('.') && value.split('.')[1] === '00') {
            input.value = value.split('.')[0]; // Remove the decimals if they are .00
        }

        // Add formatting to the value (optional: add thousand separator for larger values)
        input.value = parseFloat(input.value).toFixed(2).replace('.', ','); // Ensure two decimal places with ',' as decimal separator
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
