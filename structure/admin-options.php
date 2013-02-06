<?php

if($core->isAdmin){



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
        
		</fieldset>
        <input type="submit" name="" class="buttonenregistrer" id="" value="Créer" />
	</form>
 
</div>



<h3>???</h3>
<div id="user-groupe-list">
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
