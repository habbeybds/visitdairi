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
                <div class="row wrap-login-register">
                    <div class="col-12 col-md-4 left-side-content bg-login align-self-center none-990">
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
                        <h3>Silahkan masukkan password baru anda!</h3>
                        <div id="customer" class="tab-pane fade in active show">
                            <form class="form-recover-password" action="#" method="post">
                                <input type="hidden" name="uniqueCode" value="{{ $uniqueCode }}" />
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password Baru Anda">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="repassword" placeholder="Ulangi Password Baru Anda">
                                </div>
                                <div class="form-group text-left">
                                    <button type="submit" class="default_btn btn-resetpwd">Perbarui</button>
                                </div>
                            </form>
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

@section('scripts')
<script type="text/javascript">
    var redirect = '';
</script>
<script type="text/javascript" src="{{ asset('js/login.js') }}"></script>
@endsection