<div class="<?php echo $class; ?>">
    <div class="infos">
        <p class="jour"></p>
        <div class="image">
        	<img src="../graphisme/users.png" width="32" height="32" alt="modifier"/>
        </div>
    
    
        <div class="titre_heure">
            <p class="titre"><a href="#" title="modifier"><?php echo $nom; ?></a></p>
            <p><?php echo $mail; ?></p>
        </div>
    </div>
    
    <div class="liens">
        <a href="#" title="modifier" id="edit-<?php echo $id; ?>" class="modif_groupe"><img src="../graphisme/pencil.png" alt="modifier"/></a><a href="?page=groupe_modif&id_groupe=<?php echo $id; ?>"><img src="../graphisme/eye.png" alt="voir la liste des archives" title="voir la liste des archives"/></a>
    </div>
    
    <div class="places">
    </div>
    
    <div class="poubelle">
        <a href="#" onclick="supprGroup(<?php echo $id; ?>,'<?php echo $nom; ?>')" title="supprimer"><img src="../graphisme/trash.png" alt="supprimer"/></a>
    </div>
</div>

<div class="<?php echo $class; ?> edit" id="form-edit-<?php echo $id; ?>">
	<form action="" method="post" id="modif_groupe_form_<?php echo $id; ?>">
        <fieldset>
        <p class="legend">Informations :</p>
        	<input type="hidden" name="modif_groupe" value="ok" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <p><label for="groupe_libelle-<?php echo $id; ?>">libellé : </label><input type="text" id="groupe_libelle-<?php echo $id; ?>" name="libelle" value="<?php echo $nom; ?>" class="inputField" /></p>
        </fieldset>
        <fieldset>
        <p class="legend">Groupe d'utilisateurs accessible aux groupes :</p>
            <p><?php echo $groupes_admin; ?></p>
        </fieldset>
        <input type="submit" name="edit_dest" class="buttonenregistrer" id="edit_dest" value="Modifier" />
	</form>
</div>