$(function() {

$("#form-validasi").validate({
	rules: {
		password: {
			minlength: 8
		},
		password2: {
			minlength: 8,
			equalTo: '#password'
		}
	},
	messages: {
		nama: {
			required: 'Nama lengkap harus diisi!'
		},
		email: {
			required: 'Email harus diisi!',
			email: 'Email harus valid!'
		},
		password: {
			required: 'Password harus diisi!',
			minlength: 'Minimal 8 karakter!'
		},
		password2: {
			required: 'Konfirmasi password harus diisi!',
			minlength: 'Minimal 8 karakter!',
			equalTo: 'Konfirmasi password tidak sama!'
		}
	}
});

let flashData = $('.flash-data').data('flashdata');

if(flashData) {
	Swal.fire({
		type: 'success',
		title: 'Selamat',
		text: flashData
	});
}

});