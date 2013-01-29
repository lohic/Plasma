
// Javascript


$(document).ready(function(){
	
	var videoURL = '../'+$('.video').html();
		
	$('.video').html('<video id="video" autoplay="true"><source src="'+videoURL+'" type="video/mp4" /><p>La vid�o ne fonctionne pas</p></video>');
	
	$("video")
	.bind("ended", function(e) {
		e.preventDefault();
		// sortie
		exit_slideshow();
	})
	.bind("loadedmetadata", function(e){
		e.preventDefault();
		var width = this.videoWidth;
        var height = this.videoHeight;
        var duration = this.duration; // dur�e en ms
		
		// on stoppe le compte � rebour...
		clear_slideshow();
	});	
	
});
