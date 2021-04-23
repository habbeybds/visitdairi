<ul class="sidebarMenuInner">
	@foreach($config['header_menulink'] as $menu)
	<li><a href="{{ str_replace('{baseurl}', url('/') . '/', $menu['link']) }}" title="{{ $menu['title'] }}">{{ $menu['name'] }}</a></li>
	@endforeach

	<div class="wrap_menu_side">
		@foreach($config['header_menulink'] as $menu)
			<li><a href="{{ str_replace('{baseurl}', url('/') . '/', $menu['link']) }}" title="{{ $menu['title'] }}">{{ $menu['name'] }}</a></li>
		@endforeach

		@if($auth->logged())
			<li><a href="{{ url('/customer/profile') }}"><i class="flaticon-user-1"></i> Profile</a></li>
			<li><a href="{{ route('search') }}"><i class="fas fa-search"></i> Search</a></li>
			<li><a id="cust-logout" class="cust-logout" href="#"><i class="flaticon-logout"></i> Logout</a></li>
		@else
			<li><a href="{{ route('login') }}"><i class="far fa-sign-in-alt"></i> Login</a></li>
			<li><a href="{{ route('register') }}"><i class="far fa-user-plus"></i> Daftar</a></li>
			<li><a href="{{ route('search') }}"><i class="fas fa-search"></i> Search</a></li>
			<li><a href="{{ route('checkOrder') }}"><i class="far fa-sticky-note"></i> Cek Pesanan</a></li>
		@endif	
	</div>
</ul>