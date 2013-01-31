<div class="<?php echo $class; ?>">
    <div class="infos">
        <p class="jour"></p>
        <div class="image">
        	<img src="../graphisme/user.png" width="32" height="32" alt="modifier"/>
        </div>
    
    
        <div class="titre_heure">
            <p class="titre"><a href="#" title="modifier"><?php echo $nom; ?></a></p>
            <p><?php //echo $email; ?></p>
        </div>
    </div>
    
    <div class="liens">
        <a href="#" title="modifier" id="edit-user_groupe-<?php echo $id; ?>" class="modif_user_groupe"><img src="../graphisme/pencil.png" alt="modifier"/></a>
    </div>
    
    <div class="places">
    </div>
    
    <div class="poubelle">
        <a href="#" onClick="supprUserGroupe(<?php echo $id; ?>,'<?php echo addslashes($nom); ?>')" title="supprimer"><img src="../graphisme/trash.png" alt="supprimer"/></a>
    </div>
</div>
<div class="<?php echo $class; ?> edit" id="form-edit-user_groupe-<?php echo $id; ?>">
	<form action="" method="post" id="modif_user_groupe_form_<?php echo $id; ?>">
        <fieldset>
        <p class="legend">Informations :</p>
        	<input type="hidden" name="modif_user_groupe" value="ok" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            
            <p><label for="user_nom-<?php echo $id; ?>">nom : </label>
            <input type="text" id="user_nom-<?php echo $id; ?>" name="nom" value="<?php echo $nom; ?>" class="inputField" /></p>
            
            <p><label for="user_type-<?php echo $id; ?>">type : </label>
            <!--input type="text" id="user_type-<?php echo $id; ?>" name="type" value="<?php echo $type; ?>" class="inputField" />-->
            <?php echo createCombobox($user_level, 'type', 'user_type-'.$id	, $type, '', false);?></p>
            
            <p><label for="user_account_type-<?php echo $id; ?>">organisme : </label>
            <!--<input type="text" id="user_account_type-<?php echo $id; ?>" name="account_type" value="<?php echo $account_type; ?>" class="inputField" />-->
            <?php echo createCombobox($organismes, 'id_organisme', 'user_account_type-'.$id 	, $id_organisme, '', false);?></p>
            
        </fieldset>
        <input type="submit" name="edit_user_groupe" class="buttonenregistrer" id="edit_user_groupe" value="Modifier" />
	</form>
</div>