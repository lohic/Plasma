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

    // creation d'un item
    if($action != NULL && $action == 'create-slide'){

        $slide->update_timeline_slide();
    }
    // mise à jour d'un item
    else
    if($action != NULL && $action == 'update-slide' && isset($_POST['id_slide']) ){

        $slide->update_timeline_slide( $_POST['id_slide'] );

    }
    // suppression d'un item
    else
    if($action != NULL && $action == 'delete-slide' && isset($_POST['id_slide']) ){

        $slide->update_timeline_slide( $_POST['id_slide'], true );

    }

}