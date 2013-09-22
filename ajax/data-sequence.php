<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slide.php');


$core = new core();

if($core->isAdmin){ 

    $slide = new Slide();

    $id_groupe = isset($_GET['id_groupe']) ? $_GET['id_groupe'] : NULL;

    echo $slide->get_sequence_items($id_groupe,$core->user->userLevel);

}