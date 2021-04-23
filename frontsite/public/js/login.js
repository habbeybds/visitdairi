
let __csrf = $('meta[name=csrf-token]').attr('content');
let baseUrl = $('meta[name=baseurl]').attr('content');

$('.form-login-register').submit(function(e) {
	e.preventDefault();
	var form = $(this);
	var formData = new FormData(form[0]);
	var btn = form.find('button.default_btn');
	var msgresult = $('#msg-result'); 
	formData.append('_token', __csrf);
	btn.prop('disabled', true).html('<i class="fas fa-spin fa-spinner"></i> Autentikasi ...');
	$.ajax({
		url: baseUrl + '/customer/auth/login',
		type: 'POST',
		data: formData,
		contentType: false,
    	processData: false,
		success: function(response) {
			try {
				if(response.status == 'success') {
					window.location.href = redirect;
				} else {
					if(typeof response.message !== 'undefined') {
						msgresult
							.addClass('alert')
							.removeClass('alert-success').addClass('alert-danger')
							.html(response.message);
					}
				}
			} catch(err) {
				btn.prop('disabled', false).html('Login');
			}
		}
	})
	.done(function(e){
		btn.prop('disabled', false).html('Login');
	});
});

$('.form-forgot-password').submit(function(e) {
	e.preventDefault();
	var form = $(this);
	var formData = new FormData(form[0]);
	var btn = form.find('button.default_btn');
	var msgresult = $('#msg-result'); 
	formData.append('_token', __csrf);
	btn.prop('disabled', true).html('<i class="fas fa-spin fa-spinner"></i>&nbsp;Mengirim ...');
	$.ajax({
		url: baseUrl + '/customer/auth/forgot-password',
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
							.html(response.message);
				} else {
					if(typeof response.message !== 'undefined') {
						msgresult
							.addClass('alert')
							.removeClass('alert-success').addClass('alert-danger')
							.html(response.message);
					}
				}
			} catch(err) {
				btn.prop('disabled', false).html('Kirim');
			}
		}
	})
	.done(function(e){
		btn.prop('disabled', false).html('Kirim');
	});
});

$('.form-recover-password').submit(function(e) {
	e.preventDefault();
	var form = $(this);

	if(form.find('[name=password]').val().length == 0)
	{
		$.alert('Silahkan masukkan password baru anda','Peringatan');
		return false;
	}

	if(form.find('[name=repassword]').val().length == 0)
	{
		$.alert('Silahkan masukkan konfirmasi password baru anda','Peringatan');
		return false;
	}

	var formData = new FormData(form[0]);
	var btn = form.find('button.default_btn');
	var msgresult = $('#msg-result'); 
	formData.append('_token', __csrf);
	btn.prop('disabled', true).html('<i class="fas fa-spin fa-spinner"></i>&nbsp;Mengirim ...');
	$.ajax({
		url: baseUrl + '/customer/auth/recover-password',
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
							.html(response.message);
				} else {
					if(typeof response.message !== 'undefined') {
						msgresult
							.addClass('alert')
							.removeClass('alert-success').addClass('alert-danger')
							.html(response.message);
					}
				}
			} catch(err) {
				btn.prop('disabled', false).html('Perbarui');
			}
		}
	})
	.done(function(e){
		btn.prop('disabled', false).html('Perbarui');
	});
});