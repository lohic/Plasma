
function get_next_slide(plasma_id){
	// requ�te ajax
	//alert('next slide : plasma_id='+plasma_id);
	
	$.get("XMLrequest_get_slide.php?plasma_id=".plasma_id, function(data) {
																	
		/* transition �ventuelle */
		/*$('#template').fadeOut('slow', function(){
			$('#template').html(data);
			$('#template').fadeIn('slow');
		});*/
		$('#template').removeClass('exit');
		$('#template').html(data);
		
	});
};