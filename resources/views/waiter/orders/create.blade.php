
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
            <div class="border p-2 mb-2">
                <strong>{{ $item->name }}</strong>
                <span class="text-muted">- {{ number_format($item->price, 2) }} EGP</span>

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