@extends('layouts.main')

@section('contents')

<section class="head-title-destination single-page" style="background-image: url({{ asset('images/bg-head-4.jpg') }})">
    <div class="container">
        <div class="wrap-head">
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Cari Pesanan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>


<section class="retrieve-pnr space">
    <div class="container">
        <div class="row wrap-retrieve">
            <div class="content-retrieve align-self-center">
                <h3>Cari Pesanan Anda</h3>
                <p>Silahkan masukan Order ID / Kode Booking dan Email yang terdaftar</p>
                <form class="form-retrieve" action="#" method="post">
                	@csrf
                    <div class="form-group">
                        <input type="text" class="form-control" id="trans_id" name="trans_id" placeholder="Kode Transaksi">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <button type="button" class="default_btn btn-retrieve"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

@endsection

@section('styles')
<style type="text/css">
	#trans_id {
		text-transform: uppercase;	
	}
	
</style>
@endsection

@section('scripts')
<script type="text/javascript">
	$('.btn-retrieve').click(function(e) {
		e.preventDefault();
		var form = $('.form-retrieve');
		var transId = $('#trans_id').val();
		if(transId.length == 0) {
			$.alert('Silahkan masukkan Kode Transaksi!', 'Peringatan');
			return false;
		}
		if($('#email').val().length == 0) {
			$.alert('Silahkan masukkan Email yang terdaftar!', 'Peringatan');
			return false;
		}
		form.attr('action', '{{ url('order')}}/'+transId+'/retrieve');
		form.submit();
	});

	$('.form-control').keypress(function(e) {
		if(e.charCode == 13) {
			$('.btn-retrieve').click();
		}
	});
</script>
@endsection