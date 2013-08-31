<?php

header('Content-Type: text/html; charset=utf-8');

include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slideshow.php');
include_once("../classe/classe_fonctions.php");



$core = new core();

if(isset($core)){

    $actual_data_date   = isset( $_GET['actual_data_date'] ) ? $_GET['actual_data_date'] : '0000-00-00 00:00:00';
    $plasma_id          = isset( $_GET['plasma_id'] ) ? $_GET['plasma_id'] : 0;



    $slideshow = new Slideshow($plasma_id);

    echo $slideshow->get_ecran_data($actual_data_date);    
}else{
	$retour = new stdClass();
	$retour->update	= false;
	$retour = json_encode($retour);
	
	echo $retour;
}