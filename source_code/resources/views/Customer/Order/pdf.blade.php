<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 80px;
            margin-right: 20px;
        }

        .header-title {
            text-align: left;
        }

        .header-title h1 {
            font-size: 28px;
            margin: 0;
            color: #b8860b;
            /* Gold color similar to the image */
            font-weight: 700;
        }

        .header-title p {
            margin: 0;
            font-size: 14px;
            font-style: italic;
            color: #666;
        }

        hr {
            border: 1px solid #b8860b;
            margin-top: -50px;

        }

        .invoice-info {
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .invoice-info p {
            margin: 2px 0;
        }

        .invoice-title {
            color: #e74c3c;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .invoice-table th,
    .invoice-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    .invoice-table th {
        background-color: #f2f2f2;
        text-transform: uppercase;
        font-weight: bold;
        text-align: center;
    }

    .invoice-table td {
        text-align: right;
    }

    .invoice-table td:first-child,
    .invoice-table td:nth-child(2) {
        text-align: left;
    }

    .invoice-table .total-row {
        font-weight: bold;
        background-color: #f9f9f9;
    }

        .payment-info {
            margin-top: 20px;
            font-size: 14px;
        }

        .payment-info p {
            margin: 5px 0;
        }

        .footer {
            text-align: left;
            font-size: 14px;
            margin-top: 20px;
        }

        .footer p {
            margin: 0;
        }

        .footer img {
            margin-top: 20px;
            width: 100px;
        }

        .footer-signature {
            margin-top: 20px;
        }

        .footer-signature p {
            margin: 0;
        }

        .company-details {
            margin-top: 20px;
            font-size: 12px;
        }

        .company-details p {
            margin: 5px 0;
            line-height: 1.4;
        }

        .company-details .icon {
            margin-right: 5px;
        }

        .signature-section {
            margin-top: 20px;
            font-size: 14px;
        }

        .icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 8px;
            vertical-align: middle;
        }

        .icon-location svg {
            fill: #333;
            /* Dark color for the location icon */
        }

        .icon-envelope svg {
            fill: #FFD700;
            /* Gold color for the envelope icon */
        }

        .icon-phone svg {
            fill: #FFD700;
            /* Gold color for the phone icon */
        }

        .content p {
            margin: 0;
            /* Removes margin to prevent extra space */
            padding: 0;
            /* Removes padding to prevent extra space */
            font-weight: normal;
            /* Ensure text is not bold */
            line-height: 1.4;
            /* Adjust line height for better readability */
        }

        .payment-box {
            border: 1px solid #b8860b;
            /* Adds a border to the box */
            padding: -8px;
            /* Adds space inside the box */
            margin-top: 1px;
            /* Adds space above the box */
            border-radius: 5px;
            /* Rounds the corners of the box */
            background-color: #f9f9f9;
            /* Optional: Adds a background color */
        }
    </style>
</head>

<div class="header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
    <div style="display: flex; align-items: center;">
        <!-- Adjust the image size and position -->
        <img src="{{ $agsLogo }}" alt="AGS Logo" style="width: 130px; height: auto; margin-right: 15px;">
    </div>

    <!-- Center the text vertically and horizontally -->
    <div
        style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <h1
            style="font-size: 34px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #b8860b; font-weight: 700; margin: 0; position: absolute; top: 30; right: 0; left: 50;">
            ARKAMAYA GUNA SAHARSA
        </h1>
    </div>
</div>
<hr>
<div class="invoice-info">
    <p class="invoice-title">INVOICE</p>
    <p><strong>Number:</strong> {{ $invoiceNumber }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
</div>

@php
    // Find the active address from the collection of userAddresses
    $activeAddress = $userAddresses->firstWhere('is_active', '1');
@endphp

<div class="content">
    <p style="margin: 0;">Billed To:</p>
    <p style="margin: 0;"><strong>{{ $user->company }}</strong></p>
    @if ($activeAddress)
        <p style="margin: 0;">
            {{ $activeAddress->address }},
            {{ $activeAddress->city }},
            {{ $activeAddress->province }}
            {{ $activeAddress->postal_code }}
        </p>
    @else
        <p style="margin: 0;">No active address available.</p>
    @endif
    <br>
    <p style="margin: 0;">Dear {{ $user->company }},</p><br>
    <p style="margin: 0;">
        Based on Purchase Order No. {{ $order->id }}/{{ $order->created_at->format('Y') }},
        {{ $parameter->company_name }} submits the invoice:
    </p>
</div>




<table class="invoice-table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <!-- Original Total Price Row -->
        <tr class="total-row">
            <td colspan="4" style="text-align:right;"><strong>Total Price</strong></td>
            <td style="text-align: right;">
                <span style="{{ $order->negotiation_total ? 'text-decoration: line-through; color: #999;' : '' }}">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </span>
            </td>
        </tr>
        
        <!-- Discount Row (if negotiation_total exists) -->
        @if ($order->negotiation_total)
            <tr class="discount-row">
                <td colspan="4" style="text-align:right;"><strong>Discount</strong></td>
                <td style="text-align: right; ">
                    {{ round((($order->total - $order->negotiation_total) / $order->total) * 100, 2) }}%
                </td>
            </tr>
            <tr class="final-price-row">
                <td colspan="4" style="text-align:right;"><strong>Final Price</strong></td>
                <td style="text-align: right; color: green;">
                    Rp {{ number_format($order->negotiation_total, 0, ',', '.') }}
                </td>
            </tr>
        @endif
        
    </tbody>
</table>



<div class="payment-info">
    <p><strong>Please make payments to:</strong></p>
    <div class="payment-box">
        <p>{{ $parameter->company_name }}</p>
        <p>{{ $parameter->account_number }}</p>
        <p>{{ $parameter->bank_name }} {{ $parameter->bank_city }}</p>
        <p>{{ $parameter->bank_address }}</p>
    </div>
</div>

<div class="footer">
    <p>Should you require further information, please do not hesitate to contact the undersigned.</p>

    <div class="signature-section">
        <p>Kind Regards,</p>
        <p><strong>{{ $parameter->company_name }}</strong></p>
{{--         @foreach ($materaiImages as $image)
            <img src="{{ $image }}" alt="Materai Image" style="width: 100px;">
        @endforeach
 --}}
        <p>{{ $parameter->director }}</p>
        <p>Director</p>
    </div>

    <div class="company-details">
        <p>
            <!-- Use the base64-encoded maps-and-flags.png -->
            <img src="{{ $mapsIcon }}" alt="Location Icon"
                style="width: 10px; height: auto; margin-right: 5px;">
            {{ $parameter->address }}
        </p>
        <p>
            <!-- Use the base64-encoded email.png -->
            <img src="{{ $emailIcon }}" alt="Email Icon" style="width: 10px; height: auto; margin-right: 5px;">
            {{ $parameter->email1  }}
            |
            <!-- Use the base64-encoded phone-call.png -->
            <img src="{{ $phoneIcon }}" alt="Phone Icon" style="width: 10px; height: auto; margin-right: 5px;">
            {{ $parameter->telephone_number}}
        </p>
    </div>

</div>
</div>

<!-- Ensure Font Awesome is loaded -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>

</html>
