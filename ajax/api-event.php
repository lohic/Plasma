<?php
header('Content-Type: text/html; charset=utf-8');


$param = array();

/*year        : 2013,
month       : 9,
id_organisme: 1,
lang        : "fr"*/

$param['lang']			= "fr";
$param['year'] 			= !empty($_GET['year']) 	 	? $_GET['year'] 		: date('Y');
$param['month'] 		= !empty($_GET['month']) 		? $_GET['month'] 		: date('m');
$param['id_organisme']  = !empty($_GET['id_organisme']) ? $_GET['id_organisme'] : 1;
$param['event']  		= !empty($_GET['id_event']) 	? $_GET['id_event'] 	: NULL;
$param['session']  		= !empty($_GET['session']) 		? $_GET['session'] 		: NULL;

$url = array();

foreach ($param as $key => $value) {
	if($value!=NULL && $value!='NULL'){

		$url[] = $key.'='.$value;

	}else{

		$url[] = $key;
	}
}

$req = '?' . implode('&',$url);



$event_liste = file_get_contents("http://www.sciencespo.fr/evenements/api/".$req);



echo $event_liste;