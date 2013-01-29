<script type="text/javascript" language="javascript">
$(document).ready(function(){
	
	var itemSelected;
	
	$('#slideselector').hide();
	
	$('#slideselector').mouseleave(function(){
		$('#slideselector').hide();
	});

	
	$('#slideselector #slidelisting').load('XMLrequest_get_slide_list.php', function(){
	});
	
	var remove	 		= '<a href="#" class="del"><img src="../graphisme/round_minus.png" alt="supprimer un slide" height="16"/></a>';
	var selectM			= '<?php echo $ecran->get_form_select()->M; ?>';
	var selectJ			= '<?php echo $ecran->get_form_select()->J; ?>';
	var selectj			= '<?php echo $ecran->get_form_select()->j; ?>';
	var selectSlides	= '<input type="hidden" value="<?php echo $ecran->get_form_select()->default; ?>" name="id_slide[]" class="id_slide"/><a class="slidelistselect empty">choisir</a>'
		
	$('#add_date').click(function(){
		
		var now = new Date();
		var timestamp = now.getTime();
		
		$('#addslidedatelist').append( '<li class="new" id="'+timestamp+'"><input class="id_rel" type="hidden" name="id_rel[]" value="" /><input type="hidden" name="typerel[]" value="date" /><input type="hidden" name="timestamp[]" value="'+timestamp+'" /><input type="hidden" name="M[]" value="" /><input type="hidden" name="J[]" value="" /><input type="hidden" name="j[]" value="" /><input type="hidden" name="H[]" value="" />'+remove+'<img src="" width="28" height="18" class="icone" /><span></span> <span>date : <input name="date[]" type="text" value="<?php echo date("Y-m-d");?>" class="dateslide"/></span> <span>horaire : <input name="time[]" type="text" value="12:00:00" class="timeslide"/></span> <span>durée : <input name="duree[]" type="text" value="00:30:00" class="dureeslide"/></span> <span><a href="../slideshow/?slide_id=&preview&debug" target="_blank" class="preview"><img src="../graphisme/eye.png" alt="voir"/></a></span> '+selectSlides+'</li>' );
		
		addSlidefunction();
		updateData();
		
		return false;
	});
	
	$('#add_freq').click(function(){	
		
		var now = new Date();
		var timestamp = now.getTime();
		
		$('#addslidefreqlist').append( '<li class="new" id="'+timestamp+'"><input class="id_rel" type="hidden" name="id_rel[]" value="" /><input type="hidden" name="typerel[]" value="freq" /><input type="hidden" name="timestamp[]" value="'+timestamp+'" /><input type="hidden" name="date[]" value="" /><input type="hidden" name="time[]" value="" />'+remove+'<img src="" width="28" height="18" class="icone" /><span>'+selectM+selectJ+selectj+'</span> <span>horaire : <input name="H[]" type="text" value="12:00:00" class="timeslide"/></span> <span>durée : <input name="duree[]" type="text" value="00:30:00" class="dureeslide"/></span> <span><a href="../slideshow/?slide_id=&preview&debug" target="_blank" class="preview"><img src="../graphisme/eye.png" alt="voir"/></a></span> '+selectSlides+'</li>' );
		
		addSlidefunction();
		updateData();
		
		return false;
	});
	
	
	$('#add_flux').click(function(){	
		
		var now = new Date();
		var timestamp = now.getTime();
		
		$('#addslidefluxlist').append( '<li class="new" id="'+timestamp+'"><input class="id_rel" type="hidden" name="id_rel[]" value="" /><input type="hidden" name="typerel[]" value="freq" /><input type="hidden" name="timestamp[]" value="'+timestamp+'" /><input type="hidden" name="date[]" value="" /><input type="hidden" name="time[]" value="" />'+remove+'<img src="" width="28" height="18" class="icone" /><span>'+selectM+selectJ+selectj+'</span> <span>horaire : <input name="H[]" type="text" value="12:00:00" class="timeslide"/></span> <span>durée : <input name="duree[]" type="text" value="00:30:00" class="dureeslide"/></span> <span><a href="../slideshow/?slide_id=&preview&debug" target="_blank" class="preview"><img src="../graphisme/eye.png" alt="voir"/></a></span> '+selectSlides+'</li>' );
		
		addSlidefunction();
		updateData();
		
		return false;
	});
	
	
	addSlidefunction();
});

