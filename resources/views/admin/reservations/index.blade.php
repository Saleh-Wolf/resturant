@extends('layouts.admin')

@section('title', 'Reservations')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4">
        Reservations
    </h1>

    <a href="{{ route('admin.reservations.create') }}"
       class="btn btn-primary mb-3">

        Create Reservation

    </a>

    <div class="card">
        <div class="card-body">

            Reservations Module

        </div>
    </div>

</div>

@endsection