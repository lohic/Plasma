<?php

include_once('../classe/classe_ecran.php');



$id_plasma_groupe 	= !empty($_GET['id_groupe'])?$_GET['id_groupe']:NULL;
$annee 				= isset($_GET['annee'])?$_GET['annee']:date('Y');
$mois 				= isset($_GET['mois']) ? $_GET['mois'] : date('m');
//$code_postal 	= isset($code_postal)?$code_postal:75000;
$id_groupe 			= !empty($_GET['id_groupe']) ? $_GET['id_groupe'] : NULL;

$ecran 	= new Ecran();
$data	= $ecran->get_groupe_info($id_groupe);

$id_template = !empty($id_template)?$id_template:0;

$child_screen = $ecran->get_admin_ecran_list( $id_groupe);

$isGroup = true;

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
	<p class="intro_modif">Modification du groupe d'écrans:<!-- <a href="../slideshow/?plasma_id=<?php echo $data->id; ?>&preview&debug" target="_blank"><img src="../graphisme/eye.png" alt="voir"/>--></a></p>
	<h3><?php echo $data->nom;?></h3>
	<div class="options"> <a href="?page=ecrans_groupe_modif&id_groupe=<?php echo $data->id; ?>&publish=groupe"> Publier le groupe </a> </div>
	<form action="" method="post" id="modif_ecran_info_form">
		<input type="hidden" name="<?php echo isset($id_groupe)?'update':'create';?>" value="groupe"/>
		
		<p>info user : <?php echo $core->userLevel ?></p>
		
		<fieldset>
			<p class="legend">Informations :</p>
			
			<input type="hidden" name="id_groupe" value="<?php echo $data->id; ?>" />
			
			<p>
				<label for="nom">nom du groupe : </label>
				<input type="text" id="nom" name="nom" value="<?php echo $data->nom; ?>" class="inputField" />
			</p>
			<p><?php echo $child_screen->nombre>1?"($child_screen->nombre) écrans ":"($child_screen->nombre) écran "?></p>
		</fieldset>
		<fieldset>
			<p>
				<label for="id_playlist_locale">playlist locale :</label>
				<?php echo func::createSelect($ecran->get_playlist_list(), 'id_playlist_locale', $data->id_playlist_locale, "", true ); ?>
			</p>
			<p>
				<label for="id_playlist_nationale">playlist nationale :</label>
				<?php echo func::createSelect($ecran->get_playlist_list(), 'id_playlist_nationale', $data->id_playlist_nationale, "", true ); ?>
			</p>
			
		</fieldset>
		<input type="submit" name="edit_user" class="buttonenregistrer" id="edit_user" value="Modifier" />
	</form>
	
	<?php if( !empty($data->id)){ ?>
	
	<div id="return_refresh"></div>
	<form action="XMLrequest_update_plasma_groupe.php" method="post" id="modif_slide_list_form">
		<input type="hidden" name="id_target" value="<?php echo $data->id; ?>" />
		<fieldset>
			<p class="legend"> <a href="javascript:" id="add_alerte_locale"> <img src="../graphisme/round_plus.png" alt="ajouter un slide" height="16"/> </a> ajouter une alerte locale </p>
			<ul id="addslidealertloc">
				<?php echo $ecran->get_slide_alerte_list('groupe','locale',$data->id); ?>
			</ul>
		</fieldset>
		<fieldset>
			<p class="legend"> <a href="javascript:" id="add_alerte_nationale"> <img src="../graphisme/round_plus.png" alt="ajouter un slide" height="16"/> </a> ajouter une alerte nationale </p>
			<ul id="addslidealertnat">
				<?php echo $ecran->get_slide_alerte_list('groupe','nationale',$data->id); ?>
			</ul>
		</fieldset>
		<input type="hidden" value="" name="suppr_id_rel_slide" id="suppr_id_rel_slide">
	</form>
	<div class="reset"></div>
	<div class="child-screen">
		<?php  echo $child_screen->ecrans;?>
	</div>
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