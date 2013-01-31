<?php

include_once('../vars/constantes_vars.php');
include_once('../vars/statics_vars.php');
include_once("../classe/classe_slideshow.php");

/* */
if(isset($_GET['plasma_id'])){
/* */


$id_ecran = $_GET['plasma_id'];

$slideshow = new slideshow($id_ecran,'show');

$next_slide_id = $slideshow->get_next_slide_id(true)->id_slide; 
echo $next_slide_id;


/* */
} else {

/* slideshow par défaut ?  $id_ecran = $default_id; (de constantes_vars ?) */
echo "no plasma_id";

}
/* */
?>