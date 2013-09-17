<?php
header('Content-Type: text/html; charset=utf-8');


if(!empty($_POST['url'])){

	$url = $_POST['url'];
	$id_event = $_POST['id_event'];

	$base_dir = '../slides_images/';

	if(empty($_POST['month_event']) && empty($_POST['year_event'])){
		$date_dir = date('Y/m/');
	}else{
		$date_dir = $_POST['year_event'].'/'.$_POST['month_event'].'/';
	}
	
	$uploadDir = createPath($base_dir.$date_dir);


	$temp = explode('/', $url);

	$fileName = $id_event.'-'.$temp[count($temp)-1];

	$imageEvent = file_get_contents($url);

	file_put_contents($uploadDir.$fileName, $imageEvent);

	$retour = new stdClass();

	$retour->file	= $date_dir.$fileName ;

	// on récupère l'extension
	$completeName = explode('.',$fileName);
	$retour->ext	= $completeName[count($completeName)-1];

	echo json_encode($retour);


}

function createPath($chemin){	
	if(!is_dir($chemin)){
		mkdir($chemin, 0777, true);
	}
	return $chemin;
}
