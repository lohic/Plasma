// JavaScript Document
/*function limite(textarea, max)
{
    if(textarea.value.length >= max)
    {
        //textarea.value = textarea.value.substring(0,max);
    }
    var reste = max - textarea.value.length;
    var affichage_reste =  reste<0?0:reste;
  	$('.compteur').text(affichage_reste);
	$('.valide').text(textarea.value.substring(0,max));
	$('.entrop').text(textarea.value.substring(max));
	
	if(reste>=0){
		$('.entrop').hide();
		$(textarea).css('border-color','#EEE');
	}else{
		$('.entrop').show();
		$(textarea).css('border-color','#F00');
	}
}*/

/*
@ LOIC
@ 23/07/2012
@ pour fonctionner avec un textarea tinymce ou ckeditor
@
*/
function limite(target, max) {
	
	var istextarea 	= $(target).is('textarea');
	var textvalue 	= $(target).val();
	
	if(istextarea == true){
		textvalue = $(textvalue).text();
	}
	
    var reste 			= max - textvalue.length;
    var affichage_reste = reste < 0 ? 0 : reste;
	
  	$('.compteur').text(affichage_reste);
	$('.valide').text(textvalue.substring(0,max));
	$('.entrop').text(textvalue.substring(max));
	
	if(reste >= 0){
		$('.entrop').hide();
		$(target).css('border-color','#EEE');
	}else{
		$('.entrop').show();
		$(target).css('border-color','#F00');
	}
}



function addLimitBox(target){
	var boite = '<div class="boite"><div class="fleche"></div><div class="compteur">OK</div><div class="valide"></div><div class="entrop"></div></div>';
	
	$(target).after(boite);
	
	window.status = $(target).width()+ " " +$(target).offset().left + " " +($(target).width()+$(target).offset().left);
	
	$('.boite').css('left',$(target).width()+$(target).position().left);
}

$(document).ready(function(){
	$('.entrop').hide();
	$('.boite').hide();
	
	
	$('input[type=text], textarea').each(function(){
		if($(this).attr('max') !== undefined){
			limite(this,$(this).attr('max'));
			
			$(this).focus(function(){
				//$('.boite').css('top', $(this).offset().top);
				//$('.boite').show();
				addLimitBox(this);
				limite(this,$(this).attr('max'));
				
				$(this).keyup(function(){
					limite(this,$(this).attr('max'));
				});
				
				$(this).keydown(function(){
					limite(this,$(this).attr('max'));
				});
			});
			
			$(this).blur(function(){
				//$('.boite').hide();
				$('.boite').remove();
			});
		}
	});
	
});