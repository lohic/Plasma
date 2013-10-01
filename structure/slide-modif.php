<?php

$org 			= NULL;
$annee 			= isset($_GET['annee'])?$_GET['annee']:date('Y');
$mois 			= isset($_GET['mois']) ? $_GET['mois'] : date('m');

?>

<div class="form_container">


	<p class="intro_modif">Création d'un</p>
    <h3>Slide</h3>
	
	<div class="reset"></div>
	
	<div id="content" class="menu">
		
		<form id="slide_create" action="" method="post">
			<fieldset>
            	<p class="legend">Informations :</p>
                
                <p><label for="nom_slide">Nom du slide :</label><input name="nom_slide" type="text" value="" /></p>
                
                <p><label>Choix du template :</label>
                <?php
                global $templateListe;
                echo func::createSelect($templateListe, 'template_slide', NULL, NULL, false);
                ?></p>
                
                
                <input name="create" type="hidden" value="slide" />	
			</fieldset>
            <input name="submit" type="submit" value="OK"  class="buttonenregistrer"/>
		</form>
	
	</div>

</div>