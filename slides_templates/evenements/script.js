
// Javascript


$(document).ready(function(){
	
	var imageURL = $('.visuel img').attr('src');
		
	$('.visuel img').remove();	
	$('.visuel').css('background-image','url('+imageURL+')');
	
	
});