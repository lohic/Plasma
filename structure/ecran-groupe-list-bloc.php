<div class="<?php echo $class_groupe; ?>">
    <div class="infos">
        <p class="jour"><img src="../graphisme/monitor-group.png" alt="ecran"/>&nbsp;</p>
        <div class="image">
            <!--<a href="crop.php?id=243"><img src="upload/photos/evenement_243/mini-image.jpg?cache=1304592856" alt="Finale du Concours d'arbitrage international de Paris 2011" width="55" height="35"/></a>-->
        </div>
        <div class="titre_heure">
            <p class="titre">
                <a href="?page=ecrans_groupe_modif&id_groupe=<?php echo $id;?>" title="modifier" id="edit-<?php echo $id;?>" class="modif_groupe_ecran"><?php echo $nom;?></a>
            </p>
            <p>(<?php echo $nombre_ecran;?> écrans)</p>
        </div>
    </div>
    
    <div class="liens">
        <a href="?page=ecrans_groupe_modif&id_groupe=<?php echo $id;?>" title="modifier" id="edit2-<?php echo $id;?>" class="modif_groupe_ecran"><img src="../graphisme/pencil.png" alt="modifier"/></a>&nbsp;<br/>
        <a href="#" id="show-<?php echo $id;?>" class="show_children"><img src="../graphisme/round_plus.png" alt="archive" title="afficher les écrans" /></a>
  </div>
    
    <div class="places"></div>
    
    <div class="poubelle"></div>
</div>

<div class="child-screen">
<?php echo $ecrans;?>
</div>