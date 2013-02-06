<?php


include_once('../vars/config.php');
include_once('classe_connexion.php');
include_once('classe_slide.php');
include_once('classe_fonctions.php');
//include_once('fonctions.php');
//include_once('connexion_vars.php');
//include_once('../vars/statics_vars.php');

//$temp = new Slideshow($_GET['id']);

		

class Slideshow {
	
	var $slide_db			= NULL;
	var $id_ecran			= NULL;
	
	var $ecran				= NULL;
	
	private static $order_slide_by	= 'date';
	private static $order_ASC		= true;
	
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
			
			//$this->id_ecran = $_id_ecran;
			
			$this->ecran->id = $_id_ecran;
			
			/*$sql = sprintf("SELECT	P.nom,
									P.id_etablissement,
									P.id_groupe,
									P.id_last_slide,
									P.order_last_slide,
									P.id_default_slideshow,
									E.code_postal,
									G.id_slideshow,
									(SELECT json_data FROM ".TB."slideshows_tb WHERE id_ecran = %s ORDER BY date_publication DESC LIMIT 0,1) AS json
							FROM ".TB."ecrans_tb AS P,
							".TB."etablissements_tb AS E,
							".TB."ecrans_groupes_tb AS G
							WHERE P.id=%s
							AND P.id_etablissement = E.id
							AND P.id_groupe = G.id", func::GetSQLValueString($_id_ecran,'int'),
													 func::GetSQLValueString($_id_ecran,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			$info 			= mysql_fetch_assoc($sql_query);
			
			$this->ecran->id				= $_id_ecran;
			$this->ecran->nom				= $info['nom'];
			$this->ecran->id_etablissement	= $info['id_etablissement'];
			$this->ecran->id_groupe			= $info['id_groupe'];
			$this->ecran->id_last_slide		= $info['id_last_slide'];
			$this->ecran->order_last_slide	= $info['order_last_slide'];
			$this->ecran->code_postal		= $info['code_postal'];
			$this->ecran->id_ecran_playlist	= $info['id_default_slideshow'];
			$this->ecran->id_groupe_playlist= $info['id_slideshow'];
			$this->ecran->json				= $info['json'];*/
			
			$this->ecran = $this->get_ecran_info();
			
			$this->debugger = "";
		
