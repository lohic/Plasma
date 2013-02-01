<?php

include_once('../vars/config.php');
include_once('classe_connexion.php');
include_once('classe_fonctions.php');
include_once('classe_slideshow.php');
//include_once('connexion_vars.php');
//include_once('../vars/statics_vars.php');
//include_once('fonctions.php');


class Ecran {
	
	var $slide_db		= NULL;
	var $id				= NULL;
	
	/*
	@ GESTION DES TEMPLATE
	@
	@
	*/
	function ecran($_id=NULL){
		global $connexion_info;
		date_default_timezone_set('UTC');
		$this->slide_db		= new connexion($connexion_info['server'],$connexion_info['user'],$connexion_info['password'],$connexion_info['db']);
		
		if(!empty($_id)) $this->id = $_id;
		
		
		$this->ecran_update_data();
	}
	
	/*
	@ mise à jour des informations d'un écran
	@ LOIC
	@ 24/07/2012
	*/
	function ecran_update_data(){
		
		// on normalise les données
		// si elles sont présentes tant mieux, sinon on aura un NULL
		
		//!! LOIC
		$id									= !empty($_POST['id_ecran'])?				func::GetSQLValueString($_POST['id_ecran'],'int'):NULL;
		$id_groupe							= !empty($_POST['id_groupe'])?				func::GetSQLValueString($_POST['id_groupe'],'int'):NULL;
		$_array_val['nom']					= !empty($_POST['nom'])?					func::GetSQLValueString($_POST['nom'],'text'):NULL;
		$_array_val['id_etablissement']		= !empty($_POST['id_etablissement'])?		func::GetSQLValueString($_POST['id_etablissement'],'int'):NULL;
		$_array_val['id_default_slideshow']	= !empty($_POST['id_default_slideshow'])?	func::GetSQLValueString($_POST['id_default_slideshow'],'text'):NULL;
		$_array_val['id_groupe']			= !empty($_POST['id_groupe'])?				func::GetSQLValueString($_POST['id_groupe'],'int'):NULL;
		
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
		if(isset($_POST['publish']) && $_POST['publish'] == 'ecran'){
			$slideshow = new Slideshow();
			$slideshow->publish_slideshow($_POST['id_plasma'],NULL,NULL,NULL);
			
		}
		if(isset($_POST['publish']) && $_POST['publish'] == 'groupe'){
			$slideshow = new Slideshow();
			$slideshow->publish_slideshow(NULL,$_POST['id_groupe'],NULL,NULL);
		}
		
	}
	
	
	/*
	@ mise à jour d'un ecran
	@
	@
	*/
	function update_ecran($_array_val,$id){
		$this->slide_db->connect_db();
		
		if(!empty($this->id)){
			
			$sql_slide			= sprintf("UPDATE ".TB."ecrans_tb SET nom=%s, id_etablissement=%s, id_default_slideshow=%s, id_groupe=%s  WHERE id=%s", $_array_val['nom'],
																																						$_array_val['id_etablissement'],
																																						$_array_val['id_default_slideshow'],
																																						$_array_val['id_groupe'],
																																						$id);
			$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
			
		}

	}
	
	/*
	@ création d'un ecran
	@
	@
	*/
	function create_ecran($_array_val){
		$this->slide_db->connect_db();
					
		$sql_slide			= sprintf("INSERT INTO ".TB."ecrans_tb (nom, id_etablissement, id_default_slideshow, id_groupe) VALUES(%s,%s,%s,%s)", $_array_val['nom'],
																																					$_array_val['id_etablissement'],
																																					$_array_val['id_default_slideshow'],
																																					$_array_val['id_groupe']);
		$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
		
		$id_last_ecran = mysql_insert_id();
		
		unset($_POST);
		header('Location: ?page=ecrans_modif&id_plasma='.$id_last_ecran);

	}
	
	
	/*
	@ mise à jour d'un groupe d'ecrans
	@
	@
	*/
	function update_groupe_ecran($_array_val,$_id_groupe){
		$this->slide_db->connect_db();
		
		if(!empty($_id_groupe)){
			
			$sql_slide			= sprintf("UPDATE ".TB."ecrans_groupes_tb SET nom=%s, id_slideshow=%s WHERE id=%s", $_array_val['nom'],
																													$_array_val['id_default_slideshow'],
																													$_id_groupe);
			$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
			
		}

	}
	
