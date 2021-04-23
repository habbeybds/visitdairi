@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style type="text/css">
    .wrapper-form .floating-label label {
    transform: translateY(-17px) scale(0.58);
    color: #0092DD;
    opacity: 1;
    background: #F5F6FA;
    padding: 0px 5px;
    z-index: 9;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript">
    let __csrf = $('meta[name=csrf-token]').attr('content');
    let baseUrl = $('meta[name=baseurl]').attr('content');
    if($('[name="dob"]').length > 0) {
        let options = {
            dateFormat: 'd/m/Y'
        }
        $('[name="dob"]').flatpickr(options);
    }

    $(document).ready(function() {
        $('.form-profile').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = new FormData(form[0]);
            // check salutation
            if(form.find('[name=salutation]').val().length == 0) {
                $.alert('Kolom sapaan harus diisi!','Info');
                return false;
            }
            // cek nama depan
            if(form.find('[name=fname]').val().length < 3) {
                $.alert('Kolom nama depan harus diisi minimal 3 karakter!','Info');
                return false;
            }

            // cek gender
            if(form.find('[name=gender]').val().length == 0) {
                $.alert('Kolom jenis kelamin harus diisi!','Info');
                return false;
            }

            // cek dob -> minimal usia
            // if(form.find('[name=dob]').val().length == 0) {
                
            //     return false;
            // }
            formData.append('_token',__csrf);
            $.ajax({
                url: baseUrl + '/customer/profile/update',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    try {
                        if(response.status == 'success') {
                            $.alert('Perbaruan data profil berhasil','Sukses');
                        } else if(response.status == 'error' && (typeof response.message !== 'undefined')) {
                            $.alert(response.message,'Gagal');
                        }
                    } catch(err) {
                        //
                    }
                }
            });
        });

        $('.btn-verification').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var alert = btn.closest('.alert');
            let options = {
                _token: __csrf
            };
            
            btn.html('<i class="fas fa-spin fa-spinner"></i> Mengirim..');
            $.post(baseUrl + '/customer/send-verification', options, function(response) {
                try {
                    if(response.status == 'success') {
                        alert
                            .removeClass('alert-warning')
                            .addClass('alert-success')
                            .html(response.message);
                    } else {
                        alert
                            .removeClass('alert-success')
                            .addClass('alert-warning')
                            .html('Proses verifikasi gagal. Silahkan coba kembali sesaat lagi!');
                    }
                } catch(er) {
                    alert
                        .removeClass('alert-success')
                        .addClass('alert-warning')
                        .html('Proses verifikasi gagal. Silahkan coba kembali sesaat lagi!');
                }
            });
        });

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
                        
                        @if($detail['city_id']) 
                            $("#city").val({{ $detail['city_id'] }}).change();
                        @else
                            $("#city").val(0).change();
                        @endif
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
                        @if($detail['subdistrict_id']) 
                            $("#subdistrict").val({{ $detail['subdistrict_id'] }}).change();
                        @else
                            $("#subdistrict").val(0).change();
                        @endif
                    }
                },
                error: function ()
                {
                    console.log('error');
                }
            });
        });

        @if($detail['province_id']) 
            $("#province").val({{ $detail['province_id'] }}).change();
        @else
            $("#province").change();
        @endif
    });
</script>
@endsection