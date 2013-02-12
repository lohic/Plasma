<?php

/*
pour récupérer les zipcodes des villes :
http://apple.accuweather.com/adcbin/apple/Apple_find_city.asp?location=paris

pour récupérer la meteo,d'une ville grâce à son zipcode :
http://apple.accuweather.com/adcbin/apple/Apple_Weather_Data.asp?zipcode=EUR|FR|FR012|PARIS|

où Paris a pour zipcode "EUR|FR|FR012|PARIS|"
*/


include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('classe_connexion.php');
include_once('classe_fonctions.php');
//include_once('fonctions.php');
//include_once('connexion_vars.php');
//

class Meteo {
  
	var $meteo_db		= NULL;
	
	/*
	@ GESTION DE LA METEO
	@ GILDAS
	@ 26/07/2012
	*/
	function meteo($zipcode=NULL){
	
		global $connexion_info;
		$this->meteo_db		= new connexion($connexion_info['server'],$connexion_info['user'],$connexion_info['password'],$connexion_info['db']);
		
		$this->local_data = "../".METEO_DATA_URL;
	
		// zipcode principal
		if(!empty($zipcode)){			
			$this->zipcode = $zipcode;	
		} else {
			$this->zipcode = "EUR|FR|FR012|PARIS|";
		}
		
		$this->update_meteo();
		
	}
	
	/*
	@ mise à jour (ou pas) des données météo stockées en dur
	*/
	function update_meteo(){		
	
		// si fichier en dur dépassé
		
		$file = $this->local_data;
		
		global $meteo_refresh_delay;
		
		if(isset($_GET['debug'])){
			//echo ("Time : ".mktime().' - '.filemtime($file).' = '.(mktime()-filemtime($file)).' > '.$meteo_refresh_delay.' ? ');
		}
		 
		if(!file_exists($file) || @mktime()-filemtime($file) > $meteo_refresh_delay){ // statics_vars.php
			
			if(isset($_GET['debug'])){ echo ' Refresh from accuweather'; }
		
			// on liste les établissements
			$villes = $this->get_etablissements();
	
			$json_array = array();
			
			foreach ($villes as $nom => $zip) {
				// on load le xml
				$xml = $this->get_meteo_accuweather($zip);
				// on le parse pour en faire du json
				$json 	= $this->parse_meteo_accuweather($xml, $zip);
				// on assemble
				array_push($json_array, $json);
			}
			
			// on assemble en un seul fichier
			$full_json = '{"meteo":[';
			$full_json .= implode(', ', $json_array);
			$full_json .= ']}';
			
			// on écrit le fichier en dur
			file_put_contents($file, $full_json);
			
		}
	
	}
	
	/*
	@ obtenir le json prêt à l'intégration via jquery
	*/
	function get_meteo(){
	
		$full_json = file_get_contents($this->local_data);
		
		return $full_json;
	
	}
	
	/*
	@ lister les établissements depuis la bd sql
	*/
	function get_etablissements(){
		
		$this->meteo_db->connect_db();
		
		$query = 'SELECT*FROM '.TB.'etablissements_tb ORDER BY id ASC';
		
		$sql_villes		= sprintf($query);
		$sql_villes_query = mysql_query($sql_villes) or die(mysql_error());
		
		$villes = array();

		while ($ville_item = mysql_fetch_assoc($sql_villes_query)){
						
			//$id				= $ville_item['id'];
			//$nom				= $ville_item['nom'];
			$ville				= $ville_item['ville'];
			$zipcode			= $ville_item['code_meteo'];
			//$cp 				= $ville_item['code_postal'];			
			
			$villes[$ville] 	= $zipcode;			
		}
		
		return $villes;
		
	}
	
	/*
	@ récupération sans traitement du flux XML
	*/
	function get_meteo_accuweather($zipcode){
	
		$xml = file_get_contents("http://apple.accuweather.com/adcbin/apple/Apple_Weather_Data.asp?zipcode=".urlencode($zipcode));
		
		return $xml;
	}
	
