<?php
include_once('../vars/config.php');
include_once(REAL_LOCAL_PATH.'vars/statics_vars.php');
include_once(REAL_LOCAL_PATH.'classe/classe_core.php');
include_once(REAL_LOCAL_PATH.'classe/classe_playlist.php');
include_once(REAL_LOCAL_PATH.'classe/classe_slide.php');
include_once(REAL_LOCAL_PATH.'classe/classe_slideshow.php');
include_once(REAL_LOCAL_PATH.'classe/classe_ecran.php');
include_once(REAL_LOCAL_PATH.'classe/classe_fonctions.php');

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


?><!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Sciences Po | Administration des écrans plasma</title>
	<link href="../css/admin.css" rel="stylesheet" type="text/css" />

	<link href="../css/sciencespo-jquery-ui/jquery-ui-1.8.22.custom.css" rel="stylesheet" type="text/css">
    <link href="../css/timeline-theme.css" rel="stylesheet" type="text/css">
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <link href="../css/sciencespo.jquery.fancybox.css" rel="stylesheet" type="text/css" media="screen" />

	<!-- JQUERY -->
	<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="../js/jquery-migrate-1.2.1.min.js"></script>
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
    <!-- DFORM -->
    <script type="text/javascript" src="../js/jquery.dform-1.1.0.js" ></script>
	<!-- TINY MCE -->
	<script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
	<script type='text/javascript' src='../js/animatedcollapse.js'></script>
    <!-- FANCYBOX -->
    <script type="text/javascript" src="../js/jquery.mousewheel-3.0.6.pack.js"></script>
    <script type="text/javascript" src="../js/jquery.fancybox.pack.js?v=2.1.5"></script>

	<!--<script type="text/javascript" src="../js/fonctions.js"></script>-->
	<!-- UPLOADIFIVE -->
    <script type="text/javascript" src="../js/uploadifive-v1.1.2-standard/jquery.uploadifive.js"></script>
	<!-- SLiDES -->


	<script type="text/javascript" src="../js/script.js"></script>


	<script type="text/javascript">

		// VARIABLES POUR L'EDITION DE SLIDES
		// timestamp & token pour uplodifive
		$timestamp  = <?php echo $timestamp = time();?>;
        $token      = '<?php echo md5('sciences_po_plasma' . $timestamp);?>';
        // tableau des extensions vidéo
        $videoExt   = Array('mp4','mov');
        // template sélectionné > à supprimer à terme
        $template = 'compte_a_rebours';
        // id du slide > à supprimer à terme
        $slide_id = 1;
        $id_groupe = <?php echo !empty($_GET['id_groupe']) ? $_GET['id_groupe'] : 0; ?>;
	  		
	</script>

</head>

<body>
	<div id="page">
		<?php if(!isset($_GET['page']) || empty($_GET['page'])){ $_GET['page']= '' ; } ?>
	    <?php
		if(!$core->isAdmin){ 

		// SI ON N'EST PAS EN MODE ADMIN
	    // LE MENU D'IDENTIFICATION

			include_once(REAL_LOCAL_PATH.'structure/header.php'); 
			include_once(REAL_LOCAL_PATH.'structure/login.php');    

	    }else{

		// SINON
		// LE MENU GENERAL 

			include_once(REAL_LOCAL_PATH.'structure/header.php');
	    	include_once(REAL_LOCAL_PATH.'structure/menu.php');

			switch($_GET['page']){

				case 'playlist_select' :
					include_once(REAL_LOCAL_PATH.'structure/playlist-select.php');
				break;

				case 'playlist_create' : case 'playlist_modif' : 
					include_once(REAL_LOCAL_PATH.'structure/playlist-modif.php');
				break;

				case 'slides_select' :
					include_once(REAL_LOCAL_PATH.'structure/slide-select.php');
				break;

				case 'slide_create' : case 'slide_modif' :
					include_once(REAL_LOCAL_PATH.'structure/slide-modif.php');
				break;

				case 'slide_template' :
					include_once(REAL_LOCAL_PATH.'structure/slide-templates.php');
				break;

				case 'ecrans' :
					include_once(REAL_LOCAL_PATH.'structure/plasma-select.php');
				break;

				case 'ecrans_modif' : case 'ecran_create' :
					include_once(REAL_LOCAL_PATH.'structure/plasma-modif.php');
				break;
				
				case 'ecrans_groupe_modif' : case 'ecrans_groupe_create' :
					include_once(REAL_LOCAL_PATH.'structure/plasma-groupe-modif.php');
				break;

				case 'etablissements' :
					include_once(REAL_LOCAL_PATH.'structure/etablissements.php');
				break;
				
				case 'options' :
					include_once(REAL_LOCAL_PATH.'structure/admin-options.php');
				break;

				case 'comptes' :
					include_once(REAL_LOCAL_PATH.'structure/admin-comptes.php');
				break;

				case 'organismes' :
					include_once(REAL_LOCAL_PATH.'structure/admin-organismes.php');
				break;

				default :
					include_once(REAL_LOCAL_PATH.'structure/plasma-select.php');
				break;
			}
		?>

	    <div class="reset" ></div>
	    <div class="bottom-div"></div>

	    <?php }?>
		
	</div>
<iframe id="preview_screen" style="width:384px;height:216px;border:0;" src=""></iframe>
</body>
</html>