
// Javascript


$(document).ready(function(){
	$('body, th, td, .header, .texte, .footer, .texte2, #template').removeAttr( 'style' );

	$('body').css('transform', 'translate('+$decalX+'px, '+$decalY+'px) scale('+$scale+')');

	loadMovie();
});

function loadMovie(){
	
	$("video")
	.bind("ended", function(e) {
		e.preventDefault();
	})
	.bind("loadedmetadata", function(e){
		e.preventDefault();
		var width = this.videoWidth;
        var height = this.videoHeight;
        var duration = this.duration; // durée en ms
		
		console.log(width+" "+height+" "+duration);
	});
}

