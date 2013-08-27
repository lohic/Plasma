// Javascript
// SLIDE METEO

remplissage();

function remplissage(){
	
	// remplissage
	
	// on a déjà, en dur, 'json_data' et 'main_zip' (l'id de la ville principale)
	// écrits via php au début du template
	
	// on a aussi 'slide_duree' écrit en dur depuis la classe slideshow / generate_slide()
	
	meteo = json_data.meteo;
	for(i=0; i<meteo.length; i++){
		if(meteo[i].zipcode == main_zip){
			main_id = i; // l'id de la ville principale
			main_nom = meteo[i].ville;
		}
	}
	
	// la ville principale
	today = meteo[main_id].today;
	
	$('body').addClass(today.temperature_color);	
	$('#meteo1 .header h1').html(						today.obs);
	$('#meteo1 .header .ville span').html(				main_nom);
	$('#meteo1 .header .picto_principal').addClass(		today.weather_icon);
	$('#meteo1 .detail .thermometre').addClass(			today.temperature_icon);
	$('#meteo1 .detail .temperature span').html(		today.temperature);
	$('#meteo1 .detail .temperature_min span').html(	today.temp_min);
	$('#meteo1 .detail .temperature_max span').html(	today.temp_max);
	$('#meteo1 .detail .vent').addClass(				today.wind_icon);
	
	for(i=1; i<=6; i++){
		
		forecast = eval('meteo[main_id].forecast'+i);
		
		$('#meteo1 .forecast'+i+' .jour p').html(					forecast.obs);
		$('#meteo1 .forecast'+i+' .temps').addClass(				forecast.weather_icon);
		$('#meteo1 .forecast'+i+' .temperature_min span').html(		forecast.temp_min);
		$('#meteo1 .forecast'+i+' .temperature_max span').html(		forecast.temp_max);
	}
	
	//$('#meteo1').hide(); $('#meteo2').show();
	
	// les résumés
	rank = 0;
	for(i=0; i<meteo.length; i++){
		if(i != main_id){
			
			div = $('#meteo2 .footer .autre_campus').eq(rank);
			
			div.find('.nom_ville p').html(			meteo[i].ville);
			div.find('.temperature span').html(		meteo[i].today.temperature);
			div.find('.temps').addClass(			meteo[i].today.weather_icon);
			
			rank++;
		}
	}
	
	// timeout pour passer au 2ème slide
	// slide_duree est transmis par structure/slideshow-javascript.php

	nextMeteoDelay = Math.round((slide_duree-2000)/2);

	setTimeout(function(){
		$('#meteo1').slideUp(600);
		$('#meteo2').show();
		/*$('#meteo1').addClass('exit');*/
	}, nextMeteoDelay);

	$('#meteo2').hide();	
	
}