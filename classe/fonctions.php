<?php


/*
@ CONVERSION DES CHAINES ENVOYEES PAR LES FORMULAIRES
@
@
*/
if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
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
		}
		return $theValue;
	}
}

/////////////////////////////////////////////////
//////////// FONCTIONS DE FORMATAGE /////////////
/////////////////////////////////////////////////

/*
@ CONVERSION DES URLs EN LIENS CLICABLES
@
@
*/
function formatage($texte){
	
	if (ereg("[\"|'][[:alpha:]]+://",$texte) == false){
        $texte = ereg_replace('([[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/])', '<a target=\"_new\" href="\\1">\\1</a>', $texte); 
		$texte = ereg_replace("(^| |\n)(www([.]?[a-zA-Z0-9_/-])*)", "\\1<a target=\"_new\" href=\"http://\\2\">\\2</a>", $texte);
    } 
	
	return $texte;
}


/*
@ IDENTIFICATION DES LEGENDES VIA [[ texte ]]
@
@
*/
function legende($matches){
	static $countLegend = 0;
	$countLegend++;
    return '<a class="legendlabel" href="#" onmouseover="showlegend('.$countLegend.')" onmouseout="showlegend()">['.$countLegend.']<span id="legende'.$countLegend.'" class="legende"><span class="number">'.$countLegend.'.</span>'.$matches[1].'</span></a>';
}

function trouverlegende($texte){
	return preg_replace_callback("/\[\[(.+)\]\]/Ui","legende",$texte);
	
}

