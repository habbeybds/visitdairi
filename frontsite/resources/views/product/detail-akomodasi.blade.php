@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{asset('images/hotel-bg.jpg')}})">
    <div class="container">
        <div class="wrap-head">
            <h3>{{ $partner->company_name }}</h3>
            <ul class="review-title has-star" data-rating="5"> </ul>
            <span class="point-star">5.0</span>
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Hotel Detail</li>
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
                        <a href="#2">Lokasi</a>
                    </li>
                    <li>
                        <a href="#3">Gallery</a>
                    </li>
                    <li>
                        <a href="#4">Detail Produk</a>
                    </li>
                    <li>
                        <a href="#6">Reviews</a>
                    </li>
                    <li class="change-search">
                        <a href="product/all/akomodasi"><i class="fas fa-search"></i> Ganti Pencarian</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="tour-detail space">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card card-section" id="1">
                    <div class="block-header">
                        <h3>Overview</h3>
                    </div>
                    <div class="block-body">
                        <div class="partner-section name-partner">
                            <img src="{{config('constants.UPLOAD_PATH').$partner->company_logo}}" alt="logo">
                            <h6>{{ $partner->company_name }}</h6>
                        </div>
                        <p>{!! $partner->company_overview !!}</p>
                    </div>

                </div>
                <div class="card card-section" id="2">
                    <div class="block-header">
                        <h3>Lokasi</h3>
                    </div>
                    <div class="block-body">
                        <div class="gmap">
                            {!! $partner->url_map !!}
                        </div>
                    </div>

                </div>
                <div class="card card-section" id="3">
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
                
                <div class="card card-section d-none" id="4">
                    <div class="block-header">
                        <h3>Fasilitas</h3>
                    </div>
                    <div class="block-body">
                        <div class="wrap-facility">

                            {!! $facilities !!}
                        </div>
                        <div class="wrap-facility more">
                            <a class="moreless-button">Lihat Semua Fasilitas</a>
                            <div class="morefacility">
                                <div class="item-facility">
                                    <h6 class="title-facility"><i class="flaticon-do-not-disturb"></i>Kesehatan & Medis</h6>
                                    <ul class="facility">
                                        <li>Sauna</li>
                                        <li>Apotek</li>
                                        <li>Spa</li>
                                        <li>Pijat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-widget hotel-widget">
                    <div class="block-body">
                        <form class="wrapper-form" action="#">
                            <div class="form-group">
                                <div class="select-avail-tour">
                                    <div class="position-relative">
                                        <label>Pilih Tanggal CheckIn</label>
                                        <input type="text" class="form-control" id="date-start" name="date" value="">
                                        <i class="flaticon-calendar-interface-symbol-tool"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="select-avail-tour">
                                    <div class="position-relative">
                                        <label>Pilih Tanggal CheckOut</label>
                                        <input type="text" class="form-control" id="date-end" name="date" value="">
                                        <i class="flaticon-calendar-interface-symbol-tool"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex field-room-guest">
                                <div class="form-group mr-1">
                                    <label><i class="flaticon-hotel-sign"></i> Jumlah Kamar</label>
                                    <div class="input-group plus-minus">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="numroom[1]">
                                                <span class="fas fa-minus"></span>
                                            </button>
                                        </span>
                                        <input type="text" name="numroom[1]" class="form-control input-number" value="1" min="1" max="10">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="numroom[1]">
                                                <span class="fas fa-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group ml-1">
                                <label><i class="flaticon-guest"></i> Jumlah Tamu</label>
                                <div class="input-group plus-minus">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="numpax[1]">
                                            <span class="fas fa-minus"></span>
                                        </button>
                                    </span>
                                    <input type="text" name="numpax[1]" class="form-control input-number" value="1" min="1" max="10">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="numpax[1]">
                                            <span class="fas fa-plus"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            </div>
                            <div class="form-group wrap-total">
                                <div class="wrap-btn col-12 col-md-12">
                                    <a href="#6" class="default_btn btn-check-avail">Cek Ketersediaan</a>
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
<section class="tour-detail space list-avail" id="5">
    <div class="container">
        
    </div>
