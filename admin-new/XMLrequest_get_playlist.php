<?php
include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_ecran.php');
include_once('../classe/classe_fonctions.php');


$id_plasma 		= !empty($_GET['id_plasma'])?$_GET['id_plasma']:NULL;
$annee 			= isset($_GET['annee'])?$_GET['annee'] : date('Y');
$mois 			= isset($_GET['mois']) ? $_GET['mois'] : date('m');
//$code_postal 	= isset($code_postal)?$code_postal:75000;
$id_groupe 		= !empty($_GET['id_groupe']) ? $_GET['id_groupe'] : !empty($id_groupe) ? $id_groupe : NULL;


$ecran 	= new Ecran($id_plasma);
$data	= !empty($id_groupe)? $ecran->get_info() : $data	= $ecran->get_groupe_info($id_groupe);


$data	= $ecran->get_groupe_info($id_groupe);


/*if(empty($data->id_groupe)){
	$id_groupe = !empty($_GET['id_groupe'])?$_GET['id_groupe']:NULL;
}else{
	$id_groupe = !empty($data->id_groupe)?$data->id_groupe:NULL;	
}*/


if(isset ( $_GET['type_playlist'] )){
	$type_playlist = $_GET['type_playlist'];
}else if ( isset( $type_playlist )) {
	$type_playlist = $type_playlist ;
}else{
	$type_playlist = 'locale';
}
//$type_playlist = !empty($type_playlist) ? $type_playlist : !empty( $_GET['type_playlist']) ? $_GET['type_playlist'] : 'locale';

$id_SELECT		= $type_playlist == 'locale' ? 'id_playlist_locale'	: 'id_playlist_nationale';
$titre_SELECT	= $type_playlist == 'locale' ? 'playlist locale'	: 'playlist nationale';
$data_SELECT	= $type_playlist == 'locale' ? $data->id_playlist_locale	: $data->id_playlist_nationale;
$anneeSELECT	= $type_playlist == 'locale' ? 'annee_locale'	: 'annee_nationale';
$moisSELECT		= $type_playlist == 'locale' ? 'mois_locale'	: 'mois_nationale';

?>

<label for="<?php echo $id_SELECT; ?>"><?php echo $titre_SELECT; ?> :</label>
<?php echo func::createSelect($ecran->get_playlist_list(), $id_SELECT, $data_SELECT, "", true ); ?>
<?php //echo func::createSelect($anneeListe,	$anneeSELECT,	$annee,	"onchange=\"$('#playlist_select_form').submit();\"", false ); ?>
<?php //echo func::createSelect($moisListe,	$moisSELECT	,	$mois,	"onchange=\"$('#playlist_select_form').submit();\"", false ); ?>