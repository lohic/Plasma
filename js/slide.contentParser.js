// JavaScript Document




/////// public : remplir la page avec le template html et les données JSon

function setPublicDisplay(templ_num, slide_id){
	
	// on perd templ_num et slide_id dans les crochets du get par ex... et ça servira aussi plus tard.
	templId = templ_num;
	slideId = slide_id;
	
	// charger le template
	$.get('templates/template'+templ_num+'.php?show', function(data) {
											   
		// écrire le template temporairement, pour le scanner
		$('#template').html(data);
							
		// load json data
		$.getJSON('classe/getSQL.php?id='+slideId, function(data){
															
			$.each(data, function(key, val) {
				// on écrit val dans l'élément qui a le bon id : key = id html...
				$('#'+key).html(val);
			});	
		});
	});
	
	// et c'est tout !
	// le json contient directement les balises html des images par exemple.
}



/////// private : créer le form d'admin à partir du template html

function getFieldsFromHTML(templ_num, slide_id){
	
	// on perd templ_num et slide_id dans les crochets du get par ex... et ça servira aussi plus tard.
	templId = templ_num;
	slideId = slide_id;
	
	// on va stocker les codes JS à exécuter plus tard, quand on aura modifé le dom
	// c'est une liste de fonctions...
	asyncFuncs = new Array();
	
	// charger le template avec ?admin pour récupérer les alt="" et title="" qui sont cachés autrement
	$.get('templates/template'+templ_num+'.php?admin', function(data) {
											   
		// écrire le template temporairement, pour le scanner
		$('#template').html(data);
		
		// c'est parti !
	
		// on récupère les valeurs json pour remplir...
		json = Array();
		$.getJSON('classe/getSQL.php?id='+slideId, function(data){
			$.each(data, function(key, val) {
				json[key] = decodeJSON(val);
			});	
			
			// on récupère les objets html du template, ceux de la classe .edit
			listeChampsCodes = new Array();
			listeChampsKeys = new Array();
			listeChampsType = new Array();
								
			$('#template .edit').each(function(index){
									 
				// on récupère le nom du champ
				key = $(this).attr('id');
				
				// on stocke la liste des champs pour plus tard...
				listeChampsKeys.push(key);
				
				if($(this).hasClass('textarea')){
					listeChampsCodes.push(setTextArea($(this), key, json[key]));
					
				} else if($(this).hasClass('checkbox')){
					listeChampsCodes.push(setCheckBox($(this), key, json[key]));	
					
				} else if($(this).hasClass('textfield')){
					listeChampsCodes.push(setTextField($(this), key, json[key]));	
					
				} else if($(this).hasClass('listmenu')){
					listeChampsCodes.push(setListMenu($(this), key, json[key]));		
					
				} else if($(this).hasClass('image')){
					listeChampsCodes.push(setImage($(this), key, json[key]));		
		
				} else {
					// on cache
					listeChampsCodes.push(setHidden($(this), key, json[key]));				
				}
				
			});	
			
			// on vide la template temporaire
			$('#template').html('');
			
			// on remplit le formulaire...
			$('#adminForm').html('<h1>Template '+templId+' / slide json '+slideId+'</h1><br /><br />');
			for(i=0; i<listeChampsCodes.length; i++){
				$('#adminForm').append(listeChampsCodes[i]);
			}
			
			// bouton de validation
			$('#adminForm').append('<br /><br /><input name="submit" type="submit" value="OK" />');
			
			// exécution des codes JS après l'écriture du code html...
			for(i=0; i<asyncFuncs.length; i++){
				asyncFuncs[i]();
			}
			
		});
	});
}


// private : parsing des formulaires pour l'enregistrement dans la bd

function recForm(){
	
	/*
	la fonction est appelée lors de la validation du formulaire.
	
	on a déjà à dispo :
	- slideId qui est l'id sql de la fiche
	- listeChampsKeys[] avec la lise des clés, qui correspond au name du champ
	- listeChampsType[] avec le type de chaque champ pour un traitement sur-mesure
	*/
	
	// on écrit la chaine json au fur et à mesure
	json_string = '{';
	
	for(i=0; i<listeChampsKeys.length; i++){
		// on récupère le type du champ de formulaire, et on extrait la valeur à enregistrer
		
		if(listeChampsType[i] == 'textfield'){
			valeur = getTextField(listeChampsKeys[i]);
			
		} else if(listeChampsType[i] == 'textarea'){
			valeur = getTextArea(listeChampsKeys[i]);
			
		} else if(listeChampsType[i] == 'image'){
			valeur = getImage(listeChampsKeys[i]);
			
		} else if(listeChampsType[i] == 'checkbox'){
			valeur = getCheckBox(listeChampsKeys[i]);	
			
		} else if(listeChampsType[i] == 'listmenu'){
			valeur = getListMenu(listeChampsKeys[i]);	
			
		} else if(listeChampsType[i] == 'hidden'){
			valeur = getHidden(listeChampsKeys[i]);			
		}
		
		// on écrit le json (et on filtre les " dans la valeur s'il y en a)
		json_string += '"'+listeChampsKeys[i]+'":"'+encodeAllFields(valeur)+'", ';
	}
	
	// hop, on vire ', '
	json_string = json_string.substr(0, json_string.length-2);	
	json_string += '}';
	
	
	// compilation json pour la requête sql
	sendJson = encodeJSON(json_string);
	
	// affichage pour test
	$('#adminForm').prepend(sendJson+'<br /><br /><br />');
	
	// rec dans la bd
	$.post('classe/setSQL.php', {id:slideId, query: sendJson}, function(data){	
		// debug
		$('#adminForm').prepend("POST data sent to setSQL.php... <br />"+data+"<br />");
	});
	
	
}

/*function submitUpload(JQobj, dossier){
	fichier = JQobj.val();
	alert(fichier);
	
	// gère l'upload du fichier
	/*$.get("classe/upload.php?file="+fichier+"&repository="+dossier, function(data){
		// debug
		$('#adminForm').prepend("Upload via classe/upload.php... <br />"+data+"<br />");
	});*/
	
	/*$.ajax({ 
	type: "POST",
	url: "classe/upload.php",
	    enctype: 'multipart/form-data',
	    data: {file: fichier, repository:''},
		success: function(data){
		   alert("Data Uploaded. "+data);
		}
	});     
	
	// update l'image affichée
	
	// update le champ hidden
	
	
}*/