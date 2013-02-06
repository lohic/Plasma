<div class="<?php echo $class; ?>">
    <div class="infos">
        <p class="jour"></p>
        <div class="image">
        	<img src="../graphisme/monitor-group.png" width="32" height="32" alt="modifier"/>
        </div>
    
    
        <div class="titre_heure">
            <p class="titre"><a href="#" title="modifier"><?php echo $data->nom; ?></a></p>
            <p><?php ?></p>
        </div>
    </div>
    
    <div class="liens">
        <a href="#" title="modifier" id="edit-<?php echo $data->id; ?>" class="modif_ecran_groupe"><img src="../graphisme/pencil.png" alt="modifier"/></a>
    </div>
    
    <div class="places">
    </div>
    
    <div class="poubelle">
        <a href="#" onClick="supprUser(<?php echo $data->id; ?>,'<?php echo $data->nom; ?>')" title="supprimer"><img src="../graphisme/trash.png" alt="supprimer"/></a>
    </div>
</div>
<div class="<?php echo $class; ?> edit" id="form-edit-<?php echo $data->id; ?>">
	<form action="" method="post" id="modif_ecran_groupe_form_<?php echo $data->id; ?>">
		<fieldset>
			<p class="legend">Informations :</p>
			<input type="hidden" name="update" value="groupe_ecran" />
			<input type="hidden" name="id" value="<?php echo $data->id; ?>" />		
			<p>
				<label for="nom">nom du groupe d'écran : </label>
				<input type="text" id="nom" name="nom" value="<?php echo $data->nom; ?>" class="inputField" />
			</p>
			<p>
				<label for="id_etablissement">établissement de l'écran : </label>
				<?php echo func::createSelect($data->etablissement_list, 'id_etablissement', $data->id_etablissement, "onchange=\"$('#news_select_form').submit();\"", false ); ?>
			</p>	
		</fieldset>
		<fieldset>
			<p>
				<label for="id_playlist_locale">playlist locale :</label>
				<?php echo func::createSelect($data->playlist_list, 'id_playlist_locale', $data->id_playlist_locale, "", true ); ?>
			</p>
			<p>
				<label for="id_playlist_nationale">playlist nationale :</label>
				<?php echo func::createSelect($data->playlist_list, 'id_playlist_nationale', $data->id_playlist_nationale, "", true ); ?>
			</p>
		</fieldset>
		<input type="submit" name="edit_ecran_groupe" class="buttonenregistrer" id="edit_ecran_groupe" value="Modifier" />
	</form>
</div>