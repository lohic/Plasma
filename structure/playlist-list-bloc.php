<div class="<?php echo $class; ?>">
    <div class="infos">
        <p class="jour"><?php $temp = explode('-',$date); echo $temp[2]; ?></p>
        <div class="image">
            <!--<a href="./?page=playlist_modif&id_playlist=<?php echo $id; ?>"><img src="../graphisme/2x2_grid.png" alt="icone template" width="30" height="30"/></a>-->
        </div>
    
    
        <div class="titre_heure">
            <p class="titre"><a href="./?page=playlist_modif&id_playlist=<?php echo $id; ?>" title="modifier"><?php echo $nom; ?></a></p>
            <p><?php //echo $template; ?></p>
        </div>
    </div>
    
    <div class="liens">
    
        <a href="./?page=playlist_modif&id_playlist=<?php echo $id; ?>" title="modifier"><img src="../graphisme/pencil.png" alt="modifier"/></a>
		&nbsp;
		<!--<a href="../slideshow/?slide_id=<?php echo $id; ?>&preview" target="_blank"><img src="../graphisme/eye.png" alt="voir"/></a>-->
		<br/>
		
    </div>
    <div class="places">
        
    </div>
    <div class="poubelle">
        <a href="#" onClick="supprPlaylist(<?php echo $id; ?>,'<?php echo $nom; ?>')" title="supprimer"><img src="../graphisme/trash.png" alt="supprimer"/></a>
    
    </div>
</div>