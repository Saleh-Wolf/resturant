@extends('layouts.admin')

@section('title', 'Stock Movements')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">
        Stock Movements Report
    </h1>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Ingredient</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Reference</th>
                        <th>Notes</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($movements as $movement)

                        <tr>

                            <td>
                                {{ $movement->created_at->format('Y-m-d H:i') }}
                            </td>

                            <td>
                                {{ $movement->ingredient->name ?? '-' }}
                            </td>

                            <td>
                                @if($movement->type === 'addition')
                                    <span class="badge badge-success">
                                        Addition
                                    </span>
                                @elseif($movement->type === 'deduction')
                                    <span class="badge badge-danger">
                                        Deduction
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        Adjustment
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ number_format($movement->quantity, 2) }}
                            </td>

                            <td>
                                @if($movement->reference_type && $movement->reference_id)
                                    {{ class_basename($movement->reference_type) }}
                                    #{{ $movement->reference_id }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ $movement->notes ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                No stock movements found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $movements->links() }}

        </div>
    </div>

</div>
@endsection