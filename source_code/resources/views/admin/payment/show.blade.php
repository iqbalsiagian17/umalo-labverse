@extends('layouts.admin.master')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Payment Details</h1>

    <div class="card">
        <div class="card-body">
            <h5>Payment ID: {{ $payment->id }}</h5>
            <h5>Order ID: {{ $payment->order_id }}</h5>
            <h5>Customer: {{ $payment->order->user->full_name ?? $payment->order->user->name }}</h5>
            <h5>Status: {{ ucfirst($payment->status) }}</h5>
            <h5>Payment Proof:</h5>
            @if($payment->payment_proof)
                <a href="{{ asset($payment->payment_proof) }}" target="_blank" class="btn btn-primary">View Proof</a>
            @else
                <span class="text-muted">No proof uploaded</span>
            @endif
            <hr>
            @if($payment->status === 'pending')
                <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Verify Payment</button>
                </form>
                <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this payment?')">Reject</button>
                </form>
            @endif
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Back to Payments</a>
        </div>
    </div>
</div>
@endsection
