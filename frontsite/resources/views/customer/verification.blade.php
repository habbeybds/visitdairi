@extends('layouts.main')
@section('contents')
<section class="head-title-destination single-page" style="background-image: url({{ asset('images/bg-head-2.jpg') }})">
    <div class="container">
        <div class="wrap-head">
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Verifikasi Sukses</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="payment-landing-page space">
    <div class="container">

        <div class="wrap-head-landing col-12 col-md-6">
            <div class="title-landing">
                <h3>Verifikasi Berhasil</h3>
            </div>
            <div class="success-payment-notif">
                <h1><i class="far fa-check-circle"></i></h1>
                <h6>Terimakasih telah melakukan verifikasi akun di visitdairi.com. Silahkan login ke portal member visitdairi dan temukan kemudahan dalam bertansaksi anda.</h6>
            </div>
        </div>

    </div>
</section>
@endsection