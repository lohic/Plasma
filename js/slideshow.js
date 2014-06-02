/**
 * contient les différenets fonctions pour gérer le slideshows d'un écran 
 * @author Loic Horellou
 * @version v1.0beta
 */


/**
 * Initialisation du javascript et la boucle
 */
$(document).ready(function(){

	// variables par defaut
	$plasma_id			= getUrlVars().plasma_id;
	$actual_data_date	= '0000-00-00 00:00:00';
	$actual_item_id		= 'undefined';
	$meteo_id			= 'EUR|FR|FR012|PARIS|';
	$code_postal		= '75000';

	$slide_loaded		= false;
	$data_loaded		= false;


	
	$slides				= Array(); // liste des slides

	$now				= new Date();

	$start				= new Date();
	$end				= new Date();
	
	$newStart			= new Date();
	$newEnd				= new Date();
	
	$last_ordre			= 0;
	
	$isActualStillHere  = false;

	$template			= 'default';
	$slide_data			= {};	// données du slide actuel
	$slide_id 			= 0;	// id du slide (contenu)
	$actual_item_id		= 0;	// id de l'item (élément de la timeline ou séquentiel)

	$nbr				= 0;	// nombre d'items
	

	// on vérifie si on doit juste afficher un slide ou le slideshow d'un écran
	// si slide_id alors on affiche le slide
	if(typeof(getUrlVars().slide_id)!='undefined' ){
		console.log("on prévisualise un slide");

		$template = getUrlVars().template;
		$slide_id = getUrlVars().slide_id;

		if($template != 'meteo'){
			$.ajax({
				type: "GET",
				url: "../ajax/data-slide.php",
				data: {slide_id : $slide_id},
				dataType: 'json',
				success: function(json){
					$('.info').text( 'Données du slide chargées' );
					console.log("DATA SLIDE CHARGEES "+json);
					$slide_data	= json;

					$('body')
					.removeClass('half')
					.removeClass('exit');
					load_slide($template,$slide_data);
					$slide_loaded = true;
				}
			});
		}else{
			$slide_data = {};
			load_slide($template,$slide_data);
			$slide_loaded = true;
		}
	}
	// sinon un slideshow
	else{
		// ON AMORCE LE RAFRAICHISSEMENT SUR UN INTERVAL DE TEMPS DONNÉ
		console.log("on visualise un écran");
		refresh();
		setInterval(refresh, 1000);

		console.log('id ecran : '+$plasma_id);
	}

	// on prépare les boutons de la console de test (affichée si '&debug' est passé en paramètre dans l'url )
	$('#half_button').click(function(e){
		$('body').toggleClass('half');
		e.preventDefault();
	});

	$('#exit_button').click(function(e){
		$('body').toggleClass('exit');
		e.preventDefault();
	});

	$('#pause_button').click(function(e){
		$('body').toggleClass('pause');
		e.preventDefault();
	})
});



/**
 * La fonction qui sert à rafraichir les données d'un écran
 * @return {null}
 */
