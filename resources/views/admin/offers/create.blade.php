@extends('layouts.admin')

@section('title', 'Create Offer')

@section('content')
    <div class="container-fluid">

        <h1 class="mb-4">Create Offer</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.offers.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Offer Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Discount Type</label>
                        <select name="discount_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Discount Value</label>
                        <input type="number" step="0.01" name="discount_value" class="form-control"
                            value="{{ old('discount_value') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Applicable Items</label>

                        @foreach ($menuItems as $item)
                            <div class="form-check">
                                <input type="checkbox" name="menu_items[]" value="{{ $item->id }}"
                                    class="form-check-input" id="item_{{ $item->id }}">

                                <label class="form-check-label" for="item_{{ $item->id }}">
                                    {{ $item->name }} - {{ number_format($item->price, 2) }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>

                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="display_on_menu" class="form-check-input" id="display_on_menu" checked>

                        <label class="form-check-label" for="display_on_menu">
                            Display on Menu
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Offer
                    </button>

                </form>

            </div>
        </div>

    </div>
@endsection
