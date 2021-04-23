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
                        <li class="breadcrumb-item active">Cek Pesanan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="payment-landing-page space">
    <div class="container">

    	@if($trans)
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
                    <p class="book-desc">{!!$transDetail['subtitle']!!}</p>
                    @if(isset($transDetail['schedule_date']))
                    <div class="date-book">
                        <h5>Date</h5>
                        <p class="info-date">
                            <i class="fas fa-calendar-alt"></i> {{$transDetail['schedule_date']}}
                        </p>
                    </div>
                    @endif
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

        @if($payment)
        <div class="payment-detail col-12 col-md-6">
            <h5 class="title-summary">Detail Pembayaran</h5>
            <div class="detail-pay-content">
                <div>
                    <label>Nomor Transaksi : <span>{{$trans->invoice_number}}</span></label>
                </div>
                <hr class="hr-dashed margin">
                <div>
                    <label>Type Pembayaran : <span>{{$payment->payment_channel_name}}</span></label>
                </div>
                <hr class="hr-dashed margin">
                <div>
                    <label>Total Pembayaran : <span>Rp. {{number_format($payment->total_payment,0,',','.')}}</span></label>
                </div>
                
            </div>
        </div>
        @endif
        @else
        <div class="payment-detail col-12 col-md-6">
        	<h5 class="title-summary">Tidak Ditemukan!</h5>
            <div class="detail-pay-content">
            	<div>
            		<label>Kode pesanan <b>{{ $orderId }}</b> tidak ditemukan atau Email anda tidak sesuai. Pastikan kode pesanan yang anda masukkan sudah sesuai.</label>
                </div>
                <hr class="hr-dashed margin">
                <div>
                    <a href="{{ route('checkOrder') }}" class="btn btn-info btn-sm"><i class="fas fa-history"></i> Kembali ke cari pesanan</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection