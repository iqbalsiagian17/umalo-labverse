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
            margin: 20px 0;
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

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .invoice-table th {
            background-color: #f2f2f2;
            text-transform: uppercase;
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
    </style>
</head>

<body>
    <div class="header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <div style="display: flex; align-items: center;">
            <div style="font-size: 36px; font-family: 'Brush Script MT', cursive; color: #b8860b;">
                ags.
            </div>
            <p style="font-family: 'Brush Script MT', cursive; font-size: 16px; color: #333; margin: 0 0 0 10px;">
                Simplifying Industries
            </p>
        </div>
        <div style="text-align: center;">
            <h1
                style="font-size: 28px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #b8860b; font-weight: 700; margin: 0;">
                ARKAMAYA GUNA SAHARSA
            </h1>
        </div>
    </div>


    <hr>

    <div class="invoice-info">
        <p class="invoice-title">INVOICE</p>
        <p><strong>Number:</strong> 240186/INV-AGS-GSA/VII/2024</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </div>
    
    @php
        // Find the active address from the collection of userAddresses
        $activeAddress = $userAddresses->firstWhere('status', 'aktif');
    @endphp
    
    <div class="content">
        <p style="margin: 0;">Billed To:</p>
        <p style="margin: 0;"><strong>{{ $userDetail->perusahaan }}</strong></p>
        @if($activeAddress)
            <p style="margin: 0;">
                {{ $activeAddress->alamat }},
                {{ $activeAddress->kota }},
                {{ $activeAddress->provinsi }} 
                {{ $activeAddress->kode_pos }}
            </p>
        @else
            <p style="margin: 0;">No active address available.</p>
        @endif
        <br>
        <p style="margin: 0;">Dear {{ $userDetail->perusahaan }},</p><br>
        <p style="margin: 0;">
            Based on Purchase Order No. {{ $order->id }}/GSA-PROC/IV/{{ $order->created_at->format('Y') }},
            PT. Arkamaya Guna Saharsa submits the invoice:
        </p>
    </div>
    
    


    <table class="invoice-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->produk->nama }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            @if (
                $order->orderItems->contains(function ($item) {
                    return $item->produk->nego == 'ya';
                }) && $order->harga_setelah_nego)
                <tr>
                    <td colspan="4" style="text-align:right;"><strong>Harga Setelah Nego</strong></td>
                    <td>Rp {{ number_format($order->harga_setelah_nego, 0, ',', '.') }}</td>
                </tr>
            @endif

            <tr>
                <td colspan="4" style="text-align:right;"><strong>Subtotal</strong></td>
                <td>Rp {{ number_format($order->harga_setelah_nego ?? $order->harga_total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>PPN ({{ $ppn->ppn }}%)</strong></td>
                <td>
                    Rp
                    {{ number_format(($order->harga_setelah_nego ?? $order->harga_total) * ($ppn->ppn / 100), 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Total Price Include PPN</strong></td>
                <td>
                    Rp
                    {{ number_format(($order->harga_setelah_nego ?? $order->harga_total) + ($order->harga_setelah_nego ?? $order->harga_total) * ($ppn->ppn / 100), 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>


    <div class="payment-info">
        <p><strong>Please make payments to:</strong></p>
        <p>PT. Arkamaya Guna Saharsa</p>
        <p>121-00-002881-1</p>
        <p>Bank Mandiri Kebon Sirih</p>
        <p>Jl. Tanah Abang Timur No. 1, RT.2/RW.3,</p>
        <p> Gambir, Central Jakarta City, Jakarta 10110</p>
    </div>

    <div class="footer">
        <p>Should you require further information, please do not hesitate to contact the undersigned.</p>

        <div class="signature-section">
            <p>Kind Regards,</p>
            <p><strong>PT. Arkamaya Guna Saharsa</strong></p>
            @foreach ($materaiImages as $image)
                <img src="{{ $image }}" alt="Materai Image" style="width: 100px;">
            @endforeach

            <p>Agustina Panjaitan</p>
            <p>Director</p>
        </div>

        <div class="company-details">
            <p>
                <span class="icon icon-location">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                        <path
                            d="M168 0C75.2 0 0 75.2 0 168s168 344 168 344s168-266.7 168-344S260.8 0 168 0zM168 256c-48.5 0-88-39.5-88-88s39.5-88 88-88s88 39.5 88 88S216.5 256 168 256z" />
                    </svg>
                </span>
                Jl. Matraman Raya No.148, Blok A2 No. 3 RT.1/RW.4, Kb. Manggis, Kec. Matraman, Kota Jakarta Timur,
                Daerah Khusus Ibukota Jakarta 13150
            </p>
            <p>
                <span class="icon icon-envelope">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                            d="M502.3 190.8c3.5 2.7 5.7 7 5.7 11.6c0 4.2-2 8.2-5.4 10.8L256 416 8.5 213.2C4.9 210.7 2 206.7 2 202.4c0-4.6 2.2-8.9 5.7-11.6L256 32l246.3 158.8zM496 464H16c-8.8 0-16-7.2-16-16v-288l224 160c13.6 9.7 32 9.7 45.6 0L512 160v288C512 456.8 504.8 464 496 464z" />
                    </svg>
                </span>
                info@labtek.id
                |
                <span class="icon icon-phone">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                            d="M493.4 24.6L368 9.3c-8.3-1.1-16.6 2.5-22.4 8.9l-104 112c-6.3 6.8-7.8 16.6-4.3 25.1l32 80c3.1 7.8 1.4 16.9-4.3 23l-56 56c-3.1 3.1-8.3 3.1-11.4 0L128 336c-6.3-6.3-16.4-7.9-24.3-4.3l-80 32c-8.4 3.5-18.2 1.9-25-4.3L8.9 144c-6.4-6.4-9.9-14.7-8.9-22.9l15.3-125.4c1.2-10.2 9.2-18.5 19.5-19.5l96-8.5c6.8-.7 13.5 2.1 18.1 7.7l96 112c4.5 5.2 6.5 12 5.7 18.9L144 96 288 240l-96 64z" />
                    </svg>
                </span>
                (021) 85850913
            </p>
        </div>

    </div>
    </div>

    <!-- Ensure Font Awesome is loaded -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>

</html>
