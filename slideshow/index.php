<?php

include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once("../classe/classe_slideshow.php");
include_once("../classe/classe_fonctions.php");


$isdebug	= isset($_GET['debug'])?	true:false;
$ispreview	= isset($_GET['preview'])?	true:false;
$istiny		= isset($_GET['tiny'])?		true:false;

/* */
if(isset($_GET['plasma_id']) || isset($_GET['slide_id'])){
/* */	
		
	if(isset($_GET['slide_id'])){
		//$id_ecran = $_GET['slide_id'];
		$id_ecran = 0;
	}else if(isset($_GET['plasma_id'])){
		$id_ecran = $_GET['plasma_id'];
	}
	
	$slideshow = new slideshow($id_ecran);	
	
	echo $slideshow->run($ispreview,$isdebug,$istiny);

/* */

} else {

	/* slideshow par d�faut ?  $id_ecran = $default_id; (de constantes_vars ?) */
	echo "no plasma_id / slide_id / slideshow_id ...";

}
/* */