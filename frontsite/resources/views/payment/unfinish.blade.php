@extends('layouts.main')
@section('contents')
<section class="head-title-destination single-page" style="background-image: url({{ asset('images/bg-head-2.jpg') }})">
        <div class="container">
            <div class="wrap-head">
                <div class="breadcrumb-style">
                    <nav aria-label="breadcrumb" class="list-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item">Pemesanan</li>
                            <li class="breadcrumb-item">Pembayaran</li>
                            <li class="breadcrumb-item active">Gagal Bayar</li>
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
                    <h3>Pembayaran Gagal</h3>
                </div>
                <div class="success-payment-notif">
                    <h1><i class="far fa-times-circle fail"></i></h1>
                    <h6>Mohon maaf pembayaran anda gagal maka dari itu pesanan anda akan dibatalkan. Silahkan lakukan pemesanan ulang dengan detail berikut.</h6>
                </div>
            </div>

            <div class="summary-booking col-12 col-md-6">
                <h5 class="title-summary">Detail Pesananan</h5>
                <div class="booking-code">
                    <h6>Kode Booking : <span>{{$trans->trans_code}}</span></h6>
                </div>
                <div class="book-details">
                    <div class="img-book">
                        <img src="{{config('constants.UPLOAD_DIR') . $transDetail['product_thumbnail']}}" alt="img"/>
                    </div>
                    <div class="book-content">
                        <h3 class="book-title">{{$transDetail['title']}}</h3>
                        <p class="book-desc">{{$transDetail['subtitle']}}</p>
                        <div class="date-book">
                            <h5>Date</h5>
                            <p class="info-date">
                                <i class="fas fa-calendar-alt"></i> {{$transDetail['schedule_date']}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="payment-detail col-12 col-md-6">
                <h5 class="title-summary">Informasi Kontak</h5>
                <div class="detail-pay-content">
                    <div>
                        <label>Nama Kontak : <span>{{$trans->customer_name}}</span></label>
                    </div>
                    <hr class="hr-dashed margin">
                    <div>
                        <label>Phone Number : <span>{{$trans->customer_phone}}</span></label>
                    </div>
                    <hr class="hr-dashed margin">
                    <div>
                        <label>Email : <span>{{$trans->customer_email}}</span></label>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection