<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Restaurant Menu
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold">
            Restaurant Menu
        </h1>

        <p class="text-muted">

            Table #{{ $table->table_number }}

        </p>

    </div>

    @foreach($categories as $category)

        <div class="card mb-4 shadow-sm">

            <div class="card-header bg-dark text-white">

                <h3 class="mb-0">

                    {{ $category->name }}

                </h3>

            </div>

            <div class="card-body">

                <div class="row">

                    @foreach($category->menuItems as $item)

                        <div class="col-md-6 mb-3">

                            <div class="border rounded p-3 h-100">

                                <h5 class="mb-2">

                                    {{ $item->name }}

                                </h5>

                                @if($item->description)

                                    <p class="text-muted mb-2">

                                        {{ $item->description }}

                                    </p>

                                @endif

                                <strong class="text-success">

                                    {{ number_format($item->price, 2) }}

                                </strong>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    @endforeach

</div>

</body>

</html>