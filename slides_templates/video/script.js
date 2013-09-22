
// Javascript


$(document).ready(function(){
	$('body, th, td, .header, .texte, .footer, .texte2, #template').removeAttr( 'style' );

	loadMovie();
});

function loadMovie(){
	
	//var videoURL = '../'+$('.video').html().replace(/\s/g, '').replace(/(\r\n|\n|\r)/gm,"");
	//alert('#'+videoURL+'#');
		
	//$('.video').html('<video id="video" autoplay="true" src="'+videoURL+'"><p>La vidéo ne fonctionne pas</p></video>');
	
	$("video")
	.bind("ended", function(e) {
		e.preventDefault();
		// sortie
		//exit_slideshow();
	})
	.bind("loadedmetadata", function(e){
		e.preventDefault();
		var width = this.videoWidth;
        var height = this.videoHeight;
        var duration = this.duration; // durée en ms
		
		// on stoppe le compte à rebour...
		//clear_slideshow();
		//
		console.log(width+" "+height+" "+duration);
	});
}

