<?php


$annee = isset($_GET['annee'])?$_GET['annee']:date('Y');
$mois = isset($_GET['mois']) ? $_GET['mois'] : date('m');

$id_template	= !empty($_GET['id_template'])?$_GET['id_template']:-1;
$annee			= !empty($_GET['annee'])?$_GET['annee']:date('Y');
$mois			= !empty($_GET['mois'])?$_GET['mois']:date('m');
$code_postal	= !empty($_GET['code_postal'])?$_GET['code_postal']:-1;

?>


<div id="news_select" class="form_container">
    <form id="news_select_form" action="" method="get">
        <input type="hidden" name="page" value="news_select" />
        <?php //createSelect($array, $name='', $id = NULL, $additionnal=NULL, $isnull=true) ?>
        <?php //echo createSelect($slideshow::get_slideshow_category_list($core->groups_id)	, 'id_template'		, $id_template	, "onchange=\"$('#news_select_form').submit();\"",false);?>
        <?php //echo createSelect($anneeListe, 'annee', $annee, "onchange=\"$('#news_select_form').submit();\"", false ); ?>
        <?php echo func::createSelect($villeListe, 'code_postal', $code_postal, "onchange=\"$('#news_select_form').submit();\"", false ); ?>
    </form>
</div>


<div id="news_listing" class="listing_container">
	
    <?php $ecran->get_admin_ecran_groupe_list(); ?>
	
</div>


<form id="suppr_plasma_form" method="post">
    <input type="hidden" name="id_suppr_plasma" id="id_suppr_plasma" value="" />
    <input type="hidden" name="suppr_plasma" id="suppr_plasma" value="ok" />
</form>

<script type="text/javascript" language="javascript">
$.fn.tagName = function() {
   return this.get(0).tagName.toLowerCase();
}

$(document).ready(function(){
	$('#add_plasma_form').hide();

	$('#add_plasma').click(function(){
		$('#add_plasma_form').slideToggle();
		$('.edit').slideUp();
	});
	
	$('.edit').hide();
	$('.child-screen').hide();
	
	$('.show_children').click(function(){
		$('.child-screen').removeClass('open');
		$(this).parent().parent().next().addClass('open');
		$('.child-screen').not('.open').slideUp();
		$('.open').slideToggle('fast',function(){
			$('.child-screen .ecran')
			.stop()
			.css('opacity',0)
			.animate({opacity:1},'slow');
		});
	});
	

	// lors de la mise à jour du input range
    $("input[type=range]").on('change', function () {
        var valof = $(this).val();
        $(this).parent().find('output span').text(valof);
    });
});



	
function supprPlasma(id, nom){
	if(confirm('Voulez vous supprimer l\'écran '+nom+' ? Cette action est irréversible.')){
		$('#id_suppr_plasma').val(id);
		$('#suppr_plasma_form').submit();
	}
}

</script>
