@extends('layouts.admin')

@section('title', 'Table QR Code')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">

                Table {{ $table->table_number }}

            </h3>
        </div>

        <div class="card-body text-center">

            {!! QrCode::size(300)->generate(
                route('scan.menu', $table->qr_token)
            ) !!}

            <hr>

            <p>

                {{ route('scan.menu', $table->qr_token) }}

            </p>

        </div>

    </div>

</div>

@endsection