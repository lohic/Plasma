<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
//include_once('../vars/constantes_vars.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slide.php');
//include_once("../classe/fonctions.php");
include_once("../classe/classe_fonctions.php");

$core = new core();

if($core->isAdmin){ 

	$slide = new Slide();
	
	
	if(isset($_POST['id']) && isset($_POST['lang'])){
	
		$id				= $_POST['id'];
		$lang			= $_POST['lang'];
		
		//echo $year.' / '.$month.' / '.$id_organisme.' / '.$lang;
		
		// connecte l'api		
		$json = $slide->get_event_data($id,$lang);
		echo utf8_encode(html_entity_decode($json));
		/*$json = json_decode($json);
		
		// �crit un select des �v�nements retourn�s
		$data_json = $json->{"evenement"};
		
		$id 			= $data_json->{"id"};
		$titre 			= $data_json->{"titre"};
		$org 			= $data_json->{"organisateur"};
		$org_qualite	= $data_json->{"organisateur_qualite"};
		$coorg			= $data_json->{"coorganisateur"};
		$coorg_qualite 	= $data_json->{"coorganisateur_qualite"};
		$url			= $data_json->{"url"};
		$url_image		= $data_json->{"url_image"};
		
		// sessions
		$sessions_json = $data_json->{"sessions"};*/
		
		/*$eventListe = array();
		foreach ($data_json as $value) {
			$eventListe[$value->{"id"}] = ($value->{"date"}).' / '.($value->{"titre"});
		}
		echo "<fieldset><label>Choix de l\'�v�nement</label>";
		echo createSelect(	$eventListe, 
							'event', 
							$event, 
							"onchange=\"event_fill_editor();\"", 
							true );
		echo '</fieldset>';
		*/
		
	}
}
?>