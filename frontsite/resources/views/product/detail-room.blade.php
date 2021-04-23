<div class="wrap-close">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></button>
</div>
<div class="modal-body">
    <div class="gallery-room">
        <div class="more-photo">
            <span><i class="far fa-images"></i> Foto (<span class="number-photo">{{ sizeof($images) }}</span>)</span>
        </div>
        <div id="lightbox-room" class="gallery">
            @foreach($images as $img)
            <a class="gallery-grid-main" href="{{ config('constants.UPLOAD_PATH') . $img->image_url }}" data-caption="HOTEL-IMG">
                <div class="image">
                    <img src="{{ config('constants.UPLOAD_PATH') . $img->image_url }}" alt="HOTEL-IMG">
                </div>
            </a>
            @endforeach
        </div>
        <div class="gallery-title-room bg">
            <h5>{{ trim($product['title'] . ', ' . $product['subtitle'], ', ') }}</h5>
        </div>
    </div>
    <div class="overview-room">
        <div class="block-header">
            <h6>Deskripsi Kamar</h6>
        </div>
        <div class="block-body">
            <p>{!! $product['room_description'] !!}</p>
        </div>
    </div>
    <div class="facility-room">
        <div class="block-header">
            <h6>Fasilitas Kamar</h6>
        </div>
        <div class="block-body">
            {!! $product['room_facility'] !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="wrap-footer">
        <div class="price">
            <h3>IDR {{ number_format($product['avg_price'], 0, ',','.')}}</h3>
            <p>per kamar per malam</p>
        </div>
    </div>
</div>