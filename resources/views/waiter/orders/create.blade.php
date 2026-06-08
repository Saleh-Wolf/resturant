@extends('layouts.admin')

@section('title', 'Create Order')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4">
        Create Order
    </h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('waiter.orders.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Table</label>

            <select name="restaurant_table_id" class="form-control" required>
                <option value="">Select Table</option>

                @foreach($tables as $table)
                    <option value="{{ $table->id }}">
                        {{ $table->table_number }} - {{ ucfirst($table->type) }}
                        ({{ $table->min_capacity }} - {{ $table->max_capacity }} persons)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Menu Items</label>

            @foreach($menuItems as $item)

                @php
                    $activeOffer = $item->offers()
                        ->where('is_active', true)
                        ->whereDate('start_date', '<=', today())
                        ->whereDate('end_date', '>=', today())
                        ->first();

                    $discountedPrice = $item->price;
                    $discount = 0;

                    if ($activeOffer) {
                        if ($activeOffer->discount_type === 'percentage') {
                            $discount = ($item->price * $activeOffer->discount_value) / 100;
                        } else {
                            $discount = $activeOffer->discount_value;
                        }

                        $discount = min($discount, $item->price);
                        $discountedPrice = $item->price - $discount;
                    }
                @endphp

                <div class="border rounded p-3 mb-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <strong>
                                {{ $item->name }}
                            </strong>

                            @if($activeOffer)
                                <span class="badge badge-success ml-2">
                                    OFFER
                                </span>

                                <div>
                                    <small class="text-muted">
                                        {{ $activeOffer->name }}
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div>
                            @if($activeOffer)
                                <span class="text-muted mr-2">
                                    <del>
                                        {{ number_format($item->price, 2) }} EGP
                                    </del>
                                </span>

                                <strong class="text-success">
                                    {{ number_format($discountedPrice, 2) }} EGP
                                </strong>
                            @else
                                <span class="text-muted">
                                    {{ number_format($item->price, 2) }} EGP
                                </span>
                            @endif
                        </div>

                    </div>

                    @if($activeOffer)
                        <div class="mt-2">
                            <small class="text-success">
                                Discount:
                                {{ number_format($discount, 2) }} EGP
                            </small>
                        </div>
                    @endif

                    <input type="number"
                           name="items[{{ $item->id }}][quantity]"
                           class="form-control mt-2"
                           placeholder="Quantity"
                           min="0"
                           value="0">

                    <input type="text"
                           name="items[{{ $item->id }}][notes]"
                           class="form-control mt-2"
                           placeholder="Notes">

                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">
            Place Order
        </button>
    </form>

</div>

@endsection