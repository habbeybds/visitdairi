@extends('layouts.main')

@section('contents')
    <section class="head-title-destination" style="background-image: url({{ asset('images/bg-head-3.jpg') }})">
        <div class="container">
            <div class="wrap-head">
                <div class="breadcrumb-style">
                    <nav aria-label="breadcrumb" class="list-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Semua Destinasi</li>
                        </ol>
                    </nav>
                </div>
                <p>Cari tempat wisata, atraksi, aktivitas, dan event menarik di destinasi favorit !</p>
            </div>
        </div>
    </section>

    <section class="all-news news bg-default space all-destination">
        <div class="container">

            <div class="news-content destination-list">
                <div class="row">
                    <!-- // destination list -->
                </div>
                <div class="pagination">
                    <!-- // pagination -->
                </div>
            </div>

        </div>
    </section>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/destination.js') }}"></script>
@endsection