</section>

<section class="tour-detail space reviews" id="6">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8">
                <div id="reviews"></div>
            </div>
        </div>
    </div>
</section>

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
@endsection

@section('styles')
<!-- LIGHT BOX GALLERY -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/lightbox/baguetteBox.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/flatpicker/css/flatpickr.css') }}" />
<style>
    .wrapper-form .form-group input.form-control.flatpickr-input {
        text-indent: 15px;
    }
    a.disabled {
        pointer-events: none;
        cursor: default;
        box-shadow: none;
        background-color: #e6e39a;
        color: #6f6f6f;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/flatpicker/js/flatpickr.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/visitdairi-review.js') }}"></script>
<script src="{{ asset('js/lightbox/baguetteBox.js') }}" async></script>
<script type="text/javascript">
    window.onload = function() {
        baguetteBox.run('#lightbox');
    };
</script>
<script type="text/javascript">

$('#reviews').reviews({
    url: '{{ route('ajaxGetReview') }}',
    title: 'Review',
    initCount: 2,
    loadPerPage: 3,
    data: {
        'partner_id': '{{$partner->partner_id}}',
        'product_type': 'akomodasi' 
    }
});

// datepicker
// if (window.matchMedia("(max-width: 768px)").matches) {
//     $("#date-package").flatpickr({
//         enableTime: true,
//         dateFormat: "dd-m-Y",
//         minDate: "today",
//         enableTime: false,
//         altInput: true,
//         showMonths: 1,
//         disableMobile: true,
//         mode: "range",
//     });
// } else {
//     $("#date-package").flatpickr({
//         enableTime: true,
//         dateFormat: "d-m-Y",
//         minDate: "today",
//         enableTime: false,
//         altInput: true,
//         showMonths: 2,
//         mode: "range",
//     });
// }

// datepicker
var disabledMobile = false;
if (window.matchMedia("(max-width: 678px)").matches) {
    disabledMobile = true;
}

let startpicker = flatpickr('#date-start', {
    enableTime: true,
    dateFormat: "Y-m-d",
    minDate: new Date().fp_incr(1),
    enableTime: false,
    altInput: true,
    showMonths: 1,
    disableMobile: disabledMobile,
    onClose: function(selectedDates, dateStr, instance) {
        endpicker.set('minDate', new Date(dateStr).fp_incr(1));
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
        startpicker.set('maxDate', new Date(dateStr).fp_incr(-1));
    },
});

var dateDiff = function(str1, str2) {
    var diff = Date.parse(str2) - Date.parse(str1); 
    return isNaN( diff ) ? NaN : Math.floor(diff / 86400000);
}

var __getDate = function(strdate) {
    strdate = strdate.trim();
    var d = strdate.split('-');
    if(d.length == 3) {
        return d[2] + '-' + d[1] + '-' + d[0];
    }
    return false;
}

var __splitDate = function(strdate) {
    str = strdate.split('to');
    if(str.length == 2) {
        var date1 = __getDate(str[0]);
        if(!date1) return false;
        var date2 = __getDate(str[1]);
        if(!date2) return false;
        return {
            date1: date1,
            date2: date2,
            days: dateDiff(date1, date2)
        };
    } else {
        var date1 = __getDate(str[0]);
        if(!date1) return false;
        return {
            date1: date1,
            date2: date1,
            days: 1
        };
    }
    return false;
};

var __init = function() {
    var rooms = [];

    var __calculate = function() {
        var numroom = 0;
        var totalPrice = 0;
        var maxroom = $('input[name^=numroom').val();
        var listOrder = $('.list-select-room');
        var hasItem = false;
        $('[name^=rooms]').each(function(item) {
            var val = $(this).val();
            var id = $(this).find(':selected').attr('product-id');
            var name = $(this).find(':selected').attr('product-name');
            var price = $(this).find(':selected').attr('product-price');
            if(val.length > 0) {
                hasItem = true;
                numroom += parseInt(val);
                totalPrice += parseInt(price);
                listOrder.find('[is-empty]').remove();
                if(listOrder.find('li[product-id="'+id+'"]').length == 0) {
                    listOrder.append('<li product-id="'+id+'">'+name+'</li>');
                }
            } else {
                listOrder.find('li[product-id="'+id+'"]').remove();
            }
        });
        if(!hasItem) {
            listOrder.html('<li is-empty>Silahkan pilih kamar!</li>');
        }

        $('.btn-booknow').toggleClass('disabled', maxroom != numroom);

        // numb-room
        $('.numb-room')
            .attr('data-room', numroom)
            .html(numroom);
        $('.wrap-total-select .subtotal').html('Rp. '+func.priceFormat(totalPrice))
    };

    $('.detail-room').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        // var rangeDate = $('#date-package').val();
        // var date = __splitDate(rangeDate);
        var dateStart = $('#date-start').val();
        var dateEnd = $('#date-end').val();

        var options = {
            _token: $('meta[name=csrf-token]').attr('content'),
            product_id: id,
            date1: dateStart,
            date2: dateEnd
        };
        $.post('{{ route('getRoomDetail', $partner->partner_id) }}', options, function(response) {
            $('#detail-hotel').find('.modal-content').html(response);
            $('#detail-hotel').modal('show');
        });
    });

    $('.choose-room').change(function(e) {
        var maxroom = $('input[name^=numroom').val();
        var numroom = 0;
        $('[name^=rooms]').each(function(item) {
            var val = $(this).val();
            if(val.length > 0) {
                numroom = numroom + parseInt(val);
            }
        });
        if(numroom > parseInt(maxroom))
        {
            $.alert('Anda terlalu banyak memilih kamar. Kamar yang anda pesan sebanyak '+maxroom+' kamar.', 'Info');
            $(this).val('').change();
            return false;
        }
        __calculate();
    });

    $('.btn-booknow').click(function(e) {
        e.preventDefault();
        var btn = $(this);

        // var rangeDate = $('#date-package').val();
        // var date = __splitDate(rangeDate);
        var dateStart = $('#date-start').val();
        var dateEnd = $('#date-end').val();

        $('[name^=rooms]').each(function(item) {
            var val = $(this).val();
            var id = $(this).find(':selected').attr('product-id');
            
            if(val.length > 0) {
                rooms.push({
                    productid: id,
                    quantity: val
                });
            }
        });

        var options = {
            _token: $('meta[name=csrf-token]').attr('content'),
            partner_id: '{{ $partner->partner_id }}',
            product_type: '{{ $productType }}',
            rooms: rooms,
            date1: dateStart,
            date2: dateEnd
        };

        $.post('{{ route('addcart', 'akomodasi') }}', options, function(response) {
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
};

$('.btn-check-avail').click(function(e) {
    e.preventDefault();
    // var rangeDate = $('#date-package').val();
    var dateStart = $('#date-start').val();
    var dateEnd = $('#date-end').val();
    var numroom = $('input[name^=numroom').val();
    var numpax = $('input[name^=numpax').val();

    // var date = __splitDate(rangeDate);
    // if(!date) {
    //     $.alert('Silahkan pilih tanggal menginap', 'Info');
    //     return false;
    // }

    if($('#date-start').val() == '')
    {
        $.alert('Silahkan pilih tanggal CheckIn', 'Info');
        return false;
    }
    
    if($('#date-end').val() == '')
    {
        $.alert('Silahkan pilih tanggal CheckOut', 'Info');
        return false;
    }
    
    if(numroom > numpax) {
        $.alert('Anda terlalu banyak memilih kamar', 'Info');
        return false;
    }

    var options = {
        _token: $('meta[name=csrf-token]').attr('content'),
        date1: dateStart,
        date2: dateEnd,
        numroom: numroom,
        numpax: numpax
    };

    $.post('{{ route('getHotelAvail', $partner->partner_id) }}', options, function(response) {
        $('.list-avail > .container').html(response);
        __init();
        $('body, html').animate({
            scrollTop: $('.list-avail').offset().top
        });
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
$(document).ready(function(){
    $('[data-rating].has-star').each(function(i, item) {
        var star = $(item).data('rating');
        $(item).html(func.starRating(star));
    });

});
</script>
@endsection