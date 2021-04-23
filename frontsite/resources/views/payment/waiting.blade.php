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
                            <li class="breadcrumb-item">Pembayaran</li>
                            <li class="breadcrumb-item active">Menunggu Pembayaran</li>
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
                    <h3>Menunggu Pembayaran</h3>
                </div>
                <div class="success-payment-notif">
                    <h6>Silahkan lakukan pembayaran untuk menyelasaikan pesanan Anda.</h6>
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
                        <p class="book-desc">{{$transDetail['subtitle']}}</p>
                        @if($date)
                        <div class="date-book">
                            <h5>Date</h5>
                            <p class="info-date">
                                <i class="fas fa-calendar-alt"></i> {{$transDetail['schedule_date']}}
                            </p>
                        </div>
                        @endif
                        <div class="date-book">
                            <p class="info-date">
                                Jumlah : {{$transDetail['quantity']}}
                            </p>
                        </div>
                    </div>
                </div>
                
                <hr />

                <div class="time-limit">
                    <h6>Bayar sebelum {{ $timelimit }} WIB</h6>
                </div>
                <div class="waiting-payment-card">
                    <div class="content">
                        <div class="payment-item">
                            <div class="total">
                                <p>Total Pembayaran</p>
                                <h6>Rp. {{number_format($payment->total_payment,0,',','.')}}</h6>
                            </div>
                            <div class="method-payment">
                                <p>Metode Pembayaran</p>
                                <h6>{{$payMethod}}</h6>
                            </div>
                            <div class="method-payment">
                                <p>Bank</p>
                                <h6>{{$payment->bank}}</h6>
                            </div>
                            <div class="no-virtual">
                                <p>Nomor Virtual Account</p>
                                <h6>{{$payment->va_number}}<span class="copy">Salin</span></h6>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="payment-detail col-12 col-md-6 d-none">
                <h5 class="title-summary">Tata Cara Pembayaran</h5>
                <div class="detail-pay-content">
                    <div id="cara-tf" class="wrap-how-pay">
                        <li class="list-group-item" data-toggle="collapse" data-target="#cara-tf-atm">
                            <h6 class="mt-2">Transfer Melalui ATM</h6>
                        </li>
                        <div id="cara-tf-atm" class="collapse" data-parent="#cara-tf">
                            <ol>
                                <li>Input kartu ATM dan PIN Anda</li>
                                <li>Pilih Menu Bayar/Beli</li>
                                <li>Pilih Lainnya</li>
                                <li>Pilih Multi Payment</li>
                                <li>Input 70014 sebagai Kode Institusi</li>
                                <li>Input Virtual Account Number, misal. 7001 4501 0052 1774</li>
                                <li>Pilih Benar</li>
                                <li>Pilih Ya</li>
                                <li>Ambil bukti bayar Anda</li>
                                <li>Selesai</li>
                            </ol>
                        </div>

                        <li class="list-group-item" data-toggle="collapse" data-target="#cara-tf-mobile">
                            <h6 class="mt-2">Transfer Melalui Mobile Banking</h6>
                        </li>
                        <div id="cara-tf-mobile" class="collapse" data-parent="#cara-tf">
                            <ol>
                                <li>Input kartu ATM dan PIN Anda</li>
                                <li>Pilih Menu Bayar/Beli</li>
                                <li>Pilih Lainnya</li>
                                <li>Pilih Multi Payment</li>
                                <li>Input 70014 sebagai Kode Institusi</li>
                                <li>Input Virtual Account Number, misal. 7001 4501 0052 1774</li>
                                <li>Pilih Benar</li>
                                <li>Pilih Ya</li>
                                <li>Ambil bukti bayar Anda</li>
                                <li>Selesai</li>
                            </ol>
                        </div>

                        <li class="list-group-item" data-toggle="collapse" data-target="#cara-tf-ibanking">
                            <h6 class="mt-2">Transfer Melalui Internet Banking</h6>
                        </li>
                        <div id="cara-tf-ibanking" class="collapse" data-parent="#cara-tf">
                            <ol>
                                <li>Input kartu ATM dan PIN Anda</li>
                                <li>Pilih Menu Bayar/Beli</li>
                                <li>Pilih Lainnya</li>
                                <li>Pilih Multi Payment</li>
                                <li>Input 70014 sebagai Kode Institusi</li>
                                <li>Input Virtual Account Number, misal. 7001 4501 0052 1774</li>
                                <li>Pilih Benar</li>
                                <li>Pilih Ya</li>
                                <li>Ambil bukti bayar Anda</li>
                                <li>Selesai</li>
                            </ol>
                        </div>

                        <li class="list-group-item" data-toggle="collapse" data-target="#cara-tf-smsbanking">
                            <h6 class="mt-2">Transfer Melalui SMS Banking</h6>
                        </li>
                        <div id="cara-tf-smsbanking" class="collapse" data-parent="#cara-tf">
                            <ol>
                                <li>Input kartu ATM dan PIN Anda</li>
                                <li>Pilih Menu Bayar/Beli</li>
                                <li>Pilih Lainnya</li>
                                <li>Pilih Multi Payment</li>
                                <li>Input 70014 sebagai Kode Institusi</li>
                                <li>Input Virtual Account Number, misal. 7001 4501 0052 1774</li>
                                <li>Pilih Benar</li>
                                <li>Pilih Ya</li>
                                <li>Ambil bukti bayar Anda</li>
                                <li>Selesai</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <!-- <div class="summary-booking col-12 col-md-6">
                <a href="#" class="btn btn-primary btn-block">Sudah Bayar</a>
            </div> -->

        </div>
    </section>
@endsection