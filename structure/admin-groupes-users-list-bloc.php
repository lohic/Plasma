<div class="<?php echo $class; ?>">
    <div class="infos">
        <p class="jour"></p>
        <div class="image">
        	<img src="../graphisme/user.png" width="32" height="32" alt="modifier"/>
        </div>
    
    
        <div class="titre_heure">
            <p class="titre"><a href="#" title="modifier"><?php echo $account_type=='ldap'?'LDAP : ':'MAIL : ';echo $prenom.' '.$nom; ?></a></p>
            <p><?php echo $email; ?></p>
        </div>
    </div>
    
    <div class="liens">
        <a href="#" title="modifier" id="edit-<?php echo $id; ?>" class="modif_user"><img src="../graphisme/pencil.png" alt="modifier"/></a>
    </div>
    
    <div class="places">
    </div>
    
    <div class="poubelle">
        <a href="#" onClick="supprUser(<?php echo $id; ?>,'<?php echo $nom; ?>')" title="supprimer"><img src="../graphisme/trash.png" alt="supprimer"/></a>
    </div>
</div>
<div class="<?php echo $class; ?> edit" id="form-edit-<?php echo $id; ?>">
	<form action="" method="post" id="modif_user_form_<?php echo $id; ?>">
        <fieldset>
        <p class="legend">Informations :</p>
        	<input type="hidden" name="modif_user" value="ok" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <input type="hidden" name="login" value="<?php echo $login; ?>" />
            
            <!--<p><label for="user_nom-<?php echo $id; ?>">login : </label>
            <input type="text" id="user_login-<?php echo $id; ?>" name="login" value="<?php echo $login; ?>" class="inputField" /></p>-->
            
            <p><label for="user_password-<?php echo $id; ?>">mot de passe : </label>
            <input type="text" id="user_password-<?php echo $id; ?>" name="password" value="<?php echo $password; ?>" class="inputField" /></p>
            
            <p><label for="user_prenom-<?php echo $id; ?>">prénom : </label>
            <input type="text" id="user_prenom-<?php echo $id; ?>" name="prenom" value="<?php echo $prenom; ?>" class="inputField" /></p>
            
            <p><label for="user_nom-<?php echo $id; ?>">nom : </label>
            <input type="text" id="user_nom-<?php echo $id; ?>" name="nom" value="<?php echo $nom; ?>" class="inputField" /></p>
            
            <p><label for="user_email-<?php echo $id; ?>">email : </label>
            <input type="text" id="user_email-<?php echo $id; ?>" name="email" value="<?php echo $email; ?>" class="inputField" /></p>
            
            <p><label for="user_type-<?php echo $id; ?>">type : </label>
            <!--input type="text" id="user_type-<?php echo $id; ?>" name="type" value="<?php echo $type; ?>" class="inputField" />-->
            <?php echo func::createCombobox($typeTab, 'type', 'user_type-'.$id	, $type, '', false);?></p>
            
            <p><label for="user_account_type-<?php echo $id; ?>">type de compte: </label>
            <!--<input type="text" id="user_account_type-<?php echo $id; ?>" name="account_type" value="<?php echo $account_type; ?>" class="inputField" />-->
            <?php echo func::createCombobox($accountTypeTab, 'account_type', 'user_account_type-'.$id 	, $account_type, '', false);?></p>
            <?php //createCombobox($array, $name='', $id = NULL, $selectValue=NULL, $additionnal=NULL, $isnull=true);?>
            
        </fieldset>
        <fieldset>
        <p class="legend">Contact relié aux groupes :</p>
            <p><?php echo $groupes; ?></p>
        </fieldset>
        <input type="submit" name="edit_user" class="buttonenregistrer" id="edit_user" value="Modifier" />
	</form>
</div>