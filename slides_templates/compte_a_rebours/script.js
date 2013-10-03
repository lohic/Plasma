// Javascript

$(document).ready(function(){

	$('body, th, td, .header, .texte, .footer, .texte2, #template').removeAttr( 'style' );

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
	days = Math.round(difference/(1000*60*60*24))-1;
	if(days>=0){ days = '+'+days; }
	
	// display
	$('.header h1').first().html('J'+days);
	
	// boucle	
	setTimeout(decompte, 60010);
	
}