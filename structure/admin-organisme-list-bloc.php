<div class="<?php echo $class; ?>">	
	<div class="infos">
        <p class="jour"></p>
        <div class="image">
        	<img src="../graphisme/user.png" width="32" height="32" alt="modifier"/>
        </div>
    
    
        <div class="titre_heure">
            <p class="titre"><a href="#" title="modifier"><?php echo $nom; ?></a></p>
            <p></p>
        </div>
    </div>
    
    <div class="liens">
        <a href="#" title="modifier" id="edit-organisme-<?php echo $id; ?>" class="modif_organisme"><img src="../graphisme/pencil.png" alt="modifier"/></a>
    </div>
    
    <div class="places">
    </div>
    
    <div class="poubelle">
        <a href="#" onClick="supprOrganisme(<?php echo $id; ?>,'<?php echo addslashes($nom); ?>')" title="supprimer"><img src="../graphisme/trash.png" alt="supprimer"/></a>
    </div>
</div>
<div class="<?php echo $class; ?> edit" id="form-edit-organisme-<?php echo $id; ?>">
	<form action="" method="post" id="modif_organisme_form_<?php echo $id; ?>">
	    <fieldset>
        
		<div class="options">
        <a href="?page=etablissements&id_organisme=<?php echo $id; ?>&publish=true">
            Publier l'organisme
        </a>
    </div>
    
		
		<p class="legend">Informations :</p>
        	<input type="hidden" name="modif_organisme" value="ok" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
    
            
            <p><label for="organisme_nom-<?php echo $id; ?>">nom : </label>
            <input type="text" id="organisme_nom-<?php echo $id; ?>" name="nom" value="<?php echo $nom; ?>" class="inputField" /></p>
            
            <p><label for="organisme_GA-<?php echo $id; ?>">ID google analytics : </label>
            <input type="text" id="organisme_GA-<?php echo $id; ?>" name="google_analytics_id" value="<?php echo $google_analytics_id; ?>" class="inputField" /></p>
            
            <p><label for="organisme_type-<?php echo $id; ?>">type : </label>
            <?php echo createCombobox($user_level, 'type', 'organisme_type-'.$id	, $type, '', false);?></p>
           
            
        </fieldset>
        <input type="submit" name="edit_organisme" class="buttonenregistrer" id="edit_organisme" value="Modifier" />
	</form>
</div>