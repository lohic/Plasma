<?php

if($core->isAdmin){
	
include_once('../classe/classe_organisme.php');

global $typeTab;

$organisme = new organisme();
$id_organisme = NULL;

if(!isset($type))
	$type = NULL;



if(isset($_POST['suppr_user_groupe']) && $_POST['suppr_user_groupe'] == 'ok'){
	
	$organisme->suppr_user_groupe($_POST['id_suppr_user_groupe']);	
}


if(isset($_POST['create_user_groupe']) && $_POST['create_user_groupe'] == 'ok'){
	$val['nom']					= $_POST['nom'];
	$val['type']				= $_POST['type'];
	$val['id_organisme']		= $_POST['id_organisme'];
	

	$organisme->create_user_groupe($val);	
}



if(isset($_POST['modif_user_groupe']) && $_POST['modif_user_groupe'] == 'ok'){
	$val['id']					= $_POST['id'];
	$val['nom']					= $_POST['nom'];
	$val['type']				= $_POST['type'];
	$val['id_organisme']		= $_POST['id_organisme'];
	

	$organisme->create_user_groupe($val,$val['id']);	
}

?>

<div class="form_container">
<div id="options">
	<p class="intro_modif">Gestion des</p>

	<div class="options">
        <a href="#" id="add_user_groupe">
            <img src="../graphisme/round_plus.png" alt="ajouter"/>
        </a>
    </div>

    
    <div class="reset"></div>
    
    <form action="" method="post" id="add_user_groupe_form">
        <fieldset>
        <p class="legend">Création d'un groupe d'utilisateurs :</p>
        	<input type="hidden" name="create_user_groupe" value="ok" />
            
            <p><label for="user_nom">nom : </label>
            <input type="text" id="user_nom" name="nom" value="" class="inputField" /></p>
            
            <p><label for="user_type">type : </label>
            <?php echo func::createCombobox($typeTab, 'type', 'user_type', $type, '', false);?></p>
            
            <p><label for="user_account_type">organisme : </label>
            <?php echo func::createCombobox($organisme->get_organisme_liste(), 'id_organisme', 'user_account_type' 	, $id_organisme, '', false);?></p>
            
        </fieldset>
        <input type="submit" name="edit_user_groupe" class="buttonenregistrer" id="edit_user_groupe" value="Créer" />
	</form>
 
</div>



<h3>Liste des Groupes d'écrans</h3>
<div id="user-groupe-list">
<?php echo $organisme->get_user_groupe_edit_liste(); ?>
</div>


<form id="suppr_user_groupe_form" action="" method="post">
        <input type="hidden" name="suppr_user_groupe" id="suppr_user_groupe" value="ok" />
        <input type="hidden" name="id_suppr_user_groupe" id="id_suppr_user_groupe" value="" />
</form>



</div>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
	
	$('#add_user_groupe_form').hide();
	
	$('#add_user_groupe').click(function(){
		$('#add_user_groupe_form').slideToggle();
		$('.edit').slideUp();
	});

	$('.edit').hide();
	
	$('.modif_user_groupe').click(function(){
		$('#add_user_groupe_form').slideUp();
		$('.edit').removeClass('open');
		$('#form-'+$(this).attr('id')).addClass('open');
		$('.edit').not('.open').slideUp();
		$('.open').slideToggle();
	});
	
});

function supprUserGroupe(id, nom){
	if(confirm('Voulez vous supprimer le groupe d\'utilisateurs '+nom+' ? Cette action est irréversible, et supprimera toutes les liaisons vers les gabarits, groupes de destinataires ou catégories d\'actualités.')){
		$('#id_suppr_user_groupe').val(id);
		$('#suppr_user_groupe_form').submit();
	}
}
</script>

<?php }else{ ?>
<p>Vous n'êtes pas administrateur</p>
<?php } ?>