	/*
	@ traitement du flux XML accuweather
	*/
	function parse_meteo_accuweather($xml, $zip=NULL){
		
		global $cold_treshold;
		global $hot_treshold;
		global $wind_teshold;
		
	
		$current = preg_replace("#(.*)<CurrentConditions>(.*)</CurrentConditions>(.*)#isU", '$2', $xml);
		$forecast = preg_replace("#(.*)<Forecast>(.*)</Forecast>(.*)#isU", '$2', $xml);
		
		$nom_ville = preg_replace("#(.*)<City>(.*)</City>(.*)$#isU", '$2', $current);

		/*
		A extraire de current :
		- weather icon
		- temperature
		- icone de temperature
		- couleur de la page suivant la temperature
		- temp min
		- temp max
		- vent
		*/
		$weather_icon = $this->get_weather_icon(intval(preg_replace("#(.*)<WeatherIcon>(.*)</WeatherIcon>(.*)#isU", '$2', $current)));

		$fahrenheit = intval(preg_replace("#(.*)<Temperature>(.*)</Temperature>(.*)#isU", '$2', $current));
		$temperature = $this->toCelsius($fahrenheit);

		if ($temperature<$cold_treshold) {
			$temp_icon = "froid";
		} else if ($temperature>$hot_treshold) {
			$temp_icon = "chaud";
		} else {
			$temp_icon = "moyen";
		}

		if ($temperature<=5) {
			$temp_color = "basse";
		} else if ($temperature<=15) {
			$temp_color = "moyen_basse";
		} else if ($temperature<=25) {
			$temp_color = "moyen_haute";
		} else {
			$temp_color = "haute";
		}

		$wind = intval(preg_replace("#(.*)<WindSpeed>(.*)</WindSpeed>(.*)#isU", '$2', $current));
		if ($wind > $wind_teshold) {
			$wind_icon = "fort";
		} else {
			$wind_icon = "faible";
		}

		// temp min et max sont à extraire du 1er forecast
		$today = preg_replace("#(.*)<day number=\"1\">(.*)</day>(.*)#isU", '$2', $forecast);

		$low_temp = $this->toCelsius(intval(preg_replace("#(.*)<Low_Temperature>(.*)</Low_Temperature>(.*)#isU", '$2', $today)));
		$high_temp = $this->toCelsius(intval(preg_replace("#(.*)<High_Temperature>(.*)</High_Temperature>(.*)#isU", '$2', $today)));
		$obsdate = $this->datefr(substr($today, strpos($today, "<ObsDate>")+15, 10));

		$json = '{
				"ville": "'.$nom_ville.'",
				"zipcode": "'.$zip.'",
				"today":
				{
					"obs": "'.$obsdate.'",
					"weather_icon": "'.$weather_icon.'",
					"temperature": "'.$temperature.'",
					"temperature_icon": "'.$temp_icon.'",
					"temperature_color": "'.$temp_color.'",
					"temp_min": "'.$low_temp.'",
					"temp_max": "'.$high_temp.'",
					"wind_icon": "'.$wind_icon.'"
				}';

		/*
		A extraire de forecast, sur 6 jours :
		- weather icon
		- min
		- max
		*/

		for ($i=2; $i <= 7; $i++) { 

			$day = preg_replace("#(.*)<day number=\"".$i."\">(.*)</day>(.*)#isU", '$2', $forecast);

			$obsdate = $this->datefr(substr($day, strpos($day, "<ObsDate>")+15, 10));
			$weather_icon = $this->get_weather_icon(intval(preg_replace("#(.*)<WeatherIcon>(.*)</WeatherIcon>(.*)#isU", '$2', $day)));
			$low_temp = $this->toCelsius(intval(preg_replace("#(.*)<Low_Temperature>(.*)</Low_Temperature>(.*)#isU", '$2', $day)));
			$high_temp = $this->toCelsius(intval(preg_replace("#(.*)<High_Temperature>(.*)</High_Temperature>(.*)#isU", '$2', $day)));

			$json .= ',
				"forecast'.($i-1).'":
				{
					"obs": "'.$obsdate.'",
					"weather_icon": "'.$weather_icon.'",
					"temp_min": "'.$low_temp.'",
					"temp_max": "'.$high_temp.'"
				}';

		}
		$json .= '
		}';

