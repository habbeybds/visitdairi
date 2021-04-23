@extends('layouts.main')

@section('contents')
<!-- <section class="banner">
    <div id="banner-home" class="carousel slide" data-ride="carousel">
        {!! $htmlSlider !!}
        <div class="carousel-control">
            <a class="carousel-control-prev" href="#banner-home" role="button" data-slide="prev">
                <i class="far fa-arrow-left"></i>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#banner-home" role="button" data-slide="next">
                <i class="far fa-arrow-right"></i>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    //hapus
    <div class="divider-bg"></div>
</section> -->

<script defer type="text/javascript" src="{{ asset('js/banner-slider.js?').rand() }}"></script>
<section class="benner">
    <div id="banner-slider" class="banner-home">
    {!! $htmlSlickSlider !!}
    </div>
   
    <div id="externalNav">
    {!! $htmlSlickSliderNavigation !!}
    </div>
</section>

<section class="about_us space">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-7">
                <div class="title-section">
                    <h6>{{ $config['siteabout']['name'] }}
                        <span class="icon_title"><img src="{{ asset('images/icon_title.png') }}" alt="icon title" /></span>
                    </h6>
                    <h3>{{ $config['siteabout']['title'] }}</h3>
                </div>
                <div class="desc_about">
                    <p>{{ $config['siteabout']['description'] }}</p>
                </div>
                <div class="wrap_btn">
                    <a class="default_btn" href="{{ str_replace('{baseurl}', url('/') . '/', $config['siteabout']['link']) }}">Selengkapnya</a>
                </div>
            </div>
            <div class="col-12 col-md-5">
                <div class="thumbnail-yt">
                    <div class="side-effect"></div>
                    <div class="bg-thumbnail"></div>
                    <img class="img-fluid" src="{{ $config['siteabout']['image'] }}" alt="thumbnail-youtube" />
                    <div class="wrap-btn-yt">
                        <a class="btn-yt video-icon" data-toggle="modal" data-target="#yt-modal" data-src="https://www.youtube.com/embed/{{ $config['siteabout']['videoid'] }}">
                            <i class="fas fa-play"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="destination space">
    <div class="container">
        <div class="row">
            <div class="title-section-2">
                <span class="badge-title">{{ $section['destination']['badge'] }}</span>
                <h3>{{ $section['destination']['title'] }}</h3>
                <h6>{{ $section['destination']['description'] }}</h6>
            </div>
        </div>
    </div>
    <div class="wrap_btn_destination">
        <a class="btn-default-line" href="{{ $section['destination']['button_link'] }}">{{ $section['destination']['button_caption'] }}</a>
    </div>
    <div class="owl-carousel owl-theme" id="slider-destination">
        @foreach($destinations as $destination)
        <div class="item">
            <a href="{{ route('destinationdetail', [$destination['id'], $destination['slug']]) }}" class="wrap_item">
                <div>
                    <div class="bg-slide-owl"></div>
                    <div class="caption-destination">
                        <ul class="star-destination has-star" data-rating="5"></ul>
                        <div class="title-destination">
                            <h5>{{ $destination['name'] }}</h5>
                            <p>{{ $destination['title'] }}</p>
                        </div>
                    </div>
                    <img src="{{ $destination['image'] }}" alt="img-destination" />
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>

