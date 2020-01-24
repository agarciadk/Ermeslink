// Algoritmo por parte del cliente
// Hacer peticion AJAX a bin/contatti.php del formulario, con todos sus datos, para invocar 
// el phpMailer del servidor, crear aviso en la página de que el email ha
// sido enviado

$(document).ready(function(){
'use strict';
	$('#success-message').hide();
	$('#error-message').hide();
	$('#contact_form').submit(function(e){
		e.preventDefault();
		var captchResponse = $('#g-recaptcha-response').val();
		if(captchResponse === 0){
		   // user has not yet checked the 'I am not a robot' checkbox
			console.log('please, check the captcha');
		} else {
			console.log($('#contact_form').serialize());
			var url = "contact.php";
			$.ajax({
				type: "POST",
				url: url,
				data: $('#contact_form').serialize(),
				success: function(data) {
					console.log("paso por aqui: " + data.msg);
					$('#success-message').fadeTo(2000,500).slideUp(500, function(){
						$('#success-message').slideUp(500);
					});				
				},
				error: function(error) {
					console.log('Algo ha salido mal: ',error.responseJSON.code, error.responseJSON.msg);
					$('#error-message').faceTo(2000,500).slideUp(500, function(){
						$('#error-message').slideUp(500);
					});
				}
			});
		}
	});
});