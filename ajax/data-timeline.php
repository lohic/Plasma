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

    $id_groupe = isset($_GET['id_groupe']) ? $_GET['id_groupe'] : NULL;

    $data_screens   = $slide->get_timeline_screens($id_groupe);
    $data_items     = $slide->get_timeline_items($id_groupe);

    $json = array();

    if(!empty($data_screens )){
        $json[] = $data_screens->json;
    }

    if (!empty($data_items)){
        $json[] = $data_items;
    }


?>// JavaScript Document
var screen_list = [
    <?php echo !empty($data_screens) ? $data_screens->tab : ''; ?>
];

data = [
    <?php echo implode(',', $json) ?>
];

<?php
}