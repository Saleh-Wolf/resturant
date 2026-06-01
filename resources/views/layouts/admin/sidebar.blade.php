<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">
            Restaurant System
        </span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column">

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
               

            </ul>

        </nav>

    </div>

</aside>
