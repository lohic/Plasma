// Javascript
// SLIDE METEO


$(document).ready(function(){

	$('body, th, td, .header, .texte, .footer, .texte2, #template').removeAttr( 'style' );

	remplissage();
});


function remplissage(){

	// remplissage
	// 
	// 
	main_zip = $('body').data('code-meteo');
	console.log('meteo MAIN ZIP: '+main_zip);
	
	// on a déjà, en dur, 'json_data' et 'main_zip' (l'id de la ville principale)
	// écrits via php au début du template
	
	// on a aussi 'slide_duree' écrit en dur depuis la classe slideshow / generate_slide()

	$.ajax({
		type: "GET",
		url: "../vars/meteo_json.txt",
		data: {cache : $now},
		dataType: 'json',
		//async:false,
		success: function(json_data){
			console.log('data meteo loaded');

			meteo = json_data.meteo;

			for(i=0; i<meteo.length; i++){
				if(meteo[i].zipcode == main_zip){
					main_id = i; // l'id de la ville principale
					main_nom = meteo[i].ville;
				}
			}
			
			// la ville principale
			today = meteo[main_id].today;

			console.log(today);
			
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
		}
	});
}
