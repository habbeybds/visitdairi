@extends('layouts.main')

@section('contents')
<link rel="stylesheet" type="text/css" href="{{ asset('css/store.css').'?k='.rand() }}">

<section class="head-title-store" style="background-image: url({{ asset('images/bg-head-4.jpg') }})">
    <div class="bg-color-overlay"></div>
    <div class="container">
        <div class="wrap-head">
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Store</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<section class="store">
    <div class="container">
        <div class="header-toko row">
            <div class="col-12 p-lr-0">
                <div class="alert alert-info alert-dismissible" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span class="title"><b>Informasi Toko</b></span>
                    <div class="desc">Toko beroperasi pukul 10:00-16:00 WIB. Pesanan yang lewat pukul 16:00 akan diproses saat toko beroperasi kembali.</div>
                </div>
            </div>
            <div class="col-12 p-lr-0">
                <div class="header-store">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 line-scat">
                                <div class="row">
                                    <div class="col-2">
                                        <div class="counter-img">
                                            <div class="cover-img">
                                                <div class="shape-img">
                                                    <div class="profile-img" style="background-image: url(images/partners/1/logitech.jpg)"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <div class="cover-header">
                                            <div class="store-title">
                                                <h3>Logitech G Official</h3>
                                            </div>
                                            <div class="location-map">
                                                <button type="button" class="btn-maps btn btn-success" data-toggle="modal" data-target="#mapsModal">
                                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                                    maps
                                                </button>
                                            </div>
                                            <div class="description-store">
                                                <button type="button" class="btn-desc-store btn btn-light" data-toggle="modal" data-target="#StoreInfoModal">
                                                    <i class="fa fa-university" aria-hidden="true"></i>
                                                    Info
                                                </button>
                                            </div>
                                        </div>



                                        <!-- Modal Maps-->
                                        <div class="modal fade" id="mapsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <div class="row">
                                                            <div class="col-11">
                                                                <h5 class="modal-title" id="exampleModalLabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Maps Toko</h5>
                                                            </div>
                                                            <div class="col-1">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body">
                                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d996.3048037655883!2d98.30949722015332!3d2.751309657719782!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x303048009269d3df%3A0x50923cffc8679605!2sHOTEL%20ANGKASA%20RAYA!5e0!3m2!1sid!2sid!4v1609374756857!5m2!1sid!2sid" width="600" height="450" frameborder="0" style="border:0; width: 100%;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Info-->
                                        <div class="modal fade" id="StoreInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <div class="row">
                                                            <div class="col-11">
                                                                <h5 class="modal-title" id="exampleModalLabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Info Toko</h5>
                                                            </div>
                                                            <div class="col-1">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body">
                                                        <span>Logitech G Official adalah akun resmi Logitech Official Shop di platfom VisitDairi
                                                            Resi Update Otomatis Di Akun Pembeli Jadi Tidak Perlu Menunggu Resi Untuk Di Input.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="address-logo">
                                    <i class="fa fa-address-card" aria-hidden="true"></i>
                                </div>
                                <div class="address-store">
                                    <span style="font-size: 13px;">Jl H Dugul Gg Pendowo Limo No.3</span><br>
                                    <span style="font-size: 14px; font-weight: 600;">Medan</span><br>
                                    <span style="font-size: 14px; font-weight: 600;">Sumatera Utara</span><br>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="address-email">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i><span style="font-size: 13px;"> rahmad.septian@gmail.com</span><br>
                                </div>
                                <div class="address-phone">
                                    <i class="fa fa-phone" aria-hidden="true"></i><span style="font-size: 13px;"> 081932650166</span><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="body-content">
            <div class="row">
                <div class="col-12 p-lr-0">
                    <ul class="nav nav-tabs">
                        <li class="nav-item active">
                            <a class="nav-link active" data-toggle="tab" href="#home" aria-current="page">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#produk">Produk</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active show">
                            <div class="banner-promo-store">
                                <div class="store-etalase">
                                    <div class="img-etalase"><img class="img-fluid" src="{{ asset('images/partners/1/logitech_promo.jpg') }}" alt="promo-store title" /></div>
                                    <div class="img-etalase"><img class="img-fluid" src="{{ asset('images/partners/1/logitech_promo.jpg') }}" alt="promo-store title" /></div>
                                    <div class="img-etalase"><img class="img-fluid" src="{{ asset('images/partners/1/logitech_promo.jpg') }}" alt="promo-store title" /></div>
                                    <div class="img-etalase"><img class="img-fluid" src="{{ asset('images/partners/1/logitech_promo.jpg') }}" alt="promo-store title" /></div>
                                </div>
                            </div>

                            <div class="title-best-product">
                                Produk Unggulan
                            </div>
                            <div class="best-product">
                                <div id="list-best-product">
                                    <div class="card-product">
                                        <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                        <div class="card-product-body">
                                            <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                            <p class="card-currency">Rp.120.000</p>
                                        </div>
                                    </div>
                                    <div class="card-product">
                                        <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                        <div class="card-product-body">
                                            <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                            <p class="card-currency">Rp.120.000</p>
                                        </div>
                                    </div>
                                    <div class="card-product">
                                        <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                        <div class="card-product-body">
                                            <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                            <p class="card-currency">Rp.120.000</p>
                                        </div>
                                    </div>
                                    <div class="card-product">
                                        <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                        <div class="card-product-body">
                                            <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                            <p class="card-currency">Rp.120.000</p>
                                        </div>
                                    </div>
                                    <div class="card-product">
                                        <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                        <div class="card-product-body">
                                            <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                            <p class="card-currency">Rp.120.000</p>
                                        </div>
                                    </div>
                                    <div class="card-product">
                                        <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                        <div class="card-product-body">
                                            <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                            <p class="card-currency">Rp.120.000</p>
                                        </div>
                                    </div>
                                    <div class="card-product">
                                        <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                        <div class="card-product-body">
                                            <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                            <p class="card-currency">Rp.120.000</p>
                                        </div>
                                    </div>
                                    <div class="card-product">
                                        <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                        <div class="card-product-body">
                                            <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                            <p class="card-currency">Rp.120.000</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="produk" class="tab-pane fade">

                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="content-product">
                                            <div class="input-group">
                                                <input class="form-control py-2" type="search" value="" placeholder="Cari produk di toko ini" id="example-search-input">
                                                <span class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-12 col-md-4"></div>
                                    <div class="col-12 col-md-4">
                                        <div class="row content-product">
                                            <div class="col-3" style="margin: auto 0;">
                                                <span class="filter-produk">Urutkan</span>
                                            </div>
                                            <div class="col-6">
                                                <select class="custom-select">
                                                    <option selected>Terbaru</option>
                                                    <option value="1">Harga Terendah</option>
                                                    <option value="2">Harga Tertinggi</option>
                                                    <option value="3">Terlaris</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="daftar-produk">
                                    <div class="row">
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-15">
                                            <div class="card-product">
                                                <img class="img-fluid" src="{{ asset('images/partners/1/logitech_mouse.jpg') }}" alt="Product" />
                                                <div class="card-product-body">
                                                    <h5 class="card-title">Logitech G733 LIGHTSPEED Wireless RGB 7.1 Surround Gaming Headset - Putih</h5>
                                                    <p class="card-currency">Rp.120.000</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

@section('scripts')

<script type="text/javascript">
    $(document).ready(function() {
        $('#list-best-product').slick({
            dots: true,
            infinite: false,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 6,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 720,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });

        $('.store-etalase').slick({
            dots: false,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 2000,
            prevArrow: false,
            nextArrow: false
        });
    });
</script>
@endsection