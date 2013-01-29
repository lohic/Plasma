<?php

if($core->isAdmin){


include_once('../classe/classe_dest.php');


$dest	= new dest();
//$rss	= new rss();
//$actu	= new actu();



if(isset($_POST['create_groupe']) && !empty($_POST['libelle'])){
	$val['libelle'] = $_POST['libelle'];

}

if(isset($_POST["modif_groupe"]) && !empty($_POST['libelle']) && !empty($_POST['id'])){
	$val['libelle'] = $_POST['libelle'];
	$val['id'] = $_POST['id'];
		
	$val['groupe_admin']		= $_POST['groupe_admin'];

	$dest->modif_groupe($val);
}

if(isset($_POST["suppr_groupe"]) && !empty($_POST['id_suppr_groupe'])){
	$dest->suppr_groupe($_POST['id_suppr_groupe']);
}

?>
<div class="form_container">
	<!--<h1>Gestion des groupes de destinataires</h1>-->

     <div class="options">
        <a href="#" id="add_groupe">
            <img src="../graphisme/round_plus.png" alt="ajouter"/>
        </a>
    </div>


    <div class="reset"></div>
        
    <form id="suppr_groupe_form" action="" method="post">
            <input type="hidden" name="suppr_groupe" id="suppr_groupe" value="ok" />
            <input type="hidden" name="id_suppr_groupe" id="id_suppr_groupe" value="" />
    </form>
    
    <form id="add_groupe_form" action="" method="post" >
        <fieldset>
        <p class="legend">Ajouter un groupe de destinataires :</p>
            <input type="hidden" name="create_groupe" value="ok" />
            <label for="groupe_libelle">libellé : </label><input type="text" name="libelle" id="groupe_libelle" class="inputField" />
        </fieldset>
            <input type="submit" name="add_groupe" class="buttonenregistrer" id="add_groupe" value="Ajouter" />
    </form>
    <div id="groupe-list">
        <?php echo $dest->get_groupe_list(); ?>
    </div>

	<div class="reset"></div>


	<script type="text/javascript" language="javascript">
    $(document).ready(function(){
            $('#add_groupe_form').hide();
        
            $('#add_groupe').click(function(){
                $('#add_groupe_form').slideToggle();
                $('.edit').slideUp();
            });
            
            $('.edit').hide();
            
            $('.modif_groupe').click(function(){
                $('#add_groupe_form').slideUp();
                $('.edit').removeClass('open');
                $('#form-'+$(this).attr('id')).addClass('open');
                $('.edit').not('.open').slideUp();
                $('.open').slideToggle();
            });
    });
	
	function supprGroup(id,nom){
		if(confirm('Voulez vous supprimer le groupe '+nom+' ? Cette action est irréversible.')){
			$('#id_suppr_groupe').val(id);
			$('#suppr_groupe_form').submit();
		}
	}
    </script>
</div>

<?php }else{ ?>
<p>Vous n'êtes pas administrateur</p>
<?php } ?>