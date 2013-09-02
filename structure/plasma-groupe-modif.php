<?php

include_once('../classe/classe_ecran.php');



$id_plasma_groupe 	= !empty($_GET['id_groupe'])?$_GET['id_groupe']:NULL;
$annee 				= isset($_GET['annee'])?$_GET['annee']:date('Y');
$mois 				= isset($_GET['mois']) ? $_GET['mois'] : date('m');
//$code_postal 	= isset($code_postal)?$code_postal:75000;
$id_groupe 			= !empty($_GET['id_groupe']) ? $_GET['id_groupe'] : NULL;

$ecran 	= new Ecran();
$data	= $ecran->get_groupe_info($id_groupe);

$id_template = !empty($id_template)?$id_template:0;

$child_screen = !empty($id_groupe) ? $ecran->get_admin_ecran_list( $id_groupe) : 0;

$isGroup = true;

?>
<style>
.tiny {
	width: 30px;
}
#return_refresh {
	background: #FF0;
	width: 800px;
}

#preview_screen{
	display: none;
	position: absolute;
	z-index: 3000;
}
</style>
<script>
$(document).ready(function(){

	/**
	 * Pour afficher ou masque le bloc de preview
	 * quand on va sur l'icone de l'oeil d'un écran
	 */
	$(".child-screen .ecran").each(function(){

		$(this).find('img[alt="voir"]').mouseenter(function(){
			console.log('enter');

			$('#preview_screen').attr('src', $(this).parent().attr('href'));
			$('#preview_screen').show();

			$( "#preview_screen" ).position({
				of: $(this).parent().parent().parent(),
				my: "center bottom",
				at: "center top-10",
				collision: "none flip"
			});
		});

	});

	$(".child-screen .ecran").mouseleave(function(){
		$('#preview_screen').hide();
	});

});
</script>

<div class="form_container">
	<p class="intro_modif"><?php echo isset($id_groupe) ? 'Modification' : 'Création'; ?> du groupe d'écrans :</p>
	<h3><?php echo $data->nom;?></h3>
	<?php if ( isset($id_groupe) ){ ?>
	<div class="options">
		<!--<a href="?page=ecrans_groupe_modif&id_groupe=<?php echo $data->id; ?>&publish=groupe">Publier le groupe</a>-->
		<button type="button" id="group_publish" >Publier le groupe</button>
	</div>
	<?php } ?>

	<form action="" method="post" id="modif_ecran_info_form">
		<input type="hidden" name="<?php echo isset($id_groupe)?'update':'create';?>" value="groupe"/>
		
		<!--<p>info user : <?php echo $core->userLevel ?></p>-->
		
		<fieldset>
			<p class="legend">Informations :</p>
			
			<input type="hidden" name="id_groupe" value="<?php echo $data->id; ?>" />
			
			<p>
				<label for="nom">nom du groupe : </label>
				<input type="text" id="nom" name="nom" value="<?php echo $data->nom; ?>" class="inputField" />
			</p>
			<p>
				<label for="id_etablissement">établissement de l'écran : </label>
				<?php echo func::createSelect($ecran->get_etablissement_list(), 'id_etablissement', $data->id_etablissement, "onchange=\"$('#news_select_form').submit();\"", false ); ?>
			</p>
			<p><?php if(isset($child_screen->nombre)) echo $child_screen->nombre>1?"($child_screen->nombre) écrans ":"($child_screen->nombre) écran "?></p>
		</fieldset>

		<input type="submit" name="edit_groupe" class="buttonenregistrer" id="edit_groupe" value="<?php echo isset($id_groupe) ? 'Modifier' : 'Créer' ; ?>" />
	</form>

	<div class="reset"></div>

	<?php if ( isset($id_groupe) ){ ?>
	<div class="child-screen">
    <?php
    echo $ecran->get_admin_ecran_list($id_groupe)->ecrans;
    ?>
	</div>


	<div class="reset"></div>

	<p>Le début d'un slide est prioritaire sur sa fin.</p>
    
    <div class="reset"></div>   
	<p>
		<button id="show_previous">Afficher les 10 jours précédents</button>
		<button id="show_next">Afficher les 10 jours suivants</button>
	</p>
	<div class="reset"></div>
	
	<p>
		<input type="text" value="group" id="group" />
		<button id="add_screen">Ajouter un écran</button>
	</p>

	<iframe id="preview_screen" style="width:384px;height:216px;border:0;" src=""></iframe>


	<div id="mytimeline"></div>

	<form id="myform" style="width:500px"></form>
	<?php } ?>
</div>



<?php if ( isset($id_groupe) ){ ?>

<!--
EDITER LES INFORMATIONS D'UN ITEM DE LA TIMELINE
-->
<script id="slide_editor" type="text/html">
    <div style="width:500px">
        <h1 id="item_title">{{content}}</h1>
        <form>
        	<fieldset>
        		<div>
		            <p>
		            	<label>Alerte / Groupe / Écran :</label>
		                <select id="screen_reference" name="screen_reference">
		                    {{#group_selector}}
		                    <option value="{{ key }}">{{ value }}</option>
		                    {{/group_selector}}
		                </select>
		            </p>
		        </div>
		        <div>
		            <p>
		            	<label>Type de slide :</label>
		                <select id="template_reference" name="template_reference">
		                    {{#template_selector}}
		                    <option value="{{ key }}">{{ value }}</option>
		                    {{/template_selector}}
		                </select>
		            </p>
		        </div>
		        <div>
		            <p>
		            	<label>Publié oui/non :</label>
		                <input id="published" type="checkbox" name="published">
		            </p>
		        </div>
	            <div>
	                <h3>Durée : {{duree}}</h3>
	                <p><span class="date">début :</span> {{annee1}}/{{mois1}}/{{jour1}} {{heure1}}:{{minute1}}:{{seconde1}}</p>
	                <p><span class="date">fin :</span> {{annee2}}/{{mois2}}/{{jour2}} {{heure2}}:{{minute2}}:{{seconde2}}</p>
	            </div>
			
				<button id="save_item">Enregistrer</button>
		        <button id="edit_slide_content">Éditer le contenu</button>
		        <button id="publish_slide">Publier le slide</button>

            </fieldset>
            
        </form>

        

        <div class="reset"></div>
    </div>
</script>

<!--
EDITER LE CONTENU D'UN SLIDE
-->
<script id="slide_content_editor" type="text/html">
    <div style="width:500px">
        <h1>OK</h1>
        <div>{{#formulaire}}</div>
        <button id="save_slide_content">Enregistrer le contenu</button>
    </div>
</script>


<!--
EDITER LES INFORMATIONS D'UN ÉCRAN
-->
<script id="screen_editor" type="text/html">
    <div style="width:500px">
        <h1>{{screen_title}}</h1>
        <form>
            <p><input type="text" value="{{screen_name}}" /></p>
            <p><select id="etablissement_selector">
                {{#liste_etablissement}}
                <option value={{ key }}>{{ value }}</option>
                {{/liste_etablissement}}
            </select></p>
            <p><input type="text" value="{{screen_groupe}}" /><p/>
            
            <p><button id="publish_screen">Publier l’écran</button></p>
            <p><button id="save_screen">Enregistrer</button></p>
        </form>
    </div>
</script>

<?php } ?>
