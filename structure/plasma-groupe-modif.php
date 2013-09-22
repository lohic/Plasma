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

</style>

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

	<!--<div class="reset"></div>

	<p>Le début d'un slide est prioritaire sur sa fin.</p>
    
    <div class="reset"></div>   
	<p>
		<button id="show_previous">Afficher les 10 jours précédents</button>
		<button id="show_next">Afficher les 10 jours suivants</button>
	</p>-->
	<h4>Ordre des priorités :</h4>
	<p><em>Alerte locale</em> <strong>></strong> <em>Alerte nationale</em> <strong>></strong> <em>Écran</em> <strong>></strong> <em>Groupe</em> <strong>></strong> <em>Slides par séquence</em></p>
	
	<div class="reset"></div>

	<div id="dateTimeline"></div>

	<div id="sequenceTimeline">
		<div id="sequenceContainer">
		</div>
	</div>

	<div class="child-screen">
    <?php
    echo $ecran->get_admin_ecran_list($id_groupe)->ecrans;
    ?>
	</div>
	<?php } ?>

	<form id="myform" style="width:500px"></form>
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
        		<div class="slide_view" data-id-slide="{{slide_id}}" data-absolute-url="<?php echo ABSOLUTE_URL?>"><a href="<?php echo ABSOLUTE_URL?>slideshow/?slide_id={{slide_id}}&template={{template}}" target="_blank" ><img src="../graphisme/eye.png" alt="voir"/></a></div>
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
		            	<label for="template_reference">Type de slide :</label>
		                <select id="template_reference" name="template_reference">
		                    {{#template_selector}}
		                    <option value="{{ key }}">{{ value }}</option>
		                    {{/template_selector}}
		                </select>
		            </p>
		        	<p class="nometeo">
		        		<label>Année / Mois:</label>
		        		<select id="annee_slide" name="annee_slide">
		        		</select>
		        		<select id="mois_slide" name="mois_slide">
		        		</select>
		        	</p>
		        	<p class="nometeo">
		        		<label for="id_slide">Slide :</label>
		        		<select id="id_slide" name="id_slide">
		        			<option value="0">Nouveau</option>
		        		</select>
		        	</p>
		        </div>
		        <div>
		            <p>
		            	<label>Publiable oui/non :</label>
		                <input id="published" type="checkbox" name="published">
		            </p>
		        </div>
	            <div>
	                <h3>Durée : {{duree}}</h3>
	                <p><span class="date">début :</span> {{startDate}}</p>
	                <p><span class="date">fin :</span> {{endDate}}</p>
	            </div>
			
				<button id="save_item">Enregistrer</button>
		        <button id="edit_slide_content" class="nometeo">Éditer le contenu</button>

            </fieldset>
            
        </form>

        

        <div class="reset"></div>
    </div>
</script>


<!--
EDITER LES INFORMATIONS D'UN ITEM DE LA TIMELINE
-->
<script id="item_sequence_editor" type="text/html">
    <div style="width:500px">
        <h1 id="item_title">{{content}}</h1>
        <form>
        	<fieldset>
        		<div class="slide_view" data-id-slide="{{slide_id}}" data-absolute-url="<?php echo ABSOLUTE_URL?>"><a href="<?php echo ABSOLUTE_URL?>slideshow/?slide_id={{slide_id}}&template={{template}}" target="_blank" ><img src="../graphisme/eye.png" alt="voir"/></a>
        		</div>
		        <div>
		            <p>
		            	<label for="template_reference">Type de slide :</label>
		                <select id="template_reference" name="template_reference">
		                    {{#template_selector}}
		                    <option value="{{ key }}">{{ value }}</option>
		                    {{/template_selector}}
		                </select>
		            </p>
		        	<p class="nometeo">
		        		<label>Année / Mois:</label>
		        		<select id="annee_slide" name="annee_slide">
		        		</select>
		        		<select id="mois_slide" name="mois_slide">
		        		</select>
		        	</p>
		        	<p class="nometeo">
		        		<label for="id_slide">Slide :</label>
		        		<select id="id_slide" name="id_slide">
		        			<option value="0">Nouveau</option>
		        		</select>
		        	</p>
		        </div>
		        <div>
		            <p>
		            	<label>Publiable oui/non :</label>
		                <input id="published" type="checkbox" name="published">
		            </p>
		        </div>
	            <div>
	                <labem>Durée en secondes :</label>
	                <input id="duree" name="duree" value="{{duree}}" />
	                <p id="dureeHMS">{{dureeHMS}}</p>
	            </div>
			
				<button id="save_sequence_item">Enregistrer</button>
		        <button id="edit_slide_content" class="nometeo">Éditer le contenu</button>

            </fieldset>
            
        </form>

        

        <div class="reset"></div>
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


<!--
EVENT SELECTOR
-->
<script id="event_selector" type="text/html">
    <div style="width:500px">
        <h4>Sélectionner un événement</h4>
        <p><label>Organisme :</label>
        <select id="id_organisme" name="id_organisme">
            <!--<option value="1">Direction de la communication</option>-->
            <!--<option value="2">CERI</option>-->
            <!--<option value="6">Picasso</option>-->
        </select></p>

        <p><label>Année / mois :</label>
        <select id="year_event" name="year_event">
            {{#year_event}}
            <option value="{{ key }}">{{ value }}</option>
            {{/year_event}}
        </select></p>
        <p><select id="month_event" name="month_event">
            {{#month_event}}
            <option value="{{ key }}">{{ value }}</option>
            {{/month_event}}
        </select></p>

        <p><label>Événement / session:</label>
        <select id="id_event" name="id_event">
        </select>
        <select id="id_session" name="id_session">
        </select></p>

        <p><button id="refresh_event">Rafraichir les données</button></p>
    </div>
</script>


<?php } ?>
