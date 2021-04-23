@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{ asset('images/product/wisata/HEADER-IMAGE/bg-head-2-min.jpg') }})">
    <div class="container">
        <div class="wrap-head">
           <div class="breadcrumb-style">
               <nav aria-label="breadcrumb" class="list-breadcrumb">
                   <ol class="breadcrumb">
                       <li class="breadcrumb-item"><a href="#">Home</a></li>
                       <li class="breadcrumb-item active">{{ $page->title }}</li>
                   </ol>
               </nav>
           </div>
        </div>
    </div>
</section>
<section class="wrap-news space">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12 mt-5">
                <div class="wrap-title">
                    <h3>{{ $page->title }}</h3>
                </div>
                <div class="content-news">{!! $page->content !!}</div>
                @include('extensions.content.share-sosmed')
            </div>
            <div class="col-12 col-md-3 mt-5 sidebar d-none">
                <div class="card popular-post mt-0">
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

@section('styles')
<style type="text/css">
	 ul.font-normal,
	 ol.font-normal {
	 	font-size: 14px;
	 }
</style>
@endsection

@section('scripts')
<script>
    var ImgPath = '{{ config('constants.UPLOAD_PATH') }}';
</script>
<script type="text/javascript" src="{{ asset('js/posting.js') }}"></script>
@endsection