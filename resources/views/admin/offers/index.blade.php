@extends('layouts.admin')

@section('title', 'Offers')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Offers</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.offers.create') }}"
           class="btn btn-primary">
            Create Offer
        </a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Discount</th>
                        <th>Items Count</th>
                        <th>Validity</th>
                        <th>Status</th>
                        <th>Display On Menu</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($offers as $offer)
                        <tr>
                            <td>{{ $offer->name }}</td>

                            <td>
                                {{ ucfirst($offer->discount_type) }}
                                -
                                {{ $offer->discount_value }}
                            </td>

                            <td>{{ $offer->menu_items_count }}</td>

                            <td>
                                {{ $offer->start_date }}
                                →
                                {{ $offer->end_date }}
                            </td>

                            <td>
                                @if($offer->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>

                            <td>
                                @if($offer->display_on_menu)
                                    <span class="badge badge-info">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.offers.edit', $offer) }}"
                                       class="btn btn-sm btn-warning mr-1">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.offers.destroy', $offer) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this offer?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No offers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $offers->links() }}

        </div>
    </div>

</div>
@endsection