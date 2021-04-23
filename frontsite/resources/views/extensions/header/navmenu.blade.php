<ul class="navbar-nav">
    @if($auth->logged())
    <li class="nav-item"><a href="{{ url('/customer/profile') }}" class="nav-link"><i class="flaticon-user-1"></i> Profile</a></li>
    <li class="nav-item"><a href="{{ route('search') }}" class="nav-link"><i class="fas fa-search"></i> Search</a></li>
    <li class="nav-item"><a id="cust-logout" class="cust-logout" href="#" class="nav-link"><i class="flaticon-logout"></i> Logout</a></li>
    @else

    @foreach($config['header_menulink'] as $menu)
    <li class="nav-item"><a href="{{ str_replace('{baseurl}', url('/') . '/', $menu['link']) }}" title="{{ $menu['title'] }}" class="nav-link"><i class="fa fa-bars" aria-hidden="true"></i> {{ $menu['name'] }}</a></li>
    @endforeach

    <li class="nav-item">
        <a href="{{ route('checkOrder') }}" class="nav-link"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Cek Pesanan</a>
    </li>

    <li class="nav-item position-relative hide-mobile">
        <a class="nav-link cursor-pointer" rel="tooltip" title="Search" href="{{route('search')}}">
            <i class="fa fa-search"></i>
            <p class="d-lg-none">Search</p>
        </a>
    </li>
    <li class="nav-item">
        <div class="nav-link menu-login">
            <i class="fa fa-user" aria-hidden="true"></i> <a href="{{ route('login') }}"> Login </a>/
            <a href="{{ route('register') }}"> Daftar </a>
        </div>
    </li>

    @endif
</ul>