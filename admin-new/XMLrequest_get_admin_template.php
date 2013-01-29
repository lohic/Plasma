<?php
header('Content-type: text/html; charset=UTF-8');



include_once("../classe/classe_newsletter.php");
include_once('../vars/statics_vars.php');




if(!empty($_POST['id']) && $_POST['is_archive']==0){
	
	$id_newsletter = $_POST['id'];

	if(!isset($news)){
		$news = new newsletter($id_newsletter);
	}
	
	$news->create_archive($id_newsletter);
	
	echo '<img src="../graphisme/padlock_closed.png" alt="archive" />';
}else{
	$id_newsletter = $_POST['id'];
	
	if(!isset($news)){
		$news = new newsletter($id_newsletter);
	}
	
	$news->unarchive($id_newsletter);
	
	echo '<img src="../graphisme/padlock_open.png" alt="archive" title="archiver la newsletter" />';
}
 

?>