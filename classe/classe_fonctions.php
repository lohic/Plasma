<?php

/**
 * 
 */
class Func {
	

	/**
	 * Classe de fonctions génériques
	 */
	function func(){
		// rien
	}
	
	/**
	 * CONVERSION DES CHAINES ENVOYEES PAR LES FORMULAIRES from dreamweaver
	 * @param [type] $theValue           [description]
	 * @param [type] $theType            [description]
	 * @param string $theDefinedValue    [description]
	 * @param string $theNotDefinedValue [description]
	 */
	static function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
		if (PHP_VERSION < 6) {
			$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
		}
		
		$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
		
		switch ($theType) {
			case "text":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;    
			case "long":
			case "int":
				$theValue = ($theValue != "") ? intval($theValue) : "NULL";
			break;
			case "double":
				$theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
			break;
			case "date":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;
			case "defined":
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
			break;
			case "boolean":
				$theValue = $theValue ? '1' : '0';
			break;
			default :
				$theValue = "NULL";
			break;
		}
		return $theValue;
	}
	
	
	/**
	 * CONVERSION DES URLs EN LIENS CLICABLES
	 * @param  [type] $texte [description]
	 * @return [type]        [description]
	 */
	static function formatage($texte){
		
		if (ereg("[\"|'][[:alpha:]]+://",$texte) == false){
			$texte = ereg_replace('([[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/])', '<a target=\"_new\" href="\\1">\\1</a>', $texte); 
			$texte = ereg_replace("(^| |\n)(www([.]?[a-zA-Z0-9_/-])*)", "\\1<a target=\"_new\" href=\"http://\\2\">\\2</a>", $texte);
		} 
		
		return $texte;
	}
	
	
	
	/**
	 * CREER UN ELEMENT DE FORMULAIRE COMBOBOX A PARTIR D'UN TABLEAU php
	 * @param  [type]  $array       [description]
	 * @param  string  $name        [description]
	 * @param  [type]  $id          [description]
	 * @param  [type]  $additionnal [description]
	 * @param  boolean $isnull      [description]
	 * @return [type]               [description]
	 */
	static function createSelect($array, $name='', $id = NULL, $additionnal=NULL, $isnull=true){
		
		if(isset($additionnal)){$add = $additionnal; }else{ $add = '';};
	
		$selectItem = "<select name=\"$name\" id=\"$name\" $add>\n";
	
		if($isnull){ $selectItem .= "\t<option value=\"\" >Aucun</option>\n"; }
	
		foreach($array as $key => $value){
			$sep = explode('_',$key);
			
			if($sep[0]=='separateur'){
				$selectItem .= "\t<optgroup label=\"$value\"></optgroup>\n";
			}else{
				if($id && $id.'' == $key.''){ $sel = "selected=\"selected\""; }else {$sel="";}
				$selectItem .= "\t<option value=\"$key\" $sel>$value</option>\n";
			}
		}
		
		$selectItem .= "</select>";
		
		return $selectItem;
	}
	
	/**
	 * FONCTION POUR CREER UN COMBOBOX A PARTIR D'UN DOSSIER
	 * @param  [type] $_folder      [description]
	 * @param  [type] $_name        [description]
	 * @param  [type] $_id          [description]
	 * @param  [type] $_selectValue [description]
	 * @return [type]               [description]
	 */
	static function createFolderSelect($_folder=NULL,$_name=NULL,$_id=NULL,$_selectValue=NULL){
		if(isset($_folder)){
			
			if ($handle = opendir($_folder)) {
				
				$selectItem = "<select name=\"$_name\" id=\"$_id\">\n";	
				
				/* Ceci est la façon correcte de traverser un dossier. */
				while (false !== ($file = readdir($handle))) {
					
					if (substr($file,0,1)!='.'){					
							if($_selectValue && $_selectValue.'' == $file.''){ $sel = "selected=\"selected\""; }else {$sel="";}
							$selectItem .= "\t<option value=\"$file\" $sel>$file</option>\n";
					}
				}
			
				closedir($handle);		
	
				$selectItem .= "</select>";
				
				return $selectItem;
			}
		}
	}
	
	/**
	 * CREER UN GROUPE DE CASE A COCHER
	 * @param  [type] $array [description]
	 * @param  string $name  [description]
	 * @param  [type] $id    [description]
	 * @return [type]        [description]
	 */
	static function createCheckBox($array, $name='', $id=NULL){
	
		$selectItem = '';
	
		foreach($array as $key => $value){
			// $array->select	|	$array->value	|	$array->label	| $array->classe
			$classe = isset($value->classe)?' class="'.$value->classe.'" ':'';
			$checked= !empty($value->select)?'checked="checked"':'';
			$selectItem .= '<span><input type="checkbox" name="'.$name.'" value="'.$value->value.'" id="'.$id.'-'.$key.'" '.$classe.' '.$checked.'/><label for="'.$id.'-'.$key.'">'.$value->label.'</label></span>'."\n";
		}
	
		return $selectItem ;
	}


	
	/**
	 * CREER UN ELEMENT DE FORMULAIRE SELECT avec id différent de l'attribut name
	 * @param  [type]  $array       [description]
	 * @param  string  $name        [description]
	 * @param  [type]  $id          [description]
	 * @param  [type]  $selectValue [description]
	 * @param  [type]  $additionnal [description]
	 * @param  boolean $isnull      [description]
	 * @return [type]               [description]
	 */
	static function createCombobox($array, $name='', $id = NULL, $selectValue=NULL, $additionnal=NULL, $isnull=true){
		
		if(isset($additionnal)){$add = $additionnal; }else{ $add = '';}
	
		$selectItem = "<select name=\"$name\" id=\"$id\" $add>\n";
	
		if($isnull){ $selectItem .= "\t<option value=\"\" >Aucun</option>\n"; }
	
		foreach($array as $key => $value){
			$sep = explode('_',$key);
			
			if($sep[0]=='separateur'){
				$selectItem .= "\t<optgroup label=\"$value\"></optgroup>\n";
			}else{
				if($selectValue && $selectValue.'' == $key.''){ $sel = "selected=\"selected\""; }else {$sel="";}
				$selectItem .= "\t<option value=\"$key\" $sel>$value</option>\n";
			}
		}
		
		$selectItem .= "</select>";
		
		return $selectItem;
	}

	
	
	
	/**
	 * TRANSFORMER UNE CHAINE EN IDENTIFIANT : PAS D'ACCENTS, PAS D'ESPACES
	 * @param  [type] $valeur [description]
	 * @return [type]         [description]
	 */
	static function makeIdentifier($valeur){
		$valeur = strtr($valeur,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
		$valeur = preg_replace('/([^.a-z0-9]+)/i', '_', $valeur);
		
		return $valeur;
	}


	/**
	 * NETTOYAGE D'UNE CHAINE DE CARACTERES
	 * @param  [type] $valeur [description]
	 * @return [type]         [description]
	 */
	static function clean($valeur){
		return strtolower(	utf8_encode(strtr(utf8_decode($valeur),
							utf8_decode("àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'" ), 
							utf8_decode("aaaaaceeeeiiiinooooouuuuyyaaaaaceeeeiiiinooooouuuuy " ))));
	
	}
	
	
	/**
	 * UPLOADER UN FICHIER
	 * @param  [type] $file       [description]
	 * @param  [type] $repository [description]
	 * @return [type]             [description]
	 */
	static function upload($file, $repository){
		
		
		$name = $file["name"];
	
		$name = strtr($name,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
							'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
		$name = preg_replace('/([^.a-z0-9]+)/i', '_', $name);
		$pos = strrpos($name, '.');
		$extension = substr($name, $pos, strlen($name) );
		$nom = substr($name, 0, $pos);
		$cpt=0;
		while(file_exists($repository.$name)){
			$cpt++;
			$name = $nom.'('.$cpt.')'.$extension;
		}
		
		copy($file['tmp_name'], $repository.$name);
		return $name;
	}
	
	
	/**
	 * SUPPRIMER UN FICHIER
	 * @param  [type] $name       [description]
	 * @param  [type] $repository [description]
	 * @return [type]             [description]
	 */
	static function delete($name, $repository){
		unlink($repository.$name);
	}
	
	
	/**
	 * SUPPRIMER UN DOSSIER et son contenus de différents fichiers
	 * @param type $chemin 
	 * @return type
	 */
	static function delete_dir($chemin) {
		// vérifie si le nom du repertoire contient "/" à la fin
		// place le pointeur en fin d'url
		if ($chemin[strlen($chemin)-1] != '/'){
			// rajoute '/'
			$chemin .= '/';
		}
	
		if (is_dir($chemin)) {
			 $sq = opendir($chemin); // lecture
			 while ($f = readdir($sq)) {
				if ($f != '.' && $f != '..'){
					$fichier = $chemin.$f; // chemin fichier
					if (is_dir($fichier)){
						sup_repertoire($fichier);
					// rapel la fonction de manière récursive
					}else{
						// sup le fichier
						unlink($fichier);
					}
				}
			}
			closedir($d);
			rmdir($chemin); // sup le répertoire
		}else {
			unlink($chemin);  // sup le fichier
		}
	}
	

	
	/**
	 * FONCTION POUR BIEN FERMER TOUS LES TAGS D'UNE CHAINE HTML
	 * cf https://gist.github.com/supermethod/913378/raw/089317513b26e4f5a4a56ef9ee18179dfb6aae4a/gistfile1.php
	 * @param  [type] $html [description]
	 * @return [type]       [description]
	 */
	static function close_dangling_tags($html){
		#put all opened tags into an array
		preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU",$html,$result);
		$openedtags=$result[1];

		#put all closed tags into an array
		preg_match_all("#</([a-z]+)>#iU",$html,$result);
		$closedtags=$result[1];
		$len_opened = count($openedtags);
		# all tags are closed
		if(count($closedtags) == $len_opened){
			return $html;
		}

		$openedtags = array_reverse($openedtags);
		# close tags
		for($i=0;$i < $len_opened;$i++) {
			if (!in_array($openedtags[$i],$closedtags)){
				$html .= '</'.$openedtags[$i].'>';
			} else {
				unset($closedtags[array_search($openedtags[$i],$closedtags)]);
			}
		}
		return $html;
	}
	
	/**
	 * CONVERTI LES CHAINES D'UN TABLEAU EN BAS DE CASSE
	 * @param  array   $array [description]
	 * @param  integer $round [description]
	 * @return [type]         [description]
	 */
	static function arraytolower(array $array, $round = 0){ 
		return unserialize(strtolower(serialize($array))); 
	}
	
	/**
	 * GENERE UN TIMESTAMP UNIX DEPUIS UNE DATE yyyy-mm-dd hh:mm:ss
	 * @param type $date 
	 * @return type
	 */
	static function makeTime($date=NULL){
		
		//setlocale(LC_TIME, "fr_FR");
		//	$retour->mois[$row['mois']] = strftime("%B",mktime(0, 0, 0, $row['mois'], 10));
		if(!empty($date)){
			$a = date_parse($date);
			$timestamp = mktime($a['hour'], $a['minute'], $a['second'], $a['month'], $a['month'], $a['year']);
			
			return $timestamp;
		}
	}
	
	/**
	 * [time2sec description]
	 * @param  string $duree au format hh:mm:ss
	 * @return [type]        [description]
	 */
	static function time2sec($duree=NULL){
		if(!empty($duree)){
			
			$d = explode (':', $duree);
			
			return intval($d[0])*3600 + intval($d[1])*60 + intval($d[2]) ;	
		}
	}
	
	
	
	/**
	 * supprimer les sauts de ligne
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 * @author Gildas Paubert
	 */
	static function nonl($str){
		$str = str_replace("\n", "", $str);
		$str = str_replace("\r\n", "", $str);
		$str = str_replace("\r", "", $str);
		return $str;
	}
	
	/**
	 * <br /> -> newline
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 * @author Gildas Paubert
	 */
	static function br2nl($str){
		$str = preg_replace("#\<br\s*\/?\>#isU", '
	', $str);
		return $str;
	}
	

	/**
	 * Description
	 * @param type $data 
	 * @return type
	 */
	static function encodeAccentHTML($data = NULL){
		if(isset($data)){
			$trans = array(	'À'=>'&Agrave;',
							'Á'=>'&Aacute;',
							'Â'=>'&Acirc;',
							'Ã'=>'&Atilde;',
							'Ä'=>'&Auml;',
							'Å'=>'&Aring;',
							'Ç'=>'&Ccedil;',
							'È'=>'&Egrave;',
							'É'=>'&Eacute;',
							'Ê'=>'&Ecirc;',
							'Ë'=>'&Euml;',
							'Ì'=>'&Igrave;',
							'Í'=>'&Iacute;',
							'Î'=>'&Icirc;',
							'Ï'=>'&Iuml;',
							'Ò'=>'&Ograve;',
							'Ó'=>'&Oacute;',
							'Ô'=>'&Ocirc;',
							'Õ'=>'&Otilde;',
							'Ö'=>'&Ouml;',
							'Ù'=>'&Ugrave;',
							'Ú'=>'&Uacute;',
							'Û'=>'&Ucirc;',
							'Ü'=>'&Uuml;',
							'Ý'=>'&Yacute;',
							'Ÿ'=>'&Yuml;',
							'à'=>'&agrave;',
							'á'=>'&aacute;',
							'â'=>'&acirc;',
							'ã'=>'&atilde;',
							'ä'=>'&auml;',
							'å'=>'&aring;',
							'ç'=>'&ccedil;',
							'è'=>'&egrave;',
							'é'=>'&eacute;',
							'ê'=>'&ecirc;',
							'ë'=>'&euml;',
							'ì'=>'&igrave;',
							'í'=>'&iacute;',
							'î'=>'&icirc;',
							'ï'=>'&iuml;',
							'ð'=>'&eth;',
							'ò'=>'&ograve;',
							'ó'=>'&oacute;',
							'ô'=>'&ocirc;',
							'õ'=>'&otilde;',
							'ö'=>'&ouml;',
							'ù'=>'&ugrave;',
							'ú'=>'&uacute;',
							'û'=>'&ucirc;',
							'ü'=>'&uuml;',
							'ý'=>'&yacute;',
							'ÿ'=>'&yuml;');
									
			return strtr($data,$trans);
		}else{
			return false;	
		}
	}

}