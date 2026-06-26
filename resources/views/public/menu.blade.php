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
    Welcome to Table {{ $table->table_number }}
</h1>

<p class="text-muted mb-1">
    {{ ucfirst($table->type) }} Table
</p>

<p class="text-muted">
    Browse our full menu below
</p>

<div class="alert alert-info mt-3">
    <strong>Restaurant System</strong><br>
    Phone: 01000000000<br>
    Address: Main Branch
</div>

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

                        @php
                            $activeOffer = $item->offers()
                                ->where('is_active', true)
                                ->where('display_on_menu', true)
                                ->whereDate('start_date', '<=', today())
                                ->whereDate('end_date', '>=', today())
                                ->first();

                            $discountedPrice = $item->price;
                            $discount = 0;

                            if ($activeOffer) {
                                if ($activeOffer->discount_type === 'percentage') {
                                    $discount = ($item->price * $activeOffer->discount_value) / 100;
                                } else {
                                    $discount = $activeOffer->discount_value;
                                }

                                $discount = min($discount, $item->price);
                                $discountedPrice = $item->price - $discount;
                            }
                        @endphp

                        <div class="col-md-6 mb-3">

                            <div class="border rounded p-3 h-100 bg-white">

                                <div class="d-flex justify-content-between align-items-start">

                                    <h5 class="mb-2">
                                        {{ $item->name }}
                                    </h5>

                                    @if($activeOffer)
                                        <span class="badge bg-success">
                                            OFFER
                                        </span>
                                    @endif

                                </div>

                                @if($item->description)

                                    <p class="text-muted mb-2">
                                        {{ $item->description }}
                                    </p>

                                @endif

                                @if($activeOffer)

                                    <p class="mb-1">
                                        <small class="text-success fw-bold">
                                            {{ $activeOffer->name }}
                                        </small>
                                    </p>

                                    <div>
                                        <span class="text-muted me-2">
                                            <del>
                                                {{ number_format($item->price, 2) }} EGP
                                            </del>
                                        </span>

                                        <strong class="text-success">
                                            {{ number_format($discountedPrice, 2) }} EGP
                                        </strong>
                                    </div>

                                @else

                                    <strong class="text-success">
                                        {{ number_format($item->price, 2) }} EGP
                                    </strong>

                                @endif

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