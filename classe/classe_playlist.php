<?php

include_once('../vars/config.php');
include_once('classe_connexion.php');
include_once('classe_slide.php');
include_once('classe_fonctions.php');
include_once('fonctions.php');
//include_once('connexion_vars.php');
//include_once('../vars/statics_vars.php');


class Playlist {
	
	var $slide_db		= NULL;
	var $id_playlist	= NULL;
	
	/**
	* playlist constructeur de la classe playlist, pour gérer les playlists de slides
	* @author Loïc Horellou
	* @since v0.5 29/12/2012
	*/
	function playlist($_id_playlist=NULL){
		
		global $connexion_info;
		date_default_timezone_set('Europe/Paris');
		$this->slide_db		= new connexion($connexion_info['server'],$connexion_info['user'],$connexion_info['password'],$connexion_info['db']);
		
		if(!empty($_id_playlist)){
			$this->id_playlist = $_id_playlist;	
		}
		
		$this->debugger = "";
		
		if((!empty($_POST['create']) && $_POST['create'] == 'playlist') || (!empty($_POST['update']) && $_POST['update'] == 'playlist')){
		$this->updater();
		}
	}
	
	
	/**
	* updater permet de mettre à jour les différents formulaires liés à un objet playlist au moment ou celui-ci est créé ou modifié
	* @author Loïc Horellou
	* @since v0.5 29/12/2012
	*/
	function updater(){
		// on normalise les données
		// si elles sont présentes tant mieux, sinon on aura un NULL
		$id							= isset($_POST['id_playlist'])?		func::GetSQLValueString($_POST['id_playlist'],'int'):NULL;
		$_array_val['nom']			= isset($_POST['nom'])?				func::GetSQLValueString($_POST['nom'],'text'):NULL;
		$_array_val['date']			= isset($_POST['date'])?			func::GetSQLValueString($_POST['date'],'date'):NULL;
	
		
		if(!empty($_POST['create']) && $_POST['create'] == 'playlist'){
			$this->create_playlist($_array_val);
		}
		
		
		if(!empty($_POST['update']) && $_POST['update'] == 'playlist'){
			$this->update_playlist($_array_val,$id);	
		}
		
	}
	
	/**
	* update_playlist sert a mettre à jour les informations générales d'une playlist
	* @author Loïc Horellou
	* @since v0.5 10/01/2012
	*/
	function update_playlist($_array_val,$_id=NULL){
		$this->slide_db->connect_db();
	
		// REQUETE
		$sql_slide			= sprintf("UPDATE ".TB."playlist_tb SET nom=".$_array_val['nom'].", date=".$_array_val['date']." WHERE id=".$_id);
		$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
	}
	
	/**
	* create_playlist sert a créer les informations générales d'une playlist
	* @author Loïc Horellou
	* @since v0.5 10/01/2012
	*/
	function create_playlist($_array_val){
		$this->slide_db->connect_db();
				
		$sql			= sprintf("INSERT INTO ".TB."playlist_tb (nom, date) VALUES (".$_array_val['nom'].", ".$_array_val['date'].")");
		//echo $sql_slide;
		$sql_query 	= mysql_query($sql) or die(mysql_error());
		
		$id = mysql_insert_id();
		
		header('Location: '.ABSOLUTE_URL.'admin-new/?page=playlist_modif&id_playlist='.$id);
	}
	
	
	
	/**
	* get_playlist_category_list retourne la liste des playlists en fonction des droits d'administration
	# @author Loïc Horellou
	* @since v0.5 29/12/2012
	*/
	function get_playlist_category_list($admin_groupe=NULL,$annee=NULL,$mois=NULL){
		//echo 'liste des slideshows';
		global $templateListe;
		
		$array = $templateListe;
		
		
		
		return $array;
	}
	
