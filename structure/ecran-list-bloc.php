<!-- ecran-list-bloc.php -->
<div class="<?php echo $class;?> ecran">
    <div class="meta">
        <p class="icon">
        	<a href="../slideshow/?plasma_id=<?php echo $id;?>" target="_blank" title="afficher l'écran: <?php echo $nom;?>">
        		<img src="<?php echo $icone;?>" alt="ecran" width="48" height="48"/>
        </a>
        </p>
        <p class="titre">
            <!--<a href="?page=ecrans_modif&id_plasma=<?php echo $id;?>" title="modifier l'écran: <?php echo $nom;?>" id="edit-<?php echo $id;?>" class="modif_plasma">--><?php echo $nom;?><!--</a>-->
        </p>
        <p class="ville"><?php echo $ville;?></p>
    </div>
    
    <div class="ecrans-liens">
        <!--<a href="?page=ecrans_modif&id_plasma=<?php echo $id;?>" title="modifier l'écran: <?php echo $nom;?>" id="edit-<?php echo $id;?>" class="modif_plasma"><img src="../graphisme/pencil.png" alt="modifier"/></a>-->
        <a href="<?php echo ABSOLUTE_URL?>slideshow/?plasma_id=<?php echo $id;?>" target="_blank" title="afficher en mode debug l'écran: <?php echo $nom;?>"><img src="../graphisme/eye.png" alt="voir"/></a>
        <a href="#" onClick="supprEcran(<?php echo $id;?>,'<?php echo $nom;?>')" title="supprimer" class="trash"><img src="../graphisme/trash.png" alt="supprimer"/></a>
    </div>

    <?php if($core->userLevel <= 1 ){?>
    <div class="regles" data-id="<?php echo $id;?>">
        <div class="regle">
            <input id="decalX" name="decalX" type="range" min="-100" max="100" step="1" value="<?php echo $decalX; ?>" />
            <output>X <span><?php echo $decalX; ?></span>px</output>
        </div>
        <div class="regle">
            <input id="decalY" name="decalY" type="range" min="-100" max="100" step="1" value="<?php echo $decalY; ?>" />
            <output>Y <span><?php echo $decalY; ?></span>px</output>
        </div>
        <div class="regle">
            <input id="scale" name="scale" type="range" min="50" max="150" step="1" value="<?php echo $scale; ?>" />
            <output><span><?php echo $scale; ?></span>%</output>
        </div>
    </div>
    <?php } ?>
</div>
<!-- FIN ecran-list-bloc.php -->