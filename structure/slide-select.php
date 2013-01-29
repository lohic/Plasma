<?php

if(isset($_POST['suppr_slide']) && !empty($_POST['id_suppr_slide'])){
	//$news->suppr_newsletter($_POST['id_suppr_newsletter']);
}

$annee = isset($_GET['annee'])?$_GET['annee']:date('Y');
$mois = isset($_GET['mois']) ? $_GET['mois'] : date('m');

$id_template	= !empty($_GET['id_template'])?$_GET['id_template']:-1;
$annee			= !empty($_GET['annee'])?$_GET['annee']:date('Y');
$mois			= !empty($_GET['mois'])?$_GET['mois']:date('m');

?>


<div id="news_select" class="form_container">
    <form id="slide_select_form" action="" method="get">
        <input type="hidden" name="page" value="slides_select" />
        <?php //createSelect($array, $name='', $id = NULL, $additionnal=NULL, $isnull=true) ?>
        <?php echo createSelect($slide->get_slide_template_list($core->groups_id)	, 'id_template'		, $id_template	, "onchange=\"$('#slide_select_form').submit();\"",true);?>
        <?php echo createSelect($anneeListe, 'annee', $annee, "onchange=\"$('#slide_select_form').submit();\"", false ); ?>
        <?php echo createSelect($moisListe, 'mois', $mois, "onchange=\"$('#slide_select_form').submit();\"", false ); ?>
    </form>
</div>



<div id="news_listing" class="listing_container">

	<?php 
	$slide->get_slide_edit_liste($id_template, $annee, $mois);
	?>

</div>


<form id="suppr_slide_form" method="post">
    <input type="hidden" name="id_slide" id="id_suppr_slide" value="" />
    <input type="hidden" name="suppr" id="suppr_slide" value="slide" />
</form>

<script type="text/javascript" language="javascript">

function supprSlide(id, nom){ 
	// GILDAS 19/07/2012
	if(confirm("Etes-vous sûr de supprimer le slide \""+nom+"\" ?"+id)){
		$('#id_suppr_slide').val(id);
		$('#suppr_slide_form').submit();
	}
	return false;
}

/*function saveOrderPage() {
	var serialStr1 = "";
	//var serialStr2 = "";
	//var serialStr3 = "";
	$("body ul#evenements_list>li").each(function(i, elm) { serialStr1 += (i > 0 ? "|" : "") + $(elm).attr("id"); });
	// this dynamically updates string to hidden form field
	//alert( serialStr1+"\n"+serialStr2+"\n"+serialStr3);
	
	//var valeur = document.getElementById("save_value");
	var valeur = document.getElementById("save_value");
	valeur.value = serialStr1;
		
	$('#refresh_form').submit();
};*/

$(document).ready(function() {

	// ACTIVATION DU DRAG&DROP
	
	$(function() {
		$(".sort").sortable({
			connectWith: '.sort',
			helper :			function (evt, ui) { return $(ui).clone().appendTo('body').show(); },
			placeholder: "ui-state-highlight",
			//handle : 'span.handler',
			/*stop: function() {
				var order = '';
				$('.news_list').each( function () {
					order += $(this).attr('id') +':'+ $(this).sortable('toArray')+'|';
				});
				//alert(order);
				var valeur = document.getElementById("save_value");
				valeur.value = order;
				
				$('#return_refresh').text('état : Sauvegarde en cours !');
				$('#refresh_form').submit();
			}*/
		});
		
		//$(".sort_list").disableSelection();
	});

});

</script>