<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/constantes_vars.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slide.php');
//include_once("../classe/fonctions.php");
include_once("../classe/classe_fonctions.php");

$core = new core();

if($core->isAdmin){ 

	$slide = new Slide();
	
	
	if(isset($_POST['year']) && isset($_POST['month']) && isset($_POST['id_organisme']) && isset($_POST['lang'])){
	
		$year				= $_POST['year'];
		$month				= $_POST['month'];
		$id_organisme		= $_POST['id_organisme'];
		$lang				= $_POST['lang'];
		$event				= NULL;
		
		//echo $year.' / '.$month.' / '.$id_organisme.' / '.$lang;
		
		// connecte l'api		
		$json = $slide->get_event_list($id_organisme,$year,$month,$lang);
		$json = json_decode($json);
		
		// écrit un select des événements retournés
		$events_json = $json->{"evenements"}->{"evenement"};
		
		// id - titre - date
		$eventListe = array();
		foreach ($events_json as $value) {
			$eventListe[$value->{"id"}] = ($value->{"date"}).' / '.($value->{"titre"});
		}
		echo "<fieldset><label>Choix de l'&eacute;v&eacute;nement</label>";
		echo createSelect(	$eventListe, 
							'event', 
							$event, 
							"onchange=\"event_fill_editor();\"", 
							true );
		echo '<div id="session_select"></div></fieldset>';
		
	}
}

/*if(!empty($_POST['id']) && $_POST['is_archive']==0){
	
	$id_newsletter = $_POST['id'];

	if(!isset($news)){
		$news = new newsletter($id_newsletter);
	}
	
	$news->create_archive($id_newsletter);
	
	echo '<img src="../graphisme/padlock_closed.png" alt="archive" />';
}else{
	$id_newsletter = $_POST['id'];
	
	if(!isset($news)){
		$news = new newsletter($id_newsletter);
	}
	
	$news->unarchive($id_newsletter);
	
	echo '<img src="../graphisme/padlock_open.png" alt="archive" title="archiver la newsletter" />';
}*/
?>