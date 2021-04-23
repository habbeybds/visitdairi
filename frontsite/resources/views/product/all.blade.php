@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{ asset('images/bg-head-2.jpg') }})">
        <div class="container">
            <div class="wrap-head">
                <div class="breadcrumb-style">
                    <nav aria-label="breadcrumb" class="list-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item">All Product</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>


    <section class="all-product bg-default space">
        <div class="container">
            <div class="row">

                <div class="wrap-tab-product">
                    <div class="tab-product">
                        <ul class="nav nav-tabs">
                            <li><a data-toggle="tab" href="#tour"><i class="flaticon-suitcase"></i>Paket Wisata</a></li>
                            <li><a data-toggle="tab" href="#souvenir"><i class="flaticon-bag"></i>Souvenir</a></li>
                            <li><a data-toggle="tab" href="#kuliner"><i class="flaticon-restaurant-cutlery-circular-symbol-of-a-spoon-and-a-fork-in-a-circle"></i>Kuliner</a></li>
                            <li><a data-toggle="tab" href="#akomodasi"><i class="flaticon-location"></i>Akomodasi</a></li>
                            <li><a data-toggle="tab" href="#transportasi"><i class="flaticon-rent-a-car-sign"></i>Transportasi</a></li>
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    <div id="tour" class="tab-pane fade in active show">

                        <!-- SEARCH WIDGET -->
                        <div class="search-widget">
                            <div class="card">
                                <div class="title-widget">
                                    <h6><i class="flaticon-suitcase"></i> Pencarian Paket Wisata</h6>
                                </div>
                                <div class="widget-body">
                                    <form>
                                        <div class="row">
                                            <div class="form-group col-12 col-md-5">
                                                <label>Kata Kunci Tour</label>
                                                <select class="form-control select2" multiple id="tourtag" name="tourtag[]" style="width: 100%;">
                                                @foreach($tourTag as $row)
                                                    <option value="{{ $row->tag_id }}">{{ $row->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-12 col-md-3">
                                                <label>Pilih Tanggal</label>
                                                <input type="text" class="form-control" id="date-package" name="date" value="">
                                                <span class="icon-field flaticon-calendar-tool-for-time-organization"></span>
                                            </div>
                                            <div class="form-group col-12 col-md-2">
                                                <label>Jumlah Pax</label>
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
                                            <div class="form-group col-12 col-md-2">
                                                <div class="wrap-btn">
                                                    <button type="button" class="default_btn" id="btnSearchTour" onclick="search_tour('1')"><i class="fas fa-search"></i> Cari</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="wrap-list">
                            <div class="row" id="tourresult">
                                <div class="list col-12">
                                    Silahkan pilih Kata Kunci Tour, Tanggal, dan Jumlah Pax
                                </div>
                            </div>
                        </div>
                        <div class="pagination" id="tourpagination">
                            
                        </div>
                    </div>
                    <div id="souvenir" class="tab-pane fade">
                        <!-- SEARCH WIDGET -->
                        <div class="search-widget">
                            <div class="card">
                                <div class="title-widget">
                                    <h6><i class="flaticon-bag"></i> Pencarian Souvenir</h6>
                                </div>
                                <div class="widget-body">
                                    <form>
                                        <div class="row">
                                            <div class="form-group col-12 col-md-10">
                                                <label>Kata Kunci Souvenir</label>
                                                <select class="form-control select2" multiple id="souvenirtag" name="souvenirtag[]" style="width: 100%;">
                                                @foreach($souvenirTag as $row)
                                                    <option value="{{ $row->tag_id }}">{{ $row->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-12 col-md-2">
                                                <div class="wrap-btn">
                                                    <button type="button" class="default_btn" id="btnSearchSouvenir" onclick="search_souvenir('1')"><i class="fas fa-search"></i> Cari</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="wrap-list">
                            <div class="row" id="souvenirresult">
                                <div class="list col-12">
                                    Silahkan pilih Kata Kunci Souvenir
                                </div>
                            </div>
                        </div>
                        <div class="pagination" id="souvenirpagination">
                            
                        </div>
                    </div>
                    <div id="kuliner" class="tab-pane fade">

                        <!-- SEARCH WIDGET -->
                        <div class="search-widget">
                            <div class="card">
                                <div class="title-widget">
                                    <h6><i class="flaticon-restaurant-cutlery-circular-symbol-of-a-spoon-and-a-fork-in-a-circle"></i> Pencarian Kuliner</h6>
                                </div>
                                <div class="widget-body">
                                    <form>
                                        <div class="row">
                                            <div class="form-group col-12 col-md-10">
                                                <label>Kata Kunci Kuliner</label>
                                                <select class="form-control select2" multiple id="kulinertag" name="kulinertag[]" style="width: 100%;">
                                                @foreach($kulinerTag as $row)
                                                    <option value="{{ $row->tag_id }}">{{ $row->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-12 col-md-2">
                                                <div class="wrap-btn">
                                                    <button type="button" class="default_btn" id="btnSearchKuliner" onclick="search_kuliner('1')"><i class="fas fa-search"></i> Cari</button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="wrap-list">
                            <div class="row" id="kulinerresult">
                                <div class="list col-12">
                                    Silahkan pilih Kata Kunci Kuliner
                                </div>
                            </div>
                        </div>
                        <div class="pagination" id="kulinerpagination">
                            
                        </div>
                    </div>
                    <div id="akomodasi" class="tab-pane fade">

                        <!-- SEARCH WIDGET -->
                        <div class="search-widget">
                            <div class="card">
                                <div class="title-widget">
                                    <h6><i class="flaticon-location"></i> Pencarian Akomodasi</h6>
                                </div>
                                <div class="widget-body">
                                    <form>
                                        <div class="row">
                                            <div class="form-group col-12 col-md-4">
                                                <label>Kata Kunci Akomodasi</label>
                                                <select class="form-control select2" multiple id="akomodasitag" name="akomodasitag[]" style="width: 100%;">
                                                @foreach($akomodasiTag as $row)
                                                    <option value="{{ $row->tag_id }}">{{ $row->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-12 col-md-3">
                                                <label>Tanggal CheckIn</label>
                                                <input type="text" class="form-control" id="date-hotel-start" name="date" value="">
                                                <span class="icon-field flaticon-calendar-tool-for-time-organization"></span>
                                            </div>
                                            <div class="form-group col-12 col-md-3">
                                                <label>Tanggal CheckOut</label>
                                                <input type="text" class="form-control" id="date-hotel-end" name="date" value="">
                                                <span class="icon-field flaticon-calendar-tool-for-time-organization"></span>
                                            </div>
                                            <div class="form-group col-12 col-md-2">
                                                <div class="wrap-btn">
                                                    <button type="button" class="default_btn" id="btnSearchAkomodasi" onclick="search_akomodasi('1')"><i class="fas fa-search"></i> Cari</button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="wrap-list">
                            <div class="row" id="akomodasiresult">
                                <div class="list col-12">
                                    Silahkan pilih Kata Kunci Akomodasi dan Tanggal
                                </div>
                            </div>
                        </div>
                        <div class="pagination" id="akomodasipagination">
                            
                        </div>
                    </div>
                    <div id="transportasi" class="tab-pane fade">

                        <!-- SEARCH WIDGET -->
                        <div class="search-widget">
                            <div class="card">
                                <div class="title-widget">
                                    <h6><i class="flaticon-rent-a-car-sign"></i> Pencarian Transportasi</h6>
                                </div>
                                <div class="widget-body">
                                    <form>
                                        <div class="row">
                                            <div class="form-group col-12 col-md-3">
                                                <label>Tanggal Mulai</label>
                                                <input type="text" class="form-control" id="date-transport-start" name="date-transport-start" value="">
                                                <span class="icon-field flaticon-calendar-tool-for-time-organization"></span>
                                            </div>
                                            <div class="form-group col-12 col-md-3">
                                                <label>Tanggal Selesai</label>
                                                <input type="text" class="form-control" id="date-transport-end" name="date-transport-end" value="">
                                                <span class="icon-field flaticon-calendar-tool-for-time-organization"></span>
                                            </div>
                                            <div class="form-group col-12 col-md-2">
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
                                            <div class="form-group col-12 col-md-2">
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
                                            <div class="form-group col-12 col-md-2">
                                                <div class="wrap-btn">
                                                    <button type="button" class="default_btn" id="btnSearchTransportasi" onclick="search_transportasi('1')"><i class="fas fa-search"></i> Cari</button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="wrap-list">
                            <div class="row" id="transportasiresult">
                                <div class="list col-12">
                                    Silahkan pilih tanggal dan jumlah kendaraan yang ingin disewa
                                </div>
                            </div>
                        </div>
                        <div class="pagination" id="transportasipagination">
                            
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
@endsection

@section('scripts')
<!--DATEPICKER RANGE-->
<script type="text/javascript" src="{{ asset('js/flatpicker/js/flatpickr.js') }}"></script>
<script type="text/javascript">

    var baseUrl = $('meta[name=baseurl]').attr('content');

    $("#tourtag").select2({
        tags: true
    });

    $("#souvenirtag").select2({
        tags: true
    });

    $("#kulinertag").select2({
        tags: true
    });

    $("#akomodasitag").select2({
        tags: true
    });

    $(document).ready(function(){
        $('.nav-tabs a[href="#{{ $activeTab }}"]').tab('show')
    });

    $('.nav-tabs a').on('shown.bs.tab', function(event){
        var activeTab = $(event.target).text();         // active tab

        if(activeTab == 'Paket Wisata'){

            $("#tourtag").val('').change();
            // $("#tourresult").empty();
            all_tour(1);

        } else if(activeTab == 'Souvenir'){

            $("#souvenirtag").val('').change();
            // $("#souvenirresult").empty();
            all_souvenir(1);

        } else if(activeTab == 'Kuliner'){

            $("#kulinertag").val('').change();
            // $("#kulinerresult").empty();
            all_kuliner(1);

        } else if(activeTab == 'Akomodasi'){

            $("#akomodasitag").val('').change();
            // $("#akomodasiresult").empty();
            all_akomodasi(1);

        } else if(activeTab == 'Transportasi'){

            // $("#transportasiresult").empty();
            all_transportasi(1);

        }
    });

    function all_tour(page)
    {
        $.ajax({
            type    : "POST",
            dataType: "JSON",
            url     : "/product/ajax/all-tour",
            data    : {
                    _token: "{{csrf_token()}}",
                    tourpage: page
            },
            success: function(data)
            {
                if(data.status == 'success')
                {
                    var html = '';
                    $.each(data.productTour, function (i, item) {

                        html += '<div class="list col-12 col-md-6">' +
                                    '<a href="' + baseUrl + '/tour/' + item.product_id + '-' + item.slug + '">' +
                                        '<div class="wrap-img">' +
                                            '<div class="side-effect"></div>' +
                                            '<img src="' + item.product_thumbnail + '" alt="img"/>' +
                                        '</div>' +
                                        '<div class="body-list">' +
                                            '<div class="content">' +
                                                '<div class="badge-product">' +
                                                    '<h6>' + item.subtitle + '</h6>' +
                                                    '<span><i class="fas fa-star"></i> '+item.star_rating+'</span>' +
                                                '</div>' +
                                                '<h3>' + item.title + '</h3>' +
                                                '<p>' + removeTags(item.description) + '</p>' +
                                                '<p class="price">' +
                                                    '<span>Rp ' + thousandmaker(item.public_price) + '</span>' +
                                                '</p>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                    });
                    $("#tourresult").empty();
                    $("#tourresult").append(html);

                    html = '<a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>';
                    for ($x = 1; $x <= data.paging; $x++) {
                        if($x == data.page){
                            html += '<a href="#" class="active">'+$x+'</a>';
                        } else {
                            html += '<a href="#" onClick="all_tour('+$x+');">'+$x+'</a>';
                        }                        
                    };
                    html += '<a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>';

                    $("#tourpagination").empty();
                    $("#tourpagination").append(html);

                } else{
                    $.alert(data.message, 'Kesalahan');
                }
            },
            error: function ()
            {
                $.alert('Gagal mencari data', 'Kesalahan');
            }
        });
    }

    function all_souvenir(page)
    {
        $.ajax({
            type    : "POST",
            dataType: "JSON",
            url     : "/product/ajax/all-souvenir",
            data    : {
                    _token: "{{csrf_token()}}",
                    souvenirpage: page
            },
            success: function(data)
            {
                if(data.status == 'success')
                {
                    var html = '';
                    $.each(data.productSouvenir, function (i, item) {

                        html += '<div class="list col-12 col-md-6">' +
                                    '<a href="' + baseUrl + '/souvenir/' + item.product_id + '-' + item.slug + '">' +
                                        '<div class="wrap-img">' +
                                            '<div class="side-effect"></div>' +
                                            '<img src="' + item.product_thumbnail + '" alt="img"/>' +
                                        '</div>' +
                                        '<div class="body-list">' +
                                            '<div class="content">' +
                                                '<div class="badge-product">' +
                                                    '<span><i class="fas fa-star"></i> '+item.star_rating+'</span>' +
                                                '</div>' +
                                                '<h3>' + item.title + '</h3>' +
                                                '<p>' + removeTags(item.souvenir_description) + '</p>' +
                                                '<p class="price">' +
                                                    '<span>Rp ' + thousandmaker(item.default_publish_price) + '</span>' +
                                                '</p>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                    });
                    $("#souvenirresult").empty();
                    $("#souvenirresult").append(html);

                    html = '<a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>';
                    for ($x = 1; $x <= data.paging; $x++) {
                        if($x == data.page){
                            html += '<a href="#" class="active">'+$x+'</a>';
                        } else {
                            html += '<a href="#" onClick="all_souvenir('+$x+');">'+$x+'</a>';
                        }                        
                    };
                    html += '<a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>';

                    $("#souvenirpagination").empty();
                    $("#souvenirpagination").append(html);

                } else{
                    $.alert(data.message, 'Kesalahan');
                }
            },
            error: function ()
            {
                $.alert('Gagal mencari data', 'Kesalahan');
            }
        });
    }

    function all_kuliner(page)
    {
        $.ajax({
            type    : "POST",
            dataType: "JSON",
            url     : "/product/ajax/all-kuliner",
            data    : {
                    _token: "{{csrf_token()}}",
                    kulinerpage: page
            },
            success: function(data)
            {
                if(data.status == 'success')
                {
                    var html = '';
                    $.each(data.productKuliner, function (i, item) {

                        html += '<div class="list col-12 col-md-6">' +
                                    '<a href="' + baseUrl + '/kuliner/' + item.product_id + '-' + item.slug + '">' +
                                        '<div class="wrap-img">' +
                                            '<div class="side-effect"></div>' +
                                            '<img src="' + item.product_thumbnail + '" alt="img"/>' +
                                        '</div>' +
                                        '<div class="body-list">' +
                                            '<div class="content">' +
                                                '<div class="badge-product">' +
                                                    '<span><i class="fas fa-star"></i> '+item.star_rating+'</span>' +
                                                '</div>' +
                                                '<h3>' + item.title + '</h3>' +
                                                '<p>' + removeTags(item.culinary_description) + '</p>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                    });
                    $("#kulinerresult").empty();
                    $("#kulinerresult").append(html);

                    html = '<a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>';
                    for ($x = 1; $x <= data.paging; $x++) {
                        if($x == data.page){
                            html += '<a href="#" class="active">'+$x+'</a>';
                        } else {
                            html += '<a href="#" onClick="all_kuliner('+$x+');">'+$x+'</a>';
                        }                        
                    };
                    html += '<a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>';

                    $("#kulinerpagination").empty();
                    $("#kulinerpagination").append(html);

                } else{
                    $.alert(data.message, 'Kesalahan');
                }
            },
            error: function ()
            {
                $.alert('Gagal mencari data', 'Kesalahan');
            }
        });
    }

    function all_akomodasi(page)
    {
        $.ajax({
            type    : "POST",
            dataType: "JSON",
            url     : "/product/ajax/all-akomodasi",
            data    : {
                    _token: "{{csrf_token()}}",
                    akomodasipage: page
            },
            success: function(data)
            {
                if(data.status == 'success')
                {
                    var html = '';
                    $.each(data.productAkomodasi, function (i, item) {

                        html += '<div class="list col-12 col-md-6">' +
                                    '<a href="' + baseUrl + '/akomodasi/' + item.url + '">' +
                                        '<div class="wrap-img">' +
                                            '<div class="side-effect"></div>' +
                                            '<img src="' + item.product_thumbnail + '" alt="img"/>' +
                                        '</div>' +
                                        '<div class="body-list">' +
                                            '<div class="content">' +
                                                '<div class="badge-product">' +
                                                '</div>' +
                                                '<h3>' + item.product_name + '</h3>' +
                                                '<p>' + removeTags(item.description) + '</p>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                    });
                    $("#akomodasiresult").empty();
                    $("#akomodasiresult").append(html);

                    html = '<a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>';
                    for ($x = 1; $x <= data.paging; $x++) {
                        if($x == data.page){
                            html += '<a href="#" class="active">'+$x+'</a>';
                        } else {
                            html += '<a href="#" onClick="all_akomodasi('+$x+');">'+$x+'</a>';
                        }                        
                    };
                    html += '<a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>';

                    $("#akomodasipagination").empty();
                    $("#akomodasipagination").append(html);

                } else{
                    $.alert(data.message, 'Kesalahan');
                }
            },
            error: function ()
            {
                $.alert('Gagal mencari data', 'Kesalahan');
            }
        });
    }

    function all_transportasi(page)
    {
        $.ajax({
            type    : "POST",
            dataType: "JSON",
            url     : "/product/ajax/all-transportasi",
            data    : {
                    _token: "{{csrf_token()}}",
                    transportasipage: page
            },
            success: function(data)
            {
                if(data.status == 'success')
                {
                    $('#btnSearchTransportasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');

                    var html = '';
                    $.each(data.productTransportasi, function (i, item) {

                        html += '<div class="list col-12 col-md-6">' +
                                    '<a href="' + baseUrl + '/transportasi/' + item.url + '">' +
                                        '<div class="wrap-img">' +
                                            '<div class="side-effect"></div>' +
                                            '<img src="' + item.product_thumbnail + '" alt="img"/>' +
                                        '</div>' +
                                        '<div class="body-list">' +
                                            '<div class="content">' +
                                                '<h3>' + item.product_name + '</h3>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                    });
                    $("#transportasiresult").empty();
                    $("#transportasiresult").append(html);

                    html = '<a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>';
                    for ($x = 1; $x <= data.paging; $x++) {
                        if($x == data.page){
                            html += '<a href="#" class="active">'+$x+'</a>';
                        } else {
                            html += '<a href="#" onClick="all_transportasi('+$x+');">'+$x+'</a>';
                        }                        
                    };
                    html += '<a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>';

                    $("#transportasipagination").empty();
                    $("#transportasipagination").append(html);

                } else{
                    $.alert(data.message, 'Kesalahan');
                }
            },
            error: function ()
            {
                $.alert('Gagal mencari data', 'Kesalahan');
            }
        });
    }

    function search_tour(page)
    {
        $('#btnSearchTour').addClass('disabled').html('<i class="fas fa-spin fa-spinner"></i> Pengecekan');

        if($('#date-package').val() == '')
        {
            $('#btnSearchTour').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
            $.alert('Silahkan pilih Kata Kunci Tour, Tanggal, dan Jumlah Pax', 'Info');
            return false;
        }

        // if($('#tourtag').val() == '')
        // {
        //     $('#btnSearchTour').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
        //     $.alert('Silahkan pilih Kata Kunci Tour, Tanggal, dan Jumlah Pax', 'Info');
        //     return false;
        // }
        
        var tourtag = $('#tourtag').val();
        
        $.ajax({
            type    : "POST",
            dataType: "JSON",
            url     : "/product/ajax/tour",
            data    : {
                    _token: "{{csrf_token()}}",
                    tourtag: tourtag,
                    datebooking: $('#date-package').val(),
                    quantity: $('[name=quant]').val(),
                    tourpage: page
            },
            success: function(data)
            {
                if(data.status == 'success')
                {
                    $('#btnSearchTour').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');

                    var html = '';
                    $.each(data.productTour, function (i, item) {

                        html += '<div class="list col-12 col-md-6">' +
                                    '<a href="' + baseUrl + '/tour/' + item.product_id + '-' + item.slug + '">' +
                                        '<div class="wrap-img">' +
                                            '<div class="side-effect"></div>' +
                                            '<img src="' + item.product_thumbnail + '" alt="img"/>' +
                                        '</div>' +
                                        '<div class="body-list">' +
                                            '<div class="content">' +
                                                '<div class="badge-product">' +
                                                    '<h6>' + item.subtitle + '</h6>' +
                                                    '<span><i class="fas fa-star"></i> '+item.star_rating+'</span>' +
                                                '</div>' +
                                                '<h3>' + item.title + '</h3>' +
                                                '<p>' + removeTags(item.description) + '</p>' +
                                                '<p class="price">' +
                                                    '<span>Rp ' + thousandmaker(item.public_price) + '</span>' +
                                                '</p>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                    });
                    $("#tourresult").empty();
                    $("#tourresult").append(html);

                    html = '<a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>';
                    for ($x = 1; $x <= data.paging; $x++) {
                        if($x == data.page){
                            html += '<a href="#" class="active">'+$x+'</a>';
                        } else {
                            html += '<a href="#" onClick="search_tour('+$x+');">'+$x+'</a>';
                        }                        
                    };
                    html += '<a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>';

                    $("#tourpagination").empty();
                    $("#tourpagination").append(html);

                } else{
                    $('#btnSearchTour').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
                    $.alert(data.message, 'Kesalahan');
                }
            },
            error: function ()
            {
                $('#btnSearchTour').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
                $.alert('Gagal mencari data', 'Kesalahan');
            }
        });
    }

    function search_souvenir(page)
    {
        $('#btnSearchSouvenir').addClass('disabled').html('<i class="fas fa-spin fa-spinner"></i> Pengecekan');

        if($('#souvenirtag').val() == '')
        {
            $('#btnSearchSouvenir').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
            $.alert('Silahkan pilih nama souvenir', 'Info');
            return false;
        }
        
        var souvenirtag = $('#souvenirtag').val();
        
        $.ajax({
            type    : "POST",
            dataType: "JSON",
            url     : "/product/ajax/souvenir",
            data    : {
                    _token: "{{csrf_token()}}",
                    souvenirtag: souvenirtag,
                    souvenirpage: page
            },
            success: function(data)
            {
                if(data.status == 'success')
                {
                    $('#btnSearchSouvenir').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');

                    var html = '';
                    $.each(data.productSouvenir, function (i, item) {

                        html += '<div class="list col-12 col-md-6">' +
                                    '<a href="' + baseUrl + '/souvenir/' + item.product_id + '-' + item.slug + '">' +
                                        '<div class="wrap-img">' +
                                            '<div class="side-effect"></div>' +
                                            '<img src="' + item.product_thumbnail + '" alt="img"/>' +
                                        '</div>' +
                                        '<div class="body-list">' +
                                            '<div class="content">' +
                                                '<div class="badge-product">' +
                                                    '<span><i class="fas fa-star"></i> '+item.star_rating+'</span>' +
                                                '</div>' +
                                                '<h3>' + item.title + '</h3>' +
                                                '<p>' + removeTags(item.souvenir_description) + '</p>' +
                                                '<p class="price">' +
                                                    '<span>Rp ' + thousandmaker(item.default_publish_price) + '</span>' +
                                                '</p>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                    });
                    $("#souvenirresult").empty();
                    $("#souvenirresult").append(html);

                    html = '<a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>';
                    for ($x = 1; $x <= data.paging; $x++) {
                        if($x == data.page){
                            html += '<a href="#" class="active">'+$x+'</a>';
                        } else {
                            html += '<a href="#" onClick="search_souvenir('+$x+');">'+$x+'</a>';
                        }                        
                    };
                    html += '<a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>';

                    $("#souvenirpagination").empty();
                    $("#souvenirpagination").append(html);

                } else{
                    $('#btnSearchSouvenir').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
                    $.alert(data.message, 'Kesalahan');
                }
            },
            error: function ()
            {
                $('#btnSearchSouvenir').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
                $.alert('Gagal mencari data', 'Kesalahan');
            }
        });
    }

    function search_kuliner(page)
    {
        $('#btnSearchKuliner').addClass('disabled').html('<i class="fas fa-spin fa-spinner"></i> Pengecekan');

        if($('#kulinertag').val() == '')
        {
            $('#btnSearchKuliner').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
            $.alert('Silahkan pilih nama lokasi / kuliner', 'Info');
            return false;
        }
        
        var kulinertag = $('#kulinertag').val();
        
        $.ajax({
            type    : "POST",
            dataType: "JSON",
            url     : "/product/ajax/kuliner",
            data    : {
                    _token: "{{csrf_token()}}",
                    kulinertag: kulinertag,
                    kulinerpage: page
            },
            success: function(data)
            {
                if(data.status == 'success')
                {
                    $('#btnSearchKuliner').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');

                    var html = '';
                    $.each(data.productKuliner, function (i, item) {

                        html += '<div class="list col-12 col-md-6">' +
                                    '<a href="' + baseUrl + '/kuliner/' + item.product_id + '-' + item.slug + '">' +
                                        '<div class="wrap-img">' +
                                            '<div class="side-effect"></div>' +
                                            '<img src="' + item.product_thumbnail + '" alt="img"/>' +
                                        '</div>' +
                                        '<div class="body-list">' +
                                            '<div class="content">' +
                                                '<div class="badge-product">' +
                                                    '<span><i class="fas fa-star"></i> '+item.star_rating+'</span>' +
                                                '</div>' +
                                                '<h3>' + item.title + '</h3>' +
                                                '<p>' + removeTags(item.culinary_description) + '</p>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                    });
                    $("#kulinerresult").empty();
                    $("#kulinerresult").append(html);

                    html = '<a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>';
                    for ($x = 1; $x <= data.paging; $x++) {
                        if($x == data.page){
                            html += '<a href="#" class="active">'+$x+'</a>';
                        } else {
                            html += '<a href="#" onClick="search_kuliner('+$x+');">'+$x+'</a>';
                        }                        
                    };
                    html += '<a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>';

                    $("#kulinerpagination").empty();
                    $("#kulinerpagination").append(html);

                } else{
                    $('#btnSearchKuliner').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
                    $.alert(data.message, 'Kesalahan');
                }
            },
            error: function ()
            {
                $('#btnSearchKuliner').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
                $.alert('Gagal mencari data', 'Kesalahan');
            }
        });
    }

    function search_akomodasi(page)
    {
        $('#btnSearchAkomodasi').addClass('disabled').html('<i class="fas fa-spin fa-spinner"></i> Pengecekan');

        // if($('#akomodasitag').val() == '')
        // {
        //     $('#btnSearchAkomodasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
        //     $.alert('Silahkan pilih Kata Kunci Akomodasi', 'Info');
        //     return false;
        // }
        
        if($('#date-hotel-start').val() == '')
        {
            $('#btnSearchAkomodasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
            $.alert('Silahkan pilih tanggal checkin', 'Info');
            return false;
        }
            
        if($('#date-hotel-end').val() == '')
        {
            $('#btnSearchAkomodasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
            $.alert('Silahkan pilih tanggal checkout', 'Info');
            return false;
        }
            
        var akomodasitag = $('#akomodasitag').val();
        
        $.ajax({
            type    : "POST",
            dataType: "JSON",
            url     : "/product/ajax/akomodasi",
            data    : {
                    _token: "{{csrf_token()}}",
                    akomodasitag: akomodasitag,
                    datestart: $('#date-hotel-start').val(),
                    dateend: $('#date-hotel-end').val(),
                    akomodasipage: page
            },
            success: function(data)
            {
                if(data.status == 'success')
                {
                    $('#btnSearchAkomodasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');

                    var html = '';
                    $.each(data.productAkomodasi, function (i, item) {

                        html += '<div class="list col-12 col-md-6">' +
                                    '<a href="' + baseUrl + '/akomodasi/' + item.url + '">' +
                                        '<div class="wrap-img">' +
                                            '<div class="side-effect"></div>' +
                                            '<img src="' + item.product_thumbnail + '" alt="img"/>' +
                                        '</div>' +
                                        '<div class="body-list">' +
                                            '<div class="content">' +
                                                '<div class="badge-product">' +
                                                '</div>' +
                                                '<h3>' + item.product_name + '</h3>' +
                                                '<p>' + removeTags(item.description) + '</p>' +
                                                '<p class="price">' +
                                                    '<span>Rp ' + item.price + '/malam</span>' +
                                                '</p>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                    });
                    $("#akomodasiresult").empty();
                    $("#akomodasiresult").append(html);

                    html = '<a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>';
                    for ($x = 1; $x <= data.paging; $x++) {
                        if($x == data.page){
                            html += '<a href="#" class="active">'+$x+'</a>';
                        } else {
                            html += '<a href="#" onClick="search_akomodasi('+$x+');">'+$x+'</a>';
                        }                        
                    };
                    html += '<a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>';

                    $("#akomodasipagination").empty();
                    $("#akomodasipagination").append(html);

                } else{
                    $('#btnSearchAkomodasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
                    $.alert(data.message, 'Kesalahan');
                }
            },
            error: function ()
            {
                $('#btnSearchAkomodasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
                $.alert('Gagal mencari data', 'Kesalahan');
            }
        });
    }

    function search_transportasi(page)
    {
        $('#btnSearchTransportasi').addClass('disabled').html('<i class="fas fa-spin fa-spinner"></i> Pengecekan');

        if($('#date-transport-start').val() == '')
        {
            $('#btnSearchTransportasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
            $.alert('Silahkan pilih tanggal awal penyewaan', 'Info');
            return false;
        }
            
        if($('#date-transport-end').val() == '')
        {
            $('#btnSearchTransportasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
            $.alert('Silahkan pilih tanggal akhir penyewaan', 'Info');
            return false;
        }
            
        $.ajax({
            type    : "POST",
            dataType: "JSON",
            url     : "/product/ajax/transportasi",
            data    : {
                    _token: "{{csrf_token()}}",
                    datestart: $('#date-transport-start').val(),
                    dateend: $('#date-transport-end').val(),
                    quantity: $('[name=quant]').val(),
                    driver: $('[name=driver]:checked').val(),
                    transportasipage: page
            },
            success: function(data)
            {
                if(data.status == 'success')
                {
                    $('#btnSearchTransportasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');

                    var html = '';
                    $.each(data.productTransportasi, function (i, item) {

                        html += '<div class="list col-12 col-md-6">' +
                                    '<a href="' + baseUrl + '/transportasi/detail/' + item.url + '">' +
                                        '<div class="wrap-img">' +
                                            '<div class="side-effect"></div>' +
                                            '<img src="' + item.product_thumbnail + '" alt="img"/>' +
                                        '</div>' +
                                        '<div class="body-list">' +
                                            '<div class="content">' +
                                                '<div class="badge-product">' +
                                                    '<h6>' + item.company_name + '</h6>' +
                                                    '<span><i class="fas fa-star"></i> '+item.star_rating+'</span>' +
                                                '</div>' +
                                                '<h3>' + item.product_name + '</h3>' +
                                                '<p style="display: block;">';
                        
                        if(item.is_driver){
                            if(item.is_gas){
                                html += "<i class='fas fa-male'></i> Supir & Bensin<br>";
                            } else {
                                html += "<i class='fas fa-male'></i> Supir<br>";
                            }                                
                        }
                        
                        if(item.is_water){
                            html += "<i class='fab fa-gulp'></i> Air Mineral<br>";
                        }
                        
                        if(item.is_insurance){
                            html += "<i class='fas fa-car'></i> Asuransi<br>";
                        }
                        
                        if(item.is_reschedule){
                            html += "<i class='fas fa-hourglass-half'></i> Dapat Dijadwalkan Ulang<br>";
                        }
                        
                        if(item.is_refund){
                            html += "<i class='fas fa-sticky-note'></i> Dapat Direfund<br>";
                        }
                        
                        html += '</p>' +
                                                '<p class="price">' +
                                                    '<span>Rp ' + item.price + '</span>' +
                                                '</p>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                    });
                    $("#transportasiresult").empty();
                    $("#transportasiresult").append(html);

                    html = '<a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>';
                    for ($x = 1; $x <= data.paging; $x++) {
                        if($x == data.page){
                            html += '<a href="#" class="active">'+$x+'</a>';
                        } else {
                            html += '<a href="#" onClick="search_transportasi('+$x+');">'+$x+'</a>';
                        }                        
                    };
                    html += '<a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>';

                    $("#transportasipagination").empty();
                    $("#transportasipagination").append(html);

                } else{
                    $('#btnSearchTransportasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
                    $.alert(data.message, 'Kesalahan');
                }
            },
            error: function ()
            {
                $('#btnSearchTransportasi').removeClass('disabled').html('<i class="fas fa-search"></i> Cari');
                $.alert('Gagal mencari data', 'Kesalahan');
            }
        });
    }

    var disabledMobile = false;
    if (window.matchMedia("(max-width: 678px)").matches) {
        disabledMobile = true;
    }

    $("#date-package").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d",
        minDate: "today",
        enableTime: false,
        altInput: true,
        showMonths: 1,
        disableMobile: disabledMobile
    });

    let startpickerhotel = flatpickr('#date-hotel-start', {
        enableTime: true,
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(1),
        enableTime: false,
        altInput: true,
        showMonths: 1,
        disableMobile: disabledMobile,
        onClose: function(selectedDates, dateStr, instance) {
            endpickerhotel.set('minDate', new Date(dateStr).fp_incr(1));
        },
    });

    let endpickerhotel = flatpickr('#date-hotel-end', {
        enableTime: true,
        dateFormat: "Y-m-d",
        minDate: $('#date-hotel-start').attr('value'),
        enableTime: false,
        altInput: true,
        showMonths: 1,
        disableMobile: disabledMobile,
        onClose: function(selectedDates, dateStr, instance) {
            startpickerhotel.set('maxDate', new Date(dateStr).fp_incr(-1));
        },
    });

    let startpickertransport = flatpickr('#date-transport-start', {
        enableTime: true,
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(7),
        enableTime: false,
        altInput: true,
        showMonths: 1,
        disableMobile: disabledMobile,
        onClose: function(selectedDates, dateStr, instance) {
            endpickertransport.set('minDate', dateStr);
        },
    });

    let endpickertransport = flatpickr('#date-transport-end', {
        enableTime: true,
        dateFormat: "Y-m-d",
        minDate: $('#date-transport-start').attr('value'),
        enableTime: false,
        altInput: true,
        showMonths: 1,
        disableMobile: disabledMobile,
        onClose: function(selectedDates, dateStr, instance) {
            startpickertransport.set('maxDate', dateStr);
        },
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

    function thousandmaker(x){
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function removeTags(str) {
      if ((str===null) || (str===''))
      return false;
      else
      str = str.toString();
      return str.replace( /(<([^>]+)>)/ig, '');
   }

</script>
<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/product/all.js') }}"></script>
@endsection