@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{ asset('images/product/transportation/HEADER/auto-business-car-sale-consumerism-people-concept.jpg') }})">
    <div class="container">
        <div class="wrap-head">
            <h3>{{ $product->title }}</h3>
            <ul class="review-title has-star" data-rating="{{ $product->star_rating }}"> </ul>
            <span class="point-star">{{ number_format($product->star_rating,1) }}</span>
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Transportasi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="wrap-anchor space">
    <div class="container">
        <div class="card-anchor">
            <div class="menu">
                <ul class="anchor">
                    <li>
                        <a href="#1">Overview</a>
                    </li>
                    <li>
                        <a href="#2">Waktu Sewa</a>
                    </li>
                    <li>
                        <a href="#3">Harga Termasuk</a>
                    </li>
                    <li>
                        <a href="#4">Harga Tidak Termasuk</a>
                    </li>
                    <li>
                        <a href="#5">Syarat & Ketentuan</a>
                    </li>
                    <li>
                        <a href="#6">Review</a>
                    </li>
                    <li class="change-search">
                        <a href="product/all/transportasi"><i class="fas fa-search"></i> Ganti Pencarian</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="mobile-wrap-content product">
    <div class="move-change-search"></div>
    <div class="booking-product">
        <a href="#modal-booking" class="default_btn" data-toggle="modal" data-target="#modal-booking">Mulai Pemesanan</a>
    </div>
</section>

