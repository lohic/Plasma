<?php

include_once('../classe/classe_ecran.php');



$id_plasma 		= !empty($_GET['id_plasma'])?$_GET['id_plasma']:NULL;
$annee 			= isset($_GET['annee'])?$_GET['annee']:date('Y');
$mois 			= isset($_GET['mois']) ? $_GET['mois'] : date('m');
//$code_postal 	= isset($code_postal)?$code_postal:75000;

$ecran 	= new Ecran($id_plasma);
$data	= $ecran->get_info();

$id_template = !empty($id_template)?$id_template:0;

if(empty($data->id_groupe)){
	$id_groupe = !empty($_GET['id_groupe'])?$_GET['id_groupe']:NULL;
}else{
	$id_groupe = !empty($data->id_groupe)?$data->id_groupe:NULL;	
}

?>
<style>
.tiny {
	width: 30px;
}
#return_refresh {
	background: #FF0;
	width: 800px;
}
</style>

<div class="form_container">
	<p class="intro_modif"><?php echo isset($id_plasma) ? 'Modification' : 'Création'; ?> de l'écran : <a href="../slideshow/?plasma_id=<?php echo $data->id; ?>&preview&debug" target="_blank"><img src="../graphisme/eye.png" alt="voir"/></a></p>
	<h3><?php echo $data->nom; ?></h3>
	<!--<div class="options"> <a href="?page=ecrans_modif&id_plasma=<?php echo $data->id; ?>&publish=ecran"> Publier l'écran </a> </div>-->
	<form action="" method="post" id="modif_ecran_info_form">
		<input type="hidden" name="<?php echo isset($id_plasma)?'update':'create';?>" value="ecran"/>
		
		<!--<p>info user : <?php echo $core->userLevel ?></p>-->
		
		<fieldset>
			<p class="legend"> Informations :</p>
			<input type="hidden" name="id_ecran" value="<?php echo $data->id; ?>" />
			<p>
				<label for="nom">nom de l'écran : </label>
				<input type="text" id="nom" name="nom" value="<?php echo $data->nom; ?>" class="inputField" />
			</p>
			<p>
				<label for="id_etablissement">établissement de l'écran : </label>
				<?php echo func::createSelect($ecran->get_etablissement_list(), 'id_etablissement', $data->id_etablissement, "onchange=\"$('#news_select_form').submit();\"", false ); ?> </p>
			<p>
				<label for="id_groupe">écran relié au <a href="?page=ecrans_groupe_modif&id_groupe=<?php echo  $id_groupe;?>">groupe</a> :</label>
				<?php echo func::createSelect($ecran->get_ecran_groupe_list(), 'id_groupe', $id_groupe, "onchange=\"$('#news_select_form').submit();\"", false ); ?> </p>
		</fieldset>
		
		<input type="submit" name="edit_user" class="buttonenregistrer" id="edit_user" value="<?php echo isset($id_plasma) ? 'Modifier' : 'Créer'; ?>" />
	</form>
	
	
</div>

