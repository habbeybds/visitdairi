@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{ asset('images/bg-head-4.jpg') }})">
        <div class="container">
            <div class="wrap-head">
               <div class="breadcrumb-style">
                   <nav aria-label="breadcrumb" class="list-breadcrumb">
                       <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item">Souvenir</li>
                           <li class="breadcrumb-item active">Pemesanan</li>
                       </ol>
                   </nav>
               </div>
            </div>
        </div>
    </section>

    <section class="wrap-form-buyer space">
        <div class="container">
            <div class="row">
               <div class="col-12 col-md-8">
                   <div class="card booking-login-card">
                       <h3>
                           <i class="flaticon-login"></i>
                           @if($auth->logged())
                            Hai {{ $auth->getName() }}, silahkan melanjutkan pemesanan.
                            @else
                            <a href="{{ route('login') }}?redirect={{ url('order/form-pemesanan/'.$cartKey) }}">Login</a> atau <a href="#">Daftar</a> untuk nikmati berbagai kemudahan!
                            @endif
                       </h3>
                   </div>
                   <div class="card form-style wrap-group-field wrapper-form">
                       <div class="title-card">
                           <h5>Data Pemesan</h5>
                       </div>

                       <div class="wrap-form">
                           <form class="form" id="form-souvenir" method="post">
                           @csrf
                               <div class="row">
                                   <div class="col-12 col-md-2">
                                       <div class="form-group floating-label ">
                                           <select class="form-control" name="state">
                                               <option selected>Tuan</option>
                                               <option>Nyonya</option>
                                               <option>Nona</option>
                                           </select>
                                           <span class="icon-select far fa-angle-down"></span>
                                       </div>
                                   </div>
                                   <div class="col-12 col-md-10">
                                       <div class="form-group floating-label">
                                           <label>Nama Lengkap</label>
                                           <input type="text" class="form-control" name="nama" @if(isset($customer['first_name'])) value="{{ implode(' ',[$customer['first_name'], $customer['last_name']]) }}" @endif>
                                           <small>Sesuai KTP/Paspor/SIM (tanpa tanda baca dan gelar)</small>
                                       </div>
                                   </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group floating-label">
                                            <label>Nomor Telepon</label>
                                            <input type="text" class="form-control" name="telp" @if(isset($customer['phone'])) value="{{ $customer['phone_code'] . $customer['phone'] }}" @endif>
                                            <small>Contoh: No. Handphone 0812345678</small>
                                        </div>
                                    </div>
                                   <div class="col-12 col-md-6">
                                       <div class="form-group floating-label">
                                           <label>Alamat Email</label>
                                           <input type="email" class="form-control" name="email" @if(isset($customer['email'])) value="{{ $customer['email'] }}" @endif>
                                           <small>Konfirmasi pesanan akan dikirim ke alamat Email ini.</small>
                                       </div>
                                   </div>
                               </div>

                           </form>
                       </div>
                   </div>
                   
                   <div class="wrap-btn float-right">
                       <a href="#6" type="submit" class="default_btn btn-book">Booking Pesanan</a>
                   </div>
               </div>
                <div class="col-12 col-md-4">
                    <div class="card detail-pemesanan">
                        <div class="title-card">
                            <h5>Rincian Pemesanan</h5>
                        </div>
                        <div class="book-details">
                            <div class="img-book">
                                <img src="{{ config('constants.UPLOAD_PATH') . $cartData['product_thumbnail'] }}" alt="img"/>
                            </div>
                            <div class="book-content">
                                <h3 class="book-title">{{$cartData['title']}}</h3>
                                <h6>Berat: {{$cartData['weight']}} gram</h6>
                            </div>
                        </div>
                        <hr/>
                        <div class="wrap-total-book">
                            <div>
                                <div class="body-price-details">
                                    <ul>
                                        <li>Harga <small>({{ $cartData['quantity']}}x {{ $productPrice }})</small><span>IDR. {{ $subTotalPrice }}</span></li>
                                        <li>Kurir <span>IDR. {{ $courierCost }}</span></li>
                                    </ul>
                                    <hr/>
                                    <h5 class="total">Total <span>IDR. {{ $totalPrice }}</span></h5>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('styles')
<style>
    .book-content h6 {
        color: #5b5b5b;
        font-weight: 400;
    }
    .courier-option {
        display: block;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .courier-option > .item-courier {
        display: block;
    }
    .courier-option > .item-courier > img {
        width: 64px;
        max-height: 64px;
    }
    .select2-container .select2-selection--single {
        height: 45px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #717171;
        line-height: 45px;
    }
    .select2-container--default .select2-selection--single {
        border: 1px solid #dbdbdb;;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 43px;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // append star rating
        $('[data-rating].has-star').each(function(i, item) {
        var star = $(item).data('rating');
        $(item).html(func.starRating(star));
        });
        
        $('.btn-book').click(function(e) {
            e.preventDefault();
            var form = $('#form-souvenir');
            var btn = $(this);
            btn
                .addClass('disabled')
                .html('<i class="fas fa-spin fa-spinner"></i> Sedang proses..');

            $.post('{{ route('submitCheckout', $cartKey) }}', form.serialize(), function(response) {
                try{
                if(response.status == 'success') {
                    var code = response.data.order_code;
                    window.location.href='{{ url('order/payment-method/') }}/' + code;  
                } else {
                    btn.removeClass('disabled').html('BOOKING PESANAN');
                }        
                } catch(err) {
                btn.removeClass('disabled').html('BOOKING PESANAN');
                }        
            })
            .fail(function(er) {  
                btn.removeClass('disabled').html('BOOKING PESANAN');
                try {
                var message = 'Terjadi kesalahan sistem';
                if(er.status == '422') {
                    var response = $.parseJSON(er.responseText);
                    message = '';
                    $.each(response, function(i, item) {
                    message += item + '<br />';
                    });
                }       
                $.alert(message, 'Kesalahan');
                } catch(err) {
                //
                console.log(err);
                }
            });
            });
    });
</script>
@endsection