/*
@ CREER UN ELEMENT DE FORMULAIRE SELECT
@
@
*/
function createSelect($array, $name='', $id = NULL, $additionnal=NULL, $isnull=true){
	
	if(isset($additionnal)){$add = $additionnal; }else{ $add = '';}

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

/*
@ CREER UN ELEMENT DE FORMULAIRE SELECT
@ avec id différent de l'attribut name
@
*/
function createCombobox($array, $name='', $id = NULL, $selectValue=NULL, $additionnal=NULL, $isnull=true){
	
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



//// CREER UN GROUPE DE CASE A COCHER
function createCheckBox($array, $name='', $id=NULL){

	$selectItem = '';

	foreach($array as $key => $value){
		// $array->select	|	$array->value	|	$array->label	| $array->classe
		$classe = isset($value->classe)?' class="'.$value->classe.'" ':'';
		$checked= !empty($value->select)?'checked="checked"':'';
		$selectItem .= '<span><input type="checkbox" name="'.$name.'" value="'.$value->value.'" id="'.$id.'-'.$key.'" '.$classe.' '.$checked.'/><label for="'.$id.'-'.$key.'">'.$value->label.'</label></span>'."\n";
	}

	return $selectItem ;
}


// TRANSFORMER UNE CHAINE EN IDENTIFIANT : PAS D'ACCENTS, PAS D'ESPACES
function makeIdentifier($valeur){
	$valeur = strtr($valeur,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
	$valeur = preg_replace('/([^.a-z0-9]+)/i', '_', $valeur);
	
	return $valeur;
}

function clearvideo($texte){
	$texte = preg_replace("/<p>(.+)<\/p>/Ui",'',$texte);
	$texte = strip_tags($texte,'<object><param><embed>');
	return $texte;
}

function makedate($date,$lang='fr'){
	date_default_timezone_set("Europe/Paris");
	$format['fr'] = "d/m/y";
	$format['en'] = "y/m/d";
	
	return date($format[$lang],strtotime($date));
}

function dateNewsletter($date,$tab_mois=NULL,$tab_jours=NULL,$lang='fr'){
	date_default_timezone_set("Europe/Paris");
	$format['fr'] = "N-j-m";
	//$format['en'] = "Y F";

	$laDate = date($format[$lang],strtotime($date));
		
	//if($tab_mois && $tab_jours){
		$dTemp = explode('-',$laDate);

		$retour = $tab_jours[$dTemp[0]].' '.$dTemp[1].' '.$tab_mois[$dTemp[2]];

		return $retour;
	/*}else{
		return $laDate;
	}*/
}
function horaireNewsletter($date,$lang='fr'){
	date_default_timezone_set("Europe/Paris");
	$format['fr'] = "G\hi";
	//$format['en'] = "Y F";

	return date($format[$lang],strtotime($date));
}

function clean($valeur){
	return strtolower(utf8_encode(strtr(utf8_decode($valeur), 	utf8_decode("àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'" ), 
        				  							utf8_decode("aaaaaceeeeiiiinooooouuuuyyaaaaaceeeeiiiinooooouuuuy " ))));

}


/////////////////////////////////////////////////
//////////// FONCTIONS DE FICHIERS //////////////
/////////////////////////////////////////////////


//// UPLOADER UN FICHIER
function upload($file, $repository){
	
	
	$name = $file["name"];

	$name = strtr($name,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
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


/// SUPPRIMER UN FICHIER
function delete($name, $repository){
	/*
	$pos = strrpos($name, '.');
	$extension = substr($name, $pos, strlen($name) );
	$nom = substr($name, 0, $pos);
	*/
	unlink($repository.$name);
}

/// SUPPRIMER UN DOSSIER
function delete_dir($chemin) {
	// vérifie si le nom du repertoire contient "/" à la fin
	// place le pointeur en fin d'url
	if ($chemin[strlen($chemin)-1] != '/'){
		// rajoute '/'
		$chemin .= '/';
	}

	if (is_dir($chemin)) {
		 echo $chemin;
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

function sup_repertoire($chemin) {

	// vérifie si le nom du repertoire contient "/" à la fin
	if ($chemin[strlen($chemin)-1] != '/') // place le pointeur en fin d'url
	{ $chemin .= '/'; } // rajoute '/'
	
	if (is_dir($chemin)) {
	$sq = opendir($chemin); // lecture
	while ($f = readdir($sq)) {
	if ($f != '.' && $f != '..')
	{
	$fichier = $chemin.$f; // chemin fichier
	if (is_dir($fichier))
	{sup_repertoire($fichier);} // rapel la fonction de manière récursive
	else
	{unlink($fichier);} // sup le fichier
	}
	}
	closedir($d);
	rmdir($chemin); // sup le répertoire
				 }
	else {
	unlink($chemin);  // sup le fichier
	}
}


//// REDIMENTIONNER UNE IMAGE
function resize($name, $repository, $largeurMax, $hauteurMax, $vignette=false){
		
	$pos = strrpos($name, '.');
	$extension = substr($name, $pos, strlen($name) );
	$nom = substr($name, 0, $pos);
	
	if($vignette == true){
		//copy($repository.$name,$repository.$nom."_mini".$extension);
		//$img =  $repository.$nom."_mini".$extension;
		copy($repository.$name,"../vignettes/".$nom.$extension);
		$img = "../vignettes/".$nom.$extension;
	}else{
		$img = $repository.$name;
	}
	
	$size = getimagesize($img);
	
	
	$largeurInitiale = $size[0] ;
	$hauteurInitiale = $size[1] ; 


	if($largeurInitiale>$largeurMax || $hauteurInitiale>$hauteurMax ){
		if( ($largeurInitiale/$largeurMax)>($hauteurInitiale/$hauteurMax)  ){
				$largeurNew = $largeurMax;
				$hauteurNew = (($largeurNew/$largeurInitiale)*$hauteurInitiale);
		}else{
				$hauteurNew = $hauteurMax;
				$largeurNew = (($hauteurNew/$hauteurInitiale)*$largeurInitiale);
		}
		
		switch( strtolower($extension) ){
			case ".gif" : $src_img = imagecreatefromgif($img);  break;
			case ".jpg" : $src_img = imagecreatefromjpeg($img); break;
			default: echo "L'image n'est pas dans un bon format"; exit();
		}
		

		$dst_img = imagecreatetruecolor($largeurNew,$hauteurNew);
		
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $largeurNew,$hauteurNew, $largeurInitiale, $hauteurInitiale); 
		// la fonction qui redimensionne les photos
		imagejpeg($dst_img, $img , 90);
		
		imagedestroy($src_img);
		imagedestroy($dst_img);
	}
	
	return $largeurNew;
}

function checkAdmin($user_info=NULL,$groupe_item=NULL,$user_item=NULL){
	if(isset($user_info)){
		if($user_info->groupe == "admin"){
			return true;	
		}else if(($user_info->groupe==$groupe_item || $groupe_item=="all") && $groupe_item!="aucun"){
			return true;
		}else if($user_info->id==$user_item){
			return true;
		}else{
			return false;	
		}
	}else{
		return false;	
	}
}

function random($car=10) {
	$string = "";
	$chaine = "abcdefghijklmnpqrstuvwxy0123456789";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
	}
	return $string;
}

function close_dangling_tags($html){
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


function arraytolower(array $array, $round = 0){ 
  return unserialize(strtolower(serialize($array))); 
}

function createFolderSelect($_folder=NULL,$_name=NULL,$_id=NULL,$_selectValue=NULL){
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

function encodeAccentHTML($data = NULL){
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

//// virer les sauts de ligne
// GILDAS 19/07/2012
function nonl($str){
	$str = str_replace("\n", "", $str);
	$str = str_replace("\r\n", "", $str);
	$str = str_replace("\r", "", $str);
	return $str;
}

//// <br /> -> newline
// GILDAS 19/07/2012
function br2nl($str){
	$str = preg_replace("#\<br\s*\/?\>#isU", '
', $str);
	return $str;
}

?>