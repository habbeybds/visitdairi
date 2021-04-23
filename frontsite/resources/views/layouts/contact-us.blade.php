@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{ asset('images/bg-head-4.jpg') }})">
    <div class="container">
        <div class="wrap-head">
            <h3>Hubungi Kami</h3>
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Hubungi Kami</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="wrapper-form login-register become-partner space wrapper-contact mt-3 mb-5">
    <div class="col-12 col-md-6 m-auto">
        <h3>Lengkapi Permintaan Anda</h3>
        <p>Untuk respon yang lebih cepat, sampaikan pertanyaan atau permintaan Anda melalui formulir ini.</p>
    </div>
    <div class="wrap-login-register contact-us">
        <div class="content-login-register">
        	<div id="msg-result" style="text-align: left;"></div>
            <form class="form-login-register" action="#">
            	@csrf
                <div class="form-group floating-label">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group floating-label">
                    <label>Nomor Kontak</label>
                    <input type="phone" class="form-control" name="phone">
                </div>
                <div class="form-group floating-label">
                    <label>Alamat Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="form-group floating-label">
                    <label>Subjek</label>
                    <input type="text" class="form-control" name="subject">
                </div>
                <div class="form-group floating-label text-left">
                    <label>Sampaikan Pesan Anda</label>
                    <textarea class="form-control" name="custmessage" rows="5"></textarea>
                    <small>Lengkapi pesan Anda, Semakin banyak informasi, semakin mudah bagi kami untuk membantu Anda</small>
                </div>
                <div class="form-group text-left mb-0">
                    <button type="submit" class="default_btn">Kirim <i class="fas fa-paper-plane ml-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style type="text/css">
.custom-file-label {
    display: block;
    max-width: 98%;
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
}
</style>
@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

        $('.form-login-register').submit(function(e) {
   			e.preventDefault();
   			var msgresult = $('#msg-result');
   			var btn = $('.default_btn');
   			var form = $(this);
   			var formData = new FormData(form[0]);

   			btn.html('<i class="fa fa-spin fa-spinner"></i> proses..').prop('disabled', true);
   			$.ajax({
				url: '{{ route('contactUs') }}',
				type: 'POST',
				data: formData,
				contentType: false,
		    	processData: false,
				success: function(response) {
					try {
						if(response.status == 'success') {

							msgresult
								.addClass('alert')
								.removeClass('alert-danger').addClass('alert-success')
								.html(response.message);
							form[0].reset();

						} else {
							if(typeof response.errors !== 'undefined') {
								var errmsg = '';
								$.each(response.errors, function(i, item) {
									errmsg += item + ' ';
								});
								msgresult
									.addClass('alert')
									.removeClass('alert-success').addClass('alert-danger')
									.html(errmsg);
							}
						}
					} catch(err) {
						if(typeof response.errors !== 'undefined') {
							msgresult
								.addClass('alert')
								.removeClass('alert-success').addClass('alert-danger')
								.html('Pengiriman pesan gagal. Silahkan refresh halaman dan coba kembali.');
						}
					}
					btn.html('Kirim').prop('disabled', false);
				}
			}).fail(function(err) {
				var message = $.parseJSON(err.responseText);
				if(err.status == 422 || err.status == 402) {
					var errmsg = 'Kesalahan: <br />';
					$.each(message, function(i, item) {
						errmsg += '<small>- ' + item + '</small> <br>';
					});

					msgresult
						.addClass('alert')
						.removeClass('alert-success').addClass('alert-danger')
						.html(errmsg);
				}
				btn.html('Kirim').prop('disabled', false);
			});     	
        });
        
    });
</script>
@endsection