function refresh() {
	// si on n'est pas en pause 
	if(!$('body').hasClass('pause')){

		$now 		= new Date();

		// on met à jour la console
		$("#now").text($now);
		$("#end").text($end);
		$("title").text("LOOP | Écrans PLASMA : "+new Date());
		
		$.ajax({
			type: "GET",
			url: "../ajax/data-slideshow.php",
			data: {
				plasma_id : $plasma_id,
				actual_data_date: $actual_data_date,
				no_cache : new Date()
			},
			dataType: 'json',
			error : function(jqXHR,textStatus,errorThrown){
				$('.info').text('Il y a une erreur dans le chargement des données du slideshow : '+errorThrown);
			},
			success: function(json){
				//countusers=json.countusers;
				//$("#retour").text('ok : '+countusers);
				if(json.update == true){
					console.log(" ");
					console.log('NEW DATA');
					//console.log(json.screen_data.data);

					console.log( $actual_data_date + " " + json.screen_data.date_publication);

					//console.log(json.screen_data.nom_ecran);

					$actual_data_date	= json.screen_data.date_publication;				
					$meteo_id			= json.screen_data.code_meteo;
					$code_postal		= json.screen_data.code_postal;

					$slides				= json.screen_data.data;


					$nbr = $slides.length;

					console.log($nbr);

					if( $nbr > 0){

						// on trie les slides :
						// alerte locale > alerte nationale > écran > groupe  > date de début croissante
						 
						$slides = $slides.sort(function(a,b){
						    valeur =
						    (a.ref_target == 'loc' && b.ref_target == 'nat' ? -1 :
						     (a.ref_target == 'nat' && b.ref_target == 'loc' ? 1 :
						    
						      (a.ref_target == 'nat' && b.ref_target == 'grp' ? -1 :
						       (a.ref_target == 'grp' && b.ref_target == 'nat' ? 1 :
						    
						        (a.ref_target == 'nat' && b.ref_target == 'ecr' ? -1 :
						         (a.ref_target == 'ecr' && b.ref_target == 'nat' ? 1 :
						    
						          (a.ref_target == 'nat' && b.ref_target == 'seq' ? -1 :
						           (a.ref_target == 'seq' && b.ref_target == 'nat' ? 1 :
						    
						            (a.ref_target == 'loc' && b.ref_target == 'grp' ? -1 :
						             (a.ref_target == 'grp' && b.ref_target == 'loc' ? 1 :
						    
						              (a.ref_target == 'loc' && b.ref_target == 'ecr' ? -1 :
						               (a.ref_target == 'ecr' && b.ref_target == 'loc' ? 1 :
						    
						                (a.ref_target == 'loc' && b.ref_target == 'seq' ? -1 :
						                 (a.ref_target == 'seq' && b.ref_target == 'loc' ? 1 :
						    
						                  (a.ref_target == 'ecr' && b.ref_target == 'grp' ? -1 :
						                   (a.ref_target == 'grp' && b.ref_target == 'ecr' ? 1 :
						    
						                    (a.ref_target == 'grp' && b.ref_target == 'seq' ? -1 :
						                     (a.ref_target == 'seq' && b.ref_target == 'grp' ? 1 :
						    
						                      (a.ref_target == 'ecr' && b.ref_target == 'seq' ? -1 :
						                       (a.ref_target == 'seq' && b.ref_target == 'ecr' ? 1 :

						                        (a.start < b.start ? -1 : 
						                         (a.start > b.start ? 1 :

						                          (parseInt(a.ordre) <= parseInt(b.ordre) ? -1 : 1 )))))))))))))))))))))));

											   return valeur;
						});
					}

					console.log($slides);

					// POUR VERIFIER SI IL Y A UN CHANGEMENT D'HORAIRE OU QUE LE SLIDE N'EST PLUS PRESENT
					// notamment pour les alertes
					$isActualStillHere = false;
					if($actual_item_id>0){
						for(var i = 0 ; i < $nbr; i++){

							if($slides[i].id == $actual_item_id){
								$isActualStillHere = true;

								// on vérifie que le slide n'a pas été décalé dans le temps et qu'il ne doive plus s'afficher
								if( mysql2jsTimestamp($slides[i].start) > $now ){
									console.log('attention le slide est décalé dans le futur'); 
									$end = new Date($now).addSeconds(2);
								}else if( $slides[i].end != '0000-00-00 00:00:00'){
									$end = mysql2jsTimestamp( $slides[i].end );

									console.log('ON CHANGE L’HEURE DE FIN DU SLIDE ACTUEL : ' + $end);
									break;
								}
							}
						}
					}else{
						$isActualStillHere = true;
					}
			
					

					if(!$slide_loaded){
						$slide_data	= {"titre_ecran" : $('body').data('name')};
						load_slide($template,$slide_data);

						$slide_loaded = true;
					}

					$data_loaded = true;

				}else if(json.update == false){
					//$data_loaded = false;

					/*
					if($data_loaded == true){
						$data_loaded = false;
						$slide_loaded = false;
					}
					
					if(!$slide_loaded){
						$slide_data	= {"titre_ecran" : $('body').data('name')};
						load_slide($template,$slide_data);

						$slide_loaded = true;
					}
					*/
				}	
			}
		});

		loop_slideshow();
	}
}
	
