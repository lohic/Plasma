<?php
header('Content-Type: text/html; charset=utf-8');

include_once('../vars/config.php');
include_once(REAL_LOCAL_PATH.'vars/statics_vars.php');
include_once(REAL_LOCAL_PATH.'classe/classe_core.php');
include_once(REAL_LOCAL_PATH.'classe/classe_slide.php');
include_once(REAL_LOCAL_PATH."classe/classe_fonctions.php");


$core = new core();

if(isset($core)) { 

    $slide = new Slide( $_GET['slide_id'] );

    // creation d'un item
    echo $slide->get_slideshow_slide_data();

}else{
	$retour = new stdClass();
	$retour->update	= false;
	$retour = json_encode($retour);
	
	echo $retour;
}