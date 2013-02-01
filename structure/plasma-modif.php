<?php

include_once('../classe/classe_ecran.php');



$id_plasma 		= !empty($_GET['id_plasma'])?$_GET['id_plasma']:NULL;
$annee 			= isset($_GET['annee'])?$_GET['annee']:date('Y');
$mois 			= isset($_GET['mois']) ? $_GET['mois'] : date('m');
//$code_postal 	= isset($code_postal)?$code_postal:75000;


$ecran 	= new Ecran($id_plasma);
$data	= $ecran->get_info();

if(empty($data->id_groupe)){
	$id_groupe = $_GET['id_groupe'];
}else{
	$id_groupe = $data->id_groupe;	
}

?>

<style>

.tiny{
	width:30px;
}

#return_refresh{
	background:#FF0;
	width:800px;
}

</style>


<div class="form_container">
    <p class="intro_modif">Modification de l'écran : <a href="../slideshow/?plasma_id=<?php echo $data->id; ?>&preview&debug" target="_blank"><img src="../graphisme/eye.png" alt="voir"/></a></p>
    <h3><?php echo $data->nom; ?></h3>
	
	<div class="options">
        <a href="?page=ecrans_modif&id_plasma=<?php echo $data->id; ?>&publish=ecran">
            Publier l'écran
        </a>
    </div>
    
    <form action="" method="post" id="modif_ecran_info_form">
        <input type="hidden" name="<?php echo isset($id_plasma)?'update':'create';?>" value="ecran"/>
        
        <fieldset>
            <p class="legend"> Informations :</p>
            <input type="hidden" name="id_ecran" value="<?php echo $data->id; ?>" />
            
            <p>
                <label for="nom">nom de l'écran : </label>
                <input type="text" id="nom" name="nom" value="<?php echo $data->nom; ?>" class="inputField" />
            </p>
            
            <p>
                <label for="id_etablissement">établissement de l'écran : </label>
                <?php echo func::createSelect($ecran->get_etablissement_list(), 'id_etablissement', $data->id_etablissement, "onchange=\"$('#news_select_form').submit();\"", false ); ?>
            </p>
            
            <p>
                <label for="id_groupe">écran relié au <a href="?page=ecrans_groupe_modif&id_groupe=<?php echo  $id_groupe;?>">groupe</a> :</label>
                <?php echo func::createSelect($ecran->get_ecran_groupe_list(), 'id_groupe', $id_groupe, "onchange=\"$('#news_select_form').submit();\"", false ); ?>
            </p>
        </fieldset>
        <fieldset>
            <p>
                <label for="id_default_slideshow">slideshow par defaut :</label>
                <?php echo func::createSelect($ecran->get_slideshow_list(), 'id_default_slideshow', $data->id_default_slideshow, "onchange=\"$('#news_select_form').submit();\"", false ); ?>
            </p>
        </fieldset>
        <input type="submit" name="edit_user" class="buttonenregistrer" id="edit_user" value="Modifier" />
    </form>
    
	<div id="return_refresh"></div>
            
  <form action="XMLrequest_update_plasma.php" method="post" id="modif_slide_list_form">
      		 <input type="hidden" name="id_target" value="<?php echo $data->id; ?>" />      
            <fieldset>
                <p class="legend">
                	<a href="javascript:" id="add_date">
                    	<img src="../graphisme/round_plus.png" alt="ajouter un slide" height="16"/>
                    </a> date : ajouter des slides
                </p>
                <ul id="addslidedatelist">
					<?php echo $ecran->get_slide_date_list('ecran'); ?>
                </ul>
        	</fieldset>
            
            <fieldset>
                <p class="legend">
                	<a href="javascript:" id="add_freq">
                    	<img src="../graphisme/round_plus.png" alt="ajouter un slide" height="16"/>
                    </a> fréquence : ajouter des slides
                </p>
                <ul id="addslidefreqlist">
                    <?php echo $ecran->get_slide_freq_list('ecran'); ?>
                </ul>
        	</fieldset>
    	<input type="hidden" value="" name="suppr_id_rel_slide" id="suppr_id_rel_slide">
	</form>
    
    <div class="reset"></div>
</div>

<div id="slideselector">
	<div id="fleche"></div>
	<div id="liste">
		<div id="slide_select">
			<form id="slide_select_form" action="XMLrequest_get_slide_list" method="get">
				<input type="hidden" name="page" value="slides_select" />
				<?php echo func::createSelect($slide->get_slide_template_list($core->groups_id)	, 'id_template'		, $id_template	, "onchange=\"$('#slide_select_form').submit();\"",true);?>
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
