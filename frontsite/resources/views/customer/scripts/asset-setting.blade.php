@section('styles')

@endsection

@section('scripts')
<script type="text/javascript">
    let __csrf = $('meta[name=csrf-token]').attr('content');
    let baseUrl = $('meta[name=baseurl]').attr('content');

    $('.form-change-phone').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(form[0]);

        // if old password is empty
        if(form.find('[name=phone]').val().length == 0) {
            $.alert('Nomor telepon tidak boleh kosong!','Info');
            return false;
        }
        formData.append('_token',__csrf);
        $.ajax({
            url: baseUrl + '/customer/setting/update-contact',
            data: formData,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function(response) {
                try {
                    if(response.status == 'success')
                    {
                        $.alert('Update data berhasil!','Info');
                    }
                } catch(err) {
                    
                }
                
            }
        });
    });

    $('.form-change-password').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(form[0]);

        // if old password is empty
        if(form.find('[name=old-password]').val().length == 0) {
            $.alert('Silahkan masukkan password anda!','Info');
            return false;
        }
        formData.append('_token',__csrf);
    });
</script>
@endsection