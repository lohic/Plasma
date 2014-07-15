// Javascript

$(document).ready(function(){

	$('body, th, td, .header, .texte, .footer, .texte2, #template').removeAttr( 'style' );

	$('body').css('transform', 'translate('+$decalX+'px, '+$decalY+'px) scale('+$scale+')');

	console.log("compte à rebour");
	
	if($(".header").data('date') != ''){
		decompte();
	}
		
});

function decompte(){

	

	dateArray = $(".header").data('date').split('-');
	
	today = new Date();
	dateEvent = new Date(dateArray[0], dateArray[1]-1, dateArray[2]);
	
	difference = today - dateEvent;
	
	jours = Math.round(difference/(1000*60*60*24))-1;
	if(jours>=0){ jours = '+'+jours; }
	
	// display
	$('.header h1').first().html('J'+jours);

	//	
	// boucle	
	setTimeout(decompte, 1000);
}