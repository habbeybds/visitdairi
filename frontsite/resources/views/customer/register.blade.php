@extends('layouts.login')

@section('contents')
<section class="login-register">
	<div class="wrap-logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="VisitDairi"/>
        </a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 pad-0">
                <div class="row wrap-login-register w900">
                    <div class="col-12 col-md-4 left-side-content bg-login none-990">
                        <h3>Nikmati Berbagai Kemudahan</h3>
                        <p>Bergabung bersama kami di <b>VisitDairi</b>:</p>
                        <ul class="list-side">
                            <li>
                                <h6><i class="fas fa-star"></i> Easy Shopping</h6>
                                <p>Kemudahan mencari referensi berbagai aktivitas wisata, belanja & budaya</p>
                            </li>
                            <li>
                                <h6><i class="fas fa-credit-card"></i> Smart Book &amp; Pay</h6>
                                <p>Booking & pembayaran dalam 1 platform</p>
                            </li>
                        </ul>
                    </div>
					<div class="col-12 col-md-8 content-login-register align-self-center">
						<div id="msg-result" class=""></div>
					    <h3>Registrasi Akun Anda</h3>
					    <div class="tab-registration">
					        <ul class="nav nav-tabs">
					            <li class="active"><a data-toggle="tab" href="#customer" class="active">Customer</a></li>
					            <li><a href="{{ url('mendaftar-partner') }}">Partner</a></li>
					        </ul>
					        <div class="tab-content">

					            <div id="customer" class="tab-pane fade in active show">
					                <form id="form-register-customer" class="form-login-register" action="#">
					                	<div class="form-group">
					                        <input type="text" class="form-control" name="fullname" placeholder="Nama Lengkap">
					                    </div>
					                    <div class="form-group">
					                        <input type="text" class="form-control" name="email" placeholder="Email">
					                    </div>
					                    <div class="form-group">
					                        <input type="text" class="form-control" name="phone" placeholder="Nomor HP">
					                    </div>

					                    <div class="wrap-group-field row m-0">
					                        <div class="form-group col-12 col-md-6 pl-0">
					                            <input type="password" class="form-control" name="passwd" placeholder="Password">
					                        </div>
					                        <div class="form-group col-12 col-md-6 p-0">
					                            <input type="password" class="form-control" name="repasswd" placeholder="Ulangi Password">
					                        </div>
					                    </div>
					                    <div class="form-group text-left">
					                        <button type="submit" class="default_btn">Daftar</button>
					                    </div>
					                </form>
					            </div>
					            <div id="partner" class="tab-pane fade">
					                <form id="form-register-partner" class="form-login-register" action="#">
					                    <div class="form-group">
					                        <input type="text" class="form-control" name="perusahaan" placeholder="Nama Perusahaan">
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
					                    </div>
					                    <div class="wrap-group-field row m-0">
					                        <div class="form-group col-12 col-md-6 pl-0">
					                            <select class="select-2 provinsi" name="state">
					                                <option value="AL">Jawa Barat</option>
					                                <option value="WY">Jawa Timur</option>
					                                <option value="WY">Jawa Tengah</option>
					                            </select>
					                        </div>
					                        <div class="form-group col-12 col-md-6 p-0">
					                            <select class="select-2 kota" name="state">
					                                <option value="AL">Semarang</option>
					                                <option value="WY">Jogja</option>
					                                <option value="WY">Solo</option>
					                            </select>
					                        </div>
					                    </div>
					                    <div class="form-group">
					                        <textarea class="form-control" id="address" rows="3" placeholder="Alamat"></textarea>
					                    </div>
					                    <div class="wrap-group-field row m-0">
					                        <div class="form-group col-12 col-md-4 pl-0">
					                            <div class="custom-file">
					                                <input type="file" class="custom-file-input" id="foto">
					                                <label class="custom-file-label" for="foto">File</label>
					                            </div>
					                        </div>
					                        <div class="form-group col-12 col-md-4 pl-0">
					                            <div class="custom-file">
					                                <input type="file" class="custom-file-input" id="ktp">
					                                <label class="custom-file-label" for="ktp">File</label>
					                            </div>
					                        </div>
					                        <div class="form-group col-12 col-md-4 pl-0 pr-0">
					                            <div class="custom-file">
					                                <input type="file" class="custom-file-input" id="npwp">
					                                <label class="custom-file-label" for="npwp">File</label>
					                            </div>
					                        </div>
					                    </div>
					                    <div class="form-group text-left">
					                        <button type="submit" class="default_btn">Register</button>
					                    </div>
					                </form>
					            </div>

					        </div>
					    </div>
					    <div class="wrap-register-btn text-left">
					        <span>Sudah Punya Akun ? <a href="{{ route('login') }}">Login disini</a></span>
					    </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<style type="text/css">
	.login-register::before {
		background: rgba(0, 0, 0, 0.04) url(../../images/bg-login-register.jpg) top left repeat;
    	background-size: auto;
	}
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script type="text/javascript" src="{{ asset('js/register.js') }}"></script>
@endsection