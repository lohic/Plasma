<?php

if($core->isAdmin){
	
include_once('../classe/classe_organisme.php');
//include_once('../vars/statics_vars.php');

global $typeTab;

$organisme = new organisme();
$id_organisme = NULL;

if(!isset($type))
	$type = NULL;


if(isset($_POST['suppr_organisme']) && $_POST['suppr_organisme'] == 'ok'){
	
	$organisme->suppr_organisme($_POST['id_suppr_organisme']);	
}

if(isset($_POST['suppr_user_groupe']) && $_POST['suppr_user_groupe'] == 'ok'){
	
	$organisme->suppr_user_groupe($_POST['id_suppr_user_groupe']);	
}

if(isset($_POST['create_organisme']) && $_POST['create_organisme'] == 'ok'){
	$val['nom']					= $_POST['nom'];
	$val['type']				= $_POST['type'];
	$val['google_analytics_id']	= $_POST['google_analytics_id'];
	

	$organisme->create_organisme($val);	
}

if(isset($_POST['create_user_groupe']) && $_POST['create_user_groupe'] == 'ok'){
	$val['nom']					= $_POST['nom'];
	$val['type']				= $_POST['type'];
	$val['id_organisme']		= $_POST['id_organisme'];
	

	$organisme->create_user_groupe($val);	
}


if(isset($_POST['modif_organisme']) && $_POST['modif_organisme'] == 'ok'){
	$val['id']					= $_POST['id'];
	$val['nom']					= $_POST['nom'];
	$val['type']				= $_POST['type'];
	$val['google_analytics_id']	= $_POST['google_analytics_id'];
	

	$organisme->create_organisme($val,$val['id']);	
}

if(isset($_POST['modif_user_groupe']) && $_POST['modif_user_groupe'] == 'ok'){
	$val['id']					= $_POST['id'];
	$val['nom']					= $_POST['nom'];
	$val['type']				= $_POST['type'];
	$val['id_organisme']		= $_POST['id_organisme'];

	$val['groupe_plasma']		= $_POST['groupe_plasma'];	

	$organisme->create_user_groupe($val,$val['id']);	
}

?>

<div class="form_container">
<div id="options">
	<p class="intro_modif">Gestion des</p>

	<div class="options">
        <a href="#" id="add_organisme">
            <img src="../graphisme/round_plus.png" alt="ajouter"/>
        </a>
    </div>

	<div class="reset"></div>
    <form action="" method="post" id="add_organisme_form">
        <fieldset>
        <p class="legend">Création d'un organisme :</p>
        	<input type="hidden" name="create_organisme" value="ok" />
            <input type="hidden" name="id" value="" />
    
            
            <p><label for="organisme_nom">nom : </label>
            <input type="text" id="organisme_nom" name="nom" value="" class="inputField" /></p>
            
            <p><label for="organisme_GA">ID google analytics : </label>
            <input type="text" id="organisme_GA" name="google_analytics_id" value="" class="inputField" /></p>
            
            <p><label for="organisme_type">type : </label>
            <?php echo func::createCombobox($organisme->get_admin_level(), 'type', 'organisme_type'	, $type, '', false);?></p>
           
            
        </fieldset>
        <input type="submit" name="edit_organisme" class="buttonenregistrer" id="edit_organisme" value="Créer" />
	</form>
    
    <div class="reset"></div>
    
    <form action="" method="post" id="add_user_groupe_form">
        <fieldset>
        <p class="legend">Création d'un groupe d'utilisateurs :</p>
        	<input type="hidden" name="create_user_groupe" value="ok" />
            
            <p><label for="user_nom">nom : </label>
            <input type="text" id="user_nom" name="nom" value="" class="inputField" /></p>
            
            <p><label for="user_type">type : </label>
            <?php echo func::createCombobox($organisme->get_admin_level(), 'type', 'user_type', $type, '', false);?></p>
            
            <p><label for="user_account_type">organisme : </label>
            <?php echo func::createCombobox($organisme->get_organisme_liste(), 'id_organisme', 'user_account_type' 	, $id_organisme, '', false);?></p>
            
        </fieldset>
        <input type="submit" name="edit_user_groupe" class="buttonenregistrer" id="edit_user_groupe" value="Créer" />
	</form>
 
</div>


<?php if($core->isAdmin && $core->userLevel<=1){ ?>
<h3>Liste des Organismes</h3>

<div id="organisme-list">
<?php echo $organisme->get_organisme_edit_liste(); ?>
</div>

<?php } ?>


<h3>Liste des Groupes d'utilisateurs</h3>
<div id="user-groupe-list">
<?php echo $organisme->get_user_groupe_edit_liste(); ?>
</div>

<form id="suppr_organisme_form" action="" method="post">
        <input type="hidden" name="suppr_organisme" id="suppr_organisme" value="ok" />
        <input type="hidden" name="id_suppr_organisme" id="id_suppr_organisme" value="" />
</form>


<form id="suppr_user_groupe_form" action="" method="post">
        <input type="hidden" name="suppr_user_groupe" id="suppr_user_groupe" value="ok" />
        <input type="hidden" name="id_suppr_user_groupe" id="id_suppr_user_groupe" value="" />
</form>



</div>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
	$('#add_organisme_form').hide();
	$('#add_user_groupe_form').hide();
	

	$('#add_organisme').click(function(){
		$('#add_organisme_form').slideToggle();
		$('#add_user_groupe_form').slideToggle();
		$('.edit').slideUp();
	});
	
	$('.edit').hide();
	
	$('.modif_organisme').click(function(){		
		$('#add_organisme_form').slideUp();
		$('.edit').removeClass('open');
		$('#form-'+$(this).attr('id')).addClass('open');
		$('.edit').not('.open').slideUp();
		$('.open').slideToggle();
	});
	
	$('.modif_user_groupe').click(function(){
		$('#add_user_groupe_form').slideUp();
		$('.edit').removeClass('open');
		$('#form-'+$(this).attr('id')).addClass('open');
		$('.edit').not('.open').slideUp();
		$('.open').slideToggle();
	});
	
});
	
function supprOrganisme(id, nom){
	if(confirm('Voulez vous supprimer l\'organisme '+nom+' ? Cette action est irréversible et supprimera tous les groupes et leurs liaisons associés.')){
		$('#id_suppr_organisme').val(id);
		$('#suppr_organisme_form').submit();
	}
}


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
