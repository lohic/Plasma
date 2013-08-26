<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slide.php');
include_once("../classe/classe_fonctions.php");


$core = new core();

if($core->isAdmin){ 

    $slide = new Slide();

    $action = isset($_POST['action']) ? $_POST['action'] : NULL;


    if($action != NULL && $action == 'create-item'){

        $slide->update_timeline_item();
    }
}