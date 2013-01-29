<?php



?>

<div class="form_container">

<div class="options">
        <a href="#" id="add_cat_rss">
            <img src="../graphisme/round_plus.png" alt="ajouter une catégorie ou un flux RSS" title="ajouter une catégorie ou un flux RSS"/>
        </a>
    </div>

	<div class="reset"></div>
    <form id="suppr_cat_form" action="" method="post">
            <input type="hidden" name="suppr_cat" id="suppr_cat" value="ok" />
            <input type="hidden" name="id_suppr_cat" id="id_suppr_cat" value="" />
    </form>

    <form id="suppr_rss_form" action="" method="post">
            <input type="hidden" name="suppr_rss" id="suppr_rss" value="ok" />
            <input type="hidden" name="id_suppr_rss" id="id_suppr_rss" value="" />
    </form>

    <form id="add_cat_form" action="" method="post" >
        <fieldset>
        	<p class="legend">Ajouter une catégorie :</p>
        	<input type="hidden" name="add_actu_cat" value="ok" />
   			<label for="cat_libelle">titre : </label><input type="text" name="libelle" id="cat_libelle" />
        </fieldset>
        <input type="submit" name="add_cat" class="buttonenregistrer" id="add_cat" value="Ajouter une catégorie" />
    </form>
	<div class="reset"></div>

	<form id="add_rss_form" action="" method="post" >
    	<fieldset>
        	<p class="legend">Ajouter un flux RSS :</p>
        	<input type="hidden" name="create_rss" value="ok" />
        	<p><label for="rss_nom">titre : </label><input type="text" name="rss" id="rss_nom" /></p>
        	<p><label for="rss_url">url : </label><input type="text" name="URL" id="rss_url" class="inputField" /></p>
        </fieldset>
        <input type="submit" name="add_rss" class="buttonenregistrer"  id="add_rss" value="Ajouter un flux" />
    </form>
    <div class="reset"></div>


    <h3>Options</h3>
    <div id="cat-list">

    </div>
    
</div>



<script type="text/javascript" language="javascript">
$(document).ready(function(){
	$('#add_cat_form').hide();
	$('#add_rss_form').hide();

	$('#add_cat_rss').click(function(){
		$('#add_cat_form').slideToggle();
		$('#add_rss_form').slideToggle();
		$('.edit').slideUp();
	});
	
	

	
	$('.edit').hide();
	
	$('.modif_cat').click(function(){
		$('#add_cat_form').slideUp();
		$('#add_rss_form').slideUp();
		$('.edit').removeClass('open');
		$('#form-'+$(this).attr('id')).addClass('open');
		$('.edit').not('.open').slideUp();
		$('.open').slideToggle();
	});
	
	
	$('.modif_rss').click(function(){
		$('#add_cat_form').slideUp();
		$('#add_rss_form').slideUp();
		$('.edit').removeClass('open');
		$('#form-'+$(this).attr('id')).addClass('open');
		$('.edit').not('.open').slideUp();
		$('.open').slideToggle();
	});
});
	
	function supprCat(id, nom){
		if(confirm('Voulez vous supprimer la catégorie \''+nom+'\' ? Cette action est irréversible.')){
			$('#id_suppr_cat').val(id);
			$('#suppr_cat_form').submit();
		}
	}
	
	function supprRSS(id, nom){
		if(confirm('Voulez vous supprimer le flux \''+nom+'\' ? Cette action est irréversible.')){
			$('#id_suppr_rss').val(id);
			$('#suppr_rss_form').submit();
		}
	}
</script>

