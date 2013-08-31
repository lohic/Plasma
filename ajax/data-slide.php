<?php
header('Content-Type: text/html; charset=utf-8');

include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slide.php');
include_once("../classe/classe_fonctions.php");


$core = new core();

if($core->isAdmin) { 

    $slide = new Slide( $_GET['slide_id'] );

    // creation d'un item
    echo $slide->get_slideshow_slide_data();

}