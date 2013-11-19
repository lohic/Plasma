<?php


include_once('../vars/config.php');
include_once(REAL_LOCAL_PATH.'classe/classe_connexion.php');
include_once(REAL_LOCAL_PATH.'classe/classe_slide.php');
include_once(REAL_LOCAL_PATH.'classe/classe_fonctions.php');
		
/**
 * 
 */
class Slideshow {
	
	var $slide_db			= NULL;
	var $id_ecran			= NULL;
	
	var $ecran				= NULL;
	
	private static $order_slide_by	= 'date';
	private static $order_ASC		= true;
	private static $updated_screen  = array();
	
	/**
	 * slideshow constructeur de la fonction pour gérer l'affichage du slideshow d'un écran. Le slideshow est composé de slides ou de playlists
	 * @author Loïc Horellou
	 * @since v0.1
	 * @version v0.5 20/12/2012
	 * @param $_id_ecran id de l'écran que l'on souhaite afficher
	 */
	function slideshow($_id_ecran=NULL){

		global $connexion_info;
		date_default_timezone_set('Europe/Paris');
		$this->slide_db		= new connexion($connexion_info['server'],$connexion_info['user'],$connexion_info['password'],$connexion_info['db']);
	
		
		if(!empty($_id_ecran)){
			

			// instanciation des objets
			if(empty($this->ecran)){ $this-> ecran = (object)array(); }

			$this->ecran->id = $_id_ecran;
			
			$this->ecran = $this->get_ecran_info();
			
			$this->debugger = "";
		
		}
	}
	
	/**
	 * debug sert à aggréger les différents retours du mode debug
	 * @author Gildas Paubert
	 * @since v0.3
	 * @param $txt variable de texte à incrementer pour le retour du debug
	 */
	function debug($txt=''){
		//if($this->debugger == ""){ $this->debugger .= "<strong>Slideshow debugger</strong><br />"; }
		$this->debugger .= $txt.'
		<br />';
	}
	

	/**
	* sert à réiniitlaliser le debug
	*/
	function debug_reset(){
		$this->debugger = '';
	}
	
	
	/**
	 * permet le lancement d'un slideshow
	 * @author Loïc Horellou
	 * @since v0.1
	 * @see Slideshow::generate_slide_page
	 * @param $ispreview sert a afficher si on est en mode preview ou lecture (si lecture le curseur souris est masqué)
	 * @param $isdebug précise si on doit afficher le mode debug ou pas (false par defaut / true) 
	 */
	function run($ispreview=false,$isdebug =false,$istiny =false){
		//$this->slide_db->connect_db();

		echo $this->generate_slide_page($ispreview,$isdebug,$istiny);

		//$i = ($this->get_next_slide_id());
		/*
		echo "<p>slide :  $i->id_slide </p>";
		echo "<p>slideshow : $i->id_slideshow </p>";
		echo "<p>ordre : $i->ordre </p>";
		echo "<p>duree : $i->duree s</p>";
		*/
	}
	
	
	/**
	 * récupère le code HTML provenant du prochain slide (récupère en cahce le résultat de generate_slide)
	 * @author Loïc Horellou
	 * @since v0.1
	 * @see Slideshow::generate_slide
	 * @param $ispreview sert a afficher si on est en mode preview ou lecture (si lecture le curseur souris est masqué)
	 * @param $isdebug précise si on doit afficher le mode debug ou pas (false par defaut / true) 
	 */
	function generate_slide_page($ispreview=false,$isdebug=false,$istiny=false){
		//$this->slide_db->connect_db();

		//$le_slide = $this->generate_slide($isdebug);
		
		//$plasma_id = $this->ecran->id;
		//
		//
		$ecran = new stdClass();
		/*$ecran->id					= $this->ecran->id;
		$ecran->nom					= $this->ecran->nom;
		$ecran->id_etablissement	= $this->ecran->id_etablissement;
		$ecran->id_groupe			= $this->ecran->id_groupe;
		$ecran->code_postal			= $this->ecran->code_postal;
		$ecran->code_meteo			= $this->ecran->code_meteo;*/
		$ecran = $this->ecran;

		//
		$class = array();
		if($ispreview) $class[] = 'preview';
		if($isdebug) $class[] = 'debug';
		if($istiny) $class[] = 'tiny';
		$class = implode(' ', $class);
		
		$contents ='';
		ob_start();
		include_once(REAL_LOCAL_PATH.'structure/slide-generate.php') ;
		$contents .= ob_get_contents();
		ob_end_clean();
		
		return $contents;
		
	}
	