		// javascript-ready (pas de sauts de lignes ni tabulations)
		$json = str_replace(array("\r\n", "\n", "\r", "\t"), '', $json);
		
		return $json;
	}
	
	
	
	
	
	
	
	
	
	/* /////////////////////////////////////////////////////////// */
	
	function get_weather_icon($num){

		// table de transcription obtenue à partir de weatherParser.js du widget apple
	
		$weatherIcons = array(
		null,
		"soleil", 						// 1 Sunny
		"soleil",						// 2 Mostly Sunny
		"peu_nuageux",				// 3 Partly Sunny
		"peu_nuageux",				// 4 Intermittent Clouds
		"brume",				// 5 Hazy Sunshine
		"couvert",				// 6 Mostly Cloudy
		"couvert",					// 7 Cloudy (am/pm)
		"couvert",					// 8 Dreary (am/pm)
		null,						// 9 retired
		null,						// 10 retired
		"brume",						// 11 fog (am/pm)
		"pluvieux",						// 12 showers (am/pnm)
		"pluvieux",				// 13 Mostly Cloudy with Showers
		"variable",					// 14 Partly Sunny with Showers
		"orage",				// 15 Thunderstorms (am/pm)
		"orage",				// 16 Mostly Cloudy with Thunder Showers
		"orage",				// 17 Partly Sunnty with Thunder Showers
		"pluvieux",						// 18 Rain (am/pm)
		"neige",					// 19 Flurries (am/pm)
		"neige",					// 20 Mostly Cloudy with Flurries
		"neige",					// 21 Partly Sunny with Flurries
		"neige",						// 22 Snow (am/pm)
		"neige",						// 23 Mostly Cloudy with Snow
		"neige",						// 24 Ice (am/pm)
		"neige",						// 25 Sleet (am/pm)
		"neige",						// 26 Freezing Rain (am/pm)
		null,						// 27 retired
		null,						// 28 retired
		"neige",				// 29 Rain and Snow Mixed (am/pm)
		"soleil",						// 30 Hot (am/pm)
		"soleil",						// 31 Cold (am/pm)
		"wind",						// 32 Windy (am/pm)
		// Night only Icons
		"nuit",						// 33 Clear
		"nuit",						// 34 Mostly Clear
		"nuit",					// 35 Partly Cloudy
		"nuit",					// 36 Intermittent Clouds
		"nuit",				// 37 Hazy
		"nuit",				// 38 Mostly Cloudy
		"nuit",				// 39 Partly Cloudy with Showers
		"nuit", 				// 40 Mostly Cloudy with Showers
		"nuit",				// 41 Partly Cloudy with Thunder Showers
		"nuit",				// 42 Mostly Cloudy with Thunder Showers
		"nuit"						// 43 Mostly Cloudy with Flurries
		
		);
	
		return $weatherIcons[$num];
	}
	
	function toCelsius($fahrenheit){
		// farhenheit to celsius
		return round(($fahrenheit - 32) / 1.8);
	}
	
	function datefr($date) {
		// à partir d'ue date au format Anglo-saxon 06/21/2012
		$split = explode("/",$date);
	   
		$m = intval($split[0]);
		$j = intval($split[1]);
		$a = $split[2];
	
		$jours = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
		$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
	
		// jour de la semaine
		$timestamp = mktime(0, 0, 0, $m, $j, $a);
		$wd = date("w", $timestamp);
		$jsem = $jours[$wd];
	   
		return $jsem." ".$j." ".$mois[$m]." ".$a;
	}
	
}
	
	

?>