	/*
	@ création d'un groupe d'ecrans
	@
	@
	*/
	function create_groupe_ecran($_array_val){
		$this->slide_db->connect_db();
					
		$sql_slide			= sprintf("INSERT INTO ".TB."ecrans_groupes_tb (nom, id_slideshow) VALUES(%s,%s)", $_array_val['nom'],
																												$_array_val['id_default_slideshow']);
		$sql_slide_query 	= mysql_query($sql_slide) or die(mysql_error());
		
		$id_last_groupe = mysql_insert_id();
		
		header('Location: ?page=ecrans_groupe_modif&id_groupe='.$id_last_groupe);

	}
	
	
	
	/*
	@ RECUPERE LA LISTE DES SLIDES EN MODE FREQUENCE
	@
	@
	@
	*/
	function get_slide_freq_list($_type_target='ecran',$_id_groupe = NULL){
		$this->slide_db->connect_db();
				
		global $jListe;
		global $JListe;
		global $moisListe;
		
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
										R.duree AS duree,
										R.freq AS freq,
										S.nom AS nom,
										S.template AS template
										FROM ".TB."rel_slide_tb AS R
										LEFT JOIN ".TB."slides_tb AS S
										ON R.id_slide = S.id
										WHERE R.id_target=%s
										AND R.type_target = %s
										AND R.type = 'freq'
										ORDER BY freq ASC, duree ASC", 	func::GetSQLValueString($id,'int'),
																func::GetSQLValueString($_type_target,'text'));
										
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			$nbr			= mysql_num_rows($sql_query);
			
			
			while($info = mysql_fetch_assoc($sql_query)){
				
				$duree = $info['duree'];
				$json = json_decode($info['freq']);
				
				$remove = '<a href="#" class="del"><img src="../graphisme/round_minus.png" alt="supprimer un slide" height="16"/></a>';
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
				
				
				$nom	= !empty($info['nom'])?$info['nom']:'choisir';
				$empty 	= !empty($info['nom'])?'':' empty';
				$slides	 = '<input type="hidden" value="'.$info['id_slide'].'" name="id_slide[]" class="id_slide"/><a class="slidelistselect'.$empty.'">'.$nom.'</a>';
				$iconeURL= !empty($info['nom'])? ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$info['template'].'/vignette.gif' : '';
				
				$retour .= '<li><input class="id_rel" type="hidden" name="id_rel[]" value="'.$info['id'].'" /><input type="hidden" name="timestamp[]" value="" /><input type="hidden" name="typerel[]" value="freq" /><input type="hidden" name="date[]" value="" /><input type="hidden" name="time[]" value="" />'.$remove.'<img src="'.$iconeURL.'" width="28" height="18" class="icone" /><span>'.$MSelect.$JSelect.$jSelect.'</span> <span>horaire : <input name="H[]" type="text" value="'.$json->H.'" class="timeslide"/></span> <span>durée : <input name="duree[]" type="text" value="'.$duree.'" class="dureeslide"/></span> <span><a href="../slideshow/?slide_id='.$info['id_slide'].'&preview" target="_blank" class="preview"><img src="../graphisme/eye.png" alt="voir"/></a></span> '.$slides.'	</li>';
				
			}
		}
		
		
		return $retour;
	}
	
	
	/*
	@ RECUPERE LA LISTE DES SLIDES EN MODE DATE
	@
	@
	@
	*/
	function get_slide_date_list($_type_target = 'ecran',$_id_groupe = NULL){
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
										S.nom AS nom,
										S.template AS template
										FROM ".TB."rel_slide_tb AS R
										LEFT JOIN ".TB."slides_tb AS S
										ON R.id_slide = S.id
										WHERE R.id_target=%s
										AND R.type_target = %s
										AND R.type = 'date'
										ORDER BY date ASC, duree ASC", 	func::GetSQLValueString($id,'int'),
																func::GetSQLValueString($_type_target,'text'));
										
										
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
		
				$slides	 = '<input type="hidden" value="'.$info['id_slide'].'" name="id_slide[]" class="id_slide"/><a class="slidelistselect'.$empty.'">'.$nom.'</a>';
				$iconeURL= !empty($info['nom'])? ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$info['template'].'/vignette.gif' :'';
				
				
				$retour .= '<li><input class="id_rel" type="hidden" name="id_rel[]" value="'.$info['id'].'" /><input type="hidden" name="timestamp[]" value="" /><input type="hidden" name="typerel[]" value="date" /><input type="hidden" name="M[]" value="" /><input type="hidden" name="J[]" value="" /><input type="hidden" name="j[]" value="" /><input type="hidden" name="H[]" value="" />'.$remove.'<img src="'.$iconeURL.'" width="28" height="18" class="icone" /><span> <span>date : <input name="date[]" type="text" value="'.$date.'" class="dateslide"/></span></span> <span>horaire : <input type="text" name="time[]" value="'.$time.'" class="timeslide" /> <span>durée : <input name="duree[]" type="text" value="'.$duree.'" class="dureeslide"/></span> <span><a href="../slideshow/?slide_id='.$info['id_slide'].'&preview" target="_blank" class="preview"><img src="../graphisme/eye.png" alt="voir"/></a></span> '.$slides.'	</li>';
				
			}
		}
		
		return $retour;
	}
	
	
	/*
	@
	@
	@
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
		
		$retour->M 		= str_replace("\n","",func::createSelect($MListe2, 'M[]', $M, "", false ));
		$retour->j 		= str_replace("\n","",func::createSelect($jListe2, 'j[]', $j, "", false ));
		$retour->J 		= str_replace("\n","",func::createSelect($JListe2, 'J[]', $J, "", false ));
		$retour->slides = str_replace("\n","",func::createSelect($this->get_slides_list(), 'id_slide_freq[]', 1, "", false ));
		$retour->default= 1;
		
		return $retour;
	}
	
	
	/*
	@ récupération des informations d'un ecran
	@ LOIC
	@ 24/07/2012
	@
	*/
	function get_info(){
		
		// on initialise pour éviter les valeurs non déclarées dans les formulaires
		$retour->id						= NULL;
		$retour->nom					= NULL;
		$retour->id_etablissement		= NULL;
		$retour->id_groupe				= NULL;
		$retour->id_default_slideshow	= NULL;
				
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
		}
		
		return $retour;

	}
	
	/*
	@ récupération des informations d'un groupe d'ecrans
	@ LOIC
	@ 24/07/2012
	@
	*/
	function get_groupe_info($_id_groupe = NULL){
		
		// on initialise pour éviter les valeurs non déclarées dans les formulaires
		$retour->id						= NULL;
		$retour->nom					= NULL;
		$retour->id_default_slideshow	= NULL;
				
		if(!empty($_id_groupe)){
			
			$this->slide_db->connect_db();
		
			$sql		= sprintf("SELECT *
									FROM ".TB."ecrans_groupes_tb AS E
									WHERE E.id=%s",func::GetSQLValueString($_id_groupe,'int'));
			$sql_query	= mysql_query($sql) or die(mysql_error());
			
			$item = mysql_fetch_assoc($sql_query);
			
			$retour->id						= $item['id'];
			$retour->nom					= $item['nom'];
			$retour->id_default_slideshow	= $item['id_slideshow'];				
		}
		
		return $retour;

	}
	
	/*
	@ liste des établissements
	@ LOIC
	@ 24/07/2012
	@
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
	
	
	/*
	@ liste des groupes d'écrans
	@ LOIC
	@ 24/07/2012
	@
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
	
	
	/*
	@ liste des slideshows
	@ LOIC
	@ 24/07/2012
	@
	*/
	function get_slideshow_list(){		
			
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
	
	
	/*
	@ liste des slides
	@ LOIC
	@ 24/07/2012
	@
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
	
	/*
	@ liste des groupes d'écrans pour administration
	@ LOIC
	@ 24/07/2012
	@
	*/
	function get_admin_ecran_groupe_list(){		
			
			$this->slide_db->connect_db();
		
			$sql		= sprintf("SELECT *	FROM ".TB."ecrans_groupes_tb");
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
	
	/*
	@ liste des écrans pour administration (par groupe d'écran)
	@ LOIC
	@ 24/07/2012
	@
	*/
	function get_admin_ecran_list($id_groupe=NULL){		
		$retour = "";
		
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
	
	
	function get_next_slide($_id_screen=NULL,$_id_actual_slide=NULL,$_id_actual_slideshow=NULL){
		$this->slide_db->connect_db();

		if(!empty($_id_screen) && !empty($_id_actual_slide) && !empty($_id_actual_slideshow)){
		
			$ladate = date("Y-m-d");	
		
				
		}
		
	}
	
	/*
	@ METTRE A JOUR OU CREER UNE LIAISON SLIDE->ECRAN en mode freq ou date
	@ cf classe SLIDESHOW pour LIAISON SLIDE->SLIDESHOW
	@
	@
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
	
	function del_rel_slide($id_rel=NULL){
		if(!empty($id_rel)){
			
			$this->slide_db->connect_db();
			
			$sql		= sprintf("DELETE FROM ".TB."rel_slide_tb WHERE id=%s", func::GetSQLValueString($id_rel,'int'));
			$sqlquery 	= mysql_query($sql) or die(mysql_error());
		}
		
	}
	
	
}
	
	

?>