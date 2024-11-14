@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- First Card: Transaction Information -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title"><h2>Detail Transaksi</h2></div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Bagian</th>
                            <th scope="col">Informasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Transaksi ID</td>
                            <td>{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <td>User ID</td>
                            <td>{{ $order->user_id }}</td>
                        </tr>
                        <tr>
                            <td>Harga Total</td>
                            <td>
                                @if($order->harga_setelah_nego)
                                    <span style="text-decoration: line-through;">Rp {{ number_format($order->harga_total, 0, ',', '.') }}</span><br>
                                    <span>Rp {{ number_format($order->harga_setelah_nego, 0, ',', '.') }}</span>
                                @else
                                    Rp {{ number_format($order->harga_total, 0, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Transaksi</td>
                            <td>{{ $order->created_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Second Card: Transaction Items -->
 <div class="card mb-4">
    <div class="card-header">
        <div class="card-title"><h2>Item Transaksi</h2></div>
    </div>
    <div class="card-body">
        @if($order->orderItems && $order->orderItems->isNotEmpty())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Nama Product</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->Product_id }}</td>
                            <td>{{ $item->Product->nama }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ 'Rp ' . number_format($item->harga, 0, ',', '.') }}</td>
                            <td>
                                @if($order->harga_setelah_nego)
                                    <span style="text-decoration: line-through;">Rp {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}</span><br>
                                    <span>Rp {{ number_format($item->jumlah * ($order->harga_setelah_nego / $order->orderItems->sum('jumlah')), 0, ',', '.') }}</span>
                                @else
                                    Rp {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No Transaction Items Available</p>
        @endif
    </div>
</div>

    </div>
</div>

<!-- Action Buttons -->
<div class="mt-4">
    @php
        $negotiable = $order->orderItems->contains(function($item) {
            return $item->Product->nego == 'ya';
        });
    @endphp

    <form id="statusForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" id="statusInput" value="{{ $order->status }}">

        <div class="form-group" id="subtotalGroup" @if($order->status != 'Negosiasi') style="display: none;" @endif>
            <label for="subtotal">Ubah Harga Total Setelah Negoisasi</label>
            <input type="number" name="subtotal" id="subtotal" class="form-control" value="{{ $order->harga_setelah_nego ?? $order->harga_total }}">
        </div

        <!-- Tracking Number Input -->
        @if($order->status == 'Packing')
            <div class="form-group">
                <label for="nomor_resi">Nomor Resi (Tracking Number)</label>
                <input type="text" name="nomor_resi" id="nomor_resi" class="form-control" placeholder="Enter Tracking Number">
            </div>
        @endif

        <!-- WhatsApp Number Input -->
        <div class="form-group" id="whatsappGroup" style="display: none;">
            <label for="whatsapp_number">Nomor WhatsApp</label>
            <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control">
            @if ($errors->has('whatsapp_number'))
                <small class="text-danger">{{ $errors->first('whatsapp_number') }}</small>
            @endif
        </div>

         <!-- Cancel Button -->
         @if($order->status !== 'Selesai' && $order->status !== 'Cancelled')
         <button type="button" name="action" value="cancel" class="btn btn-danger" onclick="cancelOrder()">Cancel</button>
     @endif

        <!-- Dynamic Buttons -->
        @if($negotiable)
            @if($order->status == 'Menunggu Konfirmasi Admin untuk Negosiasi')
                <button type="button" id="accButton" class="btn btn-success" onclick="prepareNegosiasi()">Konfirmasi</button>
                <button type="button" id="rejectNegoButton" class="btn btn-danger" onclick="rejectNegotiation()">Tolak</button>
            @elseif($order->status == 'Negosiasi')
                <button type="button" id="nextButton" class="btn btn-primary" onclick="updateStatus('Diterima')">Update</button>
                <button type="button" id="rejectNegoButton" class="btn btn-danger" onclick="rejectNegotiation()">Tolak</button>
            @elseif($order->status == 'Diterima')
                <button type="button" id="nextButton" class="btn btn-primary" onclick="updateStatus('Packing')">Update</button>
            @elseif($order->status == 'Packing')
                <button type="button" id="nextButton" class="btn btn-primary" onclick="updateStatus('Pengiriman')">Update</button>
            @elseif($order->status == 'Pengiriman')
                <button type="button" id="nextButton" class="btn btn-primary" onclick="updateStatus('Selesai')">Selesai</button>
            @endif
        @else
            @if($order->status == 'Menunggu Konfirmasi Admin')
                <button type="button" id="accButton" class="btn btn-success" onclick="updateStatus('Diterima')">Konfirmasi</button>
            @elseif($order->status == 'Diterima')
                <button type="button" id="nextButton" class="btn btn-primary" onclick="updateStatus('Packing')">Update</button>
            @elseif($order->status == 'Packing')
                <button type="button" id="nextButton" class="btn btn-primary" onclick="updateStatus('Pengiriman')">Update</button>
            @elseif($order->status == 'Pengiriman')
                <button type="button" id="nextButton" class="btn btn-primary" onclick="updateStatus('Selesai')">Selesai</button>
            @endif
        @endif
    </form>
</div>


<a href="{{ route('transaksi.index') }}" class="btn btn-primary mt-3">Kembali</a>
<!-- Bukti Pembayaran -->
@if($order->bukti_pembayaran)
<div class="card mt-4">
    <div class="card-header">
        <h5>Bukti Pembayaran</h5>
    </div>
    <div class="card-body">
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#buktiPembayaranModal">
            Lihat Bukti Pembayaran
        </button>

        <div class="modal fade" id="buktiPembayaranModal" tabindex="-1" aria-labelledby="buktiPembayaranModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buktiPembayaranModalLabel">Bukti Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset('uploads/bukti_pembayaran/' . $order->bukti_pembayaran) }}" class="img-fluid" alt="Bukti Pembayaran">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function prepareNegosiasi() {
        $('#statusInput').val('Negosiasi');
        $('#whatsappGroup').show();
        $('#accButton').text('Kirim WA dan Konfirmasi');
        $('#accButton').attr('onclick', 'submitAndUpdateNegosiasi()');
    }

    function submitAndUpdateNegosiasi() {
        // Set status to Negosiasi
        $('#statusInput').val('Negosiasi');

        // Submit the form with the updated status
        submitForm(function() {
            // After form submission and status update
            alert('Status updated to Negosiasi successfully.');

            // Show the subtotal input form after the alert
            $('#subtotalGroup').show();
            $('#whatsappGroup').hide();

            // Update the button to allow updating status to "Diterima"
            updateButton('Update to Diterima', 'Diterima', 'btn-primary');
        });
    }

    function submitAndUpdateNegosiasi(isRejected = false) {
    // Set status based on negotiation acceptance or rejection
    if (isRejected) {
        $('#statusInput').val('Diterima');
    } else {
        $('#statusInput').val('Negosiasi');
    }

    // Submit the form with the updated status
    submitForm(function() {
        let message = isRejected
            ? 'Negosiasi Ditolak, Orderan Berlanjut Ke Order Reguler. Status updated to Diterima successfully.'
            : 'Status updated to Negosiasi successfully.';
        alert(message);

        if (isRejected) {
            // If rejected, hide WhatsApp group, subtotal input, and update button to 'Diterima'
            $('#whatsappGroup').hide();
            $('#subtotalGroup').hide();
            updateButton('Update to Packing', 'Packing', 'btn-primary');
        } else {
            // If accepted, show subtotal input form and update button to 'Diterima'
            $('#subtotalGroup').show();
            updateButton('Update to Diterima', 'Diterima', 'btn-primary');
        }
    });
}

// Function to reject negotiation and move directly to 'Diterima'
function rejectNegotiation() {
    submitAndUpdateNegosiasi(true);
}



    function updateStatus(newStatus) {
        if (newStatus === 'Packing' && !{{ $order->bukti_pembayaran ? 'true' : 'false' }}) {
        alert('Bukti pembayaran harus diunggah sebelum status bisa diubah menjadi Packing.');
        return;
    }

        $('#statusInput').val(newStatus);

        // Hide subtotal form for other statuses
        if (newStatus === 'Packing' || newStatus === 'Pengiriman' || newStatus === 'Selesai') {
            $('#subtotalGroup').hide();
        }

        submitForm();
    }
    function cancelOrder() {
        // Set status to Cancelled
        $('#statusInput').val('Cancelled');

        // Submit the form with the updated status
        submitForm(function() {
            alert('Order has been cancelled successfully.');
            // Optional: Redirect or reset the form if needed
            window.location.href = '{{ route("transaksi.index") }}'; // Redirect to the transaction index page
        });
    }


    function submitForm(callback = null) {
        $.ajax({
            url: '{{ route("transaksi.update", $order->id) }}',
            method: 'PUT',
            data: $('#statusForm').serialize(),
            success: function(response) {
                if (response.success) {
                    // Show success alert
                    alert(response.message);

                    // Execute the callback function if provided
                    if (callback) callback();

                    let currentStatus = $('#statusInput').val();
                    let nextStatusMap = {
                        'Menunggu Konfirmasi Admin': 'Diterima',
                        'Menunggu Konfirmasi Admin untuk Negosiasi': 'Negosiasi',
                        'Negosiasi': 'Diterima',
                        'Diterima': 'Packing',
                        'Packing': 'Pengiriman',
                        'Pengiriman': 'Selesai'
                    };

                    if (nextStatusMap[currentStatus]) {
                        let nextStatus = nextStatusMap[currentStatus];
                        $('#statusInput').val(nextStatus);

                        if (currentStatus === 'Diterima') {
                            updateButton('Update to Packing', 'Packing', 'btn-primary');
                        } else if (currentStatus === 'Packing') {
                            updateButton('Update to Pengiriman', 'Pengiriman', 'btn-primary');
                        } else if (currentStatus === 'Pengiriman') {
                            updateButton('Selesai', 'Selesai', 'btn-primary');
                        } else if (currentStatus === 'Selesai') {
                            $('#nextButton').remove();
                            alert('Order has been completed.');
                        }

                        if (nextStatus === 'Pengiriman') {
                            if ($('#resiGroup').length === 0) {
                                let nomorResiInput = `
                                    <div class="form-group" id="resiGroup">
                                        <label for="nomor_resi">Nomor Resi (Tracking Number)</label>
                                        <input type="text" name="nomor_resi" id="nomor_resi" class="form-control" placeholder="Enter Tracking Number">
                                    </div>`;
                                $('#statusForm').append(nomorResiInput);
                                $('#nomor_resi').focus();
                            }
                        } else {
                            $('#resiGroup').remove();
                        }
                    }

                } else {
                    alert('Failed to update the status. Please try again.');
                }
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred while updating the status. Please try again.';
                alert(errorMessage);
            }
        });
    }
    function updateButton(text, nextStatus, btnClass) {
        $('#accButton, #nextButton').text(text)
            .attr('onclick', `updateStatus('${nextStatus}')`)
            .removeClass('btn-success btn-warning btn-primary')
            .addClass(btnClass)
            .attr('id', 'nextButton');
    }

</script>


@endsection
