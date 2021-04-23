<nav class="navbar">
    <ul class="navbar-nav">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="flaticon-user-1"></i> Profile</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <li>
                    <a class="dropdown-item" href="{{ url('/customer/profile') }}">
                        <i class="flaticon-user-1"></i> Akun
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ url('/customer/order-list') }}">
                        <i class="flaticon-sticky-note"></i> Riwayat Pembelian
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ url('/customer/setting') }}">
                        <i class="flaticon-levels"></i> Pengaturan
                    </a>
                </li>
                <li><a id="cust-logout" class="dropdown-item cust-logout" href="#"><i class="flaticon-logout"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>