<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">
            Restaurant System
        </span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">

                @if (Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}"
                            class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Categories</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.menu-items.index') }}"
                            class="nav-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-utensils"></i>
                            <p>Menu</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.tables.index') }}"
                            class="nav-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chair"></i>
                            <p>Tables</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.reservations.index') }}"
                            class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>Reservations</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.reports.sales') }}"
                            class="nav-link {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>Sales Report</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.reports.orders') }}"
                            class="nav-link {{ request()->routeIs('admin.reports.orders') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Orders Report</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.reservations') }}"
                            class="nav-link {{ request()->routeIs('admin.reports.reservations') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-calendar-alt"></i>

                            <p>Reservations Report</p>

                        </a>
                    </li>
                @elseif(Auth::user()->role === 'waiter')
                    <li class="nav-item">
                        <a href="{{ route('waiter.orders.index') }}"
                            class="nav-link {{ request()->routeIs('waiter.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Orders</p>
                        </a>
                    </li>
                @elseif(Auth::user()->role === 'kitchen_staff')
                    <li class="nav-item">
                        <a href="{{ route('kitchen.orders.index') }}"
                            class="nav-link {{ request()->routeIs('kitchen.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-fire"></i>
                            <p>Kitchen Orders</p>
                        </a>
                    </li>
                @elseif(Auth::user()->role === 'cashier')
                    <li class="nav-item">
                        <a href="{{ route('cashier.orders.index') }}"
                            class="nav-link {{ request()->routeIs('cashier.orders.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Cashier</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('cashier.orders.history') }}"
                            class="nav-link {{ request()->routeIs('cashier.orders.history') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>History</p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
    </div>

</aside>
