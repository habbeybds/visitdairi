@extends('layouts.main')

@section('contents')
<section class="head-title-destination single-page" style="background-image: url({{ asset('images/bg-head-2.jpg') }})">
    <div class="container">
        <div class="wrap-head">
           <div class="breadcrumb-style">
               <nav aria-label="breadcrumb" class="list-breadcrumb">
                   <ol class="breadcrumb">
                       <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                       <li class="breadcrumb-item">Akomodasi</li>
                       <li class="breadcrumb-item active">Pemesanan</li>
                   </ol>
               </nav>
           </div>
        </div>
    </div>
</section>

<section class="wrap-form-buyer space">
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-8">
        <div class="card booking-login-card">
          <h3>
            <i class="flaticon-login"></i>
            @if($auth->logged())
            Hai {{ $auth->getName() }}, silahkan melanjutkan pemesanan.
            @else
            <a href="{{ route('login') }}?redirect={{ url('order/form-pemesanan/'.$cartKey) }}">Login</a> atau <a href="#">Daftar</a> untuk nikmati berbagai kemudahan!
            @endif
          </h3>
        </div>
          
          <!-- MOBILE -->
          <section class="mobile-wrap-content product">
            <div class="booking-product">
              <a href="#modal-pemesanan" class="default_btn" data-toggle="modal" data-target="#modal-pemesanan"><i class="flaticon-sticky-note"></i> Rincian Pemesanan</a>
            </div>
          </section>
          <div class="card form-style wrap-group-field wrapper-form">
            <div class="title-card">
              <h5>Data Pemesan</h5>
            </div>
               <div class="wrap-form">
                   <form class="form" id="form-akomodasi" method="post">
                    @csrf
                       <div class="row">
                           <div class="col-12 col-md-2">
                               <div class="form-group floating-label ">
                                   <select class="form-control" name="state">
                                       <option selected>Tuan</option>
                                       <option>Nyonya</option>
                                       <option>Nona</option>
                                   </select>
                                   <span class="icon-select far fa-angle-down"></span>
                               </div>
                           </div>
                           <div class="col-12 col-md-10">
                               <div class="form-group floating-label">
                                   <label>Nama Lengkap</label>
                                   <input type="text" class="form-control" name="nama" @if(isset($customer['first_name'])) value="{{ implode(' ',[$customer['first_name'], $customer['last_name']]) }}" readonly @endif>
                                   <small>Sesuai KTP/Paspor/SIM (tanpa tanda baca dan gelar)</small>
                               </div>
                           </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group floating-label">
                                    <label>Nomor Telepon</label>
                                    <input type="text" class="form-control" name="telp" @if(isset($customer['phone'])) value="{{ $customer['phone_code'] . $customer['phone'] }}" readonly @endif>
                                    <small>Contoh: No. Handphone 0812345678</small>
                                </div>
                            </div>
                           <div class="col-12 col-md-6">
                               <div class="form-group floating-label">
                                   <label>Alamat Email</label>
                                   <input type="email" class="form-control" name="email" @if(isset($customer['email'])) value="{{ $customer['email'] }}" readonly @endif>
                                   <small>Konfirmasi pesanan akan dikirim ke alamat Email ini.</small>
                               </div>
                           </div>
                           
                       </div>

                   </form>
               </div>
           </div>
           <div class="wrap-btn float-right">
               <a href="#" type="submit" class="default_btn btn-book">Booking Pesanan</a>
           </div>
       	</div>
        <div class="col-12 col-md-4">
            <div class="card detail-pemesanan">
                <div class="title-card">
                    <h5>Rincian Pemesanan</h5>
                </div>
                <div class="book-details">
                    <div class="img-book">
                        <img src="{{ config('constants.UPLOAD_PATH') . $cartData['partner_thumbnail'] }}" alt="img"/>
                    </div>
                    <div class="book-content">
                        <h3 class="book-title">{{ $cartData['company_name'] }}</h3>
                        <p class="book-desc"></p>
                    </div>
                </div>
                <div class="date-book">
                    <h5>Date</h5>
                    <p class="info-date">
                        <i class="fas fa-calendar-alt"> <b>CHECK-IN</b>: {{ $checkin }}</i> 
                    </p>
                    <p class="info-date">
                        <i class="fas fa-calendar-alt"> <b>CHECK-OUT</b>: {{ $checkout }}</i> 
                    </p>
                </div>
                <hr/>
                <div class="wrap-total-book">
                    <div>
                        <div class="body-price-details">
                            <ul>
                                <li>Pesan Kamar<span></span></li>
                                @foreach($rooms as $room)
                                <li>({{$room['quantity']}}x)  {{$room['title']}}<span>IDR. {{ number_format($room['quantity'] * $room['product_price'],0,',','.')}}</span></li>
                                @endforeach
                            </ul>
                            <hr/>
                            <h5 class="total">Total <span>IDR. {{ $totalPrice }}</span></h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
  </div>
</section>

<!--  MODAL RINCIAN PEMESANAN  -->
<div class="modal modal-mobile fade" id="modal-pemesanan" tabindex="-1" role="dialog" aria-labelledby="modal-pemesanan-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<script type="text/javascript">
  var baseUrl = $('meta[name=baseurl]').attr('content');
  $(document).ready(function(){

    // append star rating
  	$('[data-rating].has-star').each(function(i, item) {
      var star = $(item).data('rating');
      $(item).html(func.starRating(star));
    });

    $('.btn-book').click(function(e) {
      e.preventDefault();
      var form = $('#form-akomodasi');
      var btn = $(this);
      btn
        .addClass('disabled')
        .html('<i class="fas fa-spin fa-spinner"></i> Sedang proses..');

      $.post('{{ route('submitCheckout', $cartKey) }}', form.serialize(), function(response) {
        try{
          if(response.status == 'success') {
            var code = response.data.order_code;
            window.location.href='{{ url('order/payment-method/') }}/' + code;  
          } else {
            btn.removeClass('disabled').html('BOOKING PESANAN');
          }        
        } catch(err) {
          btn.removeClass('disabled').html('BOOKING PESANAN');
        }        
      })
      .fail(function(er) {  
        btn.removeClass('disabled').html('BOOKING PESANAN');
        try {
          var message = 'Terjadi kesalahan sistem';
          if(er.status == '422') {
            var response = $.parseJSON(er.responseText);
            message = '';
            $.each(response, function(i, item) {
              message += item + '<br />';
            });
          }       
          $.alert(message, 'Kesalahan');
        } catch(err) {
          //
          console.log(err);
        }
      });
    });
  });
</script>

<script type="text/javascript">
  //Mobile Move Content
  $(document).ready(function () {
    if (window.matchMedia('(max-width: 768px)').matches) {
      $('.wrap-form-buyer .detail-pemesanan').appendTo('#modal-pemesanan .modal-body');
    }
  })
</script>
@endsection