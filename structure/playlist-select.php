<?php

include_once('../classe/classe_playlist.php');
//include_once('../classe/classe_ecran.php');

//$id_slideshow 	= !empty($_GET['id_slideshow'])?$_GET['id_slideshow']:NULL;
$annee 			= isset($_GET['annee'])?$_GET['annee']:date('Y');
$mois 			= isset($_GET['mois']) ? $_GET['mois'] : date('m');

//$listeCampus	= 

$playlist	 	= new Playlist();

?>


<div id="news_select" class="form_container">
    <form id="playlist_select_form" action="" method="get">
        <input type="hidden" name="page" value="playlist_select" />
        <?php //createSelect($array, $name='', $id = NULL, $additionnal=NULL, $isnull=true) ?>
        <?php //echo func::createSelect($slideshow->get_slideshow_category_list($core->groups_id)	, 'id_template'		, $id_template	, "onchange=\"$('#news_select_form').submit();\"",false);?>
        <?php echo func::createSelect($anneeListe,	'annee',	$annee,	"onchange=\"$('#playlist_select_form').submit();\"", false ); ?>
        <?php echo func::createSelect($moisListe,	'mois',		$mois,	"onchange=\"$('#playlist_select_form').submit();\"", false ); ?>
    </form>
</div>

<div id="news_listing">
	<?php echo $playlist->get_playlist_edit_liste($annee,$mois); ?>
</div>


<script type="text/javascript" language="javascript">
$(document).ready(function() {


});
</script>