<?php

include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once("../classe/classe_slideshow.php");

//$json->countusers = rand(0,40);

//$retour = json_encode($json);

//echo $retour;

$id_ecran = $_POST['plasma_id'];

$slideshow = new slideshow($id_ecran,'show');

echo $slideshow->get_ecran_data( $_POST['actual_date_json']);