<?php
include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_playlist.php');
include_once('../classe/classe_slide.php');
include_once('../classe/classe_slideshow.php');
include_once('../classe/classe_ecran.php');
include_once('../classe/classe_fonctions.php');

$core = new core();

$id_playlist 	= !empty($_GET['id_playlist'])?$_GET['id_playlist']:NULL;

if(isset($_GET['id_slide'])){
	$slide = new Slide($_GET['id_slide']);
}else{
	$slide = new Slide();
}

if(isset($_GET['id_slideshow'])){
	$slideshow = new SlideShow($_GET['id_slideshow']);
}else{
	$slideshow = new SlideShow();
}

$playlist 	= new Playlist($id_playlist);


$id_plasma	= !empty($_GET['id_plasma'])?$_GET['id_plasma']:NULL;
$ecran 		= new Ecran($id_plasma);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Sciences Po | Administration des écrans plasma</title>
	<link href="../css/admin.css" rel="stylesheet" type="text/css" />

	<link href="../css/sciencespo-jquery-ui/jquery-ui-1.8.22.custom.css" rel="stylesheet" type="text/css">
    <link href="../css/timeline.css" rel="stylesheet" type="text/css">
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <link href="../css/sciencespo.jquery.fancybox.css" rel="stylesheet" type="text/css" media="screen" />

	<!-- JQUERY -->
	<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <!-- ICANHAZ -->
    <script type="text/javascript" src="../js/ICanHaz.min.js" ></script>
    <!-- TIMLINE -->
    <script type="text/javascript" src="../js/timeline-min.js"></script>
    <script type="text/javascript" src="../js/timeline-locales.js"></script>
	<!-- JQUERY UI -->
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../js/jquery.iframe-post-form.js"></script>
	<script type="text/javascript" src="../js/jquery.dragsort-0.3.10.js"></script>
	<script type="text/javascript" src="../js/jquery.cookie.js"></script>
	<script type='text/javascript' src='../js/jquery.form.js'></script>
	<!-- TINY MCE -->
	<script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
	<script type='text/javascript' src='../js/animatedcollapse.js'></script>
    <!-- FANCYBOX -->
    <script type="text/javascript" src="../js/jquery.mousewheel-3.0.6.pack.js"></script>
    <script type="text/javascript" src="../js/jquery.fancybox.js?v=2.1.4"></script>
	<!--<script type="text/javascript" src="../js/json-minified.js"></script>-->
	<!--<script type="text/javascript" src="../js/splitter.js"></script>-->
	<script type="text/javascript" src="../js/fonctions.js"></script>
	<!-- UPLOADIFIVE -->
    <script type="text/javascript" src="../js/uploadifive-v1.1.2-standard/jquery.uploadifive.js"></script>
	<!-- SLiDES -->
	<!--<script type="text/javascript" src="../js/jquery.upload-1.0.2.min.js"></script>-->
	<!--<script language="javascript" type="text/javascript" src="../js/slide.setInputs.js"></script>
	<script language="javascript" type="text/javascript" src="../js/slide.contentParser.js"></script>-->
	<script type="text/javascript" src="../js/slide.compteur-field.js"></script>

	<script type="text/javascript" src="../js/script.js"></script>





	<script type="text/javascript">

	    <!--

		//GESTION DE MENU

		$(document).ready( function () {

			$('ul#menuDown > li').mouseover(function(){ $(this).children('a').addClass('menuDown-hover').siblings('ul').show(); });
			$('ul#menuDown > li').mouseout(function(){ $(this).children('a').removeClass('menuDown-hover').siblings('ul').hide(); });
			

			// GESTION DU MENU PRINCIPAL

			$("#globalnav>ul>li>a").each( function () {	
				$(this).attr("class","");
			} ) ;


			$("#globalnav>ul>li>a").click( function () {
				$("#globalnav>ul>li>a").each( function () {
					$(this).attr("class","");

				} ) ;

				$(this).attr("class","select");	
			} ) ;



			$("li span.trash").each( function () {	
				$(this).click( function () {

					$(this).parent().remove();
					var order = '';

					$('.news_list').each( function () {
						order += $(this).attr('id') +':'+ $(this).sortable('toArray')+'|';
					});

					//alert(order);

					var valeur = document.getElementById("save_value");
					valeur.value = order;

					$('#return_refresh').text('état : Sauvegarde en cours !');
					$('#refresh_form').submit();
				} ) ;
			} ) ;
			
			
			// UPLOADIFIVE
			$('input[name="file"]').uploadifive( {
				'uploadScript' : 'uploadifive/uploadifive.php',
				'removeCompleted' : true,
				'fileSizeLimit' : 0,
				'method' : 'post',
				'formData' : { 'subfolder' : $('input[name="file"]').attr('subfolder') },
				'onUploadComplete' : function(file, data) {
					fieldname = $('input[name="file"]').attr('fieldname');
					$('input[name="'+fieldname+'"]').val(data);
					$('img[name="'+fieldname+'"]').attr('src', '../'+data);
				}
			} );
		} ) ;

		$(function() {
			$(".date").datepicker({
				dateFormat: 'yy-mm-dd',
				firstDay: 1,
				dayNamesMin : ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
				monthNames : ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre']
			});
		});
	</script>



</head>


<body>
<div id="page">
	<?php if(!isset($_GET['page']) || empty($_GET['page'])){ $_GET['page']= '' ; } ?>
    <?php
	if(!$core->isAdmin){ 

	// SI ON N'EST PAS EN MODE ADMIN
    // LE MENU D'IDENTIFICATION

	

		include_once('../structure/header.php'); 
		include_once('../structure/login.php');    

    }else{

	// SINON
	// LE MENU GENERAL 

		include_once('../structure/header.php');
    	include_once('../structure/menu.php');

	

		switch($_GET['page']){

			case 'playlist_select' :
				include_once('../structure/playlist-select.php');
			break;

			case 'playlist_create' : case 'playlist_modif' : 
				include_once('../structure/playlist-modif.php');
			break;

			case 'slides_select' :
				include_once('../structure/slide-select.php');
			break;

			case 'slide_create' : case 'slide_modif' :
				include_once('../structure/slide-modif.php');
			break;

			case 'slide_template' :
				include_once('../structure/slide-templates.php');
			break;

			case 'ecrans' :
				include_once('../structure/plasma-select.php');
			break;

			case 'ecrans_modif' : case 'ecran_create' :
				include_once('../structure/plasma-modif.php');
			break;
			
			case 'ecrans_groupe_modif' : case 'ecrans_groupe_create' :
				include_once('../structure/plasma-groupe-modif.php');
			break;

			case 'etablissements' :
				include_once('../structure/etablissements.php');
			break;
			
			case 'options' :
				include_once('../structure/admin-options.php');
			break;

			case 'comptes' :
				include_once('../structure/admin-comptes.php');
			break;

			case 'organismes' :
				include_once('../structure/admin-organismes.php');
			break;

			default :
				include_once('../structure/plasma-select.php');
			break;

		}

		?>

        

    <div class="reset" ></div>
    <div class="bottom-div"></div>

    <?php }?>

</div>

</body>
</html>