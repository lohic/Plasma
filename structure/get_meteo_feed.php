<?php

/*
reçoit $_GET['postal_name'] qui est lid'enitifant de la ville récupéré sur 
http://apple.accuweather.com/adcbin/apple/Apple_find_city.asp?location=paris

on récupère la météo sur l'url :
http://apple.accuweather.com/adcbin/apple/Apple_Weather_Data.asp?zipcode=EUR|FR|FR012|PARIS|

'paris' -> 'EUR|FR|FR012|PARIS|'
*/

///////////////////////////
// global settings

$meteo_refresh_delay = 2*60*60; // 2 heures
$wind_teshold = 18.5; // en mph, environ 30 km/h... 1 km = 0.62 miles
$cold_treshold = 10; // en degrés
$hot_treshold = 26; // en degrés

///////////////////////////

function get_weather_icon($num){

	// table de transcription obtenue à partir de weatherParser.js du widget apple

	$weatherIcons = array(
	null,
	"ensoleille", 						// 1 Sunny
	"ensoleille",						// 2 Mostly Sunny
	"peunuageux",				// 3 Partly Sunny
	"peunuageux",				// 4 Intermittent Clouds
	"brumeux",				// 5 Hazy Sunshine
	"couvert",				// 6 Mostly Cloudy
	"couvert",					// 7 Cloudy (am/pm)
	"couvert",					// 8 Dreary (am/pm)
	null,						// 9 retired
	null,						// 10 retired
	"brumeux",						// 11 fog (am/pm)
	"pluvieux",						// 12 showers (am/pnm)
	"pluvieux",				// 13 Mostly Cloudy with Showers
	"variable",					// 14 Partly Sunny with Showers
	"orageux",				// 15 Thunderstorms (am/pm)
	"orageux",				// 16 Mostly Cloudy with Thunder Showers
	"orageux",				// 17 Partly Sunnty with Thunder Showers
	"pluvieux",						// 18 Rain (am/pm)
	"eneige",					// 19 Flurries (am/pm)
	"eneige",					// 20 Mostly Cloudy with Flurries
	"eneige",					// 21 Partly Sunny with Flurries
	"eneige",						// 22 Snow (am/pm)
	"eneige",						// 23 Mostly Cloudy with Snow
	"eneige",						// 24 Ice (am/pm)
	"eneige",						// 25 Sleet (am/pm)
	"eneige",						// 26 Freezing Rain (am/pm)
	null,						// 27 retired
	null,						// 28 retired
	"eneige",				// 29 Rain and Snow Mixed (am/pm)
	"ensoleille",						// 30 Hot (am/pm)
	"ensoleille",						// 31 Cold (am/pm)
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

//////////////////////////////////////////////////////////////////////////////
$_GET['postal_name'] = 'test';

if (isset($_GET['postal_name'])){

	include_once("connect.php");

	// postal_name -> postal_id
	/*$req = "SELECT postal_id FROM plasma_postal WHERE postal_name='".$_GET['postal_name']."' LIMIT 1";
	$lareq = mysql_query($req); $resultat = mysql_fetch_array($lareq);
	$postal_id = $resultat['postal_id'];*/
	$postal_id = "EUR|FR|FR012|PARIS|";

	// date de mise à jour du flux
	$req = "SELECT datecrea FROM plasma_meteo WHERE postal_id='".$postal_id."' LIMIT 1";
	$lareq = mysql_query($req); $resultat = mysql_fetch_array($lareq);
	$date_archive = $resultat['datecrea'];

	$curdate = intval(time());

	if($curdate-$date_archive>=$meteo_refresh_delay){
		// il faut mettre à jour

		$xml = file_get_contents("http://apple.accuweather.com/adcbin/apple/Apple_Weather_Data.asp?zipcode=".$postal_id);

		// traitements
		$current = preg_replace("#(.*)<CurrentConditions>(.*)</CurrentConditions>(.*)#isU", '$2', $xml);
		$forecast = preg_replace("#(.*)<Forecast>(.*)</Forecast>(.*)#isU", '$2', $xml);

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
		$weather_icon = get_weather_icon(intval(preg_replace("#(.*)<WeatherIcon>(.*)</WeatherIcon>(.*)#isU", '$2', $current)));

		$fahrenheit = intval(preg_replace("#(.*)<Temperature>(.*)</Temperature>(.*)#isU", '$2', $current));
		$temperature = toCelsius($fahrenheit);

		if ($temperature<$cold_treshold) {
			$temp_icon = "froid";
		} else if ($temperature>$hot_treshold) {
			$temp_icon = "chaud";
		} else {
			$temp_icon = "moyen";
		}

		if ($temperature<=5) {
			$temp_color = "bleu";
		} else if ($temperature<=15) {
			$temp_color = "vert";
		} else if ($temperature<=25) {
			$temp_color = "orange";
		} else {
			$temp_color = "rouge";
		}

		$wind = intval(preg_replace("#(.*)<WindSpeed>(.*)</WindSpeed>(.*)#isU", '$2', $current));
		if ($wind > $wind_teshold) {
			$wind_icon = "ventfort";
		} else {
			$wind_icon = "ventfaible";
		}

		// temp min et max sont à extraire du 1er forecast
		$today = preg_replace("#(.*)<day number=\"1\">(.*)</day>(.*)#isU", '$2', $forecast);

		$low_temp = toCelsius(intval(preg_replace("#(.*)<Low_Temperature>(.*)</Low_Temperature>(.*)#isU", '$2', $today)));
		$high_temp = toCelsius(intval(preg_replace("#(.*)<High_Temperature>(.*)</High_Temperature>(.*)#isU", '$2', $today)));
		$obsdate = datefr(substr($today, strpos($today, "<ObsDate>")+15, 10));

		$json = '{
				"today":
				{
					"obsdate": "'.$obsdate.'",
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

			$obsdate = datefr(substr($day, strpos($day, "<ObsDate>")+15, 10));
			$weather_icon = get_weather_icon(intval(preg_replace("#(.*)<WeatherIcon>(.*)</WeatherIcon>(.*)#isU", '$2', $day)));
			$low_temp = toCelsius(intval(preg_replace("#(.*)<Low_Temperature>(.*)</Low_Temperature>(.*)#isU", '$2', $day)));
			$high_temp = toCelsius(intval(preg_replace("#(.*)<High_Temperature>(.*)</High_Temperature>(.*)#isU", '$2', $day)));

			$json .= ',
				"forecast'.($i-1).'":
				{
					"obsdate": "'.$obsdate.'",
					"weather_icon": "'.$weather_icon.'",
					"temp_min": "'.$low_temp.'",
					"temp_max": "'.$high_temp.'"
				}';

		}
		$json .= '
		}';

		// javascript-ready (pas de sauts de lignes ni tabulations)
		$json = str_replace(array("\r\n", "\n", "\r", "\t"), '', $json);


		// maj bd sql avec $curdate et $json pour la fiche où postal_id = $postal_id
		$req = "UPDATE plasma_meteo SET datecrea='".$curdate."', json='".$json."' WHERE postal_id='".$postal_id."'";
		mysql_query($req);

		// sortie json
		echo $json;		

	} else {
		// pas de maj, on utilise la bd sql
		$req = "SELECT json FROM plasma_meteo WHERE postal_id='".$postal_id."' LIMIT 1";
		$lareq = mysql_query($req); $resultat = mysql_fetch_array($lareq);
		$json = $resultat['json'];

		echo $json;
	}

	mysql_close();
} ?>