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
        <?php echo func::createSelect($slide->get_slide_template_list($core->groups_id)	, 'id_template'		, $id_template	, "onchange=\"$('#slide_select_form').submit();\"",true);?>
        <?php echo func::createSelect($anneeListe, 'annee', $annee, "onchange=\"$('#slide_select_form').submit();\"", false ); ?>
        <?php echo func::createSelect($moisListe, 'mois', $mois, "onchange=\"$('#slide_select_form').submit();\"", false ); ?>
    </form>
</div>


<form id="myform" style="width:500px"></form>

<div id="slide_listing" class="listing_container">

	<?php 
	$slide->get_slide_edit_liste($id_template, $annee, $mois);
	?>

</div>


<form id="suppr_slide_form" method="post">
    <input type="hidden" name="cache" value="" id="cache_slide">
    <input type="hidden" name="id_slide" id="id_suppr_slide" value="" />
    <input type="hidden" name="suppr" id="suppr_slide" value="slide" />
</form>


<!--
EVENT SELECTOR
-->
<script id="event_selector" type="text/html">
    <div style="width:500px">
        <h4>Sélectionner un événement</h4>
        <p><label>Organisme :</label>
        <select id="id_organisme" name="id_organisme">
            <!--<option value="1">Direction de la communication</option>-->
            <!--<option value="2">CERI</option>-->
            <!--<option value="6">Picasso</option>-->
        </select></p>

        <p><label>Année / mois :</label>
        <select id="year_event" name="year_event">
            {{#year_event}}
            <option value="{{ key }}">{{ value }}</option>
            {{/year_event}}
        </select></p>
        <p><select id="month_event" name="month_event">
            {{#month_event}}
            <option value="{{ key }}">{{ value }}</option>
            {{/month_event}}
        </select></p>

        <p><label>Événement / session:</label>
        <select id="id_event" name="id_event">
        </select>
        <select id="id_session" name="id_session">
        </select></p>

        <p><button id="refresh_event">Rafraichir les données</button></p>
    </div>
</script>

<script type="text/javascript" language="javascript">

function supprSlide(id, nom){

    console.log($(this));

	/*if(confirm("Etes-vous sûr de supprimer le slide \""+nom+"\" ?"+id)){

		$('#id_suppr_slide').val(id);
		$('#suppr_slide_form').submit();
	}*/
	return false;
}

$(document).ready(function(){

    $('.slide-list-elem .poubelle a').click(function(event){
        event.preventDefault();

        //console.log( $(this).data('nom') );

        var isHidden = $(this).parent().parent().hasClass('hidden');


        if(isHidden){
            var message = "Vous allez réafficher le slide « "+ $(this).data('nom') + "» !";
            $('#cache_slide').val(0);
        }else{
            var message = "Êtes vous sure de vouloir supprimer le slide « "+ $(this).data('nom') + "» ?";
            $('#cache_slide').val(1);
        }

        if(confirm(message)){
            $('#id_suppr_slide').val($(this).data('id'));
            $('#suppr_slide_form').submit();
        }
        return false;

    });

});



</script>