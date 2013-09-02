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
	<div class="options"> <a href="?page=ecrans_modif&id_plasma=<?php echo $data->id; ?>&publish=ecran"> Publier l'écran </a> </div>
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
		<!--<fieldset>
			<p>
				<label for="id_playlist_locale">playlist locale :</label>
				<?php echo func::createSelect($ecran->get_playlist_list(), 'id_playlist_locale', $data->id_playlist_locale, "", true ); ?>
			</p>
			<p>
				<label for="id_playlist_nationale">playlist nationale :</label>
				<?php echo func::createSelect($ecran->get_playlist_list(), 'id_playlist_nationale', $data->id_playlist_nationale, "", true ); ?>
			</p>
				
		</fieldset>-->
		<input type="submit" name="edit_user" class="buttonenregistrer" id="edit_user" value="<?php echo isset($id_plasma) ? 'Modifier' : 'Créer'; ?>" />
	</form>
	
	<?php if( !empty($data->id)){ ?>
	
	<div id="return_refresh"></div>
	<form action="XMLrequest_update_slide_rel.php" method="post" id="modif_slide_list_form">
		<input type="hidden" name="id_target" value="<?php echo $data->id; ?>" />
		<input type="hidden" name="type_target" value="ecran" />
		<fieldset>
			<p class="legend"> <a href="javascript:" id="add_alerte_locale"> <img src="../graphisme/round_plus.png" alt="ajouter un slide" height="16"/> </a> ajouter une alerte locale </p>
			<ul id="addslidealertelocale">
				<?php echo $ecran->get_slide_alerte_list('ecran',75000); ?>
			</ul>
		</fieldset>
		
		<?php if ($core->userLevel <=1 ){ ?>
		
		<fieldset>
			<p class="legend"> <a href="javascript:" id="add_alerte_nationale"> <img src="../graphisme/round_plus.png" alt="ajouter un slide" height="16"/> </a> ajouter une alerte nationale </p>
			<ul id="addslidealertnationale">
				<?php echo $ecran->get_slide_alerte_list('ecran','all',NULL); ?>
			</ul>
		</fieldset>
		
		<?php } ?>
		<input type="hidden" value="" name="suppr_id_rel_slide" id="suppr_id_rel_slide">
	</form>
	<div class="reset"></div>
</div>



<div id="slideselector">
	<div id="fleche"></div>
	<div id="liste">
		<div id="slide_select">
			<form id="slide_select_form" action="XMLrequest_get_slide_list.php" method="get">
				<input type="hidden" name="page" value="slides_select" />
				<?php echo func::createSelect($slide->get_slide_template_list($core->groups_id)	, 'id_template'	, $id_template	, "onchange=\"$('#slide_select_form').submit();\"",true);?>
				<?php echo func::createSelect($anneeListe, 'annee', $annee, "onchange=\"$('#slide_select_form').submit();\"", false ); ?>
				<?php echo func::createSelect($moisListe, 'mois', $mois, "onchange=\"$('#slide_select_form').submit();\"", false ); ?>
			</form>
		</div>
		<div id="id-selected-ecran"></div>
		<div id="slidelisting">
		</div>

	</div>
</div>

<?php include_once('../structure/javascript-add-slide.php'); ?>

<?php } ?>