	/**
	 * generate_slide sert à générer l'affichage d'un slide
	 * @author Loïc Horellou
	 * @since v0.1
	 * @version v0.5 20/12/2012
	 * @param $isdebug précise si on doit afficher le mode debug ou pas (false par defaut / true)
	 * @return HTML pour le mode debug si activé
	 */
	function generate_slide($isdebug=false){

		$this->debug("ecran / id:".$this->ecran->id);
		
		$next_slide_info = $this->get_next_slide_id(); // ressert en fin de fonction
		// si on n'a pas de slide $next_slide_info vaudra false
		
		
		if($next_slide_info != false){
			$slide = new Slide($next_slide_info->id_slide);
			
			$info = $slide->get_slide_info();
			
			//var_dump($info);
			
			/*
			$retour->id					= $slide_item['id'];
			$retour->nom				= $slide_item['nom'];
			$retour->template			= $slide_item['template'];
			$retour->json				= $slide_item['json'];
			$retour->icone				= ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$slide_item['template'].'/vignette.gif';
			$retour->chemin				= LOCAL_PATH.SLIDE_TEMPLATE_FOLDER.$slide_item['template'].'/index.php';
			*/
					
			$contents = "";
			
			// html
			ob_start();
			// euh pourquoi empty ??? LOIC
			if(!empty($info->json))
				include_once($info->chemin);
			$contents .= ob_get_contents();
			ob_end_clean();
			
			// nettoyage du code pour un affichage publique
			$contents = preg_replace('# max="(.*)"#isU', '', $contents);
			$contents = preg_replace('# alt="(.*)"#isU', '', $contents);
			$contents = preg_replace('# title="(.*)"#isU', '', $contents);	
			$contents = preg_replace('#( ?)(textarea|textfield|checkbox|hidden|image|edit|listmenu|radiobutton|date)( ?)#isU', '', $contents);
			
			// remplissage de la template
			$json = $info->json;
			$json = stripslashes($json);
			$json = json_decode($json);
			
			$images_url = addslashes(ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$info->template.'/images/');
			$font_url	= addslashes(ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.$info->template.'/fonts/');
			
			if(!empty($info->json))
				foreach ($json as $name => $value) {
					$contents = preg_replace('#id="'.$name.'">(.*)<\/#isU', 'id="'.$name.'">'.$value.'</', $contents);
				} // c'est rempli !
			
			// css (avant)
			ob_start();
			// euh pourquoi empty ??? LOIC
			//if(!empty($info->json))
				include_once($info->css);
			$contents = '<style type="text/css">'.ob_get_contents().'</style>'.$contents;
			ob_end_clean();
			// pour mettre à jour les liens des images en CSS
			$contents = str_replace('url("images/','url("'.$images_url,$contents);
			$contents = str_replace('url(\'fonts/','url(\''.$font_url,$contents);
			
			
			// js du template (après)
			ob_start();
			// euh pourquoi empty ??? LOIC
			//if(!empty($info->json))
				include_once($info->script);
			$contents .= '<script language="javascript">'.ob_get_contents().'</script>';
			ob_end_clean();	
			
			// slide suivant dans X milisecondes	
			$duree = ($next_slide_info->duree);	
			$this->debug('durée: '.$duree.' sec <span id="compteur" style="display:block; width:300px; height:10px; background-color:#000; margin-top:10px;"></span>');
			$duree *= 1000;
			
			// on créé un timer pour la durée du slide moins 2 sec
			// à 2 sec de la fin on ajoute une classe .exit au div #template 
	
			// js général pour tout slideshow 
			ob_start();
				include_once(REAL_LOCAL_PATH.'structure/slideshow-javascript.php');
				$contents .= ob_get_contents();
			ob_end_clean();	
			
			
			// on vérifie qu'on est pas en mode test d'un slide sinon
			// maj de l'index de la playlist, puisqu'on vient de sauter au slide suivant
			
			$send = array();
			$send['id_last_slide'] 			= $info->id;
			$send['id_last_slideshow']		= $next_slide_info->id_playlist;
			$send['order_last_slide']		= $next_slide_info->ordre;
			
			$this->update_ecran_info($send);
			
			
			$debug = $this->debugger;
			//
			if($isdebug){
				return '<div id="debug">'.$debug.'</div>'.$contents;	
			}else{
				return $contents;
			}
		}else{
			//echo 'pas de slide';
			include_once (REAL_LOCAL_PATH.'structure/slideshow-javascript.php');
			include_once (REAL_LOCAL_PATH.'structure/default-slide.php');
			
			// dans ce cas on affiche le nom de l'écran et on continue à scanner
		}
	}
	
	
	/**
	* order_slideshow_json sert à trier les données JSON d'un écran
	*/
	private static function order_slideshow_json($a, $b){
		//return $a->id_slide < $b->id_slide ? -1 : $a->id_slide == $b->id_slide ? 0 : 1;
		if ($a->{self::$order_slide_by} == $b->{self::$order_slide_by}) {
			return 0;
		}
		if(self::$order_ASC){
			return ($a->{self::$order_slide_by} < $b->{self::$order_slide_by}) ? -1 : 1;
		}else{
			return ($a->{self::$order_slide_by} < $b->{self::$order_slide_by}) ? 1 : -1;
		}
	}
	


