@extends('layouts.main')

@section('contents')
    <section class="head-title-destination" style="background-image: url({{ asset('images/bg-head-4.jpg') }})">
        <div class="container">
            <div class="wrap-head">
                <h3>{{ $product->title }}</h3>
                <ul class="review-title has-star" data-rating="{{ $product->star_rating }}"> </ul>
                <span class="point-star">{{ number_format($product->star_rating,1) }}</span>
                <div class="breadcrumb-style">
                    <nav aria-label="breadcrumb" class="list-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item">Produk Detail</li>
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
                            <a href="#1">Gallery</a>
                        </li>
                        <li>
                            <a href="#2">Deskripsi</a>
                        </li>
                        <li>
                            <a href="#3">Syarat dan Ketentuan</a>
                        </li>
                        <li>
                            <a href="#4">Lokasi</a>
                        </li>
                        <li>
                            <a href="#5">Review</a>
                        </li>
                        <li class="change-search">
                            <a href="product/all/souvenir"><i class="fas fa-search"></i> Ganti Pencarian</a>
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
                            <h3>Gallery</h3>
                        </div>
                        <div class="block-body">
                            <div class="tour-detail-gallery">
                                <div class="gallery-tour">
                                    <div class="more-photo">
                                        <span><i class="far fa-images"></i> foto lainnya</span>
                                    </div>
                                    <div id="lightbox" class="gallery">
                                        @foreach($images as $img)
                                        <a class="gallery-grid{{ $img['mainclass'] }}" href="{{ $img['url'] }}" data-caption="{{ $img['title'] }}">
                                            <div class="image">
                                                <img src="{{ $img['url'] }}" alt="{{ $img['title'] }}">
                                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-section" id="2">
                        <div class="block-header">
                            <h3>Deskripsi</h3>
                        </div>
                        <div class="block-body">
                            {!! $product->souvenir_description !!}
                        </div>

                    </div>
                    <div class="card card-section" id="3">
                        <div class="block-header">
                            <h3>Syarat & Ketentuan</h3>
                        </div>
                        <div class="block-body">
                           	<div class="terms-souvenir">
	                    		{!! $product->tnc_shopping !!}		
                           	</div>
                        </div>

                    </div>
                    <div class="card card-section" id="4">
                        <div class="block-header">
                            <h3>Lokasi</h3>
                        </div>
                        <div class="block-body">
                            <div class="partner-section name-partner">
                                <img src="{{config('constants.UPLOAD_PATH').$product->company_logo}}" alt="logo">
                                <h6>{{$product->company_name}}</h6>
                            </div>
                            <div class="gmap">
                            {!! $product->url_map !!}
                            </div>
                        </div>

                    </div>
                    <div id="5">
                        <div id="reviews"></div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card card-widget">
                        <div class="block-body">
                            <div class="form-group">
                                <label>Jumlah Barang</label>
                                <div class="input-group plus-minus">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
                                          <span class="fas fa-minus"></span>
                                        </button>
                                    </span>
                                    <input type="text" name="quant[1]" class="form-control input-number" value="1" min="1" max="10">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="quant[1]">
                                          <span class="fas fa-plus"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="price">
                                <div class="singles_item">
                                    <div class="wrap-count-item">
                                        <p class="value" data-price="{{$product->default_publish_price}}">Rp. {{number_format($product->default_publish_price,0,'.',',')}} x <span data-qty="1">1</span></p>
                                    </div>
                                    <div class="info">
                                        <p class="value" data-total="0">Rp. 0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group wrap-total">
                                <div class="wrap-btn col-12 col-md-12">
                                    <a href="#2" class="default_btn btn-booknow">Booking Sekarang</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- <section class="tour-detail space reviews" id="5">
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
<!-- LIGHT BOX GALLERY -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/lightbox/baguetteBox.css') }}">
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
<script src="{{ asset('js/lightbox/baguetteBox.js') }}" async></script>
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

    $('#reviews').reviews({
        url: '{{ route('ajaxGetReview') }}',
        title: 'Review',
        initCount: 2,
        loadPerPage: 3,
        data: {
            'product_id': '{{$product->product_id}}',
            'product_type': 'souvenir' 
        }
    });

    var elmQty = $('[data-qty]');
    
    var calculate = function() {
        var qty = parseInt(elmQty.attr('data-qty'));
        var price = parseInt($('[data-price]').attr('data-price'));
        var total = qty * price;
        $('[data-total]').attr('data-total', total);
        $('[data-total]').html('Rp. '+func.priceFormat(total));
        
    }
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

        $('.plus-minus .input-number').change();
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

    $('.plus-minus .btn-number').click(function(e){
        e.preventDefault();

        fieldName = $(this).attr('data-field');
        type      = $(this).attr('data-type');
        var input = $("input[name='"+fieldName+"']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if(type == 'minus') {

                if(currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if(parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if(type == 'plus') {

                if(currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if(parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });
    $('.plus-minus .input-number').focusin(function(){
        $(this).data('oldValue', $(this).val());
    });
    $('.plus-minus .input-number').change(function() {

        minValue =  parseInt($(this).attr('min'));
        maxValue =  parseInt($(this).attr('max'));
        valueCurrent = parseInt($(this).val());

        name = $(this).attr('name');
        if(valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        if(valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the maximum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        elmQty.attr('data-qty', valueCurrent).html(valueCurrent);
        calculate();

    });
    $(".plus-minus .input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $('.btn-booknow').click(function(e) {
        e.preventDefault();
        var btn = $(this);

        if($('[data-qty]').attr('data-qty') < 1)
        {
            $.alert('Silahkan pilih jumlah barang yang akan dibeli', 'Info');
            return false;
        }
        
        var options = {
            _token: $('[name=csrf-token]').attr('content'),
            product_id: '{{ $product->product_id }}',
            product_type: '{{ $productType }}',
            quantity: $('[data-qty]').attr('data-qty')
        };

        btn
            .addClass('disabled')
            .html('<i class="fas fa-spin fa-spinner"></i> Sedang proses..');
        $.post('{{ route('addcart', 'souvenir') }}', options, function(response) {
            try {
                if(response.status == 'success') {
                    btn
                        .addClass('disabled')
                        .html('<i class="fas fa-spin fa-spinner"></i> Meneruskan..');
                    window.location.href = '{{ route('formShipment', $cartKey) }}';
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
        // $('.card-widget').find('.default_btn').bind('click', function(e) {
        //     e.preventDefault();
        //     var target = $(this).attr("href");
        //     $('html, body').stop().animate({
        //         scrollTop: $(target).offset().top
        //     }, 600, function() {
        //         location.hash = target;
        //     });
        //     return false;
        // });
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