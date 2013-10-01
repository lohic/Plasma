<?php 

//include_once("constantes_vars.php");

date_default_timezone_set('UTC');


$langEvenement = array();
$langEvenement[0]	= 'français';
$langEvenement[1]	= 'anglais';
$langEvenement[2]	= 'chinois';
$langEvenement[3]	= 'allemand';
$langEvenement[4]	= 'danois';
$langEvenement[5]	= 'espagnol';
$langEvenement[6]	= 'italien';
$langEvenement[7]	= 'japonais';
$langEvenement[8]	= 'polonais';
$langEvenement[9]	= 'russe';
$langEvenement[10]	= 'tchèque';

$moisListe = array();
$moisListe['01']	= 'janvier';
$moisListe['02']	= 'février';
$moisListe['03']	= 'mars';
$moisListe['04']	= 'avril';
$moisListe['05']	= 'mai';
$moisListe['06']	= 'juin';
$moisListe['07']	= 'juillet';
$moisListe['08']	= 'août';
$moisListe['09']	= 'septembre';
$moisListe['10']	= 'octobre';
$moisListe['11']	= 'novembre';
$moisListe['12']	= 'décembre';

$jourListe = array();
$jourListe[1]		= 'lundi';
$jourListe[2]		= 'mardi';
$jourListe[3]		= 'mercredi';
$jourListe[4]		= 'jeudi';
$jourListe[5]		= 'vendredi';
$jourListe[6]		= 'samedi';
$jourListe[7]		= 'dimanche';


$villeListe = array();
$villeListe['75000']	= 'Paris';
$villeListe['21000']	= 'Dijon';
$villeListe['76600']	= 'Le Havre';
$villeListe['06500']	= 'Menton';
$villeListe['54000']	= 'Nancy';
$villeListe['86000']	= 'Poitiers';
$villeListe['51100']	= 'Reims';

$anneeListe = array();
for($i=date('Y')+1;$i>=2012;$i--){
	$anneeListe[$i] = $i;
}

$JListe = array();
for($i=1;$i<=31;$i++){
	$JListe[$i] = $i;
}

$jListe = array();
$jListe['1']	= 'lundi';
$jListe['2']	= 'mardi';
$jListe['3']	= 'mercredi';
$jListe['4']	= 'jeudi';
$jListe['5']	= 'vendredi';
$jListe['6']	= 'samedi';
$jListe['7']	= 'dimanche';


// la liste des templates hors METEO et DEFAULT 
$templateListe = array();
foreach(glob("{".LOCAL_PATH.SLIDE_TEMPLATE_FOLDER."*}",GLOB_BRACE) as $folder){
    
        if(is_dir($folder)){
        	$dossier = str_replace(LOCAL_PATH.SLIDE_TEMPLATE_FOLDER,'',$folder);
        	if($dossier != 'default' && $dossier != 'meteo'){
      			$templateListe[$dossier] = $dossier ;
      		}
		}
}

$typeTab				= array();
$typeTab['admin'] 		= 'administrateur';
$typeTab['super_admin']	= 'super administrateur';

$accountTypeTab			= array();
$accountTypeTab['mail']	= 'compte mail';
$accountTypeTab['ldap']	= 'compte ldap';

/* meteo */

$meteo_refresh_delay = 10*60*60; // 2 minutes
$meteo_wind_teshold = 18.5; // en mph, environ 30 km/h... 1 km = 0.62 miles
$meteo_cold_treshold = 10; // en degrés
$meteo_hot_treshold = 26; // en degrés
