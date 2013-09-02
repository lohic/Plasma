<?php

include_once('../vars/config.php');
include_once('classe_connexion.php');
include_once('classe_fonctions.php');
include_once('classe_slideshow.php');
//include_once('connexion_vars.php');
//include_once('../vars/statics_vars.php');
//include_once('fonctions.php');

/**
 * 
 */
class Ecran {
	
	var $slide_db		= NULL;
	var $id				= NULL;
	static $updated		= false;
	
	/**
	 * GESTION DES TEMPLATE
	 * @param  [type] $_id [description]
	 * @return [type]      [description]
	 */
	function ecran($_id=NULL){
		global $connexion_info;
		date_default_timezone_set('UTC');
		$this->slide_db		= new connexion($connexion_info['server'],$connexion_info['user'],$connexion_info['password'],$connexion_info['db']);
		
		if(!empty($_id)) $this->id = $_id;
		
		if( self::$updated == false){
			$this->ecran_update_data();
		}
	}
	
	/**
	 * mise à jour des informations d'un écran
	 * @author LOIC
	 * @date 24/07/2012
	 */
	function ecran_update_data(){
		
		// on normalise les données
		// si elles sont présentes tant mieux, sinon on aura un NULL
		
		//!! LOIC
		$id									= !empty($_POST['id_ecran'])?				func::GetSQLValueString($_POST['id_ecran'],'int'):NULL;
		$id_groupe							= !empty($_POST['id_groupe'])?				func::GetSQLValueString($_POST['id_groupe'],'int'):NULL;
		$_array_val['nom']					= !empty($_POST['nom'])?					func::GetSQLValueString($_POST['nom'],'text'):NULL;
		$_array_val['id_etablissement']		= !empty($_POST['id_etablissement'])?		func::GetSQLValueString($_POST['id_etablissement'],'int'):0;
		$_array_val['id_default_slideshow']	= !empty($_POST['id_default_slideshow'])?	func::GetSQLValueString($_POST['id_default_slideshow'],'text'):0;
		$_array_val['id_playlist_locale']	= !empty($_POST['id_playlist_locale'])?		func::GetSQLValueString($_POST['id_playlist_locale'],'text'):0;
		$_array_val['id_playlist_nationale']= !empty($_POST['id_playlist_nationale'])?	func::GetSQLValueString($_POST['id_playlist_nationale'],'text'):0;
		$_array_val['id_groupe']			= !empty($_POST['id_groupe'])?				func::GetSQLValueString($_POST['id_groupe'],'int'):NULL;
		$_array_val['id_groupe_user']		= !empty($_SESSION['id_actual_group'])?		func::GetSQLValueString($_SESSION['id_actual_group'], "int"):NULL;
		
		// dans les formulaires de slide, il faudra
		// prévoir un champ caché update, creat ou suppr suivant le cas de figure
		// « slide » est utilisé ici mais on utilisera « slideshow » ou « ecran » ou autre dans les autres cas
		
		// ECRANS
		if(isset($_POST['create']) && $_POST['create'] == 'ecran'){
			$this->create_ecran($_array_val);			
		}
		
		if(isset($_POST['suppr']) && $_POST['suppr'] == 'ecran'){
			$this->suppr_ecran($id);
		}
		
		if(isset($_POST['update']) && $_POST['update'] == 'ecran'){
			$this->update_ecran($_array_val,$id);
		}
		
		
		// GROUPES
		if(isset($_POST['create']) && $_POST['create'] == 'groupe'){
			$this->create_groupe_ecran($_array_val);			
		}
		
		if(isset($_POST['suppr']) && $_POST['suppr'] == 'groupe'){
			$this->suppr_groupe_ecran($id);
		}
		
		if(isset($_POST['update']) && $_POST['update'] == 'groupe'){
			$this->update_groupe_ecran($_array_val,$id_groupe);
		}
		
		
		// PUBLICATION
		if(isset($_GET['publish']) && $_GET['publish'] == 'ecran'){
			$slideshow = new Slideshow();
			$slideshow->publish_slideshow($_GET['id_plasma'],NULL,NULL,NULL);
			
		}
		if(isset($_GET['publish']) && $_GET['publish'] == 'groupe'){
			$slideshow = new Slideshow();
			$slideshow->publish_slideshow(NULL,$_GET['id_groupe'],NULL,NULL);
		}
		
		self::$updated = true;
	}
	
	
	/**
	 * mise à jour d'un ecran
	 * @param  [type] $_array_val [description]
	 * @param  [type] $id         [description]
	 * @return [type]             [description]
	 */
	function update_ecran($_array_val,$id){
		$this->slide_db->connect_db();
		
		if(!empty($this->id)){
			
			$sql_slide			= sprintf("UPDATE ".TB."ecrans_tb SET nom=%s,
																		id_etablissement=%s,
																		id_default_slideshow=%s,
																		id_playlist_locale=%s,
																		id_playlist_nationale=%s,
																		id_groupe=%s  WHERE id=%s", $_array_val['nom'],
																									$_array_val['id_etablissement'],
																									$_array_val['id_default_slideshow'],
																									$_array_val['id_playlist_locale'],
																									$_array_val['id_playlist_nationale'],
																									$_array_val['id_groupe'],
																									$id);
			$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
			
		}

	}
	
