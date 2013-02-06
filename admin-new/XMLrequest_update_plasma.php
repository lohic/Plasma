<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slideshow.php');
include_once('../classe/classe_playlist.php');
include_once("../classe/classe_fonctions.php");

$core = new core();

if($core->isAdmin){ 

	$id_playlist = !empty($_POST['id_target'])? $_POST['id_target'] : NULL;

	$playlist = new Playlist($id_playlist);
	
	//echo $_POST['id_ecran']." ".substr($_POST['suppr_id_rel_slide'],1).' |||| ';
	
	$new_id = array();
	
	//$i = 0;
		
	$playlist->del_rel_slide(substr($_POST['suppr_id_rel_slide'],1));
		
		
	foreach($_POST['id_rel'] as $key=>$id){
		
		if(empty($id)) $id = NULL;
		
		//echo $_POST['typerel'][$key];
		
		//echo '<p>key : '.$key.' -> '.$_POST['typerel'][$key].'</p>';
		
		if(isset($_POST['typerel'][$key])){
			
			$_array_val = array();
			
			
			if($_POST['j'][$key] != NULL){
				$jour = '"j":"'.$_POST['j'][$key].'"';
			}else if($_POST['J'][$key] != NULL){
				$jour = '"J":"'.$_POST['J'][$key].'"';
			}		
			
			$json = !empty($jour) ? '{"M":"'.$_POST['M'][$key].'",'.$jour.',"H":"'.$_POST['H'][$key].'"}' : NULL;
							
			$_array_val['freq']			= $json; 
			
						
			$_array_val['ordre']		= $_POST['ordre'][$key];
			$_array_val['duree'] 		= $_POST['duree'][$key];
			$_array_val['date'] 		= $_POST['date'][$key]; 
			$_array_val['time'] 		= $_POST['time'][$key]; 
			$_array_val['id_slide']		= $_POST['id_slide'][$key];
			$_array_val['type']			= $_POST['typerel'][$key];
			$_array_val['ordre']		= $_POST['ordre'][$key];
			$_array_val['alerte']		= $_POST['alerte'][$key];
			$_array_val['id_target']	= $_POST['id_target'];
			$_array_val['type_target']	= 'ecran';
	
			$id_created = $playlist->update_rel_slide($_array_val ,$id);
			
			if(!empty($id_created)) $new_id[$_POST['timestamp'][$key]] = $id_created;
			
			//$i++;
		}
	}
	
	echo json_encode($new_id);
	
	//echo implode(',',$new_id);	
}