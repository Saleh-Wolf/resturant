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

            @if (isset($reservation) && $reservation)
                <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                <div class="alert alert-info">
                    <strong>Reservation:</strong>
                    {{ $reservation->reservation_number }}

                    <br>

                    <strong>Customer:</strong>
                    {{ $reservation->customer_name }}

                    <br>

                    <strong>Guests:</strong>
                    {{ $reservation->guest_count }}
                </div>
            @endif
           <div class="form-group">

    <label>Order Type</label>

    <select name="order_type"
            id="orderType"
            class="form-control"
            required>

        <option value="dine_in"
            {{ isset($reservation) && $reservation ? 'selected' : '' }}>
            Dine In
        </option>

        <option value="takeaway">
            Takeaway
        </option>

    </select>

</div>





           <div class="form-group" id="tableSection">

    <label>Table</label>

    <select name="restaurant_table_id"
            id="restaurantTableSelect"
            class="form-control">

        <option value="">
            Select Table
        </option>

        @foreach ($tables as $table)
            <option value="{{ $table->id }}"
                {{ isset($reservation) && $reservation && $reservation->restaurant_table_id == $table->id ? 'selected' : '' }}>

                {{ $table->table_number }}
                -
                {{ ucfirst($table->type) }}
                ({{ $table->min_capacity }} - {{ $table->max_capacity }} persons)

            </option>
        @endforeach

    </select>

</div>







            <div class="card mb-4">
                <div class="card-body">

                    <div class="form-group mb-2">
                        <label>Search Menu Items</label>

                        <div class="input-group">
                            <input type="text" id="menuSearch" class="form-control" placeholder="Search by item name...">

                            <div class="input-group-append">
                                <button type="button" id="clearSearch" class="btn btn-secondary">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="suggestions" class="list-group" style="display:none;">
                    </div>

                </div>
            </div>


            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        Order Summary
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row text-center">

                        <div class="col-md-3">
                            <strong>Items Selected</strong>
                            <h4 id="summaryItems">0</h4>
                        </div>

                        <div class="col-md-3">
                            <strong>Subtotal</strong>
                            <h4 id="summarySubtotal">0.00 EGP</h4>
                        </div>

                        <div class="col-md-3">
                            <strong>Discount</strong>
                            <h4 id="summaryDiscount">0.00 EGP</h4>
                        </div>

                        <div class="col-md-3">
                            <strong>Final Total</strong>
                            <h4 id="summaryTotal" class="text-success">0.00 EGP</h4>
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Menu Items</label>



                @foreach ($menuItems as $item)
                    @php
                        $activeOffer = $item
                            ->offers()
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

                    <div class="border rounded p-3 mb-3 menu-item-card" data-name="{{ strtolower($item->name) }}"
                        data-id="{{ $item->id }}" data-original-price="{{ $item->price }}"
                        data-unit-price="{{ $discountedPrice }}" data-discount="{{ $discount }}">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <strong class="menu-item-name">
                                    {{ $item->name }}
                                </strong>

                                @if ($activeOffer)
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
                                @if ($activeOffer)
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

                        @if ($activeOffer)
                            <div class="mt-2">
                                <small class="text-success">
                                    Discount:
                                    {{ number_format($discount, 2) }} EGP
                                </small>
                            </div>
                        @endif

                        <input type="number" name="items[{{ $item->id }}][quantity]"
                            class="form-control mt-2 quantity-input" placeholder="Quantity" min="0" value="0">

                        <input type="text" name="items[{{ $item->id }}][notes]" class="form-control mt-2"
                            placeholder="Notes">

                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">
                Place Order
            </button>
        </form>

    </div>

 <script>
    const searchInput = document.getElementById('menuSearch');
    const clearSearchButton = document.getElementById('clearSearch');
    const suggestionsBox = document.getElementById('suggestions');
    const itemCards = document.querySelectorAll('.menu-item-card');
    const quantityInputs = document.querySelectorAll('.quantity-input');

    const summaryItems = document.getElementById('summaryItems');
    const summarySubtotal = document.getElementById('summarySubtotal');
    const summaryDiscount = document.getElementById('summaryDiscount');
    const summaryTotal = document.getElementById('summaryTotal');

    const orderType = document.getElementById('orderType');
    const tableSection = document.getElementById('tableSection');
    const restaurantTableSelect = document.getElementById('restaurantTableSelect');

    function formatMoney(value) {
        return Number(value).toFixed(2) + ' EGP';
    }

    function resetSearch() {
        searchInput.value = '';
        suggestionsBox.innerHTML = '';
        suggestionsBox.style.display = 'none';

        itemCards.forEach(function(card) {
            card.style.display = 'block';
        });
    }

    function updateOrderSummary() {
        let selectedItems = 0;
        let subtotal = 0;
        let discountTotal = 0;
        let finalTotal = 0;

        itemCards.forEach(function(card) {
            const quantityInput = card.querySelector('.quantity-input');
            const quantity = parseInt(quantityInput.value) || 0;

            if (quantity > 0) {
                selectedItems += quantity;

                const originalPrice = parseFloat(card.dataset.originalPrice) || 0;
                const unitPrice = parseFloat(card.dataset.unitPrice) || 0;
                const discount = parseFloat(card.dataset.discount) || 0;

                subtotal += originalPrice * quantity;
                discountTotal += discount * quantity;
                finalTotal += unitPrice * quantity;
            }
        });

        summaryItems.textContent = selectedItems;
        summarySubtotal.textContent = formatMoney(subtotal);
        summaryDiscount.textContent = formatMoney(discountTotal);
        summaryTotal.textContent = formatMoney(finalTotal);
    }

    function toggleTableSection() {
        if (orderType.value === 'takeaway') {
            tableSection.style.display = 'none';
            restaurantTableSelect.removeAttribute('required');
            restaurantTableSelect.value = '';
        } else {
            tableSection.style.display = 'block';
            restaurantTableSelect.setAttribute('required', 'required');
        }
    }

    searchInput.addEventListener('input', function() {
        const searchValue = this.value.toLowerCase().trim();

        suggestionsBox.innerHTML = '';

        if (searchValue === '') {
            resetSearch();
            return;
        }

        let matches = 0;

        itemCards.forEach(function(card) {
            const itemName = card.dataset.name;

            if (itemName.includes(searchValue)) {
                card.style.display = 'block';
                matches++;

                const suggestion = document.createElement('button');
                suggestion.type = 'button';
                suggestion.className = 'list-group-item list-group-item-action';
                suggestion.textContent = card.querySelector('.menu-item-name').textContent.trim();

                suggestion.addEventListener('click', function() {
                    searchInput.value = suggestion.textContent;
                    suggestionsBox.style.display = 'none';

                    itemCards.forEach(function(otherCard) {
                        otherCard.style.display = 'none';
                    });

                    card.style.display = 'block';

                    card.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    const quantityInput = card.querySelector('.quantity-input');

                    if (quantityInput) {
                        quantityInput.focus();
                    }
                });

                suggestionsBox.appendChild(suggestion);
            } else {
                card.style.display = 'none';
            }
        });

        suggestionsBox.style.display = 'block';

        if (matches === 0) {
            suggestionsBox.innerHTML =
                '<div class="list-group-item text-muted">No items found</div>';
        }
    });

    quantityInputs.forEach(function(input) {
        input.addEventListener('input', updateOrderSummary);
    });

    clearSearchButton.addEventListener('click', function() {
        resetSearch();
        searchInput.focus();
    });

    orderType.addEventListener('change', toggleTableSection);

    toggleTableSection();
    updateOrderSummary();
</script>

@endsection
