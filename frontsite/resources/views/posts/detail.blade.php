@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{ asset('images/bg-head-2.jpg') }})">
    <div class="container">
        <div class="wrap-head">
           <div class="breadcrumb-style">
               <nav aria-label="breadcrumb" class="list-breadcrumb">
                   <ol class="breadcrumb">
                       <li class="breadcrumb-item"><a href="#">Home</a></li>
                       <li class="breadcrumb-item active">Kabar Dairi</li>
                   </ol>
               </nav>
           </div>
        </div>
    </div>
</section>

<section class="wrap-news space">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-9 mt-5">
                @if($post->is_featured)
                <div class="feature-image">
                    <img src="{{ config('constants.UPLOAD_PATH').$post->image }}" alt="img"/>
                </div>
                @endif
                <div class="wrap-title">
                    <div class="sub-item">
                        <ul class="post-detail">
                            <li><span class="fas fa-calendar-alt"></span> {{ $post->post_date }}</li>
                            <li>Diposting oleh : Admin VisitDairi</li>
                        </ul>
                    </div>
                    <h3>{{ $post->post_title }}</h3>
                </div>
                <div class="content-news">
                    @if($post->post_hightlight && !empty($post->post_hightlight))
                    <div class="quote-content">
                        <p>{{ strip_tags($post->post_highlight) }}</p>
                    </div>
                    @endif
                    {!! $post->post_content !!}
                </div>
                <div class="share-sosmed d-flex">
                    <h6>Share : </h6>
                    <span>
                        <ul>
                            <li>
                                <a href="">
                                    <i class="flaticon-facebook-app-symbol"></i>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <i class="flaticon-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <i class="flaticon-instagram"></i>
                                </a>
                            </li>
                        </ul>
                    </span>
                </div>
            </div>
            <div class="col-12 col-md-3 mt-5 sidebar">
                <div class="card category">
                    <h3>Category</h3>
                    <div class="sidebar-content">
                        <ul class="sidebar-list">
                        	{!! $categories !!}
                        </ul>
                    </div>
                </div>
                <div class="card popular-post">
                    <h3>Kabar Populer</h3>
                    <div class="sidebar-content">
                        <ul class="sidebar-list">
                        	<li><small><i class="fas fa-spin fa-spinner"></i> mengambil data..</small></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script type="text/javascript">
    var ImgPath = '{{ config('constants.UPLOAD_PATH') }}';
</script>
<script type="text/javascript" src="{{ asset('js/posting.js') }}"></script>
@endsection