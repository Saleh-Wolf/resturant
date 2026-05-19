@extends('layouts.admin')

@section('title', 'Waiter Dashboard')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Waiter Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <a href="#" class="btn btn-primary btn-block">New Order</a>
        </div>

        <div class="col-md-4">
            <a href="#" class="btn btn-success btn-block">View Tables</a>
        </div>

        <div class="col-md-4">
            <a href="#" class="btn btn-warning btn-block">View Reservations</a>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">My Active Orders</h3>
        </div>
        <div class="card-body">
            <p class="text-muted mb-0">No active orders yet.</p>
        </div>
    </div>

</div>
@endsection
