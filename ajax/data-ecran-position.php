<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
include_once(REAL_LOCAL_PATH.'vars/statics_vars.php');
include_once(REAL_LOCAL_PATH.'classe/classe_core.php');
include_once(REAL_LOCAL_PATH.'classe/classe_ecran.php');
include_once(REAL_LOCAL_PATH."classe/classe_fonctions.php");


$core = new core();

if($core->isAdmin){ 

    $ecran = new ecran($_POST['id']);
    
}