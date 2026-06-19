@extends('layouts.admin')

@section('title', 'Reservation Details')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h1>Reservation Details</h1>

        <a href="{{ route('admin.reservations.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Customer Information</h3>
        </div>

        <div class="card-body">
            <p><strong>Name:</strong> {{ $reservation->customer_name }}</p>
            <p><strong>Phone:</strong> {{ $reservation->customer_phone }}</p>
            <p><strong>Guests:</strong> {{ $reservation->guest_count }}</p>
            <p><strong>Special Occasion:</strong> {{ $reservation->special_occasion ?? '-' }}</p>
            <p><strong>Notes:</strong> {{ $reservation->notes ?? '-' }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Reservation Information</h3>
        </div>

        <div class="card-body">
            <p><strong>Reservation No:</strong> {{ $reservation->reservation_number }}</p>
            <p><strong>Type:</strong> {{ ucfirst($reservation->reservation_type) }}</p>
            <p><strong>Table:</strong> {{ $reservation->table->table_number ?? '-' }}</p>
            <p><strong>Date:</strong> {{ $reservation->reservation_date }}</p>
            <p><strong>Time:</strong> {{ $reservation->reservation_time ?? '-' }}</p>
            <p><strong>Duration:</strong> {{ $reservation->estimated_duration ?? '-' }} h</p>

            <p>
                <strong>Status:</strong>
                <span class="badge badge-info">
                    {{ ucwords(str_replace('_', ' ', $reservation->status)) }}
                </span>
            </p>
        </div>
    </div>

    @if($reservation->order)
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Linked Order</h3>
            </div>

            <div class="card-body">
                <p><strong>Order #:</strong> #{{ $reservation->order->id }}</p>
                <p><strong>Status:</strong> {{ ucfirst($reservation->order->status) }}</p>
                <p><strong>Total:</strong> {{ number_format($reservation->order->total, 2) }} EGP</p>

                <a href="{{ route('waiter.orders.show', $reservation->order) }}"
                   class="btn btn-info">
                    View Order
                </a>
            </div>
        </div>
    @endif

</div>
@endsection