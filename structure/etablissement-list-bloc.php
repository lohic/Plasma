<div class="<?php echo $class; ?>">	
	<div class="infos">
        <p class="jour"></p>
        <div class="image">
        	<img src="../graphisme/cube.png" width="32" height="32" alt="modifier"/>
        </div>
    
    
        <div class="titre_heure">
            <p class="titre"><a href="#" title="modifier"><?php echo $nom; ?></a></p>
            <p></p>
        </div>
    </div>
    
    <div class="liens">
        <a href="#" title="modifier" id="edit-etablissement-<?php echo $id; ?>" class="modif_etablissement"><img src="../graphisme/pencil.png" alt="modifier"/></a>
    </div>
    
    <div class="places">
    </div>
    
    <div class="poubelle">
        <a href="#" onClick="supprOrganisme(<?php echo $id; ?>,'<?php echo addslashes($nom); ?>')" title="supprimer"><img src="../graphisme/trash.png" alt="supprimer"/></a>
    </div>
</div>
<div class="<?php echo $class; ?> edit" id="form-edit-etablissement-<?php echo $id; ?>">
	<form action="" method="post" id="modif_etablissement_form_<?php echo $id; ?>">
	    <fieldset>
        
		<div class="options">
        <a href="?page=etablissements&id_etablissement=<?php echo $id; ?>&publish=etablissement">
            Publier l'établissment
        </a>
    </div>
    
		
		<p class="legend">Informations :</p>
        	<input type="hidden" name="modif_etablissement" value="ok" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
    
            
            <p><label for="etablissement_nom-<?php echo $id; ?>">nom : </label>
            <input type="text" id="etablissement_nom-<?php echo $id; ?>" name="nom" value="<?php echo $nom; ?>" class="inputField" /></p>
			
			<p><label for="etablissement_ville-<?php echo $id; ?>">ville : </label>
			<input type="text" id="etablissement_ville-<?php echo $id; ?>" name="ville" value="<?php echo $ville; ?>" class="inputField" /></p>
			
			<p><label for="etablissement_code_meteo-<?php echo $id; ?>">code meteo : </label>
			<input type="text" id="etablissement_code_meteo-<?php echo $id; ?>" name="code_meteo" value="<?php echo $code_meteo; ?>" class="inputField" /></p>
			
			<p><label for="etablissement_code_postal-<?php echo $id; ?>">code postal : </label>
			<input type="text" id="etablissement_code_postal-<?php echo $id; ?>" name="code_postal" value="<?php echo $code_postal; ?>" class="inputField" /></p>
                
            
        </fieldset>
        <input type="submit" name="edit_etablissement" class="buttonenregistrer" id="edit_etablissement" value="Modifier" />
	</form>
</div>