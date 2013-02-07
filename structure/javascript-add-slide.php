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
	var selectM			= '<?php echo $MSelect = $ecran->get_form_select()->M; ?>';
	var selectJ			= '<?php echo $JSelect = $ecran->get_form_select()->J; ?>';
	var selectj			= '<?php echo $jSelect = $ecran->get_form_select()->j; ?>';
	var selectSlides	= '<input type="hidden" value="<?php echo $ecran->get_form_select()->default; ?>" name="id_slide[]" class="id_slide"/><a class="slidelistselect empty">choisir</a>'
	
	// AJOUT DE DATE
	$('#add_date').click(function(){
		
		var now = new Date();
		var timestamp = now.getTime();
		
		$('#addslidedatelist').append( '<?php
		$is_date = true;
		$is_freq = false;
		$is_flux = false;
		ob_start();
		include('../structure/slide-playlist-list-bloc.php');
		$js = ob_get_contents();
		ob_end_clean();
		
		$js = str_replace("\r",'',$js);
		echo $js = str_replace("\n",'',$js);
		
		?>');
		
		addSlidefunction();
		updateData();
		
		return false;
	});
	
	// AJOUT DE FREQUENCE
	$('#add_freq').click(function(){	
		
		var now = new Date();
		var timestamp = now.getTime();
		
		
		$('#addslidefreqlist').append( '<?php
		$is_date = false;
		$is_freq = true;
		$is_flux = false;
		ob_start();
		include('../structure/slide-playlist-list-bloc.php');
		$js = ob_get_contents();
		ob_end_clean();
		
		$js = str_replace("\r",'',$js);
		echo $js = str_replace("\n",'',$js);
		
		?>');
		
		addSlidefunction();
		updateData();
		
		return false;
	});
	
	
	// AJOUT DE FLUX
	$('#add_flux').click(function(){	
		
		var now = new Date();
		var timestamp = now.getTime();
		
		
		$('#addslidefluxlist').append( '<?php
		$is_date = false;
		$is_freq = false;
		$is_flux = true;
		ob_start();
		include('../structure/slide-playlist-list-bloc.php');
		$js = ob_get_contents();
		ob_end_clean();
		
		$js = str_replace("\r",'',$js);
		echo $js = str_replace("\n",'',$js);
		
		?>');
		
		addSlidefunction();
		updateData();
		
		return false;
	});
	
	// AJOUT D'ALERTE LOCALE (DATE)
	$('#add_alerte_locale').click(function(){
			
		var now = new Date();
		var timestamp = now.getTime();
		
		$('#addslidealertelocale').append( '<?php
		$is_date	= true;
		$is_freq	= false;
		$is_flux	= false;
		$alerte		= '75000';
		ob_start();
		include('../structure/slide-playlist-list-bloc.php');
		$js = ob_get_contents();
		ob_end_clean();
		
		$js = str_replace("\r",'',$js);
		echo $js = str_replace("\n",'',$js);
		
		?>');
		
		addSlidefunction();
		updateData();
		
		
		
		return false;
	});
	
	// AJOUT D'ALERTE NATIONALE (DATE)
	$('#add_alerte_nationale').click(function(){
			
		var now = new Date();
		var timestamp = now.getTime();
		
		$('#addslidealertnationale').append( '<?php
		$is_date	= true;
		$is_freq	= false;
		$is_flux	= false;
		$alerte		= 'all';
		ob_start();
		include('../structure/slide-playlist-list-bloc.php');
		$js = ob_get_contents();
		ob_end_clean();
		
		$js = str_replace("\r",'',$js);
		echo $js = str_replace("\n",'',$js);
		
		?>');
		
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
				
		$('#return_refresh').html('<p>valeurs sauvegardées<p>'+data);
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