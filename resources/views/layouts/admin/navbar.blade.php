<nav class="main-header navbar navbar-expand navbar-white navbar-light"
     style="margin-top: 15px;">

    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown">

           <a class="nav-link d-flex align-items-center"
   data-toggle="dropdown"
   href="#">

    @if(auth()->user()->profile_picture)
        <img
            src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
            alt="Profile"
            style="
                width:80px;
                height:80px;
                border-radius:50%;
                object-fit:cover;
                border:2px solid #dee2e6;
            "
        >
    @else
        <i class="fas fa-user-circle"
           style="font-size:40px;"></i>
    @endif

    <span class="ml-2 text-dark font-weight-bold">
        {{ auth()->user()->name }}
    </span>

</a>





            <div class="dropdown-menu dropdown-menu-right">

                <span class="dropdown-item-text">
                    {{ auth()->user()->name }}
                </span>

                <div class="dropdown-divider"></div>

                <a href="{{ route('profile.edit') }}" class="dropdown-item">

                    <i class="fas fa-user mr-2"></i>
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="dropdown-item">

                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </form>

            </div>

        </li>

    </ul>

</nav>