	/**
	* get_playlist_edit_liste retourne la liste des playlists en fonction des droits d'administration
	# @author Loïc Horellou
	* @since v0.5 29/12/2012
	*/
	function get_playlist_edit_liste($annee=NULL,$mois=NULL){
		$this->slide_db->connect_db();
		
		$optListe = array();
		
		if(empty($annee)) $annee = date('Y');
		if(empty($mois))  $mois  = date('m');
		
		/*if($template!=-1){ 
			array_push($optListe, "template='".$template."'"); 
		}*/
		array_push($optListe, "YEAR(date)=".$annee);
		array_push($optListe, "MONTH(date)=".$mois);
		
		if(count($optListe)>0){
			$opt = " WHERE ".implode(" AND ", $optListe);
		} else {
			$opt = "";
		}
		
		$query = 'SELECT id,nom,date FROM '.TB.'playlist_tb'.$opt.' ORDER BY date DESC';
		
		$sql		= sprintf($query); //echo $sql_slide;	
		$sql_query = mysql_query($sql) or die(mysql_error());
		
		$i = 0;

		while ($item = mysql_fetch_assoc($sql_query)){
						
			$class				= 'listItemRubrique'.($i+1);
			$id					= $item['id'];
			$nom				= $item['nom'];
			$date				= $item['date'];
			//$template			= $slide_item['template'];
			//$icone				= ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$template.'/vignette.gif';
				
			include('../structure/playlist-list-bloc.php');
			
			$i = ($i+1)%2;
			
		}

	}
	
	
	function get_playlist_info($id_playlist = NULL){
		if(!empty($this->id_playlist)){
			$this->slide_db->connect_db();
			
			$sql_playlist		= sprintf("SELECT * FROM ".TB."playlist_tb WHERE id=%s",func::GetSQLValueString($this->id_playlist,'int'));
			$sql_playlist_query = mysql_query($sql_playlist) or die(mysql_error());
			
			$slideshow_item = mysql_fetch_assoc($sql_playlist_query);
					
			$retour->id					= $slideshow_item['id'];
			$retour->nom				= $slideshow_item['nom'];
			$retour->date				= $slideshow_item['date'];
			
			return $retour;
		}else{
					
			$retour->id					= NULL;
			$retour->nom				= "Playlist du ".date("d/m/Y");
			$retour->date				= date("Y-m-d");
			
			return $retour;	
		}
	}
	
