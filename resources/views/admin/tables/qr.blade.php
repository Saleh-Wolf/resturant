@extends('layouts.admin')

@section('title', 'Table QR Code')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                Table {{ $table->table_number }} QR Code
            </h3>

            <a href="{{ route('admin.tables.index') }}"
               class="btn btn-secondary btn-sm">
                Back to Tables
            </a>
        </div>

        <div class="card-body text-center">

            @if (session('success'))
                <div class="alert alert-success text-left">
                    {{ session('success') }}
                </div>
            @endif

            <div id="qr-print-area">

                <h4>
                    Table {{ $table->table_number }}
                </h4>

                <p>
                    {{ ucfirst($table->type) }}
                </p>

                <div class="my-4">
                    {!! QrCode::size(300)->generate(
                        route('public.table.menu', [
                            $table->table_number,
                            $table->qr_token,
                        ])
                    ) !!}
                </div>

                <p id="qr-link">
                    {{ route('public.table.menu', [
                        $table->table_number,
                        $table->qr_token,
                    ]) }}
                </p>

            </div>

            <hr>

            <div class="mt-3">

                <a href="{{ route('admin.tables.qr.download', $table) }}"
                   class="btn btn-success">
                    Download QR
                </a>

                <button type="button"
                        onclick="window.print()"
                        class="btn btn-primary">
                    Print QR
                </button>

                <button type="button"
                        onclick="copyQrLink()"
                        class="btn btn-info">
                    Copy Link
                </button>

                <form action="{{ route('admin.tables.qr.regenerate', $table) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Regenerate QR code? Old QR link will stop working.')">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-danger">
                        Regenerate QR
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<script>
    function copyQrLink() {
        const link = document.getElementById('qr-link').innerText.trim();

        navigator.clipboard.writeText(link);

        alert('QR link copied successfully');
    }
</script>

@endsection