			//var_dump($this->ecran);
			//$this->publish_slideshow(1,NULL,NULL,NULL);
			//var_dump($this->get_next_slide_id());
			//var_dump($this->debugger);
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
	* permet le lancement d'un slideshow
	* @author Loïc Horellou
	* @since v0.1
	* @see Slideshow::generate_slide_page
	* @param $ispreview sert a afficher si on est en mode preview ou lecture (si lecture le curseur souris est masqué)
	* @param $isdebug précise si on doit afficher le mode debug ou pas (false par defaut / true) 
	*/
	function run($ispreview=false,$isdebug =false){
		//$this->slide_db->connect_db();

		echo $this->generate_slide_page($ispreview,$isdebug);

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
	function generate_slide_page($ispreview=false,$isdebug=false){
		//$this->slide_db->connect_db();

		//$confirm = LOCAL_PATH.'structure/slide_generate.php';
		
		
		$le_slide = $this->generate_slide($isdebug);
		
		$plasma_id = $this->ecran->id;
		
		$contents ='';
		ob_start();
		include_once(LOCAL_PATH.'structure/slide-generate.php') ;
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
		//$this->slide_db->connect_db();
		$this->debug("ecran / id:".$this->ecran->id);
		
		$next_slide_info = $this->get_next_slide_id(); // ressert en fin de fonction
		//echo $next_slide_info->id_slide;
					
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
		
		
		// js (après)
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
		$contents .= '
		<script language="javascript">
		
			function clear_slideshow(){
				clearTimeout(exit);
				clearTimeout(verif);
				$("#compteur").stop();
			}

			function exit_slideshow(){
				
				clearTimeout(exit);				
				
				// sortie CSS
				$("#template").addClass("exit"); 
				$("#debug").append("<p>exit</p>");
				
				// sortie dans 2 sec
				end = setTimeout(function(){ 

					clearTimeout(end);
					
					// écriture du slide
					$("#template").removeClass("exit");
					$("#template").html(nextSlideData);
					
					// preload du suivant
					get_next_slide('.($info->id).', false);

				}, 2000);

			}
			
			verif = setTimeout(function(){});
			
			function secure_loop(){
				get_next_id('.($info->id).');
				
				clearTimeout(verif);
				verif = setTimeout(secure_loop, 20000);
			}

			function play_slideshow(slide_duree){
				
				// sortie programmée
				exit = setTimeout(exit_slideshow, slide_duree-2000);
				
				// debug bar
				$("#compteur").animate({width:"0px"}, slide_duree, "linear");
				
				// preload du suivant
				get_next_slide('.($info->id).', false);
				
				// verif
				secure_loop();

			}

			play_slideshow('.$duree.');

		</script>';
		
		
		// on vérifie qu'on est pas en mode test d'un slide sinon
		// maj de l'index de la playlist, puisqu'on vient de sauter au slide suivant
		if(isset($next_slide_info->test) && $next_slide_info->test != true){
		
			$send = array();
			$send['id_last_slide'] 			= $info->id;
			$send['id_last_slideshow']		= $next_slide_info->id_playlist;
			$send['order_last_slide']		= $next_slide_info->ordre;
			
			$this->update_ecran_info($send);
		
		}
		
		$debug = $this->debugger;
		//
		if($isdebug){
			return '<div id="debug">'.$debug.'</div>'.$contents;	
		}else{
			return $contents;
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
	* request_next_slide_id_by_type sert à interroger les données JSON publiées pour écran
	* @author Loïc Horellou
	* @since v0.5 22/12/2012
	* @param $id_target id de l'élément ciblé (peut être un écran, un groupe d'écran ou une playlist)
	* @param $type_target le type de l'élément ciblé (groupe, ecran, slideshow)
	* @param $type le type de slide cherché (date, freq, flux)
	* @param $alerte si slide cherché doit être une alerte (false, national, code_postal)
	* @return retourne un objet avec contenant les variables id_slide,id_playlist,ordre,duree ou false si aucun slide n'a été trouvé
	*/
	function request_next_slide_id_by_type($id_target, $type_target='ecran', $type='date', $alerte=false, $JSON = true){
						
		
		if($JSON){
			$json_data = !empty($this->ecran->json) ? json_decode($this->ecran->json) : '';
			$slides = array();

			// SI ON UTILISE LE MODE JSON
			
			
			////////// SLIDE EN MODE DATE !!!!!!
			if($type == 'date'){
				
				$ladate			= date("Y-m-d G:i:s");
				$timestamp		= func::makeTime($ladate);
			
				foreach($json_data->data as $data){
					if($data->type_target		== $type_target
						&& $data->date			<= $ladate
						&& $data->type			== 'date'
						&& $data->id_target		== $id_target
						&& $data->alerte		== $alerte)
					{		
						$key = 'slide-'.$data->id;	
						$slides[$key] = $data;
					}
				}
				
				self::$order_slide_by	= 'date';
				self::$order_ASC		= false;
				
				uasort($slides, array('slideshow','order_slideshow_json'));
				
				if(count($slides)>0){
					$slide = array_shift($slides);
															
					$horaire = explode(' ',$slide->date);
					$reste = $this->verif_time_slide($horaire[1],$slide->duree);
					
					$reste = $this->verif_time_slide($horaire[1],$slide->duree);
					
					if($reste){
						$retour->id_slide		= $slide->id_slide;
						$retour->id_playlist	= false;
						$retour->ordre			= false;
						$retour->duree			= $reste; //func::time2sec($info['duree']); // sec
						
						
						$this->debug('on change de slide / id:'.($retour->id_slide).' - durée:'.($retour->duree));
						$this->debug('mode : date');
						$this->debug('alerte : '.$alerte);
						
						return $retour;
					}else{
						$this->debug('PAS DE SLIDE TROUVÉ');
						$this->debug('id écran : '.($this->ecran->id));
						
						return false;	
					}
				}else{
					$this->debug('PAS DE SLIDE TROUVÉ');
					$this->debug('id écran : '.($this->ecran->id));
					
					return false;	
				}
					
			}
			
			////////// SLIDE EN MODE FREQ !!!!!!	
			else if ($type == 'freq'){
				
							
				foreach($json_data->data as $data){
					if($data->type_target		== 'playlist'
						&& $data->type			== 'freq'
						&& $data->id_target		== $id_target){
						$key = 'slide-'.$data->id;	
						$slides[$key] = $data;
					}
				}
				
				
				if(count($slides) <= 0){
					$this->debug('PAS DE SLIDE TROUVÉ');
					$this->debug('id écran : '.($this->ecran->id));
					return false;
				}
				$frequences = array();
				$horaires = array();
				
				$M = date('m');		// 01 -> 12
				$J = date('d');		// 01 -> 31
				$j = date('N');		// 1 	-> 7
				$H = date('H:i:s');	// 00:00:00
			
				
				foreach($slides as $slide){
					$id = $slide->id_slide;
					$val = $slide->freq;
										
					$frequences[] = $val;
								
					if($val->M == '*' || $val->M == $M ){
						if(isset($val->J) && $val->J == $J ){				
							$reste = $this->verif_time_slide($val->H,$slide->duree);
							
							if($reste){
									$horaires[$val->H]->id = $id;
									$horaires[$val->H]->duree = $reste;
							}
						}
						if(isset($val->j) && ($val->j == '*' || $val->j == $j)){
							$reste = $this->verif_time_slide($val->H,$slide->duree);
							
							if($reste){
									$horaires[$val->H]->id = $id;
									$horaires[$val->H]->duree = $reste;
							}
						}
					}
				}
				
				ksort($horaires);
				
							
				if( $horaires && count($horaires) > 0 ){
					//echo "<p> ID : ".current($horaires)->id.' horaire : '.current(array_keys($horaires))." reste : ".current($horaires)->duree."</p>\n"; 
					$retour->id_slide		= current($horaires)->id;
					$retour->id_playlist	= false;
					$retour->ordre			= false;
					$retour->duree			= current($horaires)->duree; // sec
					
					$this->debug('on change de slide / id:'.($retour->id_slide));
					$this->debug('durée : '.($retour->duree));
					$this->debug('mode : fréquence');
					
					return $retour;
				}else{
					$this->debug('PAS DE SLIDE TROUVÉ');
					$this->debug('id écran : '.($this->ecran->id));
					
					return false;
				}
				
			
				
			}
			
			////////// SLIDE EN MODE SEQUENTIEL!!!!!!
			else if ($type == 'flux'){
				
				
				
				$ladate			= date("Y-m-d G:i:s");
				$timestamp		= func::makeTime($ladate);
				
				
				foreach($json_data->data as $data){
					if(empty($ordre_first_slide)) {
						$ordre_first_slide	= $data->ordre;
						$id_first_slide		= $data->id_slide;
						$duree_first_slide	= $data->duree;
					}else{
						if( $data->ordre <= $ordre_first_slide ){
							$ordre_first_slide	= $data->ordre;
							$id_first_slide		= $data->id_slide;
							$duree_first_slide	= $data->duree;
						}
					}
					
					if(empty($ordre_max_slide)) {
						$ordre_max_slide = $data->ordre;
					}else{
						if( $data->ordre >= $ordre_max_slide ){
							$ordre_max_slide	= $data->ordre;
						}
					}
					
				}
			
				foreach($json_data->data as $data){
					if($data->type_target		== $type_target
						&& $data->ordre			>= $this->ecran->order_last_slide
						&& $data->type			== 'flux'
						&& $data->id_target		== $id_target){
						$key = 'slide-'.$data->id;	
						$slides[$key] = $data;
					}
				}
				
				self::$order_slide_by	= 'ordre';
				self::$order_ASC		= true;
				
				uasort($slides, array('slideshow','order_slideshow_json'));
								
				$slide = array_shift($slides);		
				
				if(count($slides)>0){
					
					if($ordre_max_slide< $this->ecran->order_last_slide){
						$retour->id_slide		= $id_first_slide;
						$retour->ordre			= $ordre_first_slide;
						$retour->duree			= func::time2sec($duree_first_slide); // sec
					}else{
						$retour->id_slide		= $slide->id_slide;
						$retour->ordre			= $slide->ordre;
						$retour->duree			= func::time2sec($slide->duree); // sec
					}	
					$retour->id_playlist	= $slide->id_target;
					
					$this->debug('on change de slide / id:'.($retour->id_slide));
					$this->debug('durée : '.($retour->duree));
					$this->debug('ordre : '.($retour->ordre).'/'.$ordre_max_slide);
					$this->debug('id_playlist : '.($slide->id_target));
					$this->debug('mode : séquentiel');
					
					return $retour;
				}else{	
					$this->debug('PAS DE SLIDE TROUVÉ');
					$this->debug('id écran : '.($this->ecran->id));
							
					return false;	
				}
				
			}else{
				$this->debug('PAS DE SLIDE TROUVÉ');
				$this->debug('id écran : '.($this->ecran->id));
				
				return false;	
			}
			
		}else{
			// SI ON UTILISE LE MODE SQL ET PAS LE MODE JSON
			if($type == 'date'){
				
				////////// SLIDE EN MODE DATE !!!!!!
				
				$ladate			= date("Y-m-d G:i:s");
				$timestamp		= func::makeTime($ladate);
				
				$sql			= sprintf("SELECT *
												FROM ".TB."rel_slide_tb
												WHERE date<%s
												AND id_target=%s
												AND type_target=%s
												AND alerte=%s
												AND type = 'date'
												ORDER BY date DESC
												LIMIT 0,1",	func::GetSQLValueString($ladate,'text'),
															func::GetSQLValueString($id_target,'int'),
															func::GetSQLValueString($type_target,'text'),
															func::GetSQLValueString($alerte,'text'));
															
				$sql_query		= mysql_query($sql) or die(mysql_error());							
				$info 			= mysql_fetch_assoc($sql_query);
				$nbr			= mysql_num_rows($sql_query);
				
				
				if($nbr>0){
					
					$horaire = explode(' ',$info['date']);
					$reste = $this->verif_time_slide($horaire[1],$info['duree']);
				
					if($reste){
						$retour->id_slide		= $info['id_slide'];
						$retour->id_playlist	= false;
						$retour->ordre			= false;
						$retour->duree			= $reste; //func::time2sec($info['duree']); // sec
						
						
						$this->debug('on change de slide / id:'.($retour->id_slide).' - durée:'.($retour->duree));
						$this->debug('mode : date');
						$this->debug('alerte : '.$alerte);
						
						return $retour;
					}else{
						$this->debug('PAS DE SLIDE TROUVÉ');
						$this->debug('id écran : '.($this->ecran->id));
						
						return false;	
					}
				}else{	
					$this->debug('PAS DE SLIDE TROUVÉ');
					$this->debug('id écran : '.($this->ecran->id));			
					
					return false;	
				}
				
				
				
			}else if ($type == 'freq'){
					
				////////// SLIDE EN MODE FREQUENCE !!!!!!
			
				$sql			= sprintf("SELECT *
											FROM ".TB."rel_slide_tb
											WHERE id_target=%s
											AND type_target = 'playlist'
											AND type ='freq'", func::GetSQLValueString($this->ecran->id,'int'));
				$sql_query		= mysql_query($sql) or die(mysql_error());							
				$nbr			= mysql_num_rows($sql_query);
				
				if($nbr <= 0){
					$this->debug('PAS DE SLIDE TROUVÉ');
					$this->debug('id écran : '.($this->ecran->id));
					return false;
				}
				$frequences = array();
				$horaires = array();
				
				$M = date('m');		// 01 -> 12
				$J = date('d');		// 01 -> 31
				$j = date('N');		// 1 	-> 7
				$H = date('H:i:s');	// 00:00:00
			
				
				while($info = mysql_fetch_assoc($sql_query)){
					$id = $info['id_slide'];
					$val = json_decode($info['freq']);
					$frequences[] = $val;
								
					if($val->M == '*' || $val->M == $M ){
						if(isset($val->J) && $val->J == $J ){				
							$reste = $this->verif_time_slide($val->H,$info['duree']);
							
							if($reste){
									$horaires[$val->H]->id = $id;
									$horaires[$val->H]->duree = $reste;
							}
						}
						if(isset($val->j) && ($val->j == '*' || $val->j == $j)){
							$reste = $this->verif_time_slide($val->H,$info['duree']);
							
							if($reste){
									$horaires[$val->H]->id = $id;
									$horaires[$val->H]->duree = $reste;
							}
						}
					}
				}
				
				ksort($horaires);
							
				if( $horaires && count($horaires) > 0 ){
					//echo "<p> ID : ".current($horaires)->id.' horaire : '.current(array_keys($horaires))." reste : ".current($horaires)->duree."</p>\n"; 
					$retour->id_slide		= current($horaires)->id;
					$retour->id_playlist	= false;
					$retour->ordre			= false;
					$retour->duree			= current($horaires)->duree; // sec
					
					$this->debug('on change de slide / id:'.($retour->id_slide));
					$this->debug('durée : '.($retour->duree));
					$this->debug('mode : fréquence');
					
					return $retour;
				}else{
					$this->debug('PAS DE SLIDE TROUVÉ');
					$this->debug('id écran : '.($this->ecran->id));
					
					return false;
				}
				
					
			}else if ($type == 'flux'){
				
				////////// SLIDE EN MODE SEQUENTIEL!!!!!!
				
				$sql			= sprintf("SELECT id,id_slide,id_target,ordre,duree,
												(SELECT MAX(ordre)  FROM ".TB."rel_slide_tb WHERE id_target=%s AND type_target=%s ORDER BY ordre ASC) AS ordre_max_slide,
												(SELECT id_slide	FROM ".TB."rel_slide_tb WHERE id_target=%s AND type_target=%s ORDER BY ordre ASC LIMIT 0,1) AS id_first_slide,
												(SELECT ordre  		FROM ".TB."rel_slide_tb WHERE id_target=%s AND type_target=%s ORDER BY ordre ASC LIMIT 0,1) AS ordre_first_slide,
												(SELECT duree 		FROM ".TB."rel_slide_tb WHERE id_target=%s AND type_target=%s ORDER BY ordre ASC LIMIT 0,1) AS duree_first_slide
												FROM ".TB."rel_slide_tb
												WHERE id_target=%s
												AND type_target=%s
												AND ordre >= %s
												AND type = 'flux'
												ORDER BY ordre ASC
												LIMIT 0,1", func::GetSQLValueString($id_target,'int'),
															func::GetSQLValueString($type_target,'text'),
															func::GetSQLValueString($id_target,'int'),
															func::GetSQLValueString($type_target,'text'),
															func::GetSQLValueString($id_target,'int'),
															func::GetSQLValueString($type_target,'text'),
															func::GetSQLValueString($id_target,'int'),
															func::GetSQLValueString($type_target,'text'),
															func::GetSQLValueString($id_target,'int'),
															func::GetSQLValueString($type_target,'text'),
															func::GetSQLValueString($this->ecran->order_last_slide,'int')	);
															
				$sql_query		= mysql_query($sql) or die(mysql_error());							
				$info 			= mysql_fetch_assoc($sql_query);
				$nbr			= mysql_num_rows($sql_query);
				
				
				if($nbr>0){
					
					if($info['ordre_max_slide']< $this->ecran->order_last_slide){
						$retour->id_slide		= $info['id_first_slide'];
						$retour->ordre			= $info['ordre_first_slide'];
						$retour->duree			= func::time2sec($info['duree_first_slide']); // sec
					}else{
						$retour->id_slide		= $info['id_slide'];
						$retour->ordre			= $info['ordre'];
						$retour->duree			= func::time2sec($info['duree']); // sec
					}	
					$retour->id_playlist	= $info['id_target'];
					
					$this->debug('on change de slide / id:'.($retour->id_slide));
					$this->debug('durée : '.($retour->duree));
					$this->debug('ordre : '.($retour->ordre).'/'.$info['ordre_max_slide']);
					$this->debug('id_playlist : '.($info['id_target']));
					$this->debug('mode : séquentiel');
					
					return $retour;
				}else{	
					$this->debug('PAS DE SLIDE TROUVÉ');
					$this->debug('id écran : '.($this->ecran->id));
							
					return false;	
				}
				
			}else{
				$this->debug('PAS DE SLIDE TROUVÉ');
				$this->debug('id écran : '.($this->ecran->id));
				
				return false;	
			}
		}
	}
	
	/**
	* verif_time_slide sert à vérifier si un slide corrspond à l'horaire actuel et renvoie la durée restante si nécessaire
	* @author Loïc Horellou
	* @since 0.5 18/12/2012
	* @param $start_time heure de commencement du slide hh:mm:ss
	* @param $duree duree du slide hh:mm:ss
	* @return la duree en secondes ou false (durée restante si le slide est dans l'intervale de temps)
	*/
	function verif_time_slide($start_time_slide,$duree_slide){
		
		$t = func::time2sec($start_time_slide);
		$d = func::time2sec($duree_slide);
		
		$a = func::time2sec(date('G:i:s'));
		
		//echo "start : $t, duree $d, actuel $a";
		
		if($t<=$a && $a<$t+$d){
			return $d-($a-$t);
		}else{
			return false;	
		}
	}
	
	/**
	* get_next_slide_id récupération de l'id du prochain slide d'un écran C'EST LE COEUR DU SYSTÈME - C'EST POUR ÇA QUE LA FONCTION EST UN PEU LONGUE
	* @author Loïc Horellou
	* @since 0.5 21/12/2012
	* @param $test pour savoir si on teste l'id ou pas, dans ce cas ou mets ou pas à jour les informations de l'écran
	* @return false ou un objet contenant les informations liées au slide (id_slide,id_playlist,ordre,duree)
	*/
	function get_next_slide_id($test=false){
		/*
		> ecran
		> groupe
		> slideshow		
		*/
		
		if(isset($_GET['slide_id'])){
			/*
			@ switch pour un aperçu d'un seul slide
			@ GILDAS
			@ 20/07/2012
			*/
			$retour->id_slide		= func::GetSQLValueString($_GET['slide_id'],'int');
			$retour->id_slideshow	= false;
			$retour->ordre			= false;
			$retour->duree			= 120; // sec
			$retour->test			= true;
			
			return $retour;
			
		
		} else {
			if(!empty($this->ecran->id)){
			
				$this->slide_db->connect_db();
				
				//$ecran_info = $this->get_ecran_info();
				$this->ecran = $this->get_ecran_info();
				$slide_info = false;
				
				// A TERME IL FAUDRA FAIRE UNE FONCTION QUI PARCOURT UN TABLEAU !
				//		id_target												type_target			type_slide			alerte
				
				//1 	$this->ecran->id										'ecran'				'date'				$this->ecran->code_postal
				//2		$this->ecran->id_groupe 								'groupe'			'date'				$this->ecran->code_postal
				
				//3		$this->ecran->id										'ecran'				'date'				'all'
				//4		$this->ecran->id_groupe									'groupe'			'date'				'all'
				
				//5		$this->ecran->id_ecran_playlist_locale					'playlist'			'date'				false
				//6		$this->ecran->id_ecran_playlist_nationale				'playlist'			'date'				false
				//7		$this->ecran->id_groupe_playlist_locale					'playlist'			'date'				false
				//8		$this->ecran->id_groupe_playlist_nationale				'playlist'			'date'				false
				
				//9		$this->ecran->id_ecran_playlist_locale					'playlist'			'freq'				false
				//10	$this->ecran->id_ecran_playlist_nationale				'playlist'			'freq'				false
				//11	$this->ecran->id_groupe_playlist_locale					'playlist'			'freq'				false
				//12	$this->ecran->id_groupe_playlist_nationale				'playlist'			'freq'				false
				
				//13	$this->ecran->id_ecran_playlist_locale					'playlist'			'flux'				false
				//14	$this->ecran->id_ecran_playlist_nationale				'playlist'			'flux'				false
				//15	$this->ecran->id_groupe_playlist_locale					'playlist'			'flux'				false
				//16	$this->ecran->id_groupe_playlist_nationale				'playlist'			'flux'				false
				
				
				
					//		id_target												type_target			type_slide			alerte
				
				if(! $slide_info){
					//1 	$this->ecran->id										'ecran'				'date'				$this->ecran->code_postal
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id,'ecran','date',$this->ecran->code_postal);
				}
					
				if(! $slide_info){
					//2		$this->ecran->id_groupe 								'groupe'			'date'				$this->ecran->code_postal
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_groupe,'groupe','date',$this->ecran->code_postal);
				}else{
					return $slide_info;
				}
					
				if(! $slide_info){
					//3		$this->ecran->id										'ecran'				'date'				'all'
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id,'ecran','date','all');
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//4		$this->ecran->id_groupe									'groupe'			'date'				'all'
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_groupe,'groupe','date','all');
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//5		$this->ecran->id_ecran_playlist_locale					'playlist'			'date'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_ecran_playlist_locale,'playlist','date',false);
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//6		$this->ecran->id_ecran_playlist_nationale				'playlist'			'date'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_ecran_playlist_nationale,'playlist','date',false);
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//7		$this->ecran->id_groupe_playlist_locale					'playlist'			'date'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_groupe_playlist_locale,'playlist','date',false);
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//8		$this->ecran->id_groupe_playlist_nationale				'playlist'			'date'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_groupe_playlist_nationale,'playlist','date',false);
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//9		$this->ecran->id_ecran_playlist_locale					'playlist'			'freq'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_ecran_playlist_locale,'playlist','freq',false);
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//10	$this->ecran->id_ecran_playlist_nationale				'playlist'			'freq'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_ecran_playlist_nationale,'playlist','freq',false);
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//11	$this->ecran->id_groupe_playlist_locale					'playlist'			'freq'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_groupe_playlist_locale,'playlist','freq',false);
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//12	$this->ecran->id_groupe_playlist_nationale				'playlist'			'freq'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_groupe_playlist_nationale,'playlist','freq',false);
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//13	$this->ecran->id_ecran_playlist_locale					'playlist'			'flux'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_ecran_playlist_locale,'playlist','flux',false);
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//14	$this->ecran->id_ecran_playlist_nationale				'playlist'			'flux'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_ecran_playlist_nationale,'playlist','flux',false);
				}else{
					return $slide_info;
				}
				
				if(! $slide_info){			
					//15	$this->ecran->id_groupe_playlist_locale					'playlist'			'flux'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_groupe_playlist_locale,'playlist','flux',false);
				}else{
					return $slide_info;
				}

				if(! $slide_info){			
					//16	$this->ecran->id_groupe_playlist_nationale				'playlist'			'flux'				false
					$slide_info = $this->request_next_slide_id_by_type($this->ecran->id_groupe_playlist_nationale,'playlist','flux',false);
				}else{
					return $slide_info;
				}

				
				if(! $slide_info){
					$this->debug('PAS DE SLIDE TROUVÉ');
					$this->debug('id écran : '.($this->ecran->id));
					
					return false;
				}else{
					return $slide_info;
				}
				
				
			}else{
				$this->debug('PAS DE SLIDE TROUVÉ');
				$this->debug('PAS D\'ID ÉCRAN');
				//$this->debug('id écran : '.($this->ecran->id));
				
				return false;	
			}
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
			/*$this->slide_db->connect_db();
			$sql = sprintf("SELECT 	P.id_groupe,P.id_last_slide,
									P.id_last_slideshow,
									P.order_last_slide,
									E.code_meteo,
									P.id_default_slideshow
							FROM ".TB."ecrans_tb AS P, ".TB."etablissements_tb AS E
							WHERE P.id=%s
							AND P.id_etablissement = E.id",func::GetSQLValueString($this->ecran->id,'int'));
			$sql_query = mysql_query($sql) or die(mysql_error());
			
			$ecran_item = mysql_fetch_assoc($sql_query);
		
			$retour->id_groupe			= $ecran_item['id_groupe'];
			$retour->id_last_slide		= !empty($ecran_item['id_last_slide']) ? $ecran_item['id_last_slide'] : 0;
			$retour->id_last_slideshow	= !empty($ecran_item['id_last_slideshow']) ? $ecran_item['id_last_slideshow'] : $ecran_item['id_default_slideshow'];
			$retour->order_last_slide	= !empty($ecran_item['order_last_slide']) ? $ecran_item['order_last_slide'] : 0;
			$retour->code_meteo			= $ecran_item['code_meteo'];
			
			return $retour;*/
			
			$sql = sprintf("SELECT	P.nom,
									P.id_etablissement,
									P.id_groupe,
									P.id_last_slide,
									P.order_last_slide,
									P.id_default_slideshow,
									E.code_postal,
									G.id_slideshow,
									(SELECT json_data FROM ".TB."slideshows_tb WHERE id_ecran = %s ORDER BY date_publication DESC LIMIT 0,1) AS json
							FROM ".TB."ecrans_tb AS P,
							".TB."etablissements_tb AS E,
							".TB."ecrans_groupes_tb AS G
							WHERE P.id=%s
							AND P.id_etablissement = E.id
							AND P.id_groupe = G.id", func::GetSQLValueString($this->ecran->id,'int'),
													 func::GetSQLValueString($this->ecran->id,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());							
			$info 			= mysql_fetch_assoc($sql_query);
			
			$retour->id					= $this->ecran->id;
			$retour->nom				= $info['nom'];
			$retour->id_etablissement	= $info['id_etablissement'];
			$retour->id_groupe			= $info['id_groupe'];
			$retour->id_last_slide		= $info['id_last_slide'];
			$retour->order_last_slide	= $info['order_last_slide'];
			$retour->code_postal		= $info['code_postal'];
			$retour->id_ecran_playlist	= $info['id_default_slideshow'];
			$retour->id_groupe_playlist = $info['id_slideshow'];
			$retour->json				= $info['json'];
			
			return $retour;
		}
		
	}
	