	/*
	@ RECUPERE LA LISTE DES SLIDES EN MODE FLUX
	@
	@
	@
	*/
	function get_slide_list($type_slide=NULL){
		global $jListe;
		global $JListe;
		global $moisListe;
		
		$this->slide_db->connect_db();
		
		$retour = '';

		
		if(!empty($this->id_playlist)){
			
			$sql			= sprintf("SELECT R.id AS id,
										R.id_slide AS id_slide,
										R.id_target AS id_target,
										R.date AS date,
										R.duree AS duree,
										R.freq AS freq,
										R.type AS type,							
										S.nom AS nom,
										S.template AS template
										FROM ".TB."rel_slide_tb AS R
										LEFT JOIN ".TB."slides_tb AS S
										ON R.id_slide = S.id
										WHERE R.id_target=%s
										AND R.type_target = 'playlist'
										AND R.type = %s
										ORDER BY ordre ASC, duree ASC", 	func::GetSQLValueString($this->id_playlist,'int'),
																			func::GetSQLValueString($type_slide,'text'));
																			
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			$nbr			= mysql_num_rows($sql_query);
			
			
			while($info = mysql_fetch_assoc($sql_query)){
				
				$duree = $info['duree'];
				$json = json_decode($info['freq']);
				
				$remove = '<a href="#" class="del"><img src="../graphisme/round_minus.png" alt="supprimer un slide" height="16"/></a>';
		
				$temp	= explode(' ',$info['date']);
				$date	= $temp[0];
				$time	= $temp[1];
				//$nom	= !empty($info['nom'])?$info['nom']:'choisir';
				$nom	= $info['nom'];
				//$empty 	= !empty($info['nom'])?'':' empty';
				
				$type 	= $type_slide;
		
				//$slides	 = '<input type="hidden" value="'.$info['id_slide'].'" name="id_slide[]" class="id_slide"/><a class="slidelistselect'.$empty.'">'.$nom.'</a>';
				$iconeURL= !empty($info['nom'])? ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$info['template'].'/vignette.gif' :'';
				
				
				//$retour .= '<li><input class="id_rel" type="hidden" name="id_rel[]" value="'.$info['id'].'" /><input type="hidden" name="timestamp[]" value="" /><input type="hidden" name="typerel[]" value="date" /><input type="hidden" name="M[]" value="" /><input type="hidden" name="J[]" value="" /><input type="hidden" name="j[]" value="" /><input type="hidden" name="H[]" value="" />'.$remove.'<img src="'.$iconeURL.'" width="28" height="18" class="icone" /><span> <span>date : <input name="date[]" type="text" value="'.$date.'" class="dateslide"/></span></span> <span>horaire : <input type="text" name="time[]" value="'.$time.'" class="timeslide" /> <span>durée : <input name="duree[]" type="text" value="'.$duree.'" class="dureeslide"/></span> <span><a href="../slideshow/?slide_id='.$info['id_slide'].'&preview" target="_blank" class="preview"><img src="../graphisme/eye.png" alt="voir"/></a></span> '.$slides.'	</li>';
				
				// ANCIEN
				//$retour .= '<li><input class="id_rel" type="hidden" name="id_rel[]" value="'.$info['id'].'" /><input type="hidden" name="timestamp[]" value="" /><input type="hidden" name="typerel[]" value="flux" /><input type="hidden" name="M[]" value="" /><input type="hidden" name="J[]" value="" /><input type="hidden" name="j[]" value="" /><input type="hidden" name="H[]" value="" /><input name="date[]" type="hidden" value="" /><input type="hidden" name="time[]" value="" />'.$remove.'<img src="'.$iconeURL.'" width="28" height="18" class="icone" /> <span>durée : <input name="duree[]" type="text" value="'.$duree.'" class="dureeslide"/></span> <span><a href="../slideshow/?slide_id='.$info['id_slide'].'&preview" target="_blank" class="preview"><img src="../graphisme/eye.png" alt="voir"/></a></span> '.$slides.'	</li>';
				
				$is_date = $type_slide == 'date' ? true : false;
				$is_freq = $type_slide == 'freq' ? true : false;
				$is_flux = $type_slide == 'flux' ? true : false;
				
				if($is_freq){
					$json = json_decode($info['freq']);
					
					$M = isset($json->M)?$json->M:NULL;
					$J = isset($json->J)?$json->J:NULL;
					$j = isset($json->j)?$json->j:NULL;
					
					$jListe2[''] = '-';
					$jListe2['*'] = '*';
					$jListe2 = $jListe2+$jListe;
					
					$JListe2[''] = '-';
					$JListe2['*'] = '*';
					$JListe2 = $JListe2+$JListe;
					
					$MListe2['*'] = '*';
					$MListe2 = $MListe2+$moisListe;
					
					$MSelect = func::createSelect($MListe2, 'M[]', $M, "", false );
					$jSelect = func::createSelect($jListe2, 'j[]', $j, "", false );
					$JSelect = func::createSelect($JListe2, 'J[]', $J, "", false );	
				}
				
				ob_start();
				include('../structure/slide-playlist-list-bloc.php');
				$slideForm = ob_get_contents();
				ob_end_clean();
				
				$slideForm = str_replace("\r",'',$slideForm);
				$retour .= str_replace("\n",'',$slideForm)."\n";
				
			}
		}
		
		return $retour;
	}
	
