@extends('layouts.main')

@section('contents')
    <section class="head-title-destination" style="background-image: url(images/bg-head-2.jpg)">
        <div class="container">
            <div class="wrap-head">
                <div class="breadcrumb-style">
                    <nav aria-label="breadcrumb" class="list-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item">Pencarian</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="space landing-search">
        <div class="container">

                <div class="wrap-search-input wrapper-form">
                    <form method="GET" action="{{ route('search') }}">
                        <div class="form-group floating-label">
                            <label>Search</label>
                            <input type="text" class="form-control" name="k" value="{{$keywords}}">
                            <button type="submit" class="button"><i class="fas fa-search"></i> Cari</button>
                        </div>
                    </form>
                </div>
                @if($searchResults)
                    <div class="title-results">
                        <h3>Hasil Pencarian :</h3>
                    </div>
	                @foreach($searchResults as $key=>$result)
	                <div class="results">
	                    <h5 class="cat-list-results">{{$key}}</h5>
	                    <hr>
	                    <div class="content">
	                    	@foreach($result as $row)
	                        <div class="list">
	                            <a href="{{ url($row->content_route) }}">{{$row->content_title}} <span class="badge">{{$row->content_category}}</span></a>
	                        </div>
	                        @endforeach
	                    </div>
	                </div>
	                @endforeach
	            @elseif($keywords != '')
                    <div class="title-results">
                        <h3>Hasil Pencarian :</h3>
                    </div>
                    <div class="results">
                        Pencarian untuk <b><i>{{$keywords}}</i></b> tidak ditemukan data. Silahkan mencari dengan kata kunci yang lain!
                    </div>
                @else
                <br>
                    <div class="title-results">
                        <h3>Silahkan masukkan kata kunci pencarian</h3>
                    </div>
	            @endif
        </div>
    </section>
@endsection