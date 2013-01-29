<?php

include_once('../vars/constantes_vars.php');
include_once('../vars/statics_vars.php');
include_once("../classe/classe_slideshow.php");

$isdebug = isset($_GET['debug'])?true:false;
$ispreview = isset($_GET['preview'])?true:false;

/* */
if(isset($_GET['plasma_id']) || isset($_GET['slide_id'])){
/* */	
		
	$id_ecran = $_GET['plasma_id'];
	$slideshow = new slideshow($id_ecran,'show');	
	
	echo $slideshow->run($ispreview,$isdebug);

/* */

} else {

	/* slideshow par défaut ?  $id_ecran = $default_id; (de constantes_vars ?) */
	echo "no plasma_id / slide_id / slideshow_id ...";

}
/* */

?>