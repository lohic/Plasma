<?php
header('Content-type: text/html; charset=UTF-8');
include_once('../vars/constantes_vars.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slide.php');
include_once('../classe/fonctions.php');


$core = new core();



if(isset($_GET['id_slide'])){
	$slide = new Slide($_GET['id_slide']);
}else{
	$slide = new Slide();
}


$annee = isset($_GET['annee'])?$_GET['annee']:date('Y');
$mois = isset($_GET['mois']) ? $_GET['mois'] : date('m');
$id_template	= !empty($_GET['id_template'])?$_GET['id_template']:-1;

?>

<div>
	<?php 
	echo $slide->get_slide_popup_liste($id_template, $annee, $mois);
	?>
</div>


<script type="text/javascript" language="javascript">

$(document).ready(function() {

});

</script>