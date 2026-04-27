$(function() {
	$('.js-example-basic-single').select2();
	$('.select2').select2(); 
	$('#show-passwd').on('click', function(e) {
		var current = $(this).attr('action');
		if (current == 'hide') {
			$('#contraseña').attr('type', 'text');
			$('.icon-eye-blocked').attr('class', 'icon-eye');
			$('#show-passwd').attr('action','show');
		}
		if (current == 'show') {
			$('#contraseña').attr('type', 'password');
			$('.icon-eye').attr('class', 'icon-eye-blocked');
			$('#show-passwd').attr('action','hide');
		}
	})
	$('.datatable-basic').DataTable();

});