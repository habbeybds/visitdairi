@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{ asset('images/product/transportation/HEADER/auto-business-car-sale-consumerism-people-concept.jpg') }})">
    <div class="container">
        <div class="wrap-head">
            <h3>{{ $title }}</h3>
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Pencarian Transportasi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- LIST ROOM   -->
<section class="tour-detail list-avail" style="padding-top: 30px;">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card card-widget hotel-widget">
                    <div class="block-body car">
                        <form class="wrapper-form" action="#">
                            <img class="img-car" src="{{ $thumbnail }}" alt=""/>
                            <div class="wrap-facility-transport">
                                <h6>{{ $title }}</h6>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="card card-widget hotel-widget">
                    <div class="block-body car">
                        <form class="wrapper-form" action="#">
                            <div class="row">
                                <div class="form-group col-12 col-md-6">
                                    <div class="position-relative">
                                        <label>Tanggal Mulai</label>
                                        <input type="text" class="form-control" id="date-start" name="date" value="">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <div class="position-relative">
                                        <label>Tanggal Selesai</label>
                                        <input type="text" class="form-control" id="date-end" name="date" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 col-md-6">
                                    <div class="position-relative">
                                        <label>Jumlah Kendaraan</label>
                                        <div class="input-group plus-minus">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="quant">
                                                    <span class="fas fa-minus"></span>
                                                </button>
                                            </span>
                                            <input type="text" id="quant" name="quant" class="form-control input-number" value="1" min="1" max="10">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="quant">
                                                    <span class="fas fa-plus"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <div class="position-relative">
                                    <div class="sub-kurir">
                                        <div class="row" style="margin-top: 40px;">
                                            <div class="form-group col-12 col-md-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="customRadio1" name="driver" class="custom-control-input" value="1" checked>
                                                        <label class="custom-control-label" for="customRadio1">Dengan Supir</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="customRadio2" name="driver" class="custom-control-input" value="0">
                                                        <label class="custom-control-label" for="customRadio2">Tanpa Supir</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="wrap-btn col-12 col-md-12">
                                    <a href="#6" class="default_btn btn-cek">Cek Ketersediaan</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LIST ROOM   -->
<section class="tour-detail list-avail" id="4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-section list-item" id="search_result">
                    
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
<!--DATEPICKER RANGE-->
<script type="text/javascript" src="{{ asset('js/flatpicker/js/flatpickr.js') }}"></script>
<script type="text/javascript">
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
    // Sticky widget
    $(document).ready(function(){

        $('.btn-cek').click(function(e) {
            e.preventDefault();
            var btn = $(this);

            if($('#date-start').val() == '')
            {
                $.alert('Silahkan pilih tanggal awal penyewaan', 'Info');
                return false;
            }
            
            if($('#date-end').val() == '')
            {
                $.alert('Silahkan pilih tanggal akhir penyewaan', 'Info');
                return false;
            }
            
            var options = {
                _token: "{{csrf_token()}}",
                product_id: '{{ $product_id }}',
                date_start: $('#date-start').val(),
                date_end: $('#date-end').val(),
                quantity: $('[name=quant]').val(),
                driver: $('[name=driver]:checked').val()
            };

            btn
                .addClass('disabled')
                .html('<i class="fas fa-spin fa-spinner"></i> Sedang proses..');
            $.post(baseUrl + '/transportasi/search', options, function(response) {
                try {
                    if(response.status == 'success') {
                        btn
                            .addClass('disabled')
                            .html('<i class="fas fa-spin fa-spinner"></i>Cek Ketersediaan');

                        var html = "<div class='block-header'>" +
                                        "<h3>Penyedia Kendaraan</h3>" +
                                        "</div>" +
                                        "<div class='block-body'>";
                    
                        $.each(response.data.products, function (i, item) {
                            html += "<div class='wrap-list-product car'>" +
                                        "<div class='body-list'>" +
                                            "<div class='row'>" +
                                                "<div class='col-12 col-md-9'>" +
                                                    "<div class='row'>" +
                                                        "<div class='col-12 col-md-4 img-room'>" +
                                                            "<img class='img-car' src='"+item.company_thumbnail+"' alt='img'/>" +
                                                        "</div>" +
                                                        "<div class='col-12 col-md-8'>" +
                                                            "<h3 class='title-list'>"+item.company_name+" ( <i class='fas fa-star'></i> "+item.star_rating+" )</h3>" +
                                                            "<ul class='list-desc'>";

                            if(item.is_driver){
                                if(item.is_gas){
                                    html += "<li><i class='fas fa-male'></i> Supir & Bensin</li>";
                                } else {
                                    html += "<li><i class='fas fa-male'></i> Supir</li>";
                                }                                
                            }
                            
                            if(item.is_water){
                                html += "<li><i class='fab fa-gulp'></i> Air Mineral</li>";
                            }
                            
                            if(item.is_insurance){
                                html += "<li><i class='fas fa-car'></i> Asuransi</li>";
                            }
                            
                            if(item.is_reschedule){
                                html += "<li><i class='fas fa-hourglass-half'></i> Dapat Dijadwalkan Ulang</li>";
                            }
                            
                            if(item.is_refund){
                                html += "<li><i class='fas fa-sticky-note'></i> Dapat Direfund</li>";
                            }
                            
                            html += "</ul>" +
                                                        "</div>" +
                                                    "</div>" +
                                                "</div>" +
                                                "<div class='col-12 col-md-3'>" +
                                                    "<div class='price'>" +
                                                        "<h3>Rp "+item.price+"</h3>" +
                                                        "<p>per hari</p>" +
                                                        "<div class='wrap-select-room'>" +
                                                            "<div class='wrap-btn'>" +
                                                                "<a href='"+baseUrl + '/transportasi/detail/'+item.url+"' class='default_btn btn-booknow'>Detail</a>" +
                                                            "</div>" +
                                                        "</div>" +
                                                    "</div>" +
                                                "</div>" +
                                            "</div>" +
                                        "</div>" +
                                    "</div>";
                        });
                        html += "</div>";
                        $("#search_result").empty();
                        $("#search_result").append(html);
                        btn
                            .removeClass('disabled')
                            .html('Cek Ketersediaan');
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

    // datepicker
    var disabledMobile = false;
    if (window.matchMedia("(max-width: 678px)").matches) {
        disabledMobile = true;
    }

    let startpicker = flatpickr('#date-start', {
        enableTime: true,
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(7),
        enableTime: false,
        altInput: true,
        showMonths: 1,
        disableMobile: disabledMobile,
        onClose: function(selectedDates, dateStr, instance) {
            endpicker.set('minDate', dateStr);
        },
    });

    let endpicker = flatpickr('#date-end', {
        enableTime: true,
        dateFormat: "Y-m-d",
        minDate: $('#date-start').attr('value'),
        enableTime: false,
        altInput: true,
        showMonths: 1,
        disableMobile: disabledMobile,
        onClose: function(selectedDates, dateStr, instance) {
            startpicker.set('maxDate', dateStr);
        },
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