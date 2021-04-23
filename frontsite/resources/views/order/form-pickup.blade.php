@extends('layouts.main')

@section('contents')

<section class="head-title-destination single-page" style="background-image: url({{ asset('images/product/transportation/HEADER/auto-business-car-sale-consumerism-people-concept.jpg') }})">
    <div class="container">
        <div class="wrap-head">
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Transportasi</li>
                        <li class="breadcrumb-item active">Detail Sewa</li>
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

                <div class="card form-style wrap-group-field wrapper-form">
                    <div class="title-card">
                        <h5>Detail Penjemputan - Pengembalian</h5>
                    </div>
                    <div class="wrap-form">
                        <form class="form">
                        @csrf
                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <div class="form-group floating-label select2-style">
                                        <label>Lokasi Penjemputan</label>
                                        <input type="text" class="form-control" id="pickuplocation" name="pickuplocation" value="">
                                        <small>Supir akan menghubungimu 12 Jam sebelum waktu penjemputan</small>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group floating-label">
                                        <label>Waktu Penjemputan</label>
                                        <input id="pickuptime" type="text" class="form-control flatpickr-input active" name="pickuptime">
                                    </div>
                                </div>

                                <div class="col-12 col-md-8">
                                    <div class="form-group floating-label select2-style">
                                        <label>Lokasi Pengembalian</label>
                                        <input type="text" class="form-control" id="returnlocation" name="returnlocation" value="">
                                        <small>Supir akan menghubungimu sebelum waktu pengembalian</small>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group floating-label">
                                        <label>Waktu Pengembalian</label>
                                        <input id="returntime" type="text" class="form-control flatpickr-input active" name="returntime" placeholder="">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="form-group floating-label">
                                        <label>Permintaan Khusus (Optional)</label>
                                        <textarea class="form-control" id="spesialreq" rows="3"></textarea>
                                        <small>Tulis hal lain yang Anda butuhkan untuk melengkapi perjalanan Anda</small>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="wrap-btn float-right">
                    <a href="#" type="submit" class="default_btn btn-booknow">Lanjutkan Pesanan</a>
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
                            <h3 class="book-title">{{ $cartData['title'] }}</h3>
                        <p class="book-desc">{!! $cartData['subtitle'] !!}</p>
                        </div>
                    </div>
                    <br>
                    <div class="wrap-total-book">
                        <div class="body-price-details">
                            <ul>
                                <li>Tanggal Mulai <span>{{ $cartData['start_date'] }}</span></li>
                                <li>Tanggal Selesai <span>{{ $cartData['end_date'] }}</span></li>
                            </ul>
                        </div>
                    </div>
                    <hr/>
                    <div class="wrap-total-book">
                        <div class="body-price-details">
                            <ul>
                                <li>Harga <span>{{ $cartData['days'] }} hari x {{ $cartData['quantity'] }} Mobil x Rp {{ number_format($cartData['price'],0,',','.') }}</span></li>
                            </ul>
                            <hr/>
                            <h5 class="total">Total <span>Rp {{ number_format($cartData['tot_price']) }}</span></h5>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('styles')
<!--DATEPICKER RANGE-->
<link rel="stylesheet" type="text/css" href="{{ asset('css/flatpicker/css/flatpickr.css') }}" />
<style type="text/css">
    .card.card-widget .price .singles_item .wrap-count-item p.value {
        font-size: 13px;
        font-weight: 400;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<!-- LIGHT BOX GALLERY -->
<script src="{{ asset('js/lightbox/baguetteBox.js') }}" async></script>
<!--DATEPICKER RANGE-->
<script type="text/javascript" src="{{ asset('js/flatpicker/js/flatpickr.js') }}"></script>
<script type="text/javascript">
    window.onload = function() {
        baguetteBox.run('#lightbox');
    };

    //Mobile Move Content
    $(document).ready(function () {
        if (window.matchMedia('(max-width: 768px)').matches) {
            $('.menu ul.anchor li.change-search').appendTo('.mobile-wrap-content .move-change-search');
            $('.card.card-widget').appendTo('#modal-booking .modal-body');
        }
    })
</script>
<script type="text/javascript">
    var baseUrl = $('meta[name=baseurl]').attr('content');
    
    $("#pickuptime").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });

    $("#returntime").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });

    $(document).ready(function(){
        $('.btn-booknow').click(function(e) {
            e.preventDefault();
            var btn = $(this);

            var options = {
                _token: $('[name=csrf-token]').attr('content'),
                pickuplocation: $('#pickuplocation').val(),
                pickuptime: $('#pickuptime').val(),
                returnlocation: $('#returnlocation').val(),
                returntime: $('#returntime').val(),
                spesialreq: $('#spesialreq').val()
            };

            btn
                .addClass('disabled')
                .html('<i class="fas fa-spin fa-spinner"></i> Sedang proses..');

            $.post('{{ route('addcartpickup', 'transportasion') }}', options, function(response) {
                try {
                    if(response.status == 'success') {
                        btn
                            .addClass('disabled')
                            .html('<i class="fas fa-spin fa-spinner"></i> Meneruskan..');
                        window.location.href = '{{ route('formorder', $cartKey) }}';
                    } else {
                        if(response.code == 403) {
                            $.alert({
                                content:'Anda belum melakukan login. Silahkan login terlebih dahulu untuk atau daftar bila belum memiliki akun di VISITDAIRI', 
                                title:'Gagal',
                                buttons: {
                                    'Login Sekarang': {
                                        action: function() {
                                            window.location.href='{{ route('login') }}?redirect=' + window.location.href;
                                        }
                                    }
                                }
                            });
                        }
                        btn
                            .removeClass('disabled')
                            .html('BOOKING SEKARANG');
                    }
                    
                } catch(err) {
                    btn
                        .removeClass('disabled')
                        .html('BOOKING PESANAN');
                }
            })
            .fail(function(err) {
                try {
                    var message = 'Terjadi kesalahan sistem. Silahkan memuat ulang halaman ini!';
                    if(err.status == '422') {
                        var response = $.parseJSON(err.responseText);
                        var message = '';
                        $.each(response, function(i, item) {
                            message += item + '<br />';
                        });
                    }
                    $.alert(message, 'Kesalahan');
                    btn
                        .removeClass('disabled')
                        .html('BOOKING PESANAN');
                } catch(err) {}
            });
        });
    });
</script>
@endsection