	/**
	* update_rel_slide METTRE A JOUR OU CREER UNE LIAISON SLIDE->ECRAN en mode freq ou date
	* cf classe SLIDESHOW pour LIAISON SLIDE->SLIDESHOW
	* @author Loïc Horellou
	* @since v0.5 10/01/2012
	* @param $_array_val tableaux des valeurs attendues (id_slide, id_target, type_target, date, duree, freq, type)
	* @param $id_rel identifiant de liaison de l'élément de sp_plasma_rel_slide_tb à mettre à jour
	*/
	function update_rel_slide($_array_val=NULL,$id_rel = NULL){
		//if(!empty($this->id)){
			
			$this->slide_db->connect_db();
			
			$id_slide	= !empty($_array_val['id_slide'])?		$_array_val['id_slide'] :		NULL;
			$id_target	= !empty($_array_val['id_target'])?		$_array_val['id_target'] :		NULL;
			$type_target= !empty($_array_val['type_target'])?	$_array_val['type_target'] :	'ecran';			
			$date		= !empty($_array_val['date'])?			$_array_val['date'] :			'0000-00-00';
			$time		= !empty($_array_val['time'])?			$_array_val['time'] :			'00:00:00';
			$date		= $date.' '.$time;
			$duree		= !empty($_array_val['duree'])?			$_array_val['duree'] :			'00:00:00';
			$freq		= !empty($_array_val['freq'])?			$_array_val['freq'] :			NULL;
			$status		= NULL;
			$type		= !empty($_array_val['type'])?			$_array_val['type'] :			'date';
			$ordre		= !empty($_array_val['ordre'])?			$_array_val['ordre'] :			NULL;
			$alerte		= 0;
			
			if(!empty($id_rel)){
				$sql		= sprintf("UPDATE ".TB."rel_slide_tb SET id_slide=%s, id_target=%s, type_target=%s, date=%s, duree=%s, freq=%s, status=%s, type=%s, ordre=%s, alerte=%s   WHERE id=%s", 	func::GetSQLValueString($id_slide,'int'),
															func::GetSQLValueString($id_target,'int'),
															func::GetSQLValueString($type_target,'text'),
															func::GetSQLValueString($date,'text'),
															func::GetSQLValueString($duree,'text'),
															func::GetSQLValueString($freq,'text'),
															func::GetSQLValueString($status,'text'),
															func::GetSQLValueString($type,'text'),
															func::GetSQLValueString($ordre,'int'),
															func::GetSQLValueString($alerte,'int'),
															func::GetSQLValueString($id_rel,'int'));
				$sqlquery 	= mysql_query($sql) or die(mysql_error());
			}else{
				$sql		= sprintf("INSERT INTO ".TB."rel_slide_tb (id_slide, id_target, type_target, date, duree, freq, status, type,ordre, alerte) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",			func::GetSQLValueString($id_slide,'int'),
															func::GetSQLValueString($id_target,'int'),
															func::GetSQLValueString($type_target,'text'),
															func::GetSQLValueString($date,'text'),
															func::GetSQLValueString($duree,'text'),
															func::GetSQLValueString($freq,'text'),
															func::GetSQLValueString($status,'text'),
															func::GetSQLValueString($type,'text'),
															func::GetSQLValueString($ordre,'int'),
															func::GetSQLValueString($alerte,'int'));
				$sqlquery 	= mysql_query($sql) or die(mysql_error());
				
				return mysql_insert_id();

			}
			
		//}
	}
	
	/**
	* del_rel_slide permet de supprimer un slide relié à une playlist
	# @author Loïc Horellou
	* @since v0.5 23/01/2012
	* @param $id_rel identifiant de la ligne sp_plasma_rel_slide_tb à supprimer
	*/
	function del_rel_slide($id_rel=NULL){
		if(!empty($id_rel)){
			
			$this->slide_db->connect_db();
			
			$sql		= sprintf("DELETE FROM ".TB."rel_slide_tb WHERE id=%s", func::GetSQLValueString($id_rel,'int'));
			$sqlquery 	= mysql_query($sql) or die(mysql_error());
		}
		
	}
	
	
}
	
	