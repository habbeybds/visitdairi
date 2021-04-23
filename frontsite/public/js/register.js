$(document).ready(function(){
    $('.provinsi, .kota').select2();
});

let __csrf = $('meta[name=csrf-token]').attr('content');
let baseUrl = $('meta[name=baseurl]').attr('content');

var formCheck = function(form) {
	var result = true;

	return result;
};

var frmCustomerRegister = $('#form-register-customer');
frmCustomerRegister.find('button[type=submit]').click(function(e) {
	e.preventDefault();
	var msgresult = $('#msg-result');
	var btn = $(this);
	let valid = formCheck(frmCustomerRegister);
	if(!valid) {
		msgresult
			.addClass('alert')
			.removeClass('alert-success').addClass('alert-danger')
			.html(errmsg);
	}
	btn.html('<i class="fa fa-spin fa-spinner"></i> proses..').prop('disabled', true);
	var formData = new FormData(frmCustomerRegister[0]);
	formData.append('_token', __csrf);
	$.ajax({
		url: baseUrl + '/customer/auth/register',
		type: 'POST',
		data: formData,
		contentType: false,
    	processData: false,
		success: function(response) {
			try {
				if(response.status == 'success') {
					msgresult
						.addClass('alert')
						.removeClass('alert-danger').addClass('alert-success')
						.html('Pendaftaran Anda berhasil. Silahkan melakukan aktifasi yang telah dikirim ke email Anda.');
					frmCustomerRegister[0].reset();
				} else {
					if(typeof response.errors !== 'undefined') {
						var errmsg = '';
						$.each(response.errors, function(i, item) {
							errmsg += item + ' ';
						});
						msgresult
							.addClass('alert')
							.removeClass('alert-success').addClass('alert-danger')
							.html(errmsg);
					}
					
				}

			} catch(err) {
				if(typeof response.errors !== 'undefined') {
					msgresult
						.addClass('alert')
						.removeClass('alert-success').addClass('alert-danger')
						.html('Pendaftaran Anda gagal. Silahkan refresh halaman dan coba kembali.');
				}
			}
			btn.html('REGISTER').prop('disabled', false);
		}
	}).fail(function(err) {

		var message = $.parseJSON(err.responseText);
		if(typeof message.errors !== 'undefined') {
			var errmsg = 'Pendaftaran Anda gagal. Silahkan refresh halaman dan coba kembali.';
			$.each(message.errors, function(i, item) {
				errmsg += item + ' ';
			});
			if(err.status == 422) {
				errmsg = 'Silahkan lengkapi data anda.';
			}
			msgresult
				.addClass('alert')
				.removeClass('alert-success').addClass('alert-danger')
				.html(errmsg);
		}
		btn.html('REGISTER').prop('disabled', false);
	});
});