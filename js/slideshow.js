nextSlideData = '';

function get_next_slide(plasma_id, doNow){
	
	saved_doNow = doNow;
	
	$.get("XMLrequest_get_slide.php?plasma_id="+plasma_id, function(data){
		//alert(data);
		// stock pour plus tard
		nextSlideData = data;

		alert(data);
		
		// sortie de secours
		if(saved_doNow){
			exit_slideshow();	
		}
	});
}

nextId = false;

function get_next_id(plasma_id){
	
	saved_plasma_id = plasma_id;
	
	$.get('XMLrequest_get_slide_id.php?plasma_id='+plasma_id, function(data){
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
	});
}