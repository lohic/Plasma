<?php 
/*
@ Gildas
@ Le 12/02/2013
@ Du Javascript avec du Php dedans, c'est le JS général de tous les slideshows
*/
?>

<script language="javascript">

	function clear_slideshow(){
		clearTimeout(exit);
		clearTimeout(verif);
		$("#compteur").stop();
	}

	function exit_slideshow(){
		
		clearTimeout(exit);				
		
		// sortie CSS
		$("#template").addClass("exit"); 
		$("#debug").append("<p>exit</p>");
		
		// sortie dans 2 sec
		end = setTimeout(function(){ 

			clearTimeout(end);
			
			// écriture du slide
			$("#template").removeClass("exit");
			$("#template").html(nextSlideData);
			
			// preload du suivant
			get_next_slide(<?php echo ($info->id); ?>, false);

		}, 2000);

	}
	
	verif = setTimeout(function(){});
	
	function secure_loop(){
		get_next_id(<?php echo ($info->id); ?>);
		
		clearTimeout(verif);
		verif = setTimeout(secure_loop, 20000);
	}

	function play_slideshow(slide_duree){
		
		// sortie programmée
		exit = setTimeout(exit_slideshow, slide_duree-2000);
		
		// debug bar
		$("#compteur").animate({width:"0px"}, slide_duree, "linear");
		
		// preload du suivant
		get_next_slide(<?php echo ($info->id); ?>, false);
		
		// verif
		secure_loop();

	}

	play_slideshow(<?php echo $duree; ?>);

</script>
