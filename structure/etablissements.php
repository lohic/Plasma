<?php

if($core->isAdmin){
	
include_once('../classe/classe_etablissement.php');

global $typeTab;

$etablissement = new etablissement();
$id_etablissement = NULL;

if(!isset($type))
	$type = NULL;




?>

<div class="form_container">
<div id="options">
	<p class="intro_modif">Gestion des</p>

	<div class="options">
        <a href="#" id="add_etablissement">
            <img src="../graphisme/round_plus.png" alt="ajouter"/>
        </a>
    </div>

	<div class="reset"></div>
    <form action="" method="post" id="add_etablissement_form">
        <fieldset>
        <p class="legend">Création d'un établissement :</p>
        	<input type="hidden" name="create_etablissement" value="ok" />
            <input type="hidden" name="id" value="" />
    
            
            <p><label for="etablissement_nom">nom : </label>
            <input type="text" id="etablissement_nom" name="nom" value="" class="inputField" /></p>
            
            <p><label for="etablissement_ville-<?php echo $id; ?>">ville : </label>
			<input type="text" id="etablissement_ville-<?php echo $id; ?>" name="ville" value="" class="inputField" /></p>
			
			<p><label for="etablissement_code_meteo-<?php echo $id; ?>">code meteo : </label>
			<input type="text" id="etablissement_code_meteo-<?php echo $id; ?>" name="code_meteo" value="" class="inputField" /></p>
			
			<p><label for="etablissement_code_postal-<?php echo $id; ?>">code postal : </label>
			<input type="text" id="etablissement_code_postal-<?php echo $id; ?>" name="code_postal" value="" class="inputField" /></p>

            
           
            
        </fieldset>
        <input type="submit" name="edit_etablissement" class="buttonenregistrer" id="edit_etablissement" value="Créer" />
	</form>
    
    <div class="reset"></div>
</div>


<?php if($core->isAdmin && $core->userLevel<=1){ ?>
<h3>Établissements</h3>

<div id="etablissement-list">
<?php echo $etablissement->get_etablissement_edit_liste(); ?>
</div>

<?php } ?>


<form id="suppr_etablissement_form" action="" method="post">
        <input type="hidden" name="suppr_etablissement" id="suppr_etablissement" value="ok" />
        <input type="hidden" name="id_suppr_etablissement" id="id_suppr_etablissement" value="" />
</form>


<form id="suppr_user_groupe_form" action="" method="post">
        <input type="hidden" name="suppr_user_groupe" id="suppr_user_groupe" value="ok" />
        <input type="hidden" name="id_suppr_user_groupe" id="id_suppr_user_groupe" value="" />
</form>



</div>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
	
	$('#add_etablissement_form').hide();

	$('#add_etablissement').click(function(){
		$('#add_etablissement_form').slideToggle();
		$('.edit').slideUp();
	});
	
	$('.edit').hide();
	
	$('.modif_etablissement').click(function(e){	
	
		$('#add_etablissement_form').slideUp();
		$('.edit').removeClass('open');
		$('#form-'+$(this).attr('id')).addClass('open');
		$('.edit').not('.open').slideUp();
		$('.open').slideToggle();
		
		e.preventDefault();
	});
	

	
});
	
function supprOrganisme(id, nom){
	if(confirm('Voulez vous supprimer l\'établissement '+nom+' ?')){
		$('#id_suppr_etablissement').val(id);
		$('#suppr_etablissement_form').submit();
	}
}

</script>

<?php }else{ ?>
<p>Vous n'êtes pas administrateur</p>
<?php } ?>
