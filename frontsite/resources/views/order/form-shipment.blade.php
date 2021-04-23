@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{ asset('images/bg-head-4.jpg') }})">
    <div class="container">
        <div class="wrap-head">
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Nama Barang</li>
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
                        <h5>Tujuan Pengiriman</h5>
                    </div>

                    <div class="wrap-form">
                        <form class="form">
                        @csrf
                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <div class="form-group floating-label">
                                        <label>Nama Penerima</label>
                                        <input type="text" class="form-control" name="nama">
                                        <small>Nama lengkap penerima</small>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group floating-label">
                                        <label>Nomor Telepon</label>
                                        <input type="text" class="form-control" name="telp">
                                        <small>Contoh: No. Handphone 0812345678</small>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="form-group floating-label">
                                        <label>Alamat Pengiriman</label>
                                        <textarea class="form-control" name="alamat"></textarea>
                                        <small>Konfirmasi pesanan akan dikirim ke alamat Email ini.</small>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group floating-label">
                                        <label>Propinsi</label>
                                        <select class="select-2 provinsi" id="province" name="province">
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group floating-label">
                                        <label>Propinsi</label>
                                        <select class="select-2 kota" id="city" name="city">
                                            <option value="">Pilih Kota</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group floating-label">
                                        <label>Propinsi</label>
                                        <select class="select-2 subdistrict" id="subdistrict" name="subdistrict">
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="card form-style wrap-group-field wrapper-form">
                    <div class="title-card">
                        <h5>Pilih Kurir</h5>
                    </div>
                    <div class="wrap-form">
                        <form class="form">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="select-avail-kurir">
                                        <div class="form-group">
                                            <select id="select-package" class="select-custom" name="courier">
                                                <option value="hide">Pilih Kurir</option>
                                                @foreach($couriers as $courier)
                                                <option value="{{$courier->courier_code}}">{{$courier->courier_name}}</option>
                                                @endforeach
                                            </select>
                                            <small>Pilih Kurir yang akan mengantarkan pesanan Anda</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="sub-kurir">
                                        Layanan kurir pengiriman
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="wrap-btn float-right">
                    <a href="#6" type="submit" class="default_btn btn-booknow">Booking Pesanan</a>
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
                            <h6>Berat: {{$cartData['weight']}} gram</h6>
                        </div>
                    </div>
                    <p class="time-kurir">Estimasi lama pengiriman : <span class="txt_etd">-</span> hari</p>
                    <hr/>
                    <div class="wrap-total-book">
                        <div>
                            <div class="body-price-details">
                                <ul>
                                    <li>Harga <small>({{$cartData['quantity']}}x {{number_format($cartData['product_price'],0,',','.')}})</small>
                                    <span class="tx_subtotal" data-subtotal="{{ (int)$cartData['quantity'] * (int)$cartData['product_price'] }}">IDR. {{number_format((int)$cartData['quantity'] * (int)$cartData['product_price'],0,',','.')}}</span></li>
                                    <li>Kurir <span>IDR.&nbsp;<span class="tx_couriercost" data-couriercost="0">0</span></span></li>
                                </ul>
                                <hr/>
                                <h5 class="total">Total <span>IDR.&nbsp;<span class="tx_totalcost" data-totalcost="{{ (int)$cartData['quantity'] * (int)$cartData['product_price'] }}">{{number_format((int)$cartData['quantity'] * (int)$cartData['product_price'],0,',','.')}}</span></span></h5>
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
    select2-container--default .select2-selection--single {
        border: 1px solid #dbdbdb;;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 43px;
    }
    .select2-container--default .select2-selection--single {
        border: 1px solid #dbdbdb;
    }
    .book-content h6 {
        color: #5b5b5b;
        font-weight: 400;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<script type="text/javascript">

    var buildCourierService = function(i, item) {
        var html = '<div class="form-group">' +
            '    <div class="custom-control custom-radio">' +
            '        <input type="radio" data-cost="'+item.cost+'" data-etd="'+item.etd+'" value="'+item.service+'" id="courier_'+i+'" name="courier_service" class="custom-control-input">' +
            '        <label class="custom-control-label" for="courier_'+i+'">'+item.service+
            ' (<i class="courier-desc">'+item.description+'</i>) Rp. '+func.priceFormat(item.cost)+'</label>' +
            '    </div>' +
            '</div>';
        return html;
    }

    $(document).ready(function() {
        $('#province').select2();
		$('#city').select2();
		$('#subdistrict').select2();
        prepare_data();

        function prepare_data(){
            $.ajax({
                url     : '{{ route('ajaxGetProvince') }}',
                success: function(data)
                {
                    if(data.status == 'success'){

                        var html = '';
                        $.each(data.province, function (i, item) {
                            html += '<option value="' + item.province_id + '">' + item.name + '</option>';
                        });
                        
                        $('#province').empty();
                        $('#province').append('<option value="0">Pilih Provinsi</option>');
                        $('#province').append(html);

                    }
                },
            });
        }

        $("#province").change(function() {
            var province = $("#province option:selected").val();

            $.ajax({
                url     : '{{ route('ajaxGetCity') }}',
                data    : {
                        province: province
                },
                success: function(data)
                {
                    if(data.status == 'success'){
                        var html = '';
                        $.each(data.city, function (i, item) {
                            html += '<option value="' + item.city_id + '">' + item.city_name + ' (' + item.type + ')</option>';
                        });

                        $('#city').empty();
                        $('#city').append('<option value="0" selected>Pilih Kota</option>');
                        $('#city').append(html);
                        $("#city").val(0).change();
                        
                    }
                },
                error: function ()
                {
                    console.log('error');
                }
            });
        });
        
        $("#city").change(function() {
            var city = $("#city option:selected").val();

            $.ajax({
                url     : '{{ route('ajaxGetSubdistrict') }}',
                data    : {
                        city: city
                },
                success: function(data)
                {
                    if(data.status == 'success'){
                        var html = '';
                        $.each(data.subdistrict, function (i, item) {
                            html += '<option value="' + item.subdistrict_id + '">' + item.subdistrict_name + '</option>';
                        });

                        $('#subdistrict').empty();
                        $('#subdistrict').append('<option value="0" selected>Pilih Kecamatan</option>');
                        $('#subdistrict').append(html);
                        $("#subdistrict").val(0).change();
                        
                    }
                },
                error: function ()
                {
                    console.log('error');
                }
            });
        });
    });

    $(document).ready(function(){
        var courier = '';
        var couriercost = 0;
        var courierservice = '';
        var calculate = function() {
            var subtotal = $('.tx_subtotal').attr('data-subtotal');
            var couriercost = $('.tx_couriercost').attr('data-couriercost');
            var total = parseInt(subtotal) + parseInt(couriercost);
            $('.tx_totalcost')
                .attr('data-totalcost', total)
                .html(func.priceFormat(total));
        }

        $('.form-group select.select-custom').each(function(){
            var $this = $(this), numberOfOptions = $(this).children('option').length;

            $this.addClass('select-hidden');
            $this.wrap('<div class="select"></div>');
            $this.after('<div class="select-styled"></div>');

            var $styledSelect = $this.next('div.select-styled');
            $styledSelect.text($this.children('option').eq(0).text());

            var $list = $('<ul />', {
                'class': 'select-options'
            }).insertAfter($styledSelect);

            for (var i = 0; i < numberOfOptions; i++) {
                $('<li />', {
                    text: $this.children('option').eq(i).text(),
                    rel: $this.children('option').eq(i).val()
                }).appendTo($list);
            }

            var $listItems = $list.children('li');

            $styledSelect.click(function(e) {
                e.stopPropagation();
                $('div.select-styled.active').not(this).each(function(){
                    $(this).removeClass('active').next('ul.select-options').hide();
                });
                $(this).toggleClass('active').next('ul.select-options').toggle();
            });

            $listItems.click(function(e) {
                e.stopPropagation();
                $styledSelect.text($(this).text()).removeClass('active');
                $this.val($(this).attr('rel'));
                $list.hide();
 
                var destination = $('#city').val();
                var destinationType = 'city';
                if(($('#subdistrict').val().length > 0) && $('#subdistrict').val() > 0) 
                {
                    destination = $('#subdistrict').val();
                    destinationType = 'subdistrict';
                }
                
                // get courier services
                let options = {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    courier: $this.val(),
                    origin: '{{$origin}}',
                    originType: '{{$originType}}',
                    destination: destination,
                    destinationType: destinationType,
                    weight: '{{$cartData['weight']}}'
                };
                var serviceList = $('.sub-kurir');
                $.ajax({
                    url:'{{route('getShipmentCost')}}', 
                    type: 'post',
                    data: options, 
                    success: function(response) {
                        try {
                            if(response.status == 'success') {
                                var html = '';
                                
                                $.each(response.data,function(i, item) {
                                    html += buildCourierService(i,item);
                                });
                                serviceList.html(html);
                                serviceList.on('click','[name=courier_service]',function(e) {
                                    var etd = $(this).data('etd');
                                    var cost = parseInt($(this).data('cost'));
                                    $('.txt_etd').html(etd);
                                    $('.tx_couriercost')
                                        .attr('data-couriercost', cost)
                                        .html(func.priceFormat(cost));

                                    courier = $this.val();
                                    couriercost = cost;
                                    calculate();
                                });
                            } else {
                                serviceList.html('Layanan kurir tidak ditemukan.');
                            }
                        } catch(err) {
                            serviceList.html('Layanan kurir tidak ditemukan.');
                        }
                    },
                    beforeSend: function() {
                        serviceList.html('<i class="fas fa-spin fa-spinner"></i> cari layanan kurir');
                    }
                });
            });

            $(document).click(function() {
                $styledSelect.removeClass('active');
                $list.hide();
            });
        });

        $('.btn-booknow').click(function(e) {
            e.preventDefault();
            var btn = $(this);

            var options = {
                _token: $('[name=csrf-token]').attr('content'),
                name: $('[name=nama]').val(),
                phone: $('[name=telp]').val(),
                address: $('[name=alamat]').val(),
                courier: courier,
                courier_service: $('[name=courier_service]').val(),
                courier_cost: $('.tx_couriercost').attr('data-couriercost'),
                province_id: $('#province').val(),
                city_id: $('#city').val(),
                subdistrict_id: $('#subdistrict').val()
            };

            btn
                .addClass('disabled')
                .html('<i class="fas fa-spin fa-spinner"></i> Sedang proses..');

            $.post('{{ route('addcartshipment', 'souvenir') }}', options, function(response) {
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