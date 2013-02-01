<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
//include_once('../vars/constantes_vars.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slideshow.php');
include_once("../classe/classe_fonctions.php");

$core = new core();

if($core->isAdmin){ 

	$slideshow = new Slideshow(NULL,$_POST['id_target']);

	
	//echo $_POST['id_ecran']." ".substr($_POST['suppr_id_rel_slide'],1).' |||| ';
	
	$new_id = array();
	
	//$i = 0;
	
	$slideshow->del_rel_slide(substr($_POST['suppr_id_rel_slide'],1));
		
		
	foreach($_POST['id_rel'] as $key=>$id){
		
		if(empty($id)) $id = NULL;
		
		//echo $_POST['typerel'][$key];
		
		//echo '<p>key : '.$key.' -> '.$_POST['typerel'][$key].'</p>';
		
		if(isset($_POST['typerel'][$key])){
			
			$_array_val = array();
			
			$_array_val['ordre']		= $_POST['ordre'][$key];
			$_array_val['duree'] 		= $_POST['duree'][$key]; 
			$_array_val['id_slide']		= $_POST['id_slide'][$key];
			$_array_val['type']			= $_POST['typerel'][$key];
			$_array_val['id_target']	= $_POST['id_target'];
			$_array_val['type_target']	= 'slideshow';
	
			$id_created = $slideshow->update_rel_slide($_array_val ,$id);
			
			if(!empty($id_created)) $new_id[$_POST['timestamp'][$key]] = $id_created;
			
			//$i++;
		}
	}
	
	echo json_encode($new_id);
	
	//echo implode(',',$new_id);
	
}
