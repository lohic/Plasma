// Javascript

$(document).ready(function(){

	$('body, th, td, .header, .texte, .footer, .texte2, #template').removeAttr( 'style' );

	$('body').css('transform', 'translate('+$decalX+'px, '+$decalY+'px)');
	$('body').css('transform', 'scale('+$scale+')');

	console.log("Slide image chargé");
	
		
});
