@extends('layouts.main')

@section('contents')
<section class="head-title-destination single-page" style="background-image: url({{ asset('images/bg-head-2.jpg') }})">
    <div class="container">
        <div class="wrap-head">
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="profile space">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-3 side-menu-profile">
                <ul id="profileTab" role="tablist">
                    <li><a href="{{ url('customer/profile') }}" class="{{ Request::is('customer/profile') ? 'active' : '' }}"><i class="flaticon-user-1"></i> Akun</a></li>
                    <li><a href="{{ url('customer/order-list') }}" class="{{ Request::is('customer/order-list') ? 'active' : '' }}"><i class="flaticon-sticky-note"></i> Riwayat Pembelian</a></li>
                    <li><a href="{{ url('customer/setting') }}" class="{{ Request::is('customer/setting') ? 'active' : '' }}"><i class="flaticon-levels"></i> Pengaturan</a></li>
                    <li><a href="{{ url('customer/logout') }}" class="cust-logout"><i class="flaticon-logout"></i> Keluar</a></li>
                </ul>   
            </div>
            <div class="div-12 col-md-9 body-profile">
                <div class="tab-content" id="myTabContent">
                    @include('customer/'.$layout)
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@include('customer/scripts/asset-'.$layout)
