<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($todaySales, 2) }}</h3>
                <p>Today's Sales</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $todayOrders }}</h3>
                <p>Today's Orders</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $occupiedTables }}</h3>
                <p>Occupied Tables</p>
            </div>
            <div class="icon">
                <i class="fas fa-chair"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $confirmedReservations }}</h3>
                <p>Confirmed Reservations</p>
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
                <h3>{{ $availableTables }}</h3>
                <p>Available Tables</p>
            </div>
            <div class="icon">
                <i class="fas fa-table"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $arrivedReservations }}</h3>
                <p>Arrived Reservations</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-orange">
            <div class="inner">
                <h3>{{ $pendingOrders }}</h3>
                <p>Pending Orders</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-teal">
            <div class="inner">
                <h3>{{ $readyOrders }}</h3>
                <p>Ready Orders</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

</div>