<div class="wrap-tab-body">
    @if($detail['status'] == 1)
    <div class="alert alert-warning" role="alert">
        <small>Untuk meningkatkan kemudahan dalam bertansaksi silahkan lakukan verifikasi terlebih dahulu!</small><br />
        <button class="btn btn-light btn-sm btn-verification">Verifikasi Sekarang</button>
    </div>
    @endif
    <h3>Detail Akun</h3>
    <p>Anda dapat merubah detail akun disini</p>
    <div class="content wrapper-form">
        <form class="form-profile" action="">

            <div class="wrap-group-field row">
                <div class="form-group floating-label col-12 col-md-2">
                    <label>Sapaan {{ $detail['salutation'] }}</label>
                    <select class="form-control" name="salutation">
                        @if(!isset($detail['salutation']) || empty($detail['salutation']))
                        <option value="" selected>-</option>
                        <option value="MR">Tuan</option>
                        <option value="MRS">Nyonya</option>
                        <option value="MISS">Nona</option>
                        @else
                        <option value="MR" {{ $detail['salutation'] == 'MR' ? 'selected' : '' }}>Tuan</option>
                        <option value="MRS" {{ $detail['salutation'] == 'MRS' ? 'selected' : '' }}>Nyonya</option>
                        <option value="MISS" {{ $detail['salutation'] == 'MISS' ? 'selected' : '' }}>Nona</option>
                        @endif
                        
                    </select>
                </div>
                <div class="form-group floating-label col-12 col-md-5">
                    <label>Nama Depan</label>
                    <input type="text" class="form-control input-field" name="fname" value="{{ !empty($detail['first_name']) ? $detail['first_name'] : '' }}">
                </div>
                <div class="form-group floating-label col-12 col-md-5">
                    <label>Nama Belakang</label>
                    <input type="text" class="form-control input-field" name="lname" value="{{ !empty($detail['last_name']) ? $detail['last_name'] : '' }}">
                </div>
            </div>

            <div class="wrap-group-field row">
                <div class="form-group floating-label col-12 col-md-5 offset-2">
                    <label>Jenis Kelamin</label>
                    <select class="form-control select-2 kota" name="gender">
                        <option value="">-</option>
                        <option value="M" @if($detail['gender'] == 'M') selected @endif>Pria</option>
                        <option value="F" @if($detail['gender'] == 'F') selected @endif>Wanita</option>
                    </select>
                </div>
                <div class="form-group floating-label col-12 col-md-5">
                    <label>Tanggal Lahir</label>
                    <input type="text" class="form-control input-field" name="dob" placeholder="DD/MM/YYYY" value="{{ $detail['dob'] }}">
                </div>
            </div>

            <div class="wrap-group-field row">
                <div class="form-group floating-label col-12 col-md-10 offset-2">
                    <label>Alamat</label>
                    <textarea class="form-control" id="address" rows="3" name="address">{{ $detail['address'] }}</textarea>
                </div>
                <div class="form-group floating-label col-12 col-md-5 offset-2">
                    <label>Propinsi</label>
                    <select class="form-control select-2 kota" name="province" id="province">
                        @if($provinces)
                            @foreach($provinces as $province)
                                <option value="{{ $province->province_id }}">{{ $province->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group floating-label col-12 col-md-5">
                    <label>Kota/Kabupaten</label>
                    <select class="form-control select-2 kota" id="city" name="city">
                        <option value="">-</option>
                    </select>
                </div>
            </div>
            <div class="wrap-group-field row">
                <div class="form-group floating-label col-12 col-md-5 offset-2">
                    <label>Kecamatan</label>
                    <select class="select-2 subdistrict" id="subdistrict" name="subdistrict">
                        <option value="">-</option>
                    </select>
                </div>
                <div class="form-group floating-label col-12 col-md-2">
                    <label>Kode Pos</label>
                    <input type="text" class="form-control input-field" name="kodepos" value="{{ $detail['postcode'] }}" maxlength="5">
                </div>
            </div>

            <div class="wrap-group-field row">
                <div class="form-group col-12 col-md-10 offset-2">
                    <button type="submit" class="default_btn float-right">Perbarui</button>
                </div>
            </div>

        </form>
    </div>
</div>