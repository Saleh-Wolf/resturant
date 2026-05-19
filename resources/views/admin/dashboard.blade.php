@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <h1 class="mb-4">Admin Dashboard</h1>
    </div>
</div>

<div class="container-fluid">

    @include('admin.partials.stats-cards')

    <div class="row mt-4">

        <div class="col-md-8">

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">
                        Recent Orders
                    </h3>
                </div>

                <div class="card-body">
                    <p class="text-muted mb-0">
                        No recent orders yet.
                    </p>
                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">
                        Quick Links
                    </h3>
                </div>

                <div class="card-body">

                    <a href="#" class="btn btn-primary btn-block mb-2">
                        Manage Users
                    </a>

                    <a href="#" class="btn btn-success btn-block mb-2">
                        Manage Menu
                    </a>

                    <a href="#" class="btn btn-warning btn-block mb-2">
                        Manage Tables
                    </a>

                    <a href="#" class="btn btn-info btn-block">
                        View Reports
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