/**
 * La boucle qui est appelée toutes les secondes pour vérifier
 * le chargement des slides en fonction du fichier de données d'écran chargé
 * @return {null} 
 */
function loop_slideshow(){

	//console.log($now +" "+ new Date($end).addSeconds(-3));

	// on vérifie si on est à moins de 2 secondes de la fin
	// > on ajoute une classe « exit » à la balise body 
	if($now > new Date($end).addSeconds(-2) && $template != 'default'){
		console.log('exit');
		$('body').addClass('exit');
		$('#meteo2').addClass('exit');
	}

	// sert à indiquer la moitié du temps (utile notamment pour météo)
	// > on ajoute une classe « half » à la balise body 
	$half = ($end - $start)/2/1000;
	if($now > new Date($end).addSeconds(-$half) && $template != 'default'){
		console.log('moitié du temps');
		$('body').addClass('half');
		$('#meteo2').addClass('exit');
	}
	
	// on vérifie qu'il y a bien un slide dont end >  $now
	// qu'il y a bien au moins un slide en attente
	// et que $now et à moins de 2 secondes de la fin du slide actuel

	if( $data_loaded ){

		for(var i = 0 ; i< $nbr; i++){
			//console.log ( mysql2jsTimestamp($slides[i].start) < new Date());
			$newStart 	= mysql2jsTimestamp($slides[i].start);
			$newEnd		= mysql2jsTimestamp($slides[i].end);
			$expire		= mysql2jsTimestamp($slides[i].expire);

			// on boucle pour vérifier si un écran est dans le bon interval de temps et qu'on est bien inférieur à sa date d'expiration (si il y en a une)
			if( ($newStart < $now && $now < $newEnd && ($now < $expire || $slides[i].expire=='0000-00-00 00:00:00')) || $isActualStillHere == false){

				// si le slide détecté est différent du slide actuel
				if($actual_item_id != $slides[i].id){

					// si c'est un alerte
					if($slides[i].ref_target == 'loc' || $slides[i].ref_target == 'nat' ){
						//$slide_loaded = false;
					}
					// si c'est un slide de groupe ou un slide écran
					else if( ($slides[i].ref_target == 'ecr' || $slides[i].ref_target == 'grp') && $now >= $slides[i].end ){
						
					}
					//ON CHARGE LES DONNÉE DU PROCHAIN SLIDE
					$start		= mysql2jsTimestamp($slides[i].start);
					$end		= mysql2jsTimestamp($slides[i].end);
					$template 	= $slides[i].template;
					$slide_id	= $slides[i].id_slide;
					$actual_item_id = $slides[i].id;

					// on annonce qu'on est en attente d'un nouveau slide
					$slide_loaded = false;
				}else{
					// on ne fait rien c'est qu'on affiche le bon slide
				}		

				break;
			}else{
				// sinon on va vérifier les slides par ordre
				
			}
		}

		if($slide_loaded){

			if( $now >= $end){

				for(var i = 0 ; i< $nbr; i++){

					$expire		= mysql2jsTimestamp($slides[i].expire);

					if(parseInt($slides[i].ordre) > $last_ordre && ($now < $expire || $slides[i].expire=='0000-00-00 00:00:00')){
						$start		= $now;
						$end		= new Date($now).addSeconds(mysql2jsSecond($slides[i].duree));
						$template 	= $slides[i].template;
						$slide_id	= $slides[i].id_slide;
						$actual_item_id = $slides[i].id;

						//console.log('duree : '+mysql2jsSecond($slides[i].duree));

						$last_ordre = parseInt($slides[i].ordre);
						console.log("dernier n° d'ordre : "+$last_ordre);

						// on annonce qu'on est en attente d'un nouveau slide
						$slide_loaded = false;

						break;
					}else{
						if(i == $slides.length-1){
							$last_ordre = 0;	
						}
					}
				}
			}
		}

		// si $slide_loaded == false
		// alors on charge un nouveau slide
		// en allant chercher ses données
		if(! $slide_loaded){

			if($template != 'meteo'){

				$.ajax({
					type: "GET",
					url: "../ajax/data-slide.php",
					data: {slide_id : $slide_id},
					dataType: 'json',
					success: function(json){
						$('.info').text( 'Données du slide chargées' );
						console.log("DATA SLIDE CHARGEES :");
						console.log(json);
						$slide_data	= json;

						$('body').removeClass('exit');
						$('body').removeClass('half');
						load_slide($template,$slide_data);
						$slide_loaded = true;
					}
				});
			}else{
				$('.info').text( 'Données du slide chargées' );
				console.log("METEO");
				$slide_data = {};

				$('body').removeClass('exit');
				$('body').removeClass('half');
				load_slide($template,$slide_data);
				$slide_loaded = true;
			}
		}		

		$('.info').html( 'template : <span class="red">' + $template + '</span><br/>id_slide : <span class="red">'+$slide_id +'</span><br/>slide toujours présent ? : <span class="red">'+$isActualStillHere +'</span>');

	}else{
		
	}

    //load_slide('default',{"titre_ecran" : "test écran", "logo":true});
}

