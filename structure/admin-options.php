<?php

if($core->isAdmin){
	
include_once('../classe/classe_ecran.php');

global $typeTab;

$ecran = new ecran();
$id_ecran = NULL;

if(!isset($type))
	$type = NULL;


?>

<div class="form_container">
<div id="options">
	<p class="intro_modif">Gestion des</p>

	<div class="options">
        <a href="#" id="add_ecran_groupe">
            <img src="../graphisme/round_plus.png" alt="ajouter"/>
        </a>
    </div>

    
    <div class="reset"></div>
    
    <form action="" method="post" id="add_ecran_groupe_form">
        <fieldset>
        <p class="legend">Création d'un groupe d'écrans :</p>
        	<input type="hidden" name="create" value="groupe_ecran" />
            
            <p><label for="ecran_nom">nom : </label>
            <input type="text" id="ecran_nom" name="nom" value="" class="inputField" /></p>
			<p>
				<label for="id_etablissement">établissement de l'écran : </label>
				<?php echo func::createSelect($ecran->get_etablissement_list(), 'id_etablissement', NULL, "onchange=\"$('#news_select_form').submit();\"", false ); ?>
			</p>	
		</fieldset>
		<fieldset>
			<p>
				<label for="id_playlist_locale">playlist locale :</label>
				<?php echo func::createSelect($ecran->get_playlist_list(), 'id_playlist_locale', NULL, "", true ); ?>
			</p>
			<p>
				<label for="id_playlist_nationale">playlist nationale :</label>
				<?php echo func::createSelect($ecran->get_playlist_list(), 'id_playlist_nationale', NULL, "", true ); ?>
			</p>
		</fieldset>
        <input type="submit" name="edit_ecran_groupe" class="buttonenregistrer" id="edit_ecran_groupe" value="Créer" />
	</form>
 
</div>



<h3>Liste des Groupes d'écrans</h3>
<div id="user-groupe-list">
<?php echo $ecran->get_ecran_groupe_edit_liste(); ?>
</div>


<form id="suppr_ecran_groupe_form" action="" method="post">
        <input type="hidden" name="suppr_ecran_groupe" id="suppr_ecran_groupe" value="ok" />
        <input type="hidden" name="id_suppr_ecran_groupe" id="id_suppr_ecran_groupe" value="" />
</form>



</div>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
	
	$('#add_ecran_groupe_form').hide();
	
	$('#add_ecran_groupe').click(function(){
		$('#add_ecran_groupe_form').slideToggle();
		$('.edit').slideUp();
	});

	$('.edit').hide();
	
	$('.modif_ecran_groupe').click(function(){
		$('#add_ecran_groupe_form').slideUp();
		$('.edit').removeClass('open');
		$('#form-'+$(this).attr('id')).addClass('open');
		$('.edit').not('.open').slideUp();
		$('.open').slideToggle();
	});
	
});

function supprUserGroupe(id, nom){
	if(confirm('Voulez vous supprimer le groupe d\'écrans '+nom+' ? Cette action est irréversible, et supprimera toutes les liaisons vers les gabarits, groupes de destinataires ou catégories d\'actualités.')){
		$('#id_suppr_ecran_groupe').val(id);
		$('#suppr_ecran_groupe_form').submit();
	}
}
</script>

<?php }else{ ?>
<p>Vous n'êtes pas administrateur</p>
<?php } ?>
