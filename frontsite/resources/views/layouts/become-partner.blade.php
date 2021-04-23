@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{ asset('images/bg-head-4.jpg') }})">
    <div class="container">
        <div class="wrap-head">
            <h3>Become Partner</h3>
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Become Partner</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="become-partner space">
    <div class="container">
        <div class="title-section-3 mb-4">
            <h3>Cara Menjadi Partner</h3>
        </div>
        <div class="step-become-partner">
            <div class="row">
                <div class="card-step-partner col-12 col-md-3">
                    <div class="wrap-icon">
                        <div class="icon">
                            <img src="icon/ux.svg" alt="icon"/>
                        </div>
                    </div>
                    <div class="content-step-partner">
                        <h3 class="title">Akses Website</h3>
                        <p>Akses laman utama <a href="https://visitdairi.com/">visitdairi.com</a></p>
                    </div>
                </div>

                <div class="card-step-partner col-12 col-md-3">
                    <div class="wrap-icon">
                        <div class="icon">
                            <img src="icon/click.svg" alt="icon"/>
                        </div>
                    </div>
                    <div class="content-step-partner">
                        <h3 class="title">Mulai Daftar</h3>
                        <p>Klik daftar lalu pilih sebagai partner</p>
                    </div>
                </div>

                <div class="card-step-partner col-12 col-md-3">
                    <div class="wrap-icon">
                        <div class="icon">
                            <img src="icon/browser.svg" alt="icon"/>
                        </div>
                    </div>
                    <div class="content-step-partner">
                        <h3 class="title">Data Partner</h3>
                        <p>Lengkapi dan Isi formulir pendaftaran</p>
                    </div>
                </div>

                <div class="card-step-partner col-12 col-md-3">
                    <div class="wrap-icon">
                        <div class="icon">
                            <img src="icon/present.svg" alt="icon"/>
                        </div>
                    </div>
                    <div class="content-step-partner">
                        <h3 class="title">Benefit</h3>
                        <p>Nikmati benefit sebagai partner</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<section class="why-become-partner space pb-0">
    <div class="background-partner">
        <div class="container">
            <div class="title-section-5">
                <h6><span class="icon_title"></span>Become Partner</h6>
                <h3>Mengapa Menjadi Partner ?</h3>
            </div>
            <div class="content-why-become-partner">

                <div class="wrap-content col-12 col-md-6">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="content">
                        <h3>Gratis</h3>
                        <p>Tidak memerlukan biaya untuk memulai bisnis di Visit Dairi.</p>
                    </div>
                </div>

                <div class="wrap-content col-12 col-md-6">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="content">
                        <h3>Wisatawan Lokal dan Mancanegara</h3>
                        <p>Visit Dairi merupakan sumber referensi tentang pariwisata di Kabupaten Dairi, sekaligus melakukan transaksi dalam 1 platform, yang dapat diakses wisatawan lokal dan mancanegara.</p>
                    </div>
                </div>

                <div class="wrap-content col-12 col-md-6">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="content">
                        <h3>Promosi yang Cerdas</h3>
                        <p>Visit Dairi akan mempromosikan produk Anda dengan digital marketing sehingga jangkauan lebih luas.</p>
                    </div>
                </div>

                <div class="wrap-content col-12 col-md-6">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="content">
                        <h3>Penyedia Kebutuhan Traveling</h3>
                        <p>Anda dapat menjadi partner Visit Dairi sebagai penyedia kebutuhan traveling bagi wisatawan.</p>
                    </div>
                </div>

                <div class="wrap-content col-12 col-md-6">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="content">
                        <h3>Fitur Pendukung</h3>
                        <p>Visit Dairi memiliki banyak fitur yang dapat memudahkan pengembangan bisnis Anda, seperti metode pembayaran beragam untuk customer dan dashboard untuk Anda.</p>
                    </div>
                </div>

            </div>

            <div class="wrap-bounce-btn flash-arrow">
                <a>Mulai Registrasi<i class="fas fa-chevron-down"></i></a>
            </div>

        </div>
    </div>
</section>

