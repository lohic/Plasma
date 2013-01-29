// JavaScript Document


//// fonctions textes supplémentaires

function str_replace(texte, needle, insert){
	SRRi = texte.indexOf(needle);
	SRRr = '';
	if (SRRi == -1) return texte;
	SRRr += texte.substring(0,SRRi) + insert;
	if ( SRRi + needle.length < texte.length){
		SRRr += str_replace(texte.substring(SRRi + needle.length, texte.length), needle, insert);
		return SRRr;
	}
	// ça fonctionne aussi bien qu'en php :)
}
function addslashes(str) {
	str = str.replace(/\\/g,'\\\\');
	str = str.replace(/\'/g,'\\\'');
	str = str.replace(/\"/g,'\\"');
	str = str.replace(/\0/g,'\\0');
	return str;
}
function stripslashes(str) {
	str = str.replace(/\\'/g,'\'');
	str = str.replace(/\\"/g,'"');
	str = str.replace(/\\0/g,'\0');
	str = str.replace(/\\\\/g,'\\');
	return str;
}


//// encodage des contenus des champs avant enregistrement

function encodeAllFields(texte){
	// remplacer " par \\"
	while(texte.indexOf("\n")>=0){
		texte = texte.replace('\n', '<br />');
	}	
	return str_replace(texte, '"', '\\"');
}


//// encodage-décodage de la chaine json entière...

function encodeJSON(texte){	
	return addslashes(texte);
}
function decodeJSON(texte){
	while(texte.indexOf("br /")>=0){
		texte = texte.replace(/<br \/>/, '\n');
	}
	return texte;
}


//// labels des champs du formulaire
function getLabel(JQobj, code){
	titre = JQobj.attr('title');
	return '<label>'+titre+code+'</label>\n';	
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////


// TextArea
function setTextArea(JQobj, key, value){
	listeChampsType.push('textarea');
	return getLabel(JQobj, '<textarea name="'+key+'" cols="25" rows="10">'+value+'</textarea>');
}
function getTextArea(name){
	value = $('textarea[name="'+name+'"]').val();
	return value;
}


// TextField
function setTextField(JQobj, key, value){
	listeChampsType.push('textfield');
	return getLabel(JQobj, '<input name="'+key+'" type="text" value="'+value+'" />');
}
function getTextField(name){
	return $('input[name="'+name+'"]').val();	
}


// CheckBox requiert d'entrée la valeur si c'est coché dans la balise alt="valeur cochée" du template
function setCheckBox(JQobj, key, value){
	listeChampsType.push('checkbox');
	ischecked = "";
	if(value != ''){
		ischecked = 'checked ';	
	}
	return getLabel(JQobj, '<input name="'+key+'" type="checkbox" value="'+JQobj.attr('alt')+'" '+ischecked+'/>');
}
function getCheckBox(name){	
	field = $('input[name="'+name+'"]');
	if(field.is(':checked')){
		return field.val();
	} else {
		return '';
	}
}


// Hidden
function setHidden(JQobj, key, value){
	listeChampsType.push('hidden');	
	return 'hidden field : '+key+' = '+value+'<input name="'+key+'" type="hidden" value="'+value+'" />';	
}
function getHidden(name){
	value = $('textarea[name="'+name+'"]').val();
	return value;
}


// Image
function setImage(JQobj, key, value){
	listeChampsType.push('image');
	//return getLabel(JQobj, '<input name="'+key+'" type="text" value="'+txt+'" />');	
	
	code = getLabel(JQobj, '<br />');	
	
	// display de l'image au bon format
	code += str_replace(value, '<img ', '<img width="350" ');
	
	// champ d'upload
	code += '<br /><br /><input id="file1" name="file1" type="file" /><br />';
	
	// JQuery UPLOADIFY
	// http://www.uploadify.com/documentation/#options
	uploadify_path = 'js/uploadify-v2.1.4/'; 
	
	// comme tout ça est asynchrone on a besoin de retrouver plus tard le champ duquel on est parti...
	uploadify_obj_name = key;
	
	asyncFuncs.push(function(){
		// ça c'est une fonction qu'on ne va exécuter que lorqu'on aura déjà écrit les champs dans le dom
		// -> il faut qu'on ait déjà le champ input, pour le transformer avec uploadify...
	
		$('#file1').uploadify({ 
							  
			'uploader' : uploadify_path+'uploadify.swf',     
			'script' : uploadify_path+'uploadify.php',     
			'cancelImg' : uploadify_path+'cancel.png',
			'expressInstall' : uploadify_path+'expressInstall.swf', 		
			'buttonText' : 'Parcourir...',
			'folder' : 'templates', 
			'auto' : true, 		
			'onComplete' : function(event, ID, fileObj, response, data) {
				
				curURL = window.location.pathname;
				reg = new RegExp('/*.*/', 'g');
				curPage = curURL.split(reg);			
				curFold = curURL.replace(curPage[1], '');
				// chemin relatif du fichier
				cheminFichier = response.replace(curFold, '');
				
				// update de l'image affichée
				imgObj = $('input[name="'+uploadify_obj_name+'"]').parent().find('img');
				imgObj.attr('src', cheminFichier);
				
				// update du champ hidden
				$('input[name="'+uploadify_obj_name+'"]').val(cheminFichier);
				
			},
			'onError' : function(event,ID,fileObj,errorObj){
				$('#adminForm').prepend(errorObj.type + ' Error: ' + errorObj.info);
			}
		});
	});
	
	// hidden value (qu'on viendra changer en js au besoin)
	txt = value.substr(10, value.length-4-10);
	code += '<input name="'+key+'" type="hidden" value="'+txt+'" />'
	
	return code;
}
function getImage(name){
	value = $('input[name="'+name+'"]').val();
	return '<img src="'+value+'" />';
}


// ListMenu
function setListMenu(JQobj, key, value){
	listeChampsType.push('listmenu');
	
	// dans la template on aura utilisé alt="valeur1#valeur2#valeur3" pour lister les possibilités de saisie
	valueSet = JQobj.attr('alt').split('#');
	
	code = '<br /><select name="'+key+'">';
	for(i=0; i<valueSet.length; i++){
		selected = (valueSet[i] == value)?' selected="selected"':'';	
		code += '<option value="'+valueSet[i]+'"'+selected+'>'+valueSet[i]+'</option>';
	}
	//return getLabel(JQobj, '<input name="'+key+'" type="text" value="'+value+'" />');
	
	code += '</select><br /><br />';
	return getLabel(JQobj, code);
}
function getListMenu(name){
	return $('select[name="'+name+'"]').val();	
}