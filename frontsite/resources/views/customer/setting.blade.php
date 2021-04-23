<div class="accordion-style">
    <div class="accordion" id="setting">
        <div class="card card-accordion">
            <div class="card-header" id="accordion-setting-1">
                <a href="#" class="btn btn-header-link"
                   data-toggle="collapse"
                   data-target="#phone-email"
                   aria-expanded="true"
                   aria-controls="phone-email">Nomor Ponsel dan Email</a>
            </div>

            <div id="phone-email" class="collapse show" aria-labelledby="accordion-setting-1" data-parent="#setting">
                <div class="card-body wrapper-form">
                    <form class="form-change-phone" action="">
                        <div class="row">
                            <div class="form-group floating-label col-12 col-md-6">
                                <label>Nomor Ponsel</label>
                                <input type="text" class="form-control input-field" name="phone" placeholder="" value="{{ $customer['phone_code'] . $customer['phone'] }}">
                            </div>
                            <div class="form-group floating-label col-12 col-md-6">
                                <label>Email</label>
                                <input type="text" class="form-control input-field" name="email" placeholder="" value="{{ $customer['email'] }}">
                            </div>
                            <div class="form-group col-12 col-md-12 mb-0">
                                <button type="submit" class="default_btn float-right">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card card-accordion">
            <div class="card-header" id="accordion-setting-2">
                <a href="#" class="btn btn-header-link collapsed"
                   data-toggle="collapse"
                   data-target="#password"
                   aria-expanded="true"
                   aria-controls="password">Ubah Kata Sandi</a>
            </div>

            <div id="password" class="collapse" aria-labelledby="accordion-setting-2" data-parent="#setting">
                <div class="card-body wrapper-form">
                    <form class="form-change-password" action="">
                        <div class="row">
                            <div class="form-group floating-label col-12 col-md-6">
                                <label>Password Lama</label>
                                <input type="password" class="form-control input-field" name="old-password">
                            </div>
                            <div class="form-group floating-label col-12 col-md-6">
                                <label>Password Baru</label>
                                <input type="password" class="form-control input-field" name="new-password">
                            </div>
                            <div class="form-group floating-label col-12 col-md-6 offset-6">
                                <label>Konfirmasi Password Baru</label>
                                <input type="password" class="form-control input-field" name="confirm-password">
                            </div>
                            <div class="form-group col-12 col-md-12 mb-0">
                                <button type="submit" class="default_btn float-right">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>