	/**
	 * création d'un ecran
	 * @param  [type] $_array_val [description]
	 * @return [type]             [description]
	 */
	function create_ecran($_array_val){
		$this->slide_db->connect_db();
					
		$sql_slide			= sprintf("INSERT INTO ".TB."ecrans_tb (nom,
																	id_etablissement,
																	id_default_slideshow,
																	id_playlist_locale,
																	id_playlist_nationale,	
																	id_groupe,
																	id_groupe_user) VALUES(%s,%s,%s,%s,%s,%s,%s)",	$_array_val['nom'],
																													$_array_val['id_etablissement'],
																													$_array_val['id_default_slideshow'],
																													$_array_val['id_playlist_locale'],
																													$_array_val['id_playlist_nationale'],
																													$_array_val['id_groupe'],
																													$_array_val['id_groupe_user']);
		$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
		
		$id_last_ecran = mysql_insert_id();
		
		unset($_POST);
		header('Location: ?page=ecrans_groupe_modif&id_groupe='.$_array_val['id_groupe']);

	}
	
	
	/**
	 * mise à jour d'un groupe d'ecrans
	 * @param $_array_val liste des valeurs
	 * @param $_id_groupe id du groupe concerné
	 */
	function update_groupe_ecran($_array_val,$_id_groupe){
		$this->slide_db->connect_db();
		
		if(!empty($_id_groupe)){
			
			$sql_slide			= sprintf("UPDATE ".TB."ecrans_groupes_tb SET nom=%s,
																			id_etablissement=%s,
																			id_playlist_locale=%s,
																			id_playlist_nationale=%s WHERE id=%s", 	$_array_val['nom'],
																													$_array_val['id_etablissement'],
																													$_array_val['id_playlist_locale'],
																													$_array_val['id_playlist_nationale'],
																													$_id_groupe);
			$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
			
		}

	}
	
	/**
	 * [create_groupe_ecran description]
	 * @param  [type] $_array_val [description]
	 * @return [type]             [description]
	 */
	function create_groupe_ecran($_array_val){
		$this->slide_db->connect_db();
					
		$sql_slide			= sprintf("INSERT INTO ".TB."ecrans_groupes_tb (nom,
																			id_etablissement,	
																			id_playlist_locale,
																			id_playlist_nationale,
																			id_groupe_user) VALUES(%s,%s,%s,%s,%s)",	$_array_val['nom'],
																														$_array_val['id_etablissement'],
																														$_array_val['id_playlist_locale'],
																														$_array_val['id_playlist_nationale'],
																														$_array_val['id_groupe_user']);
		$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
		
		$id_last_groupe = mysql_insert_id();
		
		header('Location: ?page=ecrans_groupe_modif&id_groupe='.$id_last_groupe);

	}
	

	
	
	/**
	 * récupère la li *te des alertes d'un groupe ou d'un écran
	 * @param $_type_target ecran ou groupe
	 * @param $_id_groupe id du groupe si groupe
	 * @param $_type_alerte all (nationale ou 75000 code postal)
	 */
	function get_slide_alerte_list($_type_target = 'ecran',$_type_alerte='all',$_id_groupe = NULL){
		$this->slide_db->connect_db();
		
		$retour = '';
		
		if(!empty($this->id) || !empty($_id_groupe)){
			
			if($_type_target == 'groupe'){
				$id = $_id_groupe;
			}else{
				$id = $this->id;	
			}
			
			$sql			= sprintf("SELECT R.id AS id,
										R.id_slide AS id_slide,
										R.id_target AS id_target,
										R.date AS date,
										R.duree AS duree,
										R.freq AS freq,
										R.alerte AS alerte,								
										S.nom AS nom,
										S.template AS template
										FROM ".TB."rel_slide_tb AS R
										LEFT JOIN ".TB."slides_tb AS S
										ON R.id_slide = S.id
										WHERE R.id_target=%s
										AND R.type_target = %s
										AND R.type = 'date'
										AND R.alerte = %s
										ORDER BY date ASC, duree ASC", 	func::GetSQLValueString($id,'int'),
																func::GetSQLValueString($_type_target,'text'),
																func::GetSQLValueString($_type_alerte,'text'));
										
										
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			$nbr			= mysql_num_rows($sql_query);
			
			
			
			while($info = mysql_fetch_assoc($sql_query)){
				
				$duree = $info['duree'];
				$json = json_decode($info['freq']);
				
				$remove = '<a href="#" class="del"><img src="../graphisme/round_minus.png" alt="supprimer un slide" height="16"/></a>';
		
				$temp	= explode(' ',$info['date']);
				$date	= $temp[0];
				$time	= $temp[1];
				$nom	= !empty($info['nom'])?$info['nom']:'choisir';
				$empty 	= !empty($info['nom'])?'':' empty';
				$alerte = $info['alerte'];
		
				$iconeURL= !empty($info['nom'])? ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$info['template'].'/vignette.gif' :'';
				
				$type_slide = 'date';
				
				$is_date = true ;
				$is_freq = false;
				$is_flux = false;
				
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
	 * [get_form_select description]
	 * @return [type] [description]
	 */
	function get_form_select(){
		global $jListe;
		global $JListe;
		global $moisListe;
		
		$M = '*';
		$J = '';
		$j = '*';
		
		$jListe2[''] = '-';
		$jListe2['*'] = '*';
		$jListe2 = $jListe2+$jListe;
		
		$JListe2[''] = '-';
		$JListe2['*'] = '*';
		$JListe2 = $JListe2+$JListe;
		
		$MListe2['*'] = '*';
		$MListe2 = $MListe2+$moisListe;

		$retour = new stdClass();
		
		$retour->M 		= str_replace("\n","",func::createSelect($MListe2, 'M[]', $M, "", false ));
		$retour->j 		= str_replace("\n","",func::createSelect($jListe2, 'j[]', $j, "", false ));
		$retour->J 		= str_replace("\n","",func::createSelect($JListe2, 'J[]', $J, "", false ));
		$retour->slides = str_replace("\n","",func::createSelect($this->get_slides_list(), 'id_slide_freq[]', 1, "", false ));
		$retour->default= 1;
		
		return $retour;
	}
	
	
	/**
	 * récupération des informations d'un ecran
	 * @author LOIC
	 * @date 24/07/2012
	 *
	 */
	function get_info(){

		$retour = new stdClass();
		
		// on initialise pour éviter les valeurs non déclarées dans les formulaires
		$retour->id						= NULL;
		$retour->nom					= NULL;
		$retour->id_etablissement		= NULL;
		$retour->id_groupe				= NULL;
		$retour->id_default_slideshow	= NULL;
		$retour->id_playlist_locale		= NULL;
		$retour->id_playlist_nationale	= NULL;
				
		if(!empty($this->id)){
			
			$this->slide_db->connect_db();
		
			$sql		= sprintf("SELECT *
									FROM ".TB."ecrans_tb AS E
									WHERE E.id=%s",func::GetSQLValueString($this->id,'int'));
			$sql_query	= mysql_query($sql) or die(mysql_error());
			
			$item = mysql_fetch_assoc($sql_query);
			
			$retour->id						= $item['id'];
			$retour->nom					= $item['nom'];
			$retour->id_etablissement		= $item['id_etablissement'];
			$retour->id_groupe				= $item['id_groupe'];
			$retour->id_default_slideshow	= $item['id_default_slideshow'];
			$retour->id_playlist_locale		= $item['id_playlist_locale'];	
			$retour->id_playlist_nationale	= $item['id_playlist_nationale'];	
		}
		
		return $retour;

	}
	
	/**
	 * récupération des informations d'un groupe d'ecrans
	 * @author LOIC
	 * 24/07/2012
	 *
	 */
	function get_groupe_info($_id_groupe = NULL){

		// instanciation de l'objet
		$retour = new stdClass();
		
		// on initialise pour éviter les valeurs non déclarées dans les formulaires
		$retour->id						= NULL;
		$retour->nom					= NULL;
		$retour->id_etablissement		= NULL;
		$retour->id_playlist_locale		= NULL;
		$retour->id_playlist_nationale	= NULL;	
				
		if(!empty($_id_groupe)){
			
			$this->slide_db->connect_db();
		
			$sql		= sprintf("SELECT *
									FROM ".TB."ecrans_groupes_tb AS E
									WHERE E.id=%s",func::GetSQLValueString($_id_groupe,'int'));
			$sql_query	= mysql_query($sql) or die(mysql_error());
			
			$item = mysql_fetch_assoc($sql_query);
			
			$retour->id						= $item['id'];
			$retour->nom					= $item['nom'];
			$retour->id_etablissement		= $item['id_etablissement'];	
			$retour->id_playlist_locale		= $item['id_playlist_locale'];	
			$retour->id_playlist_nationale	= $item['id_playlist_nationale'];			
		}
		
		return $retour;

	}
	
	/**
	 * liste des établissements
	 * @author LOIC
	 * 24/07/2012
	 *
	 */
	function get_etablissement_list(){		
			
			$this->slide_db->connect_db();
		
			$sql		= sprintf("SELECT *	FROM ".TB."etablissements_tb");
			$sql_query	= mysql_query($sql) or die(mysql_error());
			
			//$item = mysql_fetch_assoc($sql_query);
			
			$retour = array();
			
			while($item = mysql_fetch_assoc($sql_query)){
				
				$retour[$item['id']] = $item['nom'];
				
			}
					
			return $retour;
	}
	
	
	/**
	 * liste des groupes d'écrans
	 * @author LOIC
	 * 24/07/2012
	 *
	 */
	function get_ecran_groupe_list(){		
			
			$this->slide_db->connect_db();
		
			$sql		= sprintf("SELECT *	FROM ".TB."ecrans_groupes_tb");
			$sql_query	= mysql_query($sql) or die(mysql_error());
			
			//$item = mysql_fetch_assoc($sql_query);
			
			$retour = array();
			
			while($item = mysql_fetch_assoc($sql_query)){
				
				$retour[$item['id']] = $item['nom'];
				
			}
					
			return $retour;
	}
	
	
	/**
	 * liste des slideshows
	 * @author LOIC
	 * 24/07/2012
	 *
	 */
	function get_playlist_list(){		
			
			$this->slide_db->connect_db();
		
			$sql		= sprintf("SELECT *	FROM ".TB."playlist_tb");
			$sql_query	= mysql_query($sql) or die(mysql_error());
			
			//$item = mysql_fetch_assoc($sql_query);
			
			$retour = array();
			
			while($item = mysql_fetch_assoc($sql_query)){
				
				$retour[$item['id']] = 'Playlist : '.$item['nom'];
				
			}
					
			return $retour;
	}
	
	
	/**
	 * liste des slides
	 * @author LOIC
	 * 24/07/2012
	 *
	 */
	function get_slides_list(){		
			
			$this->slide_db->connect_db();
		
			$sql		= sprintf("SELECT *	FROM ".TB."slides_tb");
			$sql_query	= mysql_query($sql) or die(mysql_error());
			
			//$item = mysql_fetch_assoc($sql_query);
			
			$retour = array();
			
			while($item = mysql_fetch_assoc($sql_query)){
				
				$retour[$item['id']] = $item['nom'];
				
			}
					
			return $retour;
	}
	
	/**
	 * liste des groupes d'écrans pour administration
	 * @author LOIC
	 * 24/07/2012
	 *
	 */
	function get_admin_ecran_groupe_list(){		
			
			if(empty($_GET['code_postal'])){
				$code_postal = '75000';
			}else{
				$code_postal = $_GET['code_postal'];
			}

			$this->slide_db->connect_db();
			
			$sql		= sprintf("SELECT G.id, G.nom FROM ".TB."ecrans_groupes_tb G, ".TB."etablissements_tb E
									WHERE E.id = G.id_etablissement
									AND E.code_postal=%s", func::GetSQLValueString($code_postal,'int'));
		
			
			$sql_query	= mysql_query($sql) or die(mysql_error());
			
			//$item = mysql_fetch_assoc($sql_query);
			
			$retour = array();			
			
			$i = 0;
			
			while($item = mysql_fetch_assoc($sql_query)){
				$id = $item['id'];
				$nom = $item['nom'];
				
				$ecrans_data = $this->get_admin_ecran_list($item['id']);
				
				$ecrans = $ecrans_data->ecrans;
				$nombre_ecran = $ecrans_data->nombre;
				
				$class_groupe = 'listItemRubrique'.($i+1);
				
				$i = ($i+1)%2;
				
				include('../structure/ecran-groupe-list-bloc.php');
				//$retour[$item['id']] = $item['nom'];
				
			}
	}
	
	/**
	 * liste des écrans pour administration (par groupe d'écran)
	 * @author Loïc Horellou
	 * @param $id_groupe
	 * @return HTML de la liste des écrans
	 */
	function get_admin_ecran_list($id_groupe=NULL){		
		$retour = new stdClass();
		
		if(isset($id_groupe)){	
			$this->slide_db->connect_db();
		
			$sql		= sprintf("	SELECT E.id AS id, E.nom AS nom, Et.ville AS ville
									FROM ".TB."ecrans_tb AS E, ".TB."etablissements_tb AS Et
									WHERE E.id_groupe=%s
									AND E.id_etablissement=Et.id",func::GetSQLValueString($id_groupe,'int'));
									
			$sql_query	= mysql_query($sql) or die(mysql_error());
			
			
			
			
			$i = 1;
			
			ob_start();
			
			$groupe = $id_groupe;

			include('../structure/ecran-list-add-bloc.php');
			
			while($item = mysql_fetch_assoc($sql_query)){
					
				$id = $item['id'];
				$nom = $item['nom'];
				$ville = $item['ville'];
				$class = 'item-'.($i+1);
				$icone = "../graphisme/monitor-48.png";
				
				$i = ($i+1)%2;
				
				include('../structure/ecran-list-bloc.php');
				//$retour[$item['id']] = $item['nom'];
				
			}
			$retour->ecrans = ob_get_contents();
			$retour->nombre = mysql_num_rows($sql_query);
			
			ob_end_clean();
		}
		return $retour;
	}
	
	
	/**
	 * [get_next_slide description]
	 * @param  [type] $_id_screen           [description]
	 * @param  [type] $_id_actual_slide     [description]
	 * @param  [type] $_id_actual_slideshow [description]
	 * @return [type]                       [description]
	 */
	function get_next_slide($_id_screen=NULL,$_id_actual_slide=NULL,$_id_actual_slideshow=NULL){
		$this->slide_db->connect_db();

		if(!empty($_id_screen) && !empty($_id_actual_slide) && !empty($_id_actual_slideshow)){
		
			$ladate = date("Y-m-d");	
		
				
		}
		
	}
	
	/**
	 * METTRE A JOUR OU CREER UNE LIAISON SLIDE->ECRAN en mode freq ou date
	 * cf classe SLIDESHOW pour LIAISON SLIDE->SLIDESHOW
	 * @param  [type] $_array_val [description]
	 * @param  [type] $id_rel     [description]
	 * @return [type]             [description]
	 */
	function update_rel_slide($_array_val=NULL,$id_rel = NULL){
		//if(!empty($this->id)){
			
			$this->slide_db->connect_db();
			
			$id_slide	= !empty($_array_val['id_slide'])?		$_array_val['id_slide'] :		NULL;
			$id_target	= !empty($_array_val['id_target'])?		$_array_val['id_target'] :		NULL;
			$type_target= !empty($_array_val['type_target'])?	$_array_val['type_target'] :	'ecran';			
			$date		= !empty($_array_val['date'])?			$_array_val['date'] :			'0000-00-00 00:00:00';
			$duree		= !empty($_array_val['duree'])?			$_array_val['duree'] :			'00:00:00';
			$freq		= !empty($_array_val['freq'])?			$_array_val['freq'] :			NULL;
			$status		= NULL;
			$type		= !empty($_array_val['type'])?			$_array_val['type'] :			'date';
			$ordre		= NULL;
			$alerte		= 0;
			
			if(!empty($id_rel)){
				$sql		= sprintf("UPDATE ".TB."rel_slide_tb SET id_slide=%s, id_target=%s, type_target=%s, date=%s, duree=%s, freq=%s, status=%s, type=%s, ordre=%s, alerte=%s   WHERE id=%s",
																																					func::GetSQLValueString($id_slide,'int'),
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
				$sql		= sprintf("INSERT INTO ".TB."rel_slide_tb (id_slide, id_target, type_target, date, duree, freq, status, type,ordre, alerte) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
																																					func::GetSQLValueString($id_slide,'int'),
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
	 * supprime les slides relieés à un écran ou à un groupe
	 * !!! il faudra passer toutes ces fonctions dans la classe slide ou alors faire une classe rel_slide
	 * pour uniformiser la gestion des relations des playlist, des écrans et des groupes d'écrans
	 * @param  [type] $id_rel [description]
	 * @return [type]         [description]
	 */
	function del_rel_slide($id_rel=NULL){
		if(!empty($id_rel)){
			
			$this->slide_db->connect_db();
			
			$sql		= sprintf("DELETE FROM ".TB."rel_slide_tb WHERE id=%s", func::GetSQLValueString($id_rel,'int'));
			$sqlquery 	= mysql_query($sql) or die(mysql_error());
		}
		
	}
	
	
}
	
