<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/constantes_vars.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_ecran.php');
include_once("../classe/classe_fonctions.php");

$core = new core();

if($core->isAdmin){ 

	$ecran = new Ecran($_POST['id_target']);

	
	//echo $_POST['id_ecran']." ".substr($_POST['suppr_id_rel_slide'],1).' |||| ';
	
	$new_id = array();
	
	//$i = 0;
	
	$ecran->del_rel_slide(substr($_POST['suppr_id_rel_slide'],1));
		
		
	foreach($_POST['id_rel'] as $key=>$id){
		
		if(empty($id)) $id = NULL;
		
		//echo $_POST['typerel'][$key];
		
		//echo '<p>key : '.$key.' -> '.$_POST['typerel'][$key].'</p>';
		
		if(isset($_POST['typerel'][$key])){
			
			$_array_val = array();
			
						
			if($_POST['typerel'][$key] == 'date'){
				// id -> id_date
				// id_target -> $id_ecran (automatique)
				// $_POST['id_slide'][$key] 
				// $_POST['date'][$key];
				// $_POST['time'][$key];
				// $_POST['duree'][$key];
				// $_POST['target'][$key]->'ecran';
				
				$tempDate = !empty($_POST['date'][$key]) ?	$_POST['date'][$key] : '0000-00-00';
				$tempTime = !empty($_POST['time'][$key]) ?	$_POST['time'][$key] : '00:00:00';
				
				$_array_val['date'] 	= $tempDate.' '.$tempTime;
			}
		
			if($_POST['typerel'][$key] == 'freq'){
				//echo $id.'=>';
				
				// id -> id_freq
				// id_target -> $id_ecran (automatique)
				// $_POST['id_slide'][$key] 
				// $_POST['J'][$key];
				// $_POST['j'][$key];
				// $_POST['M'][$key];
				// $_POST['H'][$key];
				// $_POST['duree'][$key];
				// $_POST['target'][$key]->'ecran';
				
				//echo '<p>M: '.$_POST['M'][$key].' J: '.$_POST['J'][$key].' j: '.$_POST['j'][$key].' H: '.$_POST['H'][$key].'</p>';
				
				if($_POST['j'][$key] != NULL){
					$jour = '"j":"'.$_POST['j'][$key].'"';
				}else if($_POST['J'][$key] != NULL){
					$jour = '"J":"'.$_POST['J'][$key].'"';
				}				
				
				$json = '{"M":"'.$_POST['M'][$key].'",'.$jour.',"H":"'.$_POST['H'][$key].'"}';
								
				$_array_val['freq']		= $json; 
			}
			
			$_array_val['duree'] 		= $_POST['duree'][$key]; 
			$_array_val['id_slide']		= $_POST['id_slide'][$key];
			$_array_val['type']			= $_POST['typerel'][$key];
			$_array_val['id_target']	= $_POST['id_target'];
			$_array_val['type_target']	= 'ecran';
	
			$id_created = $ecran->update_rel_slide($_array_val ,$id);
			
			if(!empty($id_created)) $new_id[$_POST['timestamp'][$key]] = $id_created;
			
			//$i++;
		}
	}
	
	echo json_encode($new_id);
	
	//echo implode(',',$new_id);
	
}