	/**
	 * get_ecran_info récupère les dernières informations d'un écran
	 * @author Loïc Horellou
	 * @since 0.1
	 * @return un objet contenant id_groupe, id_last_slide, id_last_slideshow, order_last_slide, code_meteo
	 */
	function get_ecran_info(){
		
		if(!empty($this->ecran->id)){
			
			$sql = sprintf("SELECT	P.nom,
									P.id_etablissement,
									P.id_groupe,
									P.id_last_slide,
									P.order_last_slide,
									P.id_playlist_locale AS id_ecran_playlist_locale,
									P.id_playlist_nationale AS id_ecran_playlist_nationale,
									G.id_playlist_locale AS id_groupe_playlist_locale,
									G.id_playlist_nationale AS id_groupe_playlist_nationale,
									E.code_postal,
									E.code_meteo,
									G.id_slideshow,
									(SELECT json_data FROM ".TB."slideshows_tb WHERE id_ecran = %s ORDER BY date_publication DESC LIMIT 0,1) AS json,
									(SELECT date_publication FROM ".TB."slideshows_tb WHERE id_ecran = %s ORDER BY date_publication DESC LIMIT 0,1) AS actual_date_json
							FROM ".TB."ecrans_tb AS P,
							".TB."etablissements_tb AS E,
							".TB."ecrans_groupes_tb AS G
							WHERE P.id=%s
							AND P.id_etablissement = E.id
							AND P.id_groupe = G.id", func::GetSQLValueString($this->ecran->id,'int'),
													 func::GetSQLValueString($this->ecran->id,'int'),
													 func::GetSQLValueString($this->ecran->id,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			$info 			= mysql_fetch_assoc($sql_query);
			
			// instanciation des objets
			if(empty($this->ecran)){ $this-> ecran = (object)array(); }
			$retour = (object)array();

			$retour->id								= $this->ecran->id;
			$retour->nom							= $info['nom'];
			$retour->id_etablissement				= $info['id_etablissement'];
			$retour->id_groupe						= $info['id_groupe'];
			$retour->id_last_slide					= $info['id_last_slide'];
			$retour->order_last_slide				= $info['order_last_slide'];
			$retour->code_postal					= $info['code_postal'];
			$retour->code_meteo						= $info['code_meteo'];
			$retour->id_ecran_playlist_locale		= $info['id_ecran_playlist_locale'];
			$retour->id_ecran_playlist_nationale	= $info['id_ecran_playlist_nationale'];
			$retour->id_groupe_playlist_locale		= $info['id_groupe_playlist_locale'];
			$retour->id_groupe_playlist_nationale	= $info['id_groupe_playlist_nationale'];
			$retour->id_groupe_playlist 			= $info['id_slideshow'];
			$retour->json							= $info['json'];
			$retour->actual_date_json				= $info['actual_date_json'];
			
			return $retour;
		}
		
	}
	
	/**
	 * mets à jour les informations d'un écran, met à jour les informations de l'écran après une lecture, sauvegarde les id du dernier slide, slideshow et ordre
	 * attention dasn ce cas le concept de slideshow a été remplacé par playlist (on met à jour la dernière playlist ayant été jouée)
	 * @author Loïc Horellou
	 * @since 0.1	
	 * $param un tableau contenant id_last_slide, id_last_slideshow, ordre_last_slide
	 */
	function update_ecran_info($tab=NULL){
		if(!empty($this->ecran->id) && !empty($tab)){
			$this->slide_db->connect_db();
			$sql = sprintf("UPDATE ".TB."ecrans_tb
							SET id_last_slide=%s, id_last_slideshow=%s, order_last_slide=%s
							WHERE id=%s",	func::GetSQLValueString($tab['id_last_slide'],'int'),
											func::GetSQLValueString($tab['id_last_slideshow'],'int'),
											func::GetSQLValueString($tab['order_last_slide'],'int'),
											func::GetSQLValueString($this->ecran->id,'int'));
			$sql_query = mysql_query($sql) or die(mysql_error());
			//echo ' // '.$sql.' // ';
		}
	}
	
	
	
	/**
	 * publish_slideshow sert à publier les différents slides attachés à un écran, un groupe, un établissement ou l'ensemble des écrans
	 * @param  int $update_ecran_id         id de l'écran à archiver
	 * @param  int $update_groupe_id        id du groupe d'écrans à archiver
	 * @param  int $update_etablissement_id id de l''établissement à archiver
	 * @param  boolean $update_all          true si on souhaite publier tous les slides et playlists 
	 * @return void                         Rien n'est retourné
	 */
	function publish_slideshow($update_ecran_id=NULL, $update_groupe_id=NULL, $update_etablissement_id=NULL, $update_all=false){
		
		$retour = new stdClass();

		if(!empty($update_ecran_id) && $update_all != true){
			// on arvhive l'écran dont l'id est spécifié
			$this->archive_ecran($update_ecran_id);
		}
		
		if(!empty($update_groupe_id) && $update_all != true){
			$sql = sprintf("SELECT id
							FROM ".TB."ecrans_tb
							WHERE id_groupe=%s", func::GetSQLValueString($update_groupe_id,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			
			// on archive tous les écrans du groupe d'écrans
			while($info = mysql_fetch_assoc($sql_query)){				
				$this->archive_ecran($info['id']);
			}

			mysql_free_result($sql_query);

			$sql = sprintf("SELECT last_publication
							FROM ".TB."ecrans_groupes_tb
							WHERE id=%s", func::GetSQLValueString($update_groupe_id,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());
			$info 			= mysql_fetch_assoc($sql_query);

			$retour->last_publication = func::dateFormat($info['last_publication']);
		}
		
		if(!empty($update_etablissement_id) && $update_all != true){
			$sql = sprintf("SELECT id
							FROM ".TB."ecrans_tb
							WHERE id_etablissement=%s", func::GetSQLValueString($update_etablissement_id,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			
			// on archive tous mes écrans de l'établissement
			while($info = mysql_fetch_assoc($sql_query)){
				$this->archive_ecran($info['id']);
			}
			
			mysql_free_result($sql_query);
		}
		
		if($update_all == true){
			$sql = sprintf("SELECT id
							FROM ".TB."ecrans_tb");
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			
			// on archive tous les écrans
			while($info = mysql_fetch_assoc($sql_query)){
				
				$this->archive_ecran($info['id']);
			}
			
			mysql_free_result($sql_query);
		}

		return json_encode($retour);
	}
	
	/**
	 * archive_ecran archive au format json les différents type de slides attachés à un écran (ecran, groupe, playlist de l'écran, playlist du groupe)
	 * @author Loïc Horellou
	 * @since 0.5 20/12/2012
	 * @param  [type] $_id_archive_ecran id de l'écran à archiver
	 * @return [type]                    [description]
	 */
	function archive_ecran($_id_archive_ecran){

		if(!empty($_id_archive_ecran)){
			
			$sql = sprintf("SELECT P.id_groupe AS id_groupe, P.nom AS nom, E.code_postal AS code_postal, E.code_meteo AS code_meteo
								   FROM ".TB."ecrans_tb AS P,  ".TB."etablissements_tb AS E
								   WHERE P.id_etablissement = E.id
								   AND P.id = %s", func::GetSQLValueString($_id_archive_ecran,'int') );
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			$info 			= mysql_fetch_assoc($sql_query);

			$json = new stdClass();

			$json->id_ecran			= $_id_archive_ecran;
			$json->nom_ecran		= $info['nom'];
			$json->id_groupe		= $info['id_groupe'];
			$json->code_postal		= $info['code_postal'];
			$json->code_meteo		= $info['code_meteo'];
			$json->date_publication = date('Y-m-d H:i:s');

			mysql_free_result($sql_query);

			$sql = sprintf("SELECT * FROM ".TB."timeline_item_tb WHERE
												published = %s
												AND (
													(ref_target='seq' AND id_target=%s)
													OR (	
														end >= %s
														AND (
															ref_target='nat' 
															OR (ref_target='loc' AND id_target=%s)
															OR (ref_target='grp' AND id_target=%s)
															OR (ref_target='ecr' AND id_target=%s)
														)
													)
												)
												ORDER BY start ASC,
												ordre ASC",	func::GetSQLValueString(1, 'int'),
															func::GetSQLValueString($json->id_groupe, 'int'),
															func::GetSQLValueString($json->date_publication, 'text'),
															func::GetSQLValueString($json->code_postal, 'int'),
															func::GetSQLValueString($json->id_groupe, 'int'),
															func::GetSQLValueString($json->id_ecran, 'int'));

			$sql_query		= mysql_query($sql) or die(mysql_error());

			//echo "\n".mysql_num_rows($sql_query)."\n";

			$data = new stdClass();

			$alerte_tab_loc = array();
			$alerte_tab_nat = array();

			while($info_item = mysql_fetch_assoc($sql_query)){

				//echo "\n".$info_item['id'];

				$data = new stdClass();

				$data->id 			= $info_item['id'];
				$data->id_slide 	= $info_item['id_slide'];
				$data->id_target	= $info_item['id_target'];
				$data->ref_target	= $info_item['ref_target'];
				$data->type_target	= $info_item['type_target'];
				$data->template		= $info_item['template'];
				$data->titre		= $info_item['titre'];
				$data->start		= $info_item['start'];
				$data->end			= $info_item['end'];
				$data->expire		= $info_item['expire'];
				$data->duree		= $info_item['duree'];
				$data->ordre		= $info_item['ordre'];

				$json->data[] = $data;

				// si il y a une alerte locale
				if($info_item['ref_target'] == 'loc'){
					$alerte_tab_loc[] = $data;
				}

				// si il y a une alerte nationale
				if($info_item['ref_target'] == 'nat'){
					$alerte_tab_nat[] = $data;
				}

			}
		
			$json_data = json_encode($json);
		
			$sql = sprintf("INSERT INTO ".TB."slideshows_tb (id_ecran, json_data, date_publication)
							VALUES (%s,%s,NOW())",	func::GetSQLValueString( $_id_archive_ecran ,'int'),
													func::GetSQLValueString( $json_data ,'text'));
			$sql_query		= mysql_query($sql) or die(mysql_error());


			// ON MET A JOUR LA DATE DE PUBLICATION DU GROUPE DE L'ECRAN
			$sql = sprintf("UPDATE ".TB."ecrans_groupes_tb
							SET last_publication=NOW()
							WHERE id=%s", func::GetSQLValueString($info['id_groupe'],'int') );
			$sql_query		= mysql_query($sql) or die(mysql_error());



			// on met à jour les alertes locales sur les écrans de l'tablissement
			if(count($alerte_tab_loc)>0){

				$sql = sprintf("SELECT P.id
								FROM ".TB."ecrans_tb AS P,
								".TB."ecrans_groupes_tb AS G,
								".TB."etablissements_tb AS E
								WHERE P.id_groupe = G.id
								AND G.id_etablissement = E.id
								AND E.code_postal = %s
								AND P.id_groupe <> %s",	func::GetSQLValueString($json->code_postal, 'int'),
													func::GetSQLValueString($json->id_groupe, 'int') );
				$query = mysql_query($sql) or die(mysql_error());

				while($screen = mysql_fetch_assoc($query)){

					$sql_json = sprintf("SELECT json_data
									FROM ".TB."slideshows_tb
									WHERE id_ecran=%s
									ORDER BY date_publication DESC
									LIMIT 0,1", func::GetSQLValueString($screen['id'],'int'));

					$query_json = mysql_query($sql_json) or die(mysql_error());
					$data = mysql_fetch_assoc($query_json);

					$updated_json = json_encode( $this->update_alertes($data['json_data'], $alerte_tab_loc) );

					if(!empty($updated_json) && $updated_json!= 'null'){

						$sql = sprintf("INSERT INTO ".TB."slideshows_tb (id_ecran, json_data, date_publication)
								VALUES (%s,%s,NOW())",	func::GetSQLValueString( $screen['id'] ,'int'),
														func::GetSQLValueString( $updated_json ,'text'));
						$sql_query		= mysql_query($sql) or die(mysql_error());
					}
				}

			}	

			// on met à jour les alertes nationales sur tous les écrans
			if(count($alerte_tab_nat)>0){
				
				$sql = sprintf("SELECT P.id
								FROM ".TB."ecrans_tb AS P
								WHERE P.id_groupe <> %s",	func::GetSQLValueString($json->id_groupe, 'int') );
				$query = mysql_query($sql) or die(mysql_error());

				while($screen = mysql_fetch_assoc($query)){

					$sql_json = sprintf("SELECT json_data
									FROM ".TB."slideshows_tb
									WHERE id_ecran=%s
									ORDER BY date_publication DESC
									LIMIT 0,1", func::GetSQLValueString($screen['id'],'int'));

					$query_json = mysql_query($sql_json) or die(mysql_error());
					$data = mysql_fetch_assoc($query_json);


					$updated_json = json_encode( $this->update_alertes($data['json_data'], $alerte_tab_nat) );

					if(!empty($updated_json) && $updated_json!= 'null'){

						$sql = sprintf("INSERT INTO ".TB."slideshows_tb (id_ecran, json_data, date_publication)
								VALUES (%s,%s,NOW())",	func::GetSQLValueString( $screen['id'] ,'int'),
														func::GetSQLValueString( $updated_json ,'text'));
						$sql_query		= mysql_query($sql) or die(mysql_error());
					}
				}

			}		

		}
	}

	/**
	 * [update_alertes description]
	 * @param  [type] $json_screen [description]
	 * @param  [type] $alertes_tab [description]
	 * @return [type]              [description]
	 */
	function update_alertes($json_screen = null, $alertes_tab = null){

		if( isset($json_screen) && isset($alertes_tab) ){

			$json_screen = json_decode($json_screen);

			foreach($json_screen->data as $key=>$slide_item){

				foreach($alertes_tab as $new_item){

					if($slide_item->id == $new_item->id ){
						unset($json_screen->data[$key]);
					}
				}
			}

			$json_screen->data = array_merge($json_screen->data, $alertes_tab);

			return $json_screen;
		}
	}
	
	
	/**
	 * pour récupérer les dernières information d'un écran
	 * @param  [type] $actual_date_json [description]
	 * @return [type]                   [description]
	 */
	function get_ecran_data($actual_date_json = NULL){
		
		$retour = new stdClass();

		if(!empty($this->ecran->id) && $this->ecran->id !=0 ){
			
			$sql = sprintf("		SELECT json_data AS json,
									date_publication AS date_json
									FROM ".TB."slideshows_tb WHERE id_ecran = %s ORDER BY date_publication DESC LIMIT 0,1",
									func::GetSQLValueString($this->ecran->id,'int'));
									
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			$info 			= mysql_fetch_assoc($sql_query);
				
			$date_json = $info['date_json'];
			
			if($date_json>$actual_date_json){
				// instanciation des objets
				if(empty($this->ecran)){ $this-> ecran = (object)array(); }
	
				//$retour->id								= $this->ecran->id;
				$retour->screen_data		= json_decode($info['json']);
				$retour->update				= true;			
			}else if(empty($date_json)){
				$retour->update				= false;
				$retour->nodata = true;
			}else{
				$retour->update				= false;
			}
			
			
		}else{

			$retour = new stdClass();
			$retour->update			= false;
		}

		$retour = json_encode($retour);
			
		return $retour;
		
		
	}

	/**
	 * [get_template description]
	 * @return string une balise <script> contenant la structure du slide
	 */
	function get_templates(){

		//$recursif=false;
        $ffs = scandir(REAL_LOCAL_PATH.SLIDE_TEMPLATE_FOLDER);

        $temp =array();
        //echo '<ol>';
        foreach($ffs as $templateFolder){

            if($templateFolder != '.' && $templateFolder != '..' && substr($templateFolder, 0, 1)!= '.'){

                if(isset($templateFolder)){
					echo '<script id="' . $templateFolder . '" type="text/html">'."\n";
					include_once( REAL_LOCAL_PATH.SLIDE_TEMPLATE_FOLDER . $templateFolder . '/index.php');
					echo "\n".'</script>'."\n"."\n";
				}
            }
        }
	}
}
	
	