
// Javascript


$(document).ready(function(){

	$('body, th, td, .header, .texte, .footer, .texte2, #template').removeAttr( 'style' );
	
	var imageURL = $('.visuel img').attr('src');
		
	$('.visuel img').remove();	
	$('.visuel').css('background-image','url('+imageURL+')');
	
	
	var type = $('.colonne').data('type');

	var type_event = {
		"vie_associative"                   : {texte : "Vie associative", couleur : '#37b7c1'},
	    "expositions"                       : {texte : "Expositions", couleur : '#a99685'},
	    "conferences_recherches_debats"     : {texte : "Conférences, recherches et débats", couleur: '#eb6a0a'},
	    "art_culture"                       : {texte : "Art et culture", couleur : '#e63f81'},
	    "vie_citoyenne"                     : {texte : "Vie citoyenne", couleur : '#569e5b'},
	    "rencontres_metiers"                : {texte : "Rencontres métiers", couleur : '#1455a4'}
	}

	$('#type_event').text(type_event[type].texte);

	$('body, th, td, .header, .texte, .footer, .texte2, #template').css('background-color',type_event[type].couleur);

});