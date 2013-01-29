<?php

include_once('connexion_vars.php');
include_once('classe_connexion.php');
include_once('classe_fonctions.php');
include_once('fonctions.php');
include_once('../vars/statics_vars.php');

class Slide {
	
	var $slide_db		= NULL;
	var $id				= NULL;
	var $func			= NULL;
	
	/*
	@ GESTION DES TEMPLATE
	@ LOIC
	@ 18/07/2012
	*/
	function slide($_id=NULL){
		global $plasma_cInfo;
		$this->slide_db		= new connexion($plasma_cInfo['server'],$plasma_cInfo['user'],$plasma_cInfo['password'],$plasma_cInfo['db']);
		
		if(!empty($_id)){
			
			$this->id = $_id;	
		}
		
		// « self » sert à identifier la classe quand on est en statique
		// une fonction statique n'a pas a être instanciée
		$this->slide_update_data();
	}
	
	/*
	@ mise à jour des informations d'un slide
	@ LOIC
	@ 18/07/2012
	*/
	function slide_update_data(){
		
		// on normalise les données
		// si elles sont présentes tant mieux, sinon on aura un NULL
		$id							= isset($_POST['id_slide'])?		func::GetSQLValueString($_POST['id_slide'],'int'):NULL;
		$_array_val['nom']			= isset($_POST['nom_slide'])?		func::GetSQLValueString($_POST['nom_slide'],'text'):NULL;
		$_array_val['template']		= isset($_POST['template_slide'])?	func::GetSQLValueString($_POST['template_slide'],'text'):NULL;
		$_array_val['json']			= isset($_POST['json_slide'])?		func::GetSQLValueString($_POST['json_slide'],'text'):NULL;
		
		// dans les formulaires de slide, il faudra
		// prévoir un champ caché update, creat ou suppr suivant le cas de figure
		// « slide » est utilisé ici mais on utilisera « slideshow » ou « ecran » ou autre dans les autres cas
		
		if(isset($_POST['update']) && $_POST['update'] == 'slide'){
			$this->update_slide($_array_val,$id);
		}
		
		if(isset($_POST['create']) && $_POST['create'] == 'slide'){
			$this->create_slide($_array_val);			
		}
		
		if(isset($_POST['suppr']) && $_POST['suppr'] == 'slide'){
			$this->suppr_slide($id);
		}
		
	}
	
	
	
	/*
	@ creation ou modification d'un slide
	@ LOIC
	@ 18/07/2012
	@ mod Gildas 
	@ 19/07/2012
	*/
	function update_slide($_array_val,$_id=NULL){
		$this->slide_db->connect_db();
		
		$id							= func::GetSQLValueString($_POST['id_slide'],'int');
		$nom						= func::GetSQLValueString($_POST['nom_slide'],'text');
		$_array_val['template']		= func::GetSQLValueString($_POST['template_slide'],'text');
		$date						= func::GetSQLValueString($_POST['date_slide'],'text');
		
		$json = '{';
		
		// on reçoit la liste des champs dans un POST dédié
		$fields_list = explode(" ", $_POST['champs_list']);
		
		for($i=0; $i<count($fields_list); $i++){
			if(isset($_POST[$fields_list[$i]])){
				if($fields_list[$i] == 'I'){
					// image
					$data = '<img src=\\\\\\\\"'.$_POST[$fields_list[$i]].'\\\\\\\\" />';
				
				/*} else if($fields_list[$i] == 'K'){
					// checkbox
					*/
				
				} else {
					// encodage normal
					$data = nl2br($_POST[$fields_list[$i]]);
					$data = nonl($data); // dans fonctions.php, supprime les sauts de ligne
					$data = str_replace('"', '\\\\\\\\"', $data);
				}
				
				$json .= '\\\\"'.$fields_list[$i].'\\\\":\\\\"'.$data.'\\\\", ';
			}
		}
		if(strlen($json)>2){
			$json = substr($json, 0, strlen($json)-2); // virer le dernier ', '
		}
		$json .= '}';

		// REQUETE
		$sql_slide			= sprintf("UPDATE ".TB."slides_tb SET json='".$json."', nom=".$nom.", date=".$date." WHERE id=".$id);
		$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
		
	}
	
