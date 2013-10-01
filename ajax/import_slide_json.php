<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
include_once(REAL_LOCAL_PATH.'vars/statics_vars.php');
include_once(REAL_LOCAL_PATH.'classe/classe_core.php');
include_once(REAL_LOCAL_PATH.'classe/classe_slide.php');
include_once(REAL_LOCAL_PATH."classe/classe_fonctions.php");


$core = new core();

if($core->isAdmin){ 

    $slide = new Slide();

    $slide->get_timeline_slide_data( $_GET['id_slide'], $_GET['template'] );

}