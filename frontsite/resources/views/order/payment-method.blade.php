@extends('layouts.main')

@section('contents')
    <section class="head-title-destination single-page" style="background-image: url({{ asset('images/bg-head-2.jpg') }}">
        <div class="container">
            <div class="wrap-head">
               <div class="breadcrumb-style">
                   <nav aria-label="breadcrumb" class="list-breadcrumb">
                       <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item">Pemesanan</li>
                           <li class="breadcrumb-item active">Pembayaran</li>
                       </ol>
                   </nav>
               </div>
            </div>
        </div>
    </section>

    <section class="wrap-form-buyer payment-section space">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="title">
                        <h5>Metode Pembayaran</h5>
                    </div>
                    <div class="wrap-tab-payment">
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <div class="bg-default wrap">
                                    <ul class="nav nav-tabs tabs-left sideways">
                                        {!! $tabNav !!}
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-md-9">
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    {!! $tabContent !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card detail-pemesanan">
                        <div class="title-card">
                            <h5>Rincian Pemesanan</h5>
                        </div>
                        <div class="booking-code">
                            <h6>Kode Booking : <span>{{ $trans->trans_code}}</span></h6>
                        </div>
                        <div class="book-details">
                            <div class="img-book">
                                <img src="{{ config('constants.UPLOAD_PATH') . $productDetail['product_thumbnail'] }}" alt="img"/>
                            </div>
                            <div class="book-content">
                                <h3 class="book-title">{{$productDetail['title']}}</h3>
                                <p class="book-desc">{!!$productDetail['subtitle']!!}</p>
                            </div>
                        </div>
                        <div class="date-book hidden">
                            @if(isset($productDetail['schedule_date']))
                            <h5>Date</h5>
                            <p class="info-date">
                                <i class="fas fa-calendar-alt"></i> {{$productDetail['schedule_date']}}
                            </p>
                            @endif
                            @if($transDetail->product_type_id == '5')
                            <h5>Lokasi Penjemputan</h5>
                            <p class="info-date">
                                {{ $productDetail['pickup']['pickup_location'] }} {{ $productDetail['pickup']['pickup_time'] }}
                            </p>
                            <h5>Lokasi Pengembalian</h5>
                            <p class="info-date">
                                {{ $productDetail['pickup']['return_location'] }} {{ $productDetail['pickup']['return_time'] }}
                            </p>
                            @endif
                        </div>
                        <hr/>
                        <div class="wrap-total-book">
                            <div>
                                <div class="body-price-details">
                                    <ul>
                                        <li>Total Harga <span>IDR. {{ number_format($trans->total_product_price,0,',','.') }}</span></li>
                                        @if($transDetail->product_type_id == '2')
                                        <li>Biaya Pengiriman <span>IDR. {{ number_format($courier['courier_cost'],0,',','.') }}</span></li>
                                        @endif
                                        <li>Payment Fee <span>IDR.&nbsp;<span class="paymentfee"> {{ number_format($paymentFee,0,',','.') }}</span></span></li>
                                    </ul>
                                    <hr/>
                                    <h5 class="total">Total <span>IDR. <span class="totalAmount"> {{number_format($totalAmount,0,',','.')}}</span></span></h5>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<script id="midtrans-script" type="text/javascript"
	src="https://api.midtrans.com/v2/assets/js/midtrans-new-3ds.min.js" 
	data-environment="{{ config('constants.MIDTRANS.ENVIRONMENT') }}" 
	data-client-key="{{ config('constants.MIDTRANS.CLIENT_KEY') }}"></script>
<script type="text/javascript" src="{{ asset('js/payment.js') }}"></script>
<script type="text/javascript">

	$('.btn-payment').click(function(e) {
		e.preventDefault();
		var channel = $(this).data('channel');
		if(channel.length == 0) {
			$.alert('Kegagalan sistem pembayaran. Silahkan `refresh` halaman ini!', 'Peringatan');
			return false;
		}

		var orderId = '{{ $trans->trans_id }}';
		var tokenId = '';

		var param = {
			token_id: tokenId,
			order_id: orderId,
			channel: channel
		}

		payment.transactionCharge(param);
	});

    if($('.chk-tnc').length > 0) {
        $('.chk-tnc').change(function(e) {
            e.preventDefault();
            let id = $(this).data('channel');
            let checked = $(this).prop('checked');
            $('button.btn-payment.ch'+id).prop('disabled', !checked);
        });
        $('.chk-tnc').change();
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var totalPayment = parseInt('{{$totalProductPrice}}');
        var fee = $(e.target).data('fee'); // activated tab
        var percentFee = $(e.target).data('fee-percent'); // activated tab
        $('span.paymentfee').html(func.priceFormat(parseInt(fee)+parseInt(percentFee)));
        $('span.totalAmount').html(func.priceFormat(totalPayment+parseInt(fee)+parseInt(percentFee)));
    });
</script>
@endsection

@section('styles')
<style type="text/css">
	.form-group label {
		font-size: 14px;
		margin-bottom: 0px;
		padding-top: 7px;
	}

	.form-group input {
		font-weight: 300;
	}
	.form-control::placeholder {
		color: #b3b6b9;
	}
</style>
@endsection