	/*
	@ creation  d'un slide
	@ LOIC
	@ 18/07/2012
	@ mod Gildas 
	@ 11/10/2012
	*/
	function create_slide($_array_val){
		$this->slide_db->connect_db();
		
		$nom			= func::GetSQLValueString($_POST['nom_slide'],'text');
		$template 		= func::GetSQLValueString($_POST['template_slide'],'text');
		$date 			= date('Y-m-d');

		// REQUETE
		//mysql_query("INSERT INTO ".TB."slides_tb (nom, template) values ('".$nom."', '".$template."')");
		
		$sql_slide			= sprintf("INSERT INTO ".TB."slides_tb (nom, template, date) VALUES (".$nom.", ".$template.", '".$date."')");
		//echo $sql_slide;
		$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
		
		// création des dossiers d'upload pour le slide
		$path = '../'.IMG_SLIDES;
		
		// année
		$path .= date('Y').'/';
		if(!is_dir($path)){
			mkdir($path, 0777);
		}
		
		// mois
		$path .= date('m').'/';
		if(!is_dir($path)){
			mkdir($path, 0777);
		}
		
		// id
		$id = mysql_insert_id();
		$path .= $id.'/';
		if(!is_dir($path)){
			mkdir($path, 0777);
		}
		
		// redirection
		header('Location: '.ABSOLUTE_URL.'admin-new/?page=slide_modif&id_slide='.$id);
		
	}
	
	/*
	@ GILDAS
	@ 11/10/2012
	*/
	function create_slide_folders(){
		$this->slide_db->connect_db();
		
		// création des dossiers d'upload pour le slide
		$info = $this->get_slide_info();		
		$folders = explode('/', ($info->uploads));
		$path = '../'.IMG_SLIDES;
		
		// année
		$path .= $folders[0].'/';
		if(!is_dir($path)){
			mkdir($path, 0777);
		}
		
		// mois
		$path .= $folders[1].'/';
		if(!is_dir($path)){
			mkdir($path, 0777);
		}
		
		// id
		$path .= $folders[2].'/';
		if(!is_dir($path)){
			mkdir($path, 0777);
		}
	}
	
	/*
	@ suprression d'un slide
	@ LOIC
	@ 18/07/2012
	@ mod Gildas 
	@ 11/10/2012
	*/
	function suppr_slide($_id=NULL){
		$this->slide_db->connect_db();
		
		// suppression des dossiers
		$info = $this->get_slide_info();
		$path = '../'.IMG_SLIDES.($info->uploads);		
		//delete_dir($path);

		// REQUETE
		if(isset($_POST['id_slide']) && isset($_POST['suppr']) && $_POST['suppr'] == 'slide'){ // pour éviter des erreurs...
			$id					= func::GetSQLValueString($_POST['id_slide'],'int');
			$sql_slide			= sprintf("DELETE FROM ".TB."slides_tb WHERE id=".$id);
			$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
		}
	}
	
	
	/*
	@ listage des slides existants
	@ LOIC
	@ mod GILDAS
	@ 19/07/2012
	*/
	function get_slide_edit_liste($template=NULL,$annee=NULL,$mois=NULL){
		
		$this->slide_db->connect_db();
		
		$optListe = array();
		
		if($template!=-1){ 
			array_push($optListe, "template='".$template."'"); 
		}
		array_push($optListe, "YEAR(date)=".$annee);
		array_push($optListe, "MONTH(date)=".$mois);
		
		if(count($optListe)>0){
			$opt = " WHERE ".implode(" AND ", $optListe);
		} else {
			$opt = "";
		}
		
		$query = 'SELECT id,nom,template FROM '.TB.'slides_tb'.$opt;
		
		$sql_slide		= sprintf($query); //echo $sql_slide;	
		$sql_slide_query = mysql_query($sql_slide) or die(mysql_error());
		
		$i = 0;

		while ($slide_item = mysql_fetch_assoc($sql_slide_query)){
						
			$class				= 'listItemRubrique'.($i+1);
			$id					= $slide_item['id'];
			$nom				= $slide_item['nom'];
			$template			= $slide_item['template'];
			$icone				= ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$template.'/vignette.gif';
				
			include('../structure/slide-list-bloc.php');
			
			$i = ($i+1)%2;
			
		}
		
	}
	
