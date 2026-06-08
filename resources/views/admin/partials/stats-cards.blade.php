<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">

            <div class="inner">
                <h3>
                    {{ number_format($todaySales, 2) }}
                </h3>

                <p>
                    Today's Sales
                </p>
            </div>

            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>

        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">

            <div class="inner">
                <h3>
                    {{ $todayOrders }}
                </h3>

                <p>
                    Today's Orders
                </p>
            </div>

            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>

        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">

            <div class="inner">
                <h3>
                    {{ $occupiedTables }}
                </h3>

                <p>
                    Occupied Tables
                </p>
            </div>

            <div class="icon">
                <i class="fas fa-chair"></i>
            </div>

        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">

            <div class="inner">
                <h3>
                    {{ $pendingReservations }}
                </h3>

                <p>
                    Pending Reservations
                </p>
            </div>

            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>

        </div>
    </div>

</div>

<div class="row mt-3">

    <div class="col-lg-3 col-6">

        <div class="small-box bg-primary">

            <div class="inner">

                <h3>
                    {{ $availableTables }}
                </h3>

                <p>
                    Available Tables
                </p>

            </div>

            <div class="icon">
                <i class="fas fa-table"></i>
            </div>

        </div>

    </div>

</div>