<?php

include_once('../classe/classe_playlist.php');
include_once('../classe/classe_ecran.php');

$id_playlist 	= !empty($_GET['id_playlist'])?$_GET['id_playlist']:NULL;
$annee 			= isset($_GET['annee'])?$_GET['annee']:date('Y');
$mois 			= isset($_GET['mois']) ? $_GET['mois'] : date('m');

if(!isset($playlist)) $playlist	 	= new Playlist($id_playlist);
$ecran	 		= new Ecran();
$data			= $playlist->get_playlist_info();

$id_template	= !empty($id_template)?$id_template:'';


?>
<style>

.tiny{
	width:30px;
}

#return_refresh{
	background:#FF0;
	width:800px;
}

.icone{
	cursor:move;
}

</style>

<div class="form_container">
	<p class="intro_modif"><?php echo empty($data->id)?'Création':'Modification'?> d'une playlist :</p>
    <h3><?php echo $data->nom;?></h3>
    
    <form action="" method="post" id="modif_ecran_info_form">
        <input type="hidden" name="<?php echo empty($data->id)?'create':'update';?>" value="playlist"/>
        
        <fieldset>
            <p class="legend">Informations :</p>
            <input type="hidden" name="id_playlist" value="<?php echo $data->id; ?>" />
            
            <p>
                <label for="nom">nom de la playlist : </label>
                <input type="text" id="nom" name="nom" value="<?php echo $data->nom; ?>" class="inputField" />
            </p>
			
			<p>
                <label for="nom">date : </label>
                <input type="text" id="date" name="date" value="<?php echo $data->date; ?>" class="inputField date" />
            </p>
            
        </fieldset>
        <input type="submit" name="edit_user" class="buttonenregistrer" id="edit_user" value="<?php echo empty($data->id)?'Créer':'Modifier'?>" />
    </form>
	<div class="reset"></div>
	<?php if( !empty($data->id)){ ?>
   <div id="return_refresh"></div>
            
  <form action="XMLrequest_update_slide_rel.php" method="post" id="modif_slide_list_form">
      		 <input type="hidden" name="id_target" value="<?php echo $data->id; ?>" />  
			
			<fieldset>
                <p class="legend">
                	<a href="javascript:" id="add_date">
                    	<img src="../graphisme/round_plus.png" alt="ajouter un slide" height="16"/>
                    </a> date : ajouter des slides
                </p>
				<div class="reset"></div>
                <ul id="addslidedatelist">
                    <?php echo $playlist->get_slide_list('date'); ?>
                </ul>
        	</fieldset>
			
			<fieldset>
                <p class="legend">
                	<a href="javascript:" id="add_freq">
                    	<img src="../graphisme/round_plus.png" alt="ajouter un slide" height="16"/>
                    </a> fréquence : ajouter des slides
                </p>
				<div class="reset"></div>
                <ul id="addslidefreqlist">
                    <?php echo $playlist->get_slide_list('freq'); ?>
                </ul>
        	</fieldset>
		 
            <fieldset>
                <p class="legend">
                	<a href="javascript:" id="add_flux">
                    	<img src="../graphisme/round_plus.png" alt="ajouter un slide" height="16"/>
                    </a> séquentiel : ajouter des slides
                </p>
				<div class="reset"></div>
                <ul id="addslidefluxlist" class="sort">
                    <?php echo $playlist->get_slide_list('flux'); ?>
                </ul>
        	</fieldset>
			
    	<input type="hidden" value="" name="suppr_id_rel_slide" id="suppr_id_rel_slide">
	</form>
	
	
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