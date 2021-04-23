@extends('layouts.main')

@section('contents')
<section class="head-title-destination destination-single" style="background-image: url({{ config('constants.UPLOAD_PATH') . $config['destination_header_image'] }})">
    <div class="container">
        <div class="wrap-head">
            <h3>{{ $destination->name }}</h3>
            <ul class="review-title has-star" data-rating="{{ floatval($destination->review_avg) }}"> </ul>
            <span class="point-star">{{ number_format($destination->review_avg,1,'.',',') }}</span>
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item">Destinasi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="single-destination-slider">
    <div class="container p-0">
        <div class="carousel-single-destination">
            <div class="owl-carousel owl-theme" id="single-destination">
                @foreach($images as $img)
                <div class="item">
                    <div class="bg-slide-owl"></div>
                    <img src="{{ config('constants.UPLOAD_PATH') . $img->image }}" alt="img"/>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

<section class="single-content space">
    <div class="container">
        <div class="row">
            <div class="sub-title-destination">
                <h3>{{ $destination->title }}</h3>
            </div>
            <div class="content">
                <div class="quote-content">
                    <p>{{ $destination->highlight }}</p>
                </div>
                <div>
                {!! $destination->content !!}
                </div>
            </div>
            @include('extensions.content.share-sosmed')
        </div>
    </div>
</section>

<section class="reviews space">
    <div class="container">
        <div class="row">
            <div class="title-product-single">
                <h3>Ulasan</h3>
            </div>
            <div class="content-reviews col-12 col-md-8">
            @if($reviews)
                @foreach($reviews as $review)
                <div class="body-reviews">
                    <div class="img-traveler">
                        <img src="{{ asset($review['avatar']) }}" alt="CUST"/>
                        <h6>{{ $review['customer'] }}</h6>
                    </div>
                    <div class="t-reviews " >
                        <ul class="has-star" data-rating="{{ $review['star_review'] }}"> </ul>
                        <p>{{ $review['comments'] }}</p>
                    </div>
                </div>
                @endforeach
            @else
                <p>Belum ada ulasan.</p>
            @endif
            </div>

            @if($auth->logged())
            <div class="content-form col-12 col-md-8 p-0">
                <h6>Berikan Komentar</h6>
                <form class="form-reviews" action="#">
                    <div class="bg-default">

                        <div class="row">

                            <div class="form-group col-12 col-md-12">
                                <div class="form-group">
                                    <label>Komentar <span class="label-mandatory">*</span></label>
                                    <textarea class="form-control" rows="3" placeholder="Lengkapi komentar Anda"></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control form-control-lg" type="text" placeholder="Lengkapi email Anda">
                                </div>
                                <div class="form-group">
                                    <label>Nama <span class="label-mandatory">*</span></label>
                                    <input class="form-control form-control-lg" type="text" placeholder="Lengkapi nama Anda">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 pl-5">
                                <div class="form-group">
                                    <label>Rating <span class="label-mandatory">*</span></label>
                                    <ul class="star-review">
                                        <li><i class="far fa-star"></i></li>
                                        <li><i class="far fa-star"></i></li>
                                        <li><i class="far fa-star"></i></li>
                                        <li><i class="far fa-star"></i></li>
                                        <li><i class="far fa-star"></i></li>
                                    </ul>
                                </div>
                                <div class="wrap_btn text-left">
                                    <button class="default_btn" type="submit">Kirim Komentar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</section>

<section class="list-product-single bg-default space">
    <div class="container">
        <div class="row">
            <div class="title-product-single">
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
    </div>
</section>

@endsection

@section('styles')
<style type="text/css">
    strong {
        font-weight: 600;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<script type="text/javascript">
    $('[data-rating].has-star').each(function(i, item) {
        var star = $(item).data('rating');
        $(item).html(func.starRating(star));
    });
</script>
@endsection