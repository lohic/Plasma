<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_ecran.php');
include_once("../classe/classe_fonctions.php");

$core = new core();

if($core->isAdmin){ 

    /**
     * sert à publier les slideshow des éléments suivants : écran / groupe / organisme / l'ensemble
     * @see slideshow->publish_slideshow 
     * les paramètres GET attendus sont :
     * @param int       $_GET['id_plasma']              id de l'écran à mettre à jour
     * @param int       $_GET['id_groupe']              id du groupe à mettre à jour
     * @param int       $_GET['id_etablissement']       id de l'établissement à mettre à jour
     * @param boolean   $_GET['all']                    true mets à jour tous les écrans, false par défaut
     */
    $ecran = new ecran();
    
}