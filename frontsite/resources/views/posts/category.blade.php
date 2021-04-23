@extends('layouts.main')

@section('contents')
    <section class="head-title-destination" style="background-image: url({{ asset('images/bg-head-2.jpg')}})">
        <div class="container">
            <div class="wrap-head">
                <div class="breadcrumb-style">
                    <nav aria-label="breadcrumb" class="list-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item">Kabar Dairi</li>
                            <li class="breadcrumb-item active">{{ $category->category_name }}</li>
                        </ol>
                    </nav>
                </div>
                <p>Artikel & cerita menarik untuk menginspirasi petualanganmu selanjutnya.</p>
            </div>
        </div>
    </section>

    <section class="all-news news bg-default space">
        <div class="container">

            <div class="news-content posting-list">
                <div class="row">
                    <!-- // posting list -->
                </div>
                <div class="pagination">
                    <!-- // pagination -->
                </div>
            </div>

        </div>
    </section>
@endsection

@section('styles')
<style type="text/css">
    .news .news-content .wrap-news .wrap-img::after {
        background: #f5f6fa;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript">
    var ImgPath = '{{ config('constants.UPLOAD_PATH') }}'; 
    var postCategory = '{{$category->post_category_id}}';
</script>
<script type="text/javascript" src="{{ asset('js/posting.js') }}"></script>
@endsection