/**
 * permet de charger un slide, sa feuille de style et son fichier javascript
 * @param  {string} template le nom du template à charger (nom du dossier)
 * @param  {json} 	data     les données json qui vont servir à iCanHaz à générer le slide 
 * @return {null}	         remplace le html du div#template par le slide
 */
function load_slide(template, data){
	window.refreshMeteo = null;
	window.remplissage = null;

	//console.log("template : "+template+" data : "+data);
	$('body').removeClass('exit');

	var slide = eval("ich."+template)(data);

	$("#template").empty();
    $('link[name="slide_css"]').attr('href','../slides_templates/'+template+'/style.css?cache='+$now);
    dynamicLoadJS('../slides_templates/'+template+'/script.js?cache='+$now);


    $("#template").html(slide);
}


/**
 * Pour charger dynamiquement le fichier javascript d'un slide,
 * la fonction ajoutera temporairement une balise script pour inclure le fichier 
 * @param  {string}		path le chemin du script js à charger
 * @return {null}		ajoute dans le DOM la balise script permettant de charger le script souhaité
 */
function dynamicLoadJS(path) {

    var DSLScript  = document.createElement("script");
    DSLScript.src  = path;
    DSLScript.type = "text/javascript";
    document.body.appendChild(DSLScript);
    document.body.removeChild(DSLScript);
}

/**
 * sert à convertir un timestamp mysql en objet date javascript
 * @param  {string} 		timestamp le timestamp mysql
 * @return {Date}           un objet Date javascript 
 */
function mysql2jsTimestamp(timestamp){

	var t = timestamp.split(/[- :]/);
	return new Date(t[0], t[1]-1, t[2], t[3] || 0, t[4] || 0, t[5] || 0);
}

/**
 * sert à convertir un timestamp mysql en objet date javascript
 * @param  {string} 		timestamp le timestamp mysql
 * @return {Date}           un objet Date javascript 
 */
function mysql2jsSecond(timestamp){

	var t = timestamp.split(':');
	return parseInt(t[0])*60*60 + parseInt(t[1])*60 + parseInt(t[2]);
}


/**
 * sert à récupérer les variables GET en javascript
 * @return {[type]} [description]
 */
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

/**
 * ajout d'une méthode addHours à l'objet Date
 * permet d'additionner des heures à une date donnée
 * notamment pour corriger les Date avec des décalages horaire
 * @param {h} le nombre d'heures à ajouter
 * @return {copiedDate} un objet Date
 */
Date.prototype.addSeconds= function(s){
    var copiedDate = new Date(this.getTime());
    copiedDate.setSeconds(copiedDate.getSeconds()+s);
    return copiedDate;
}