	/*
	@ listage des slides existants pour la selection dans la gestion ecran
	@ LOIC
	@ 
	@ 02/08/2012
	*/
	function get_slide_popup_liste($template=NULL,$annee=NULL,$mois=NULL,$id_selected=NULL){
		
		$this->slide_db->connect_db();
		
		$optListe = array();
		
		if($template!=-1){ 
			array_push($optListe, "template='".$template."'"); 
		}
		array_push($optListe, "YEAR(date)=".$annee);
		array_push($optListe, "MONTH(date)=".$mois);
		
		if(count($optListe)>0){
			$opt = " WHERE ".implode(" AND ", $optListe);
		} else {
			$opt = "";
		}
		
		$query = 'SELECT id,nom,template FROM '.TB.'slides_tb'.$opt;
		
		$sql_slide		= sprintf($query); //echo $sql_slide;	
		$sql_slide_query = mysql_query($sql_slide) or die(mysql_error());
		
		//$i = 0;
		
		$retour = '<ul>';

		while ($slide_item = mysql_fetch_assoc($sql_slide_query)){
						
			$id					= $slide_item['id'];
			$nom				= $slide_item['nom'];
			$template			= $slide_item['template'];
			$icone				= ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$template.'/vignette.gif';
				
			
			$retour .= "<li id='slide-$id'><img src='$icone' width='55' height='35' class='icone'><p class='meta'><span class='titre'>$nom</span><span class='template'>$template</span></p></li>\n";
			
			//$i = ($i+1)%2;
			
		}
		
		$retour .= '</ul>';
		
		return $retour;
		
	}
	
	
	
	
	function get_slide_info(){
		$this->slide_db->connect_db();
		
		$sql_slide		= sprintf("SELECT * FROM ".TB."slides_tb WHERE id=%s",func::GetSQLValueString($this->id,'int'));
		$sql_slide_query = mysql_query($sql_slide) or die(mysql_error());
		
		$slide_item = mysql_fetch_assoc($sql_slide_query);
				
		$retour->id					= $slide_item['id'];
		$retour->nom				= $slide_item['nom'];
		$retour->template			= $slide_item['template'];
		$retour->json				= $slide_item['json'];
		$retour->date				= $slide_item['date'];
		$retour->icone				= ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$slide_item['template'].'/vignette.gif';
		$retour->chemin				= LOCAL_PATH.SLIDE_TEMPLATE_FOLDER.$slide_item['template'].'/index.php';
		$retour->css				= LOCAL_PATH.SLIDE_TEMPLATE_FOLDER.$slide_item['template'].'/style.css';
		$retour->script				= LOCAL_PATH.SLIDE_TEMPLATE_FOLDER.$slide_item['template'].'/script.js';
		
		// chemin vers le dossier d'images et pièces jointes : annee/mois/id
		$retour->uploads			= str_replace('-', '/', substr($slide_item['date'], 0, 7)) . '/' . $slide_item['id'];
				
		return $retour;
		
	}
	
