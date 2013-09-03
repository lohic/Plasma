<div class="<?php echo $class; ?>">
    <div class="infos">
        <p class="jour"><?php $temp = explode('-',$date); echo $temp[2]; ?></p>
        <div class="image">
            <a href="./?page=slide_modif&id_slide=<?php echo $id; ?>"><img src="<?php echo $icone;?>" alt="icone template" width="55" height="35"/></a>
        </div>
    
    
        <div class="titre_heure">
            <p class="titre"><a href="./?page=slide_modif&id_slide=<?php echo $id; ?>" title="modifier"><?php echo $nom; ?></a></p>
            <p><?php echo $template; ?></p>
        </div>
    </div>
    
    <div class="liens">
    
        <a href="./?page=slide_modif&id_slide=<?php echo $id; ?>" title="modifier"><img src="../graphisme/pencil.png" alt="modifier"/></a>
		&nbsp;
		<span class="slide_view" data-id-slide="<?php echo $id;?>"><a href="<?php echo ABSOLUTE_URL?>slideshow/?slide_id=<?php echo $id;?>&template=<?php echo $template;?>" target="_blank"" target="_blank"><img src="../graphisme/eye.png" alt="voir"/></a></span>
		<br/>
		
    </div>
    <div class="places">
        
    </div>
    <div class="poubelle">
        <a href="#" onclick="supprSlide(<?php echo $id; ?>,'<?php echo $nom; ?>')" title="supprimer"><img src="../graphisme/trash.png" alt="supprimer"/></a>
    
    </div>
</div>