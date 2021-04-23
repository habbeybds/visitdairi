<div class="row">
    <div class="col-12 col-md-8">
        <div class="card card-section list-item">
            <div class="block-header">
                <h3>Available Room</h3>
            </div>
            <div class="block-body">
                @foreach($products as $key=>$product)
                <div class="wrap-list-product">
                    <div class="body-list">
                        <div class="row">
                            <div class="col-12 col-md-9">
                                <div class="row">
                                    <div class="col-12 col-md-4 img-room">
                                        <img src="{{config('constants.UPLOAD_PATH').$product['product_thumbnail']}}" alt="img"/>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <h3 class="title-list">{{ trim($product['title'] . ', ' . $product['subtitle'], ', ') }}</h3>
                                        <ul class="list-desc">
                                            <li><i class="fas fa-hourglass-half"></i> Reservasi 3 hari sebelumnya</li>
                                            <li><i class="flaticon-user-profile"></i> Maks {{ $product['room_capacity'] }} tamu</li>
                                            @if($product['is_refund'])
                                            <li><i class="fas fa-sticky-note"></i> Pembatalan Gratis</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="price">
                                    <h3>IDR {{ number_format($product['avg_price'], 0, '.',',')}}</h3>
                                    <p>per kamar per malam</p>

                                    <div class="wrap-select-room">
                                        <label><i class="flaticon-hotel-sign"></i> Pilih Kamar</label>
                                        <select class="form-control choose-room" name="rooms[]">
                                            <option value="" product-id="{{$product['product_id']}}" >--pilih harga--</option>
                                            @for($i=1;$i<=(int)$product['allotment'];$i++)
                                            <option value="{{ $i }}" product-id="{{$product['product_id']}}" product-price="{{ (int)$product['total_price'] * $i }}" product-name="{{ trim($product['title'] . ', ' . $product['subtitle'], ', ') }}">{{ $i }}x ({{ $days }} hari) = Rp. {{ number_format($product['total_price'] * $i, 0, '.',',')}}</option>
                                            @endfor
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="wrap-item-facility">
                            <ul class="list-facility">
                                <li><i class="flaticon-bed-1"></i> {{ $product['room_type_name'] }}</li>
                                @if($product['is_breakfast'])
                                <li><i class="flaticon-spoon-and-fork"></i> Sarapan ({{ $product['room_capacity'] }} pax)</li>
                                @endif
                            </ul>
                            <span class="more-info detail-room" data-toggle="modal" data-id="{{ $product['product_id'] }}" data-target="#detail-hotel"><i class="fas fa-info"></i>  Info Detail</span>
                        </div>

                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 wrap-price">
        <div class="card card-section">
            <div class="block-header">
                <h3>Total</h3>
            </div>
            <div class="block-body sticky-price">
                <form class="wrapper-form" action="#">
                    <ul class="list-select-room">
                        <li is-empty>Silahkan pilih kamar!</li>
                    </ul>
                    <div class="wrap-total-select">
                        <div class="col-12 col-md-6 pl-1">
                            <h6><span class="numb-room" data-room="0">0</span> Kamar seharga</h6>
                            <h3 class="subtotal">Rp. 0</h3>
                        </div>
                        <div class="col-12 col-md-6 form-group wrap-total">
                            <div class="wrap-btn">
                                <a href="#6" class="default_btn btn-booknow disabled">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="side-modal">
    <!-- Modal -->
    <div class="modal right fade" id="detail-hotel" tabindex="-1" role="dialog" aria-labelledby="detail-hotel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- // -->
            </div>
        </div>
    </div>
</div>