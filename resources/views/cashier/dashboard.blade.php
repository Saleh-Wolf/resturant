@extends('layouts.admin')

@section('title', 'Cashier Dashboard')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Cashier Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>0</h3>
                    <p>Pending Bills</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>0</h3>
                    <p>Today's Revenue</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Orders Ready For Billing</h3>
        </div>
        <div class="card-body">
            <p class="text-muted mb-0">No orders ready for billing yet.</p>
        </div>
    </div>

</div>
@endsection
