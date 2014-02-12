
// Javascript


$(document).ready(function(){
	$('body, th, td, .header, .texte, .footer, .texte2, #template').removeAttr( 'style' );

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
		
		console.log("DATA VIDEO : "+width+" "+height+" "+duration);

		centerVideo(width,height);

	});
}


function centerVideo(width,height){

	$target = $('#template');

	$tW = $target.width();
	$tH = $target.height();

	console.log("template : "+ $tW+' '+$tH);

	$ratioTarget = $tW/$tH;
	$ratioVideo = width/height;

	console.log("template : "+ $ratioTarget + " video : " + $ratioVideo);

	if( $ratioTarget < $ratioVideo){
		$('#videoContent').height(1280);
		$('#videoContent').width(1280*$ratioVideo);

		$('video').attr('height',1280);
		$('video').attr('width',1280*$ratioVideo);

		console.log(1280*$ratioVideo+' x ' + 1280);

		$('#videoContent').css('left', (1280 - 1280*$ratioVideo)/2 - 1280/4);
	}else{
		$('#videoContent').height(720);
		$('#videoContent').width(720*$ratioVideo);

		$('video').attr('height',720);
		$('video').attr('width',720*$ratioVideo);

		console.log(720*$ratioVideo +' x ' + 720);

		$('#videoContent').css('left', (1280 - 720*$ratioVideo)/2);
	}



}