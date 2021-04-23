@extends('layouts.main')

@section('contents')
<section class="head-title-destination single-page" style="background-image: url({{asset('images/bg-head-2.jpg')}})">
        <div class="container">
            <div class="wrap-head">
                <div class="breadcrumb-style">
                    <nav aria-label="breadcrumb" class="list-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item">Pemesanan</li>
                            <li class="breadcrumb-item active">Menunggu Konfirmasi</li>
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
                    <h3>Menunggu Konfirmasi</h3>
                </div>
                <div class="success-payment-notif">
                    <h6>Terima kasih atas reservasi Anda. Pemesanan anda telah tercatat dan akan segera diinformasikan kepada Partner kami. Selanjutnya konfirmasi pemesanan akan dikirimkan ke email anda beserta detail informasi yang diperlukan.</h6>
                </div>
            </div>

            <div class="summary-booking col-12 col-md-6">
                <h5 class="title-summary">Detail Pesananan</h5>
                <div class="booking-code">
                    <h6>Kode Booking : <span>{{$trans->trans_code}}</span></h6>
                </div>
                <div class="book-details">
                    <div class="img-book">
                        <img src="{{config('constants.UPLOAD_PATH') . $transDetail['product_thumbnail']}}" alt="img"/>
                    </div>
                    <div class="book-content">
                        <h3 class="book-title">{{$transDetail['title']}}</h3>
                        <div class="date-book">
                            <h5>Date</h5>
                            <p class="info-date">
                                <i class="fas fa-calendar-alt"></i><span>{{ $transDetail['schedule_date'] }} WIB</span>
                            </p>
                        </div>
                        <div class="date-book">
                            <h5>Jumlah Pax :</h5>
                            <p class="info-date">
                                <span>{{ $transDetail['quantity'] }} Orang</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection