/*
* contient les différenets fonctions pour gérer le slideshows d'un écran 
* @author Loic Horellou
*/

// console.log("Date js    "+new Date());
// console.log("Date mysql "+new mysql2jsTimestamp('2013-08-25 19:58:30'));
// var d1 = Date.createFromMysql("2011-02-20");
// var d2 = Date.createFromMysql("2011-02-20 17:16:00");
// alert("d1 year = " + d1.getFullYear());

$(document).ready(function(){
	//var actual_date_json	='<?php //echo $this->ecran->actual_date_json; ?>';
	
	$plasma_id			= getUrlVars().plasma_id;
	$actual_data_date	= '0000-00-00 00:00:00';
	$actual_item_id		= 'undefined';
	$meteo_id			= 'EUR|FR|FR012|PARIS|';
	$code_postal		= '75000';
	$nom_ecran			= "lkjlj";

	$loaded				= false;
	$data_loaded		= false;

	$data_slide_dates	= 'undefined';
	$data_slide_ordered	= 'undefined';

	$slides				= Array();

	$now				= new Date();
	$start				= new Date();
	$end				= new Date();
	$newStart			= new Date();
	$newEnd				= new Date();

	$template			= 'default';
	$slide_data			= {};
	$slide_id 			= 0;
	
	// ON AMORCE LE RAFRAICHISSEMENT SUR UN INTERVAL DE TEMPS DONNÉ
	refresh();
	setInterval(refresh, 1000);

	console.log($plasma_id);
});

/**
 * La fonction qui sert à rafraichir les données d'un écran
 * @return {null}
 */
function refresh() {

	$("#now").text($now);
	$("title").text("LOOP | Écrans PLASMA : "+new Date());


	var data_url = "../ajax/data-slideshow.php" ;
	var data_param = "action=refresh&plasma_id="+ $plasma_id +"&actual_data_date=" + $actual_data_date ;
	
	$.ajax({
		type: "GET",
		url: data_url,
		data: {plasma_id : $plasma_id, actual_data_date: $actual_data_date},
		dataType: 'json',
		//async:false,
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
				$nom_ecran			= json.screen_data.nom_ecran;

				$slides				= json.screen_data.data;


				// on trie les slides :
				// alerte locale > alerte nationale > écran > groupe  > date de début croissante
				$slides = $slides.sort(function(a,b){
				    valeur =
				    (a.ref_target == 'nat' && b.ref_target == 'loc' ? -1 :
				     (a.ref_target == 'loc' && b.ref_target == 'nat' ? 1 :
				      (a.ref_target == 'nat' && b.ref_target == 'grp' ? -1 :
				       (a.ref_target == 'grp' && b.ref_target == 'nat' ? 1 :
				        (a.ref_target == 'nat' && b.ref_target == 'ecr' ? -1 :
				         (a.ref_target == 'ecr' && b.ref_target == 'nat' ? 1 :

				          (a.ref_target == 'loc' && b.ref_target == 'grp' ? -1 :
				           (a.ref_target == 'grp' && b.ref_target == 'loc' ? 1 :
				            (a.ref_target == 'loc' && b.ref_target == 'ecr' ? -1 :
				             (a.ref_target == 'ecr' && b.ref_target == 'loc' ? 1 :

				              (a.ref_target == 'grp' && b.ref_target == 'ecr' ? 1 :
				               (a.ref_target == 'ecr' && b.ref_target == 'grp' ? -1 :
				                a.start <= b.start ? -1 : 1 ))))))))))));

				   return valeur;
				});

				//console.log($slides);
				//
				$data_loaded = true;
				$slide_data	= {"titre_ecran" : $nom_ecran};

				load_slide($template,$slide_data);
			}else{
				//console.log(" ");
				//console.log('NO NEW DATA');
				//console.log(json);
			}	
		}
	});

	
	loop_slideshow();
	//load_slide();
}
	
/**
 * La boucle qui est appelée toutes les secondes pour vérifier
 * le chargement des slides en fonction du fichier de données d'écran chargé
 * @return {null} 
 */
function loop_slideshow(){

	if($data_loaded){

		

		nbr = $slides.length;
		for(var i = 0 ; i< nbr; i++){
			//console.log ( mysql2jsTimestamp($slides[i].start) < new Date());
			$now 		= new Date();
			$newStart 	= mysql2jsTimestamp($slides[i].start);
			$newEnd		= mysql2jsTimestamp($slides[i].end);

			if( $newStart < $now && $now < $newEnd){

				// si on détecte une alerte locale ou nationale
				if(($slides[i].ref_target == 'nat' || $slides[i].ref_target == 'loc') && $actual_item_id != $slides[i].id){
					$loaded = false;
				}

				$start		= mysql2jsTimestamp($slides[i].start);
				$end		= mysql2jsTimestamp($slides[i].end);
				$template 	= $slides[i].template;
				$slide_id	= $slides[i].id_slide;
				$actual_item_id = $slides[i].id;

				//console.log( $slides[i].id );
				//ON CHARGE LES DONNÉE DU PROCHAIN SLIDE
				
				if($template != 'meteo'){
					if(! $loaded){
						$.ajax({
							type: "GET",
							url: "../ajax/data-slide.php",
							data: {slide_id : $slide_id},
							dataType: 'json',
							//async:false,
							success: function(json){
								console.log("DATA SLIDE CHARGEES "+json);
								$slide_data	= json;

								load_slide($template,$slide_data);
								$loaded = true;
							}
						});
					}
				}else{
					$slide_data = {};
					load_slide($template,$slide_data);
					$loaded = true;
				}

				break;
			}
		}

		$('.info').text( 'template : ' + $template + ' id_slide : '+$slide_id );

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

	//console.log("template : "+template+" data : "+data);

	var slide = eval("ich."+template)(data);

	$("#template").empty();
    $('link[name="slide_css"]').attr('href','../slides_templates/'+template+'/style.css?cache='+new Date());
    dynamicLoadJS('../slides_templates/'+template+'/script.js');


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
 * [createFromMysql description]
 * @param  {[type]} mysql_string [description]
 * @return {[type]}              [description]
 */
/*Date.createFromMysql = function(mysql_string)
{ 
   if(typeof mysql_string === 'string')
   {
      var t = mysql_string.split(/[- :]/);

      //when t[3], t[4] and t[5] are missing they defaults to zero
      return new Date(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0);          
   }

   return null;   
}*/


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

