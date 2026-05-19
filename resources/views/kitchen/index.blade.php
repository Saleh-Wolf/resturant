@extends('layouts.admin')

@section('title', 'Kitchen View')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Kitchen View</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Pending Orders</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">No pending orders.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">In Preparation</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">No orders in preparation.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