/*****************************
**
** SELECTEUR DE SLIDES
**
*****************************/
function slideselector(ref){
	//alert('ok');
	
	$( "#slideselector" )
	.show()
	.stop()
	.position({
		of: $( ref ),
		my: 'left center',
		at: 'center center',
		offset:'0 0',
	},function(){
		$('#slideselector #liste');
	});	
	
	$('#slideselector #id-selected-ecran').text( "ID  :"+$(ref).parent().find('.id_rel').val() );
	
	itemSelected = ref;
	
	$( "#slideselector img" )
	.unbind("click")
	.click(function(){		
		clicSlideSelector($(this));
	});
	
}

/*****************************
**
** UPDATE DES SLIDES FREQUENCE
**
*****************************/
function updateData(){
	$('#modif_slide_list_form').submit();
}

$('#modif_slide_list_form').ajaxForm({ 
	target: '#return_refresh',
	success: function(data) {
		//$('#return_refresh').html(data);
		
		$('#suppr_id_rel_slide').val('');
		
		
		$('.new').each(function(){
			$(this).find('.id_rel').val($('#return_refresh').text());
			$(this).removeClass('new');
		});
		
		var myJsonObj = jsonParse(data);
		for (var k in myJsonObj) {
		  // alerts x=Hello, World!  and  y=1,2,3
		  $('li#'+k).find('input[name*="id_rel"]').val(myJsonObj[k]);
		}
				
		$('#return_refresh').html('<p>valeurs sauvegardées<p>');
		$('#return_refresh').fadeIn('slow').delay(500).fadeOut('slow');
	}
});


$('#slide_select_form').ajaxForm({ 
	target: '#slidelisting',
	success: function() {
		$(this).show();
		
		$( "#slideselector img" )
		.unbind("click")
		.click(function(){
			clicSlideSelector($(this));
		});
	}
});


function clicSlideSelector(ref){
	var complexID = $(ref).parent().attr('id');
	var temp = complexID.split('-')
	var realID = parseInt(temp[1]);
	var icone = $(ref).parent().find('img').attr('src');
	var titre = $(ref).parent().find('.titre').text();
	var newURL = encodeURI('../slideshow/?slide_id=' + realID + '&preview');
	
	$(itemSelected).parent().find('a.slidelistselect').text( titre ).removeClass('empty');
	$(itemSelected).parent().find('img.icone').attr('src', icone );
	$(itemSelected).parent().find('input.id_slide').val( realID );
	$(itemSelected).parent().find('a.preview').attr( 'href' , newURL );
	
	$( "#slideselector" ).hide();
	
	updateData();
}

	
function supprPlasma(id, nom){
	if(confirm('Voulez vous supprimer l\'écran '+nom+' ? Cette action est irréversible.')){
		$('#id_suppr_plasma').val(id);
		$('#suppr_plasma_form').submit();
	}
}

function addSlidefunction(){
	$(".dateslide").datepicker('destroy').datepicker({
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
		dayNamesMin : ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
		monthNames : ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
		onSelect: function(){
			updateData();
		},
	});
	
	$('.timeslide').timepicker('destroy').timepicker({timeFormat: 'hh:mm:ss',
										onSelect: function(dateText, inst){
											updateData();
										}});
	$('.dureeslide').timepicker('destroy').timepicker({	timeFormat: 'hh:mm:ss',
										showSecond:true,
										secondGrid:30,
										minuteGrid:10,
										onSelect: function(dateText, inst){
											updateData();
										}});
	
	$('.del').unbind('click').click(function(){
	
		$('#suppr_id_rel_slide').val($('#suppr_id_rel_slide').val()+','+$(this).parent().find('.id_rel').val());
		$(this).parent().remove();
		updateData();
		
	});
	
	$('.slidelistselect').unbind('click').click(function(){
		slideselector($(this));
	});
	
	$('#addslidefreqlist select[name*="J"]').unbind('change').change(function(){
		$(this).parent().find('select[name*="j"]').val('');
		updateData();
	});
	
	$('#addslidefreqlist select[name*="j"]').unbind('change').change(function(){
		$(this).parent().find('select[name*="J"]').val('');
		updateData();
	});
	
	$('#addslidefreqlist select[name*="M"]').unbind('change').change(function(){
		updateData();
	});
	
	$('#addslidefreqlist select[name*="id_slide"]').unbind('change').change(function(){
		updateData();
	});	
	
	$('#addslidefreqlist input').unbind('blur').blur(function(){
		updateData();
	});
	
}

</script>