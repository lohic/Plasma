<div class="form_container">

<?php if(empty($_GET['id_slide'])){ 
/*
@ création d'un slide -> choix du template
@ GILDAS
@ 19/07/2012
*/
?>

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
                echo createSelect($templateListe, 'template_slide');
                ?></p>
                
                
                <input name="create" type="hidden" value="slide" />	
			</fieldset>
            <input name="submit" type="submit" value="OK"  class="buttonenregistrer"/>
		</form>
	
	</div>






<?php } else { 
/*
@ édition d'un slide
@ GILDAS
@ 19/07/2012
*/
?>

	<p class="intro_modif">Modification de :</p>
	<?php $info = $slide->get_slide_info(); ?>
	
	<h3><?php echo $info->nom;?></h3>
	
	
	<div class="options">
		<a href="../slideshow/?slide_id=<?php echo $info->id ?>&preview" target="_blank">
			<img src="../graphisme/eye.png" alt="voir"/>
		</a>
		<?php /*<a href="#" id="edit_newsletter">
		<img src="../graphisme/pencil.png" alt="modifier"/>
		</a>*/ ?>
	</div>
	
	<div class="reset"></div>
	
	<div id="news_save">
		<form id="news_save_form" action="" method="post" enctype="multipart/form-data"></form>
	</div>
	
	
	<!--<div id="return_refresh">état : ok</div>-->
    <form id="refresh_form" action="XMLrequest_save.php" method="post"></form>	
	
	<?php if($info->template == "evenements"){ ?>
		<div id="content" class="menu">
		
		
			<fieldset>	
			<form id="event_select_form" action="" method="post">
				
				<label>Chercher un événement</label>
				<?php //createSelect($array, $name='', $id = NULL, $additionnal=NULL, $isnull=true) ?>
				<?php 
				echo createSelect(	$anneeListe, 
									'year', 
									$annee, 
									"onchange=\"get_events();\"", 
									true ); ?>
				<?php 
				echo createSelect(	$moisListe, 
									'month', 
									$mois, 
									"onchange=\"get_events();\"",
									true ); ?>
				<?php 
				// listage des organismes depuis l'API
				$json = file_get_contents(EVENEMENT_DATA_URL.'?event');
				$json = ( json_decode(stripslashes($json)) );
				
				$org_json = $value = $json->{"evenements"}->{"organismes"}->{"organisme"};
				$orgListe = array();
				foreach ($org_json as $value) {
					$orgListe[$value->{"id"}] = ($value->{"nom"});
				}
				
				echo createSelect(	$orgListe, 
									'id_organisme', 
									$org, 
									"onchange=\"get_events();\"", 
									true ); ?>
				<?php 
				$langListe = array();
				$langListe['fr'] = "fr";
				$langListe['en'] = "en";
				echo createSelect(	$langListe, 
									'lang', 
									$org, 
									"onchange=\"get_events();\"",
									false ); ?>
									
				
			</form>
			</fieldset>	
					
		</div>
		<div id="event_liste">
			<?php // on remplit avec un fieldset de choix de l'événement en ajax ?>
		</div>	
		
		<p>&nbsp;</p>
	<?php } ?>
	
	<div class="reset"></div>
	<div id="content" class="menu">
	
		<form id="adminForm" action="" method="post">
		<?php echo $slide->create_slide_editor(); ?>
		</form>
		
		<?php /*<input name="file" id="file1" type="file" style="display:none;">*/ ?>
	</div>
	
	<div class="form_container">
		<p class="resume"><?php //echo "champs détectés : ".implode(", ",$news->set_contenu()); ?></p>
	</div>
	
	<script language="javascript" type="text/javascript">
	
	function get_events(){ // la liste des événements
	
		year_val = $('#event_select_form select[name="year"]').val();
		month_val = $('#event_select_form select[name="month"]').val();
		id_organisme_val = $('#event_select_form select[name="id_organisme"]').val();
		lang_val = $('#event_select_form select[name="lang"]').val();
		
		$.post("XMLrequest_get_event_list.php", { year: year_val, month: month_val, id_organisme: id_organisme_val, lang: lang_val }, function(data) {
			$('#event_liste').html(data);
		});
	}
	
	function event_fill_editor(){ // le data d'un événement
	
		id_event = $('#event_liste select[name="event"]').val();
		lang_event = $('#event_select_form select[name="lang"]').val();
		
		$.post("XMLrequest_get_event_data.php", { id: id_event, lang: lang_event }, function(data) {
		
			json = $.parseJSON(data);
			json = json.evenement;
			// remplissage de l'éditeur avec les données non-liées à une session
			form = $('#adminForm');
			form.find('[name="G"]').val(json.titre);
			form.find('[name="H"]').val(json.organisateur);
			form.find('[name="H2"]').val(json.organisateur_qualite);
			form.find('[name="I"]').val(json.url_image);
			$('img.mini').attr('src', json.url_image); // update de la miniature
			
			// écriture du sélect des sessions
			sessions = json.sessions;
			
			html = '<label>Choix de la session</label><select id="id_session" onchange="" name="id_session">';
			
			$.each(sessions, function(key, val) {
				html += '<option value="'+val.id+'">'+val.date_debut+' - '+val.date_fin+' '+val.lieu+'</option>';			
			});
			
			html += '</select>';
			html += '<input name="valid_session" type="button" value="Rafraichir" onclick="get_session();"/>';
			
			$('#session_select').html(html);
			// on remplit avec la 1ère session d'office
			get_session();
			
		});
	}
	
	function get_session(){ // remplissage de l'éditeur avec les contenus de la session
		id_session = $('#id_session').val();
		
		// on a déjà la var sessions qui contient toutes les sessions, et la var form le formulaire
		$.each(sessions, function(key, val) {
			if(val.id == id_session){
				form.find('[name="C"]').val(val.date_debut+' - '+val.date_fin+' / '+val.horaire_debut+' - '+val.horaire_fin);
				form.find('[name="E"]').val((val.lieu));
				form.find('[name="F"]').val(val.code_batiment!='' ? 'Bât. '+val.code_batiment : val.code_batiment);
				form.find('[name="J"]').val((val.type_inscription));
			}		
		});
	}
	
	/*function get_upload(){
		// il y a un champ d'upload caché...
		//alert('click');
		$('#file1').trigger('click');
		//$('#file1').fireEvent('click');
		//$('#file1').click();
	}*/
	
	$(document).ready(function(){
	
		// upload Ajax : http://lagoscript.org/jquery/upload
		
		$('#file1').change(function() {
			// on envoit l'id et la date de créa du slide pour placer l'image dans le bon dossier
            $(this).upload('jquery.upload.php?id=<?php echo $info->id; ?>&date=<?php echo $info->date; ?>', function(res) {
                
				//alert(res);
				alert("Fichier uploadé avec succès.");
				
				result = $.parseJSON(res);
				
				img_src = result.filename.replace('../', '<?php echo ABSOLUTE_URL; ?>')	;
						
				$('input[name="I"]').val(img_src);				
				$('img.mini').attr('src', img_src);
				
            }, 'html');
        });
		
		// quand on valide le formulaire...
		/*$('#adminForm').submit(function(){
			recForm();
			return false;
		});*/
	});
	</script>

<?php } ?>


</div>