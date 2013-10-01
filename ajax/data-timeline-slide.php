<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
include_once(REAL_LOCAL_PATH.'vars/statics_vars.php');
include_once(REAL_LOCAL_PATH.'classe/classe_core.php');
include_once(REAL_LOCAL_PATH.'classe/classe_slide.php');
include_once(REAL_LOCAL_PATH."classe/classe_fonctions.php");

$core = new core();

if($core->isAdmin){ 
    
    $action = isset($_POST['action']) ? $_POST['action'] : NULL;
    $slide = new Slide();

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
    // recupération des informations (années, mois, templates, liste des slides)
    else
    if($action != NULL && $action == 'get_select_info' && isset($_POST['id_slide']) ){

        $slide->get_select_info($_POST['id_slide']);

    }

}