<div class="wrap-tab-body">
    <h3>My Order</h3>
    <p>Lihat history transaksi Anda</p>
    <div class="input-group filter_wrap">
        <h6>Filter</h6>
        <input id="input_filter" list="menu_filter" type="text" class="input-filter" placeholder="Filter by Type " />
        <span class="fas fa-sort-down"></span>
        <div id="menu_filter" class="dropdown-menu">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="all" data-label="All" value="all">
                <label class="form-check-label" for="all">
                    All
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="menunggu" data-label="Menunggu" value="1">
                <label class="form-check-label" for="menunggu">
                    Menunggu
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="sukses" data-label="Sukses" value="2">
                <label class="form-check-label" for="sukses">
                    Sukses
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="gagal" data-label="Gagal" value="3">
                <label class="form-check-label" for="gagal">
                    Gagal
                </label>
            </div>
        </div>
    </div>
    <div class="content order-list">
        @if($orders)
            @foreach($orders as $order)
            <div class="card-history order-item" data-status="{{$order['status']}}" data-rating="{{$order['rating']}}">
                <input type="hidden" name="hdcomment" value="{{$order['comments']}}" />
                <div class="title-order-history">
                    <h6><i class="flaticon-guest"></i> {{ $order['product_type_name']}} - <span class="order-date">{{$order['orderDate']}}</span></h6>
                    @if($order['hasExpired'])<p class="status failed"><i class="fas fa-window-close"></i> Kadaluarsa</p>
                    @elseif($order['status'] == 1 && $order['type_id'] == 3)<p class="status"><i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi</p>
                    @elseif($order['status'] == 1)<p class="status"><i class="fas fa-hourglass-half fa-spin"></i> Menunggu Pembayaran</p>
                    @elseif($order['status'] == 2)<p class="status success"><i class="fas fa-check-square"></i> Sukses</p>
                    @elseif($order['status'] == 3)<p class="status failed"><i class="fas fa-window-close"></i> Gagal</p>
                    @endif
                </div>
                <div class="body-history row">
                    <div class="col-12 col-md-6">
                        <h4>Kode Pemesanan : {{$order['code']}}</h4>
                        <h3 class="title">{{$order['details']['title']}}</h3>
                        @if($order['status'] == 2 && $order['type_id'] == 2)
                        <p class="shipment-status"><b>Pengiriman:</b> <span>@if($order['shipment_status'] == 1) Barang Telah dikirim @else Menunggu pengiriman @endif</span></p>
                        @endif
                        @if($order['status'] == 1 && !$order['hasExpired'] && $order['type_id'] != 3)
                        <p class="time-limit">Batas waktu bayar: <span>{{$order['timelimit']}}</span></p>
                        @endif
                    </div>
                    <div class="col-12 col-md-6 mt-4">
                        <div class="form-group float-right">
                        @if($order['enableTracking'])
                        <button class="btn btn-warning btn-sm btn-tracking" data-courier="{{$order['courier']}}" data-awb="{{$order['awb']}}"><i class="fas fa-truck"></i> Lacak Pengiriman</button>&nbsp;
                        @endif
                        @if($order['enableComment'])
                        <button class="btn btn-warning btn-sm btn-comment" data-id="{{$order['id']}}" data-type="{{$order['type_id']}}"><i class="fas fa-comments"></i> Beri Komentar</button>&nbsp;
                        @endif
                        <form method="post" action="{{ route('retrieve', $order['code']) }}">
                            @csrf()
                            <input type="hidden" name="trans_id" value="{{$order['code']}}" />
                            <button class="btn btn-warning btn-sm">Detail Pesanan</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
        <div class="pagination">
            @foreach($orderResult['links'] as $link)
            <a href="{{ $link['url'] }}" class="@if($link['active']) active @endif">{!! $link['label'] !!}</a>
            @endforeach
        </div>
    </div>
    
</div>