	/*
	@ retourne la liste des slideshows
	@
	@
	*/
	static function get_slide_template_list($admin_groupe=NULL){
		//echo 'liste des slideshows';
		global $templateListe;
		
		$array = $templateListe;
		
		
		
		return $array;
	}
	
	
	/*
	@ creation du formulaire d'édition de slide
	@ GILDAS
	@ 19/07/2012
	@ MOD GILDAS
	@ 11/10/2012
	*/
	function create_slide_editor(){
	
		// créa des dossiers d'upload s'ils n'existent pas
		$this->create_slide_folders();
	
		$info = $this->get_slide_info();
		
		$code = '<fieldset>';
		$code .= '<p class="legend">Informations :</p>';
		$code .= '<input name="id_slide" type="hidden" value="'.($info->id).'" />';
		$code .= '<input name="template_slide" type="hidden" value="'.($info->template).'" />';	
		$code .= '<label>Nom du slide</label><input name="nom_slide" type="text" value="'.($info->nom).'" class="inputField" />';
		$code .= '<div style="clear:both;"></div><br />';
		$code .= '<label>Date de création</label><input name="date_slide" type="text" value="'.($info->date).'" class="inputField date" />';
		$code .= '</fieldset>';
		$code .= '<fieldset>';
		$code .= '<p class="legend">Données :</p>';
		
		
		$template = file_get_contents($info->chemin);
		$json = ( json_decode(stripslashes($info->json)) );
		
		// isoler les balises avec la classe edit	
		$chaine = preg_replace('#(.*)class="(.*)>#isU', 'class="$2||', $template);
		$blocs = explode('||', $chaine);
		$champs_bruts = array();
		for($i=0; $i<count($blocs); $i++){
			if(strpos($blocs[$i], 'edit')===false){} else {
				array_push($champs_bruts, $blocs[$i]);
			}
		}
		// $champs_bruts contient tout ce qu'il nous faut pour chaque champ, non-traité
		
		$champs = array();
		
		// pour chaque champ
		for($i=0; $i<count($champs_bruts); $i++){
			//echo $champs_bruts[$i].'<br />';
			// isoler l'id du champ
			$idchamp = preg_replace('#^(.*)id="(.*)"(.*)$#isU', '$2', $champs_bruts[$i]);
			array_push($champs, $idchamp);
			// isoler le titre
			$title = preg_replace('#^(.*)title="(.*)"(.*)$#isU', '$2', $champs_bruts[$i]);
			// isoler le alt s'il existe
			if(strpos($champs_bruts[$i], 'alt="')===false){
				$alt = "";
			} else {
				$alt = preg_replace('#^(.*)alt="(.*)"(.*)$#isU', '$2', $champs_bruts[$i]);
			}
			// isoler le max s'il existe (nb de chars max)
			if(strpos($champs_bruts[$i], 'max="')===false){
				$max = "";
			} else {
				$max = preg_replace('#^(.*)max="(.*)"(.*)$#isU', '$2', $champs_bruts[$i]);
				$max = ' max="'.$max.'"';
			}
			// chopper la valeur correspondante du json
			$value = $json->{$idchamp};
			
			// isoler le type de champ et l'écrire
			$classes = preg_replace('#^(.*)class="(.*)"(.*)$#isU', '$2', $champs_bruts[$i]);
			
			$html_champ = '<p><label>'.$title.'</label>';
			
			if(strpos($classes, 'textarea')>0){
				$type = "textarea";
				$value = br2nl($value); // br2nl dans fonctions.php
				$html_champ .= '<textarea name="'.$idchamp.'" cols="25" rows="10"'.$max.' class="textareaField">'.$value.'</textarea>';
				
			} else if(strpos($classes, 'textfield')>0){
				$type = "textfield";
				$html_champ .= '<input name="'.$idchamp.'" type="text" value="'.$value.'" '.$max.' class="inputField"/>';
				
			} else if(strpos($classes, 'checkbox')>0){
				$type = "checkbox";
				if($value != ''){
					$ischecked = 'checked ';	
				} else {
					$ischecked = '';
				}
				$html_champ .= '<input name="'.$idchamp.'" type="checkbox" value="'.$alt.'" '.$ischecked.'/>';
				
			} else if(strpos($classes, 'listmenu')>0){
				$type = "listmenu";	
				$valueSet = explode('#', $alt);	
				$tmp = '<select name="'.$idchamp.'"><option value="">Aucun</option>';
				for($j=0; $j<count($valueSet); $j++){
					$selected = ($valueSet[$j] == $value)?' selected="selected"':'';	
					$tmp .= '<option value="'.$valueSet[$j].'"'.$selected.'>'.$valueSet[$j].'</option>';
				}			
				$tmp .= '</select>';
				$html_champ .= $tmp;
				
			} else if(strpos($classes, 'image')>0){
			
				$type = "image";
				$src = preg_replace('#<img src="(.*)"(.*)$#isU', '$1', $value);
				
				$html_champ .= '<input name="'.$idchamp.'" type="hidden" value="'.$src.'" class="inputField"/>';
				$html_champ .= '<input name="file" id="file1" type="file" subfolder="'.($info->uploads).'" fieldname="'.$idchamp.'"/>';
				$html_champ .= '<img src="../'.$src.'" width="150" class="mini" name="'.$idchamp.'"/>';
				
			} else if(strpos($classes, 'video')>0){
			
				$type = "video";
				$src = $value;
				
				$html_champ .= '<input name="'.$idchamp.'" type="text" value="'.$src.'" class="inputField"/>';
				$html_champ .= '<input name="file" id="file1" type="file" subfolder="'.($info->uploads).'" fieldname="'.$idchamp.'"/>';
	
			} else {
				// on cache
				$type = "hidden";
				$html_champ .= 'Champ caché : '.$idchamp.' = '.$value.'<input name="'.$idchamp.'" type="hidden" value="'.$value.'" />';
			}
			
			$html_champ .= '</p>';//'</label>';
			
			$code .= $html_champ;
			
			// retour à la ligne... normalement c'est en css qu'on doit le faire
			$code .= '<div style="clear:both;"></div><br />';		
			
		}
		
		$code .= '</fieldset>';
		$code .= '<input name="submit" type="submit" value="OK" class="buttonenregistrer" />';
		
		// liste des champs édités
		$champs = implode(' ', $champs);
		$code .= '<input name="champs_list" type="hidden" value="'.$champs.'" />';
		
		// post update...
		$code .= '<input name="update" type="hidden" value="slide" />';
		
		
		return $code;
	}
	
	/*
	@ upload et traitement des images
	@ GILDAS
	@
	*/
	function upload_image($file=NULL){
		if($file){
			
		}
	}
	
	
	/*
	@ récupération du flux d'événéments
	@ LOIC
	@
	*/
	function get_event_list($id_organisme=NULL,$year=NULL,$month=NULL,$lang='fr'){
		
		$year = !empty($year)? $year : date('Y');
		$month = !empty($month)? $month : date('m');		
		
		$this->slide_db->connect_db();
		
		$json = file_get_contents(EVENEMENT_DATA_URL.'?event&year='.$year.'&month='.$month.'&id_organisme='.$id_organisme.'&lang='.$lang);
		//echo EVENEMENT_DATA_URL.'?event&year='.$year.'&month='.$month.'&id_organisme='.$id_organisme.'&lang='.$lang;
		
		return $json;
	}
	
	/*
	@ récupération d'un d'événément
	@ LOIC
	@
	*/
	function get_event_data($id=NULL,$lang='fr'){
		$this->slide_db->connect_db();
		
		$json = file_get_contents(EVENEMENT_DATA_URL.'?event='.$id.'&lang='.$lang);
		
		return $json;
		
	}
	
}
	
	

?>