<section class="product space mb-5">
    <div class="container">
        <div class="row">
            <div class="title-section-3">
                <span class="badge-title">{{ $section['recomendation']['badge'] }}</span>
                <h3>{{ $section['recomendation']['title'] }}</h3>
                <h6>{{ $section['recomendation']['description'] }}</h6>
            </div>
        </div>
    </div>
    <div class="wrap-product">
        <div class="container second-bg">
            <div class="row">

                <div class="col-6 col-md-2">
                    <div class="wrap-title-desc-product">
                        <h3 class="title-product">Visit Dairi</h3>
                        <div class="desc-product"></div>
                    </div>
                </div>
                <div class="col-6 col-md-10">
                    <div class="tab-product">
                        <ul class="nav nav-tabs">
                            {!! $productTab !!}
                        </ul>

                        <div class="tab-content">
                            {!! $productContent !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="news space">
    <div class="container">
        <div class="row">
            <div class="title-section-2">
                <span class="badge-title">{{ $section['latestpost']['badge'] }}</span>
                <h3>{{ $section['latestpost']['title'] }}</h3>
                <h6>{{ $section['latestpost']['description'] }}</h6>
            </div>
            <div class="news-content">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="wrap-news">
                            <div class="wrap-img">
                                <div class="bg-news"></div>
                                <img src="images/news/preloader.jpg" alt="img-news" />
                            </div>
                            <div class="caption-news">
                                <a href="#">
                                    <h5><i class="fas fa-spin fa-spinner"></i> Mengambil data...</h5>
                                </a>
                                <p><i class="fas fa-spin fa-spinner"></i> Mengambil data...</p>
                                <div class="date">
                                    <p>
                                        <i class="far fa-calendar-alt"></i>
                                        <span><i class="fas fa-spin fa-spinner"></i> Mengambil data...</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="wrap-news">
                            <div class="wrap-img">
                                <div class="bg-news"></div>
                                <img src="images/news/preloader.jpg" alt="img-news" />
                            </div>
                            <div class="caption-news">
                                <a href="#">
                                    <h5><i class="fas fa-spin fa-spinner"></i> Mengambil data...</h5>
                                </a>
                                <p><i class="fas fa-spin fa-spinner"></i> Mengambil data...</p>
                                <div class="date">
                                    <p>
                                        <i class="far fa-calendar-alt"></i>
                                        <span><i class="fas fa-spin fa-spinner"></i> Mengambil data...</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="wrap-news">
                            <div class="wrap-img">
                                <div class="bg-news"></div>
                                <img src="images/news/preloader.jpg" alt="img-news" />
                            </div>
                            <div class="caption-news">
                                <a href="#">
                                    <h5><i class="fas fa-spin fa-spinner"></i> Mengambil data...</h5>
                                </a>
                                <p><i class="fas fa-spin fa-spinner"></i> Mengambil data...</p>
                                <div class="date">
                                    <p>
                                        <i class="far fa-calendar-alt"></i>
                                        <span><i class="fas fa-spin fa-spinner"></i> Mengambil data...</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrap-btn">
                    <a href="{{ url('kabar-dairi') }}" class="btn-all-news">Lihat Semua Berita</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="subscribe space bg-subscribe">
    <div class="container">
        <div class="row">
            <div class="title-section-4">
                <h3><span class="badge-title">Rekomendasi</span> Langganan kabar Dairi</h3>
                <h6>Berlangganan dan terima kabar kami tentang produk dan destinasi menarik di Dairi</h6>
            </div>
            <div class="subscribe-input">
                <input type="text" class="form-control input-email" placeholder="Lengkapi Email Anda" />
                <a href="#" class="default_btn btn-submit">Subscribe</a>
            </div>
        </div>
    </div>
</section>

<!-- MODAL ABOUT US YOUTUBE -->
<div class="youtube-modal">
    <div class="modal fade" id="yt-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="fas fa-times"></span>
                    </button>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="video" class="embed-responsive-item" src="" allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style type="text/css">
    .destination .owl-carousel .owl-stage-outer .owl-stage .owl-item .item a.wrap_item .bg-slide-owl {
        height: 100% !important;
    }

    section.banner .carousel-control .carousel-control-prev {
        z-index: 99;
    }

    .subscribe .subscribe-input {
        padding-left: inherit;
    }

    .product .wrap-product h3.title-product {
        font-size: 24px;
    }

    .product .wrap-product div.desc-product p {
        color: #fff;
        font-size: 13px;
        font-weight: 300;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 5;
        overflow: hidden;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript">
    var ImgPath = '{{ config('constants.UPLOAD_PATH') }}';
</script>
<script defer type="text/javascript" src="{{ asset('js/homepage.js?').rand() }}"></script>

<script type="text/javascript">
	var subscribeButton = $('.subscribe-input > a.btn-submit');
	subscribeButton.click(function(e) {
		e.preventDefault();
		var email = $('.subscribe-input > .input-email');
		var txtemail = email.val();
		subscribeButton.html('<i class="fa fa-spin fa-spinner"></i> Mengirim..');
		var params = {
			_token: '{{ csrf_token() }}',
			email: txtemail
		};
		$.post('{{ route('subscribeSubmit') }}', params, function(response) {
			var errorMessage = 'Proses pendaftaran subscribe gagal, silahkan coba kembali!';
			try {
				if(response.status == 'success') {
					$.alert('Terimakasih telah menjadi subscriber dari VisitDairi.com, tunggu informasi menarik dari kami.', 'Submit Sukses');	
					email.val('');
				} else {
					$.alert(errorMessage, 'Submit gagal');
				}
				
			} catch(err) {
				$.alert(errorMessage, 'Submit gagal');
			}
			subscribeButton.html('Subscribe');
		})
		.fail(function(err) {
			$.alert(errorMessage, 'Submit gagal');
		});
	});
</script>

@endsection