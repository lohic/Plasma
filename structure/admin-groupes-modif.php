<?php

if($core->isAdmin){


include_once('../classe/classe_dest.php');


$dest	= new dest();


if(isset($_POST["modif_groupe"]) && !empty($_POST['id_groupe'])){

	$dest->update_groupe_dest_list($_POST['id_groupe'], $_POST['dest']);
}


?>
<div class="form_container">
	<p class="intro_modif">Liste des contacts du groupe</p>
	<h1><?php echo $dest->get_groupe_name($_GET['id_groupe']); ?></h1>


    <div class="reset"></div>

    <div id="groupe-list">
    	<form method="post" id="update_groupe_user_list">
        	<input type="hidden" id="id_groupe" name="id_groupe" value="<?php echo $_GET['id_groupe']; ?>" />
            <input type="hidden" id="modif_groupe" name="modif_groupe" value="ok" />
            
        	<fieldset>
       		 <?php echo $dest->get_groupe_contact_list($_GET['id_groupe']); ?>
        	</fieldset>
            <input type="submit" name="edit_groupe_user_list" class="buttonenregistrer" id="edit_groupe_user_list" value="Modifier"/>
        </form>
    </div>

	<div class="reset"></div>


	<script type="text/javascript" language="javascript">
    
    </script>
</div>

<?php }else{ ?>
<p>Vous n'êtes pas administrateur</p>
<?php } ?>