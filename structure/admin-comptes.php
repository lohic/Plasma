<?php

if($core->isAdmin){
	
include_once('../classe/classe_spuser.php');
//include_once('../vars/statics_vars.php');

$user = new spuser($core->plasma_db);

if(isset($_POST['suppr_user']) && !empty($_POST['suppr_user_id'])){
	$user->suppr_user($_POST['suppr_user_id']);	
}

if(!isset($type))
	$type			= NULL;

if(!isset($account_type))
	$account_type	= NULL;

if(isset($_POST['modif_user']) && $_POST['modif_user'] == 'ok'){
		
	$val['id']				= $_POST['id'];
	$val['login']			= $_POST['login'];
	$val['password']		= $_POST['password'];
	$val['type']			= $_POST['type'];
	$val['nom']				= $_POST['nom'];
	$val['prenom']			= $_POST['prenom'];
	$val['email']			= $_POST['email'];
	$val['account_type']	= $_POST['account_type'];
	
	$val['groupe_user']		= $_POST['groupe_user'];

	$user->modify_user($val);	
}


if(isset($_POST['add_user'])){
	$val['login']			= $_POST['login'];
	$val['password']		= $_POST['password'];
	$val['type']			= $_POST['type'];
	$val['nom']				= $_POST['nom'];
	$val['prenom']			= $_POST['prenom'];
	$val['email']			= $_POST['email'];
	$val['account_type']	= $_POST['account_type'];
	
	$val['groupe_user'] 	= $_POST['groupe_user'];

	$user->add_user($val);	
}

?>
<div id="options" class="form_container">
	<h3>Gestion des comptes d'accès</h3>

	<div class="options">
        <a href="#" id="add_user">
            <img src="../graphisme/round_plus.png" alt="ajouter"/>
        </a>
    </div>

	<div class="reset"></div>
	<form method="post" id="add_user_form">
        <fieldset>
        <p class="legend">Informations :</p>
        	<input type="hidden" name="add_user" value="ok" />
            <input type="hidden" name="id" value="" />
           
            <p><label for="login">login : </label>
            <input type="text" id="login" name="login" value="" class="inputField" /></p>
            
            <p><label for="user_password">mot de passe : </label>
            <input type="text" id="user_password" name="password" value="" class="inputField" /></p>
            
            <p><label for="user_prenom">prénom : </label>
            <input type="text" id="user_prenom" name="prenom" value="" class="inputField" /></p>
            
            <p><label for="user_nom">nom : </label>
            <input type="text" id="user_nom" name="nom" value="" class="inputField" /></p>
            
            <p><label for="user_email">email : </label>
            <input type="text" id="user_email" name="email" value="" class="inputField" /></p>
            
            <p><label for="user_type">type : </label>
            <?php echo createCombobox($typeTab, 'type', 'user_type'	, $type, '', false);?></p>
            
            <p><label for="user_account_type">type de compte: </label>
            <?php echo createCombobox($accountTypeTab, 'account_type', 'user_account_type' 	, $account_type, '', false);?></p>
            
        </fieldset>
        <fieldset>
        <p class="legend">Contact relié aux groupes :</p>
            <p><?php echo $core->user->get_groupe(); ?></p>
        </fieldset>
        <input type="submit" name="add_user" class="buttonenregistrer" id="add_user" value="Créer" />
	</form>
 
 	<form id="suppr_user_form" method="post">
    	<input type="hidden" name="suppr_user" value="ok" />
        <input type="hidden" name="suppr_user_id" id="suppr_user_id" value="" />
    </form>
 
</div>

<hr class="reset" />


<div id="user-list">
<?php echo $user->get_user_list(); ?>
</div>


<script type="text/javascript" language="javascript">
$(document).ready(function(){
	$('#add_user_form').hide();

	$('#add_user').click(function(){
		$('#add_user_form').slideToggle();
		$('.edit').slideUp();
	});
	
	$('.edit').hide();
	
	$('.modif_user').click(function(){
		$('#add_user_form').slideUp();
		$('.edit').removeClass('open');
		$('#form-'+$(this).attr('id')).addClass('open');
		$('.edit').not('.open').slideUp();
		$('.open').slideToggle();
	});
});
	
	function supprUser(id, nom){
		if(confirm('Voulez vous supprimer le contact '+nom+' ? Cette action est irréversible.')){
			$('#suppr_user_id').val(id);
			$('#suppr_user_form').submit();
		}
	}
</script>

<?php }else{ ?>
<p>Vous n'êtes pas administrateur</p>
<?php } ?>
