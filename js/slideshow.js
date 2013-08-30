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
	var actual_date_json	='<?php //echo $this->ecran->actual_date_json; ?>';
	var plasma_id			='<?php //echo $this->ecran->id; ?>';
	
	$actual_data_date	= 'undefined';
	$actual_slide_id	= 'undefined';
	$meteo_id			= 'EUR|FR|FR012|PARIS|';
	$plasma_id			= 1;
	$loaded				= false;

	$data_slide_dates	= 'undefined';
	$data_slide_ordered	= 'undefined';
	
	// ON AMORCE LE RAFRAICHISSEMENT SUR UN INTERVAL DE TEMPS DONNÉ
	refresh();
	setInterval(refresh, 1000);
});

function refresh() {

	$("#now").text(new Date());
	$("title").text("LOOP | Écrans PLASMA : "+new Date());

	var data_url = "ajax/data-slideshow.php" ;
	var data_param = "action=refresh&plasma_id="+ $plasma_id +"&actual_data_date=" + $actual_data_date ;
	
	$.ajax({
		type: "POST",
		url: data_url,
		data: data_param,
		dataType: 'json',
		//async:false,
		success: function(json){
			//countusers=json.countusers;
			//$("#retour").text('ok : '+countusers);
			if(json.update == true){
				console.log('NEW DATA !!!')

				$actual_data_date = json.publish_date;
				console.log(json.date_slide);

				$data_slide_dates	= json.slides_dates;
				$data_slide_ordered	= json.slides_ordered;
				
				//$('.date').text(actual_date_json);
			}else{
				console.log('NO NEW DATA');
				//console.log(json);
			}	
		}
	});

	loop_slideshow();
}
	

function loop_slideshow(){


	/*timer ++;

	var aleatoire = Math.round(Math.random()*5000);

	//console.log("hello "+new Date());

	if(! $loaded){

		slide_data = { delai: "20", image:"../dform/slides_images/2013/08/frankfurter-dauphin-magali.jpg" };

        var slide = ich.compte_a_rebours(slide_data);

        $("#template").empty();
        $('link[name="slide_css"]').attr('href','slides_templates/compte_a_rebours/style.css');
        dynamicLoadJS('slides_templates/compte_a_rebours/');
        $("#template").html(slide);

        console.log(slide);
        $loaded = true;
    }*/

    load_slide('default',{titre_ecran : 'test écran', logo:'true'});
}

function load_slide(template, data){

	if(! $loaded){

		slide_data = data;
		var slide = eval("ich."+template)(slide_data);

		$("#template").empty();
	    $('link[name="slide_css"]').attr('href','slides_templates/'+template+'/style.css');
	    dynamicLoadJS('slides_templates/'+template+'/script.js');
	    $("#template").html(slide);

    	$loaded = true;
	}
}


/*
* Pour charger dynamiquement le fichier javascript d'un slide, la fonction ajoutera temporairement une balise script pour inclure le fichier 
* @path le chemin relatif à fournir
*/
function dynamicLoadJS(path) {
    var DSLScript  = document.createElement("script");
    DSLScript.src  = path;
    DSLScript.type = "text/javascript";
    document.body.appendChild(DSLScript);
    document.body.removeChild(DSLScript);
}

function mysql2jsTimestamp(timestamp){
	var t = timestamp.split(/[- :]/);
	return new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
}

Date.createFromMysql = function(mysql_string)
{ 
   if(typeof mysql_string === 'string')
   {
      var t = mysql_string.split(/[- :]/);

      //when t[3], t[4] and t[5] are missing they defaults to zero
      return new Date(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0);          
   }

   return null;   
}




/*
* ---------------------------------
* OLD OLD OLD
* ---------------------------------
*/
nextSlideData = '';

function get_next_slide(plasma_id, doNow){
	
	saved_doNow = doNow;
	
	$.get("XMLrequest_get_slide.php?plasma_id="+plasma_id, function(data){
		//alert(data);
		// stock pour plus tard
		nextSlideData = data;
		
		// sortie de secours
		if(saved_doNow){
			exit_slideshow();	
		}
	});
}

nextId = false;

function get_next_id(plasma_id){
	
	saved_plasma_id = plasma_id;
	
	/*$.get('XMLrequest_get_slide_id.php?plasma_id='+plasma_id, function(data){
		//alert(data);
		if(!nextId){
			nextId = parseInt(data);
			//alert(nextId);
		} else {
			if(nextId != parseInt(data)){
				nextId = parseInt(data);
				
				// changement de programme !
				get_next_slide(saved_plasma_id, true); // maj dès le chargement effectué
			}
		}
	});*/
}

var timer = 0;