<section class="login-register become-partner space">
    <div class="col-12 col-md-6 m-auto pb-3">
        <h3>Lengkapi Data Anda</h3>
        <p>Silahkan lengkapi data-data berikut untuk selanjutnya akan dilakukan verifikasi oleh Admin VisitDairi</p>
    </div>
    <div class="wrap-login-register">
        <div class="content-login-register">
        	<div id="msg-result" style="text-align: left;"></div>
            <form class="form-login-register" action="#" enctype="multipart/form-data">
            	@csrf
		        <div class="form-group">
		            <input type="text" class="form-control" name="perusahaan" placeholder="Nama Perusahaan / Nama Toko">
		        </div>
		        <div class="form-group">
		            <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap">
		        </div>
		        <div class="wrap-group-field row m-0">
		            <div class="form-group col-12 col-md-6 pl-0">
		                <input type="text" class="form-control" name="telp" placeholder="Telepon">
		            </div>
		            <div class="form-group col-12 col-md-6 p-0">
		                <input type="text" class="form-control" name="email" placeholder="Email">
		            </div>
		            <div class="form-group col-12 col-md-6 pl-0">
		                <input type="text" class="form-control" name="ktp" placeholder="Nomor KTP">
		            </div>
                    <div class="form-group col-12 col-md-6 pl-0 pr-0">
                        <select class="select-2 provinsi" id="province" name="province">
                            <option value="">Pilih Provinsi</option>
                        </select>
                    </div>
		        </div>
		        <div class="wrap-group-field row m-0">
		            <div class="form-group col-12 col-md-6 pl-0">
		                <select class="select-2 kota" id="city" name="city">
		                    <option value="">Pilih Kota</option>
		                </select>
		            </div>
                    <div class="form-group col-12 col-md-6 p-0">
                        <select class="select-2 subdistrict" id="subdistrict" name="subdistrict">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
		        </div>
		        <div class="form-group">
		            <textarea class="form-control" id="address"  name="address" rows="3" placeholder="Alamat"></textarea>
		        </div>
		        <div class="wrap-group-field row m-0">
                    <div class="form-group col-12 alert alert-info text-left">
                        <small>Silahkan upload dokumen pendukung:<br />
                            <ol class="mb-0">
                                <li>Scan Foto</li>
                                <li>Scan KTP</li>
                                <li>Scan NPWP</li>
                            </ol>
                            Dengan format yang diijinkan .png / .jpeg / .jpg dan ukuran maksimum masing-masing file 256Kb
                        </small>
                    </div>
		            <div class="form-group col-12 col-md-4 pl-0">
		                <div class="custom-file">
		                    <input type="file" class="custom-file-input" id="foto" name="ffoto">
		                    <label class="custom-file-label" for="foto">File Foto</label>
		                </div>
		            </div>
		            <div class="form-group col-12 col-md-4 pl-0">
		                <div class="custom-file">
		                    <input type="file" class="custom-file-input" id="ktp" name="fktp">
		                    <label class="custom-file-label" for="ktp">File KTP</label>
		                </div>
		            </div>
		            <div class="form-group col-12 col-md-4 pl-0 pr-0">
		                <div class="custom-file">
		                    <input type="file" class="custom-file-input" id="npwp" name="fnpwp">
		                    <label class="custom-file-label" for="npwp">File NPWP</label>
		                </div>
		            </div>
		        </div>
		        <div class="form-group text-left">
		            <button type="submit" class="default_btn">Register</button>
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

    var fileMaxSize = 1024000;

	$(document).ready(function(){

		$('#province').select2();
		$('#city').select2();
		$('#subdistrict').select2();
        prepare_data();

        function prepare_data(){
            $.ajax({
                url     : '{{ route('ajaxGetProvince') }}',
                success: function(data)
                {
                    if(data.status == 'success'){

                        var html = '';
                        $.each(data.province, function (i, item) {
                            html += '<option value="' + item.province_id + '">' + item.name + '</option>';
                        });
                        
                        $('#province').empty();
                        $('#province').append('<option value="0">Pilih Provinsi</option>');
                        $('#province').append(html);

                    }
                },
            });
        }

        $("#province").change(function() {
            var province = $("#province option:selected").val();

            $.ajax({
                url     : '{{ route('ajaxGetCity') }}',
                data    : {
                        province: province
                },
                success: function(data)
                {
                    if(data.status == 'success'){
                        var html = '';
                        $.each(data.city, function (i, item) {
                            html += '<option value="' + item.city_id + '">' + item.city_name + ' (' + item.type + ')</option>';
                        });

                        $('#city').empty();
                        $('#city').append('<option value="0" selected>Pilih Kota</option>');
                        $('#city').append(html);
                        $("#city").val(0).change();
                        
                    }
                },
                error: function ()
                {
                    console.log('error');
                }
            });
        });
        
        $("#city").change(function() {
            var city = $("#city option:selected").val();

            $.ajax({
                url     : '{{ route('ajaxGetSubdistrict') }}',
                data    : {
                        city: city
                },
                success: function(data)
                {
                    if(data.status == 'success'){
                        var html = '';
                        $.each(data.subdistrict, function (i, item) {
                            html += '<option value="' + item.subdistrict_id + '">' + item.subdistrict_name + '</option>';
                        });

                        $('#subdistrict').empty();
                        $('#subdistrict').append('<option value="0" selected>Pilih Kecamatan</option>');
                        $('#subdistrict').append(html);
                        $("#subdistrict").val(0).change();
                        
                    }
                },
                error: function ()
                {
                    console.log('error');
                }
            });
        });
        
		// $('select[name=province]').select2({
		// 	ajax: {
		// 	    url: '{{ route('ajaxGetProvince') }}',
		// 	    dataType: 'json',
		// 	    processResults: function (data) {
		// 		    return {
		// 		        results: $.map(data, function(obj) {
		// 		            return { id: obj.province_id, text: obj.name };
		// 		        })
		// 		    };
		// 		}
		// 	}
		// });
        
		// $('select[name=city]').select2({
		// 	ajax: {
		// 	    url: '{{ route('ajaxGetCity') }}',
		// 	    data: function (term, page) {
		//           	return {
		//               	q: term, // search term
		//               	province: $('select[name=province]').val()
		//           	};
		//         },
		// 	    dataType: 'json',
		// 	    processResults: function (data) {
		// 		    return {
		// 		        results: $.map(data, function(obj) {
		// 		            return { id: obj.city_id, text: obj.city_name };
		// 		        })
		// 		    };
		// 		}
		// 	}
		// });

        // $('select[name=subdistrict]').select2({
        //     ajax: {
        //         url: '{{ route('ajaxGetSubdistrict') }}',
        //         data: function (term, page) {
        //             return {
        //                 q: term, // search term
        //                 city: $('select[name=city]').val()
        //             };
        //         },
        //         dataType: 'json',
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data, function(obj) {
        //                     return { id: obj.subdistrict_id, text: obj.subdistrict_name };
        //                 })
        //             };
        //         }
        //     }
        // });

        $('.custom-file > input[type=file]').change(function(e) {
            var input = this;
            var label = $('label[for='+$(this).attr('id')+']');
            if (input.files && input.files[0]) {
                if(input.files[0].type != 'image/png' && input.files[0].type != 'image/jpg' && input.files[0].type != 'image/jpeg') {
                    $.alert({
                        title: "Warning",
                        content: "Format file yang diijinkan adalah .png / .jpg / .jpeg",
                    });
                    return false;
                }

                if(input.files[0].size > fileMaxSize) {
                    $.alert({
                        title: "Warning",
                        content: "Ukuran gambar maksimal 1024 KB",
                    });
                    return false;
                }

                label.html(input.files[0].name);
                console.log(input.files[0].name);
            }
        });

		$('.wrap-bounce-btn a').click(function () {
            $('html, body').animate({
                scrollTop: $(".login-register").offset().top
            }, 500);
        });

        $('.form-login-register').submit(function(e) {
   			e.preventDefault();
   			var msgresult = $('#msg-result');
   			var btn = $('.default_btn');
   			var form = $(this);
   			var formData = new FormData(form[0]);

   			btn.html('<i class="fa fa-spin fa-spinner"></i> proses..').prop('disabled', true);
   			$.ajax({
				url: '{{ route('registerPartner') }}',
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
                            $("#province").val(0).change();

                            $('#city').empty();
                            $('#city').append('<option value="0" selected>Pilih Kota</option>');
                            $("#city").val(0).change();

                            $('#subdistrict').empty();
                            $('#subdistrict').append('<option value="0" selected>Pilih Kecamatan</option>');
                            $("#subdistrict").val(0).change();

                            $("#foto").val('');
                            $("#ktp").val('');
                            $("#npwp").val('');

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
								.html('Pendaftaran Anda gagal. Silahkan refresh halaman dan coba kembali.');
						}
					}
					btn.html('REGISTER').prop('disabled', false);
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
				btn.html('REGISTER').prop('disabled', false);
			});     	
        });
	});
</script>
@endsection