<section class="tour-detail space">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card card-section" id="1">
                    <div class="block-header">
                        <h3>{!! $companyName !!}</h3>
                    </div>
                    <div class="block-body">
                        <div class="partner-section name-partner">
                            <img src="{{ $companyLogo }}" alt="logo">
                        </div>
                        {!! $companyOverview !!}
                    </div>
                </div>
                <div class="card card-section" id="2">
                    <div class="block-header">
                        <h3>Waktu Sewa</h3>
                    </div>
                    <div class="block-body">
                        <div class="terms-transport-detail">
                            <div class="row">
                                <div class="block-body">
                                {!! $rentTimeDesc !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-section" id="3">
                    <div class="block-header">
                        <h3>Harga Termasuk</h3>
                    </div>
                    <div class="block-body">
                        <div class="terms-transport-detail">
                            <div class="row">
                                <div class="block-body col-12">
                                {!! $priceInclude !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-section" id="4">
                    <div class="block-header">
                        <h3>Harga Tidak Termasuk</h3>
                    </div>
                    <div class="block-body">
                        <div class="terms-transport-detail">
                            <div class="row">
                                <div class="block-body col-12">
                                {!! $priceExclude !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-section" id="5">
                    <div class="block-header">
                        <h3>Syarat & Ketentuan</h3>
                    </div>
                    <div class="block-body">
                        <div class="terms-transport-detail">
                            <div class="row">
                                <div class="block-body col-12">
                                {!! $tncDesc !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
                <div id="6">
                    <div id="reviews"></div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-widget transport-widget">
                    <div class="block-body car">
                        <form class="wrapper-form" action="#">
                        @csrf
                            <img class="img-car" src="{{ $thumbnail }}" alt=""/>
                            <div class="wrap-facility-transport">
                                <h6>{{ $productTitle }}</h6>
                            </div>
                            <div class="price mb-3">
                                <div class="singles_item">
                                    <div class="wrap-count-item">
                                        <p class="value">Tanggal Mulai</p>
                                    </div>
                                    <div class="wrap-count-item">
                                        <p class="value">{{ $strStartDate }}</p>
                                    </div>
                                </div>
                                <div class="singles_item">
                                    <div class="wrap-count-item">
                                        <p class="value">Tanggal Selesai</p>
                                    </div>
                                    <div class="wrap-count-item">
                                        <p class="value">{{ $strEndDate }}</p>
                                    </div>
                                </div>
                                <div class="singles_item">
                                    <div class="wrap-count-item">
                                        <p class="value">Dengan Supir</p>
                                    </div>
                                    <div class="wrap-count-item">
                                        <p class="value">{{ $strDriver }}</p>
                                    </div>
                                </div>
                                <div class="singles_item">
                                    <br>
                                </div>
                                <div class="singles_item">
                                    <div class="wrap-count-item">
                                        <p class="value">{{ $totDays }} hari x {{ $totCars }} Mobil</p>
                                    </div>
                                    <div class="info">
                                        <p class="value">Rp {{ number_format($totPrice) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group wrap-total">
                                <div class="wrap-btn col-12 col-md-12">
                                    <a href="#" class="default_btn btn-booknow">Pesan Sekarang</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <section class="tour-detail space reviews" id="6">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8">
                <div id="reviews"></div>
            </div>
        </div>
    </div>
</section> -->

<section class="recommended-product space">
    <div class="container">
        <div class="block-header">
            <h3>Produk Terkait</h3>
        </div>
        <div class="block-body all-product">
            <div class="wrap-list">
                <div class="row m-0">
                    @foreach($related_product as $related)
                    {!! $related['product_related'] !!}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!--  MODAL BOOKING PRODUCT  -->
<div class="modal modal-mobile fade" id="modal-booking" tabindex="-1" role="dialog" aria-labelledby="modal-booking-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')

<style type="text/css">
    .card.card-widget .price .singles_item .wrap-count-item p.value {
        font-size: 13px;
        font-weight: 400;
    }
</style>
@endsection

@section('scripts')

<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/visitdairi-review.js') }}"></script>

<script type="text/javascript">

    $('#reviews').reviews({
        url: '{{ route('ajaxGetReview') }}',
        title: 'Review',
        initCount: 2,
        loadPerPage: 3,
        data: {
            'product_id': '{{$product->product_id}}',
            'product_type': 'transportasi' 
        }
    });

    //Mobile Move Content
    $(document).ready(function () {
        if (window.matchMedia('(max-width: 768px)').matches) {
            $('.menu ul.anchor li.change-search').appendTo('.mobile-wrap-content .move-change-search');
            $('.card.card-widget').appendTo('#modal-booking .modal-body');
        }
    })
    
    // Sticky widget
    $(document).ready(function(){
        var div_top = $('.card-widget').offset().top;
        var stop_top = $('#5').offset().top;
        $(window).scroll(function() {
            var window_top = $(window).scrollTop() - 0;
            if (window_top > div_top && window_top < stop_top) {
                $('#sidebarMenu').hide();
                if (!$('.card-widget').is('.sticky')) {
                    $('.card-widget').addClass('sticky');
                }
            } else {
                $('#sidebarMenu').show();
                $('.card-widget').removeClass('sticky');
            }
        });

        $('[data-rating].has-star').each(function(i, item) {
            var star = $(item).data('rating');
            $(item).html(func.starRating(star));
        });

        $('.btn-booknow').click(function(e) {
            e.preventDefault();
            var btn = $(this);

            if($('#date-booking').val() == '')
            {
                $.alert('Silahkan pilih tanggal reservasi', 'Info');
                return false;
            }
            
            if($('#waktu-booking').val() == '')
            {
                $.alert('Silahkan pilih jam reservasi', 'Info');
                return false;
            }
            
            var options = {
                _token: $('[name=_token]').val(),
                product_id: '{{ $product->product_id }}',
                product_type: '{{ $productType }}',
                start_date: '{{ $startDate }}',
                end_date: '{{ $endDate }}',
                quantity: '{{ $totCars }}',
                driver: '{{ $driver }}'
            };

            btn
                .addClass('disabled')
                .html('<i class="fas fa-spin fa-spinner"></i> Sedang proses..');
            $.post('{{ route('addcart', 'transportasi') }}', options, function(response) {
                try {
                    if(response.status == 'success') {
                        btn
                            .addClass('disabled')
                            .html('<i class="fas fa-spin fa-spinner"></i> Meneruskan..');
                        window.location.href = '{{ route('formPickup', $cartKey) }}';
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
                        .html('BOOKING SEKARANG');
                }
            })
            .fail(function(er) {
                btn
                    .removeClass('disabled')
                    .html('BOOKING SEKARANG');
                try {
                    var message = 'Terjadi kesalahan sistem';
                    if(er.status == '422') {
                        var response = $.parseJSON(er.responseText);
                        var message = '';
                        $.each(response.message, function(i, item) {
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

    // Sticky anchor
    $(document).ready(function(){
        var div_top = $('.card-anchor').offset().top;
        var stop_top = $('#5').offset().top;
        $(window).scroll(function() {
            var window_top = $(window).scrollTop() - 0;
            if (window_top > div_top && window_top < stop_top) {
                $('#sidebarMenu').hide();
                if (!$('.card-anchor').is('.sticky')) {
                    $('.card-anchor').addClass('sticky');
                }
            } else {
                $('#sidebarMenu').show();
                $('.card-anchor').removeClass('sticky');
            }
        });
    });

    var disabledMobile = false;
    if (window.matchMedia("(max-width: 678px)").matches) {
        disabledMobile = true;
    }

    // Anchor
    $(document).ready(function() {
        $('.anchor').find('a').bind('click', function(e) {
            e.preventDefault();
            var target = $(this).attr("href");
            $('html, body').stop().animate({
                scrollTop: $(target).offset().top
            }, 600, function() {
                location.hash = target;
            });
            return false;
        });
    });

    $(window).scroll(function() {
        var scrollDistance = $(window).scrollTop();
        $('.card-section').each(function(i) {
            if ($(this).position().top <= scrollDistance) {
                $('.anchor a.active').removeClass('active');
                $('.anchor a').eq(i).addClass('active');
            }
        });
    }).scroll();

</script>
@endsection