	/**
	* update_ecran_info mets à jour les informations d'un écran, met à jour les informations de l'écran après une lecture, sauvegarde les id du dernier slide, slideshow et ordre
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
	* @author Loïc Horellou
	* @since 0.5 20/12/2012
	* @param $update_ecran_id			id de l'écran à archiver
	* @param $update_groupe_id			id du groupe d'écrans à archiver
	* @param $update_etablissement_id	id de l''établissement à archiver
	* @param $update_all				true si on souhaite publier tous les slides et playlists 
	*/
	function publish_slideshow($update_ecran_id=NULL, $update_groupe_id=NULL, $update_etablissement_id=NULL, $update_all=NULL){
		
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
		
	}
	
	/**
	* archive_ecran archive au format json les différents type de slides attachés à un écran (ecran, groupe, playlist de l'écran, playlist du groupe)
	* @author Loïc Horellou
	* @since 0.5 20/12/2012
	* @param $id_ecran	id de l'écran à archiver
	*/
	function archive_ecran($_id_archive_ecran){
		$sql = sprintf("SELECT P.id, P.nom, P.id_etablissement, P.id_groupe, P.id_last_slide, P.order_last_slide, P.id_playlist_locale AS ecran_playlist_locale, P.id_playlist_nationale AS ecran_playlist_nationale, E.code_postal, G.id_playlist_locale AS groupe_playlist_locale, G.id_playlist_nationale AS groupe_playlist_nationale
							FROM ".TB."ecrans_tb AS P,
							".TB."etablissements_tb AS E,
							".TB."ecrans_groupes_tb AS G
							WHERE P.id=%s
							AND P.id_etablissement = E.id
							AND P.id_groupe = G.id", func::GetSQLValueString($_id_archive_ecran,'int'));
		$sql_query		= mysql_query($sql) or die(mysql_error());							
		$info 			= mysql_fetch_assoc($sql_query);
		
		$temp_ecran->id_ecran					= $info['id'];
		$temp_ecran->id_groupe					= $info['id_groupe'];
		$temp_ecran->ecran_playlist_locale		= $info['ecran_playlist_locale'];
		$temp_ecran->ecran_playlist_nationale	= $info['ecran_playlist_nationale'];
		$temp_ecran->groupe_playlist_locale		= $info['groupe_playlist_locale'];
		$temp_ecran->groupe_playlist_nationale	= $info['groupe_playlist_nationale'];
		
		$json = NULL;
		
		if(!empty($temp_ecran->id_ecran)){
			// les slides attachés à l'écran
			$sql = sprintf("SELECT *
							FROM ".TB."rel_slide_tb
							WHERE id_target=%s
							AND type_target='ecran'",func::GetSQLValueString($temp_ecran->id_ecran,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());				
			while($info = mysql_fetch_assoc($sql_query)){
				$data->id 			= $info['id'];
				$data->id_slide 	= $info['id_slide'];
				$data->id_target	= $info['id_target'];
				$data->type_target	= $info['type_target'];
				$data->date			= $info['date'];
				$data->duree		= $info['duree'];
				$data->freq			= json_decode($info['freq']);
				$data->type			= $info['type'];
				$data->ordre		= $info['ordre'];
				$data->alerte		= $info['alerte'];
				
				
				$json->data[]		= $data;
				//$json->data_ecran[] = $data;
				
				$data = NULL;
			}
		}
		
		if(!empty($temp_ecran->id_groupe)){				
			// les slides attachés au groupe
			$sql = sprintf("SELECT *
							FROM ".TB."rel_slide_tb
							WHERE id_target=%s
							AND type_target='groupe'",func::GetSQLValueString($temp_ecran->id_groupe,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());					
			while($info = mysql_fetch_assoc($sql_query)){
				$data->id 			= $info['id'];
				$data->id_slide 	= $info['id_slide'];
				$data->id_target	= $info['id_target'];
				$data->type_target	= $info['type_target'];
				$data->date			= $info['date'];
				$data->duree		= $info['duree'];
				$data->freq			= json_decode($info['freq']);
				$data->type			= $info['type'];
				$data->ordre		= $info['ordre'];
				$data->alerte		= $info['alerte'];
				
				$json->data[]		= $data;
				//$json->data_groupe[] = $data;
				
				$data = NULL;
			}
		}
			
		if(!empty($temp_ecran->ecran_playlist_locale)){			
			// les slides attachés à la playlist de l'écran
			$sql = sprintf("SELECT *
							FROM ".TB."rel_slide_tb
							WHERE id_target=%s
							AND type_target='playlist'",func::GetSQLValueString($temp_ecran->ecran_playlist_locale,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());		
			while($info = mysql_fetch_assoc($sql_query)){
				$data->id 			= $info['id'];
				$data->id_slide 	= $info['id_slide'];
				$data->id_target	= $info['id_target'];
				$data->type_target	= $info['type_target'];
				$data->date			= $info['date'];
				$data->duree		= $info['duree'];
				$data->freq			= json_decode($info['freq']);
				$data->type			= $info['type'];
				$data->ordre		= $info['ordre'];
				$data->alerte		= $info['alerte'];
				
				$json->data[]		= $data;
				//$json->data_ecran_playlist[] = $data;
				
				$data = NULL;
			}	
		}
		
		if(!empty($temp_ecran->ecran_playlist_nationale)){			
			// les slides attachés à la playlist de l'écran
			$sql = sprintf("SELECT *
							FROM ".TB."rel_slide_tb
							WHERE id_target=%s
							AND type_target='playlist'",func::GetSQLValueString($temp_ecran->ecran_playlist_nationale,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());		
			while($info = mysql_fetch_assoc($sql_query)){
				$data->id 			= $info['id'];
				$data->id_slide 	= $info['id_slide'];
				$data->id_target	= $info['id_target'];
				$data->type_target	= $info['type_target'];
				$data->date			= $info['date'];
				$data->duree		= $info['duree'];
				$data->freq			= json_decode($info['freq']);
				$data->type			= $info['type'];
				$data->ordre		= $info['ordre'];
				$data->alerte		= $info['alerte'];
				
				$json->data[]		= $data;
				//$json->data_ecran_playlist[] = $data;
				
				$data = NULL;
			}	
		}
		
		if(!empty($temp_ecran->groupe_playlist_locale)){								
			// les slides attachés à la playlist du groupe
			$sql = sprintf("SELECT *
							FROM ".TB."rel_slide_tb
							WHERE id_target=%s
							AND type_target='playlist'",func::GetSQLValueString($temp_ecran->groupe_playlist_locale,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());		
			while($info = mysql_fetch_assoc($sql_query)){
				$data->id 			= $info['id'];
				$data->id_slide 	= $info['id_slide'];
				$data->id_target	= $info['id_target'];
				$data->type_target	= $info['type_target'];
				$data->date			= $info['date'];
				$data->duree		= $info['duree'];
				$data->freq			= json_decode($info['freq']);
				$data->type			= $info['type'];
				$data->ordre		= $info['ordre'];
				$data->alerte		= $info['alerte'];
				
				$json->data[]		= $data;
				//$json->data_groupe_playlist[] = $data;
				
				$data = NULL;
			}
		}
		
		if(!empty($temp_ecran->groupe_playlist_nationale)){								
			// les slides attachés à la playlist du groupe
			$sql = sprintf("SELECT *
							FROM ".TB."rel_slide_tb
							WHERE id_target=%s
							AND type_target='playlist'",func::GetSQLValueString($temp_ecran->groupe_playlist_nationale,'int'));
			$sql_query		= mysql_query($sql) or die(mysql_error());		
			while($info = mysql_fetch_assoc($sql_query)){
				$data->id 			= $info['id'];
				$data->id_slide 	= $info['id_slide'];
				$data->id_target	= $info['id_target'];
				$data->type_target	= $info['type_target'];
				$data->date			= $info['date'];
				$data->duree		= $info['duree'];
				$data->freq			= json_decode($info['freq']);
				$data->type			= $info['type'];
				$data->ordre		= $info['ordre'];
				$data->alerte		= $info['alerte'];
				
				$json->data[]		= $data;
				//$json->data_groupe_playlist[] = $data;
				
				$data = NULL;
			}
		}
	
		$json_data = json_encode($json);
		
		$sql = sprintf("INSERT INTO ".TB."slideshows_tb (id_ecran, json_data, date_publication) VALUES (%s,%s,NOW())",  func::GetSQLValueString($_id_archive_ecran,'int'),
																														func::GetSQLValueString($json_data,'text'));
		$sql_query		= mysql_query($sql) or die(mysql_error());	
	}	
}
	
	