<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>LOOP | Écrans PLASMA</title>

	<link href="<?php echo ABSOLUTE_URL; ?>fonts/GoetheGothic.css"  rel="stylesheet" type="text/css" />
	<link href="<?php echo ABSOLUTE_URL; ?>fonts/Fenice.css"  		rel="stylesheet" type="text/css" />
	<link href="<?php echo ABSOLUTE_URL; ?>css/slideshow.css" 		rel="stylesheet" type="text/css" />
	<link href="<?php echo ABSOLUTE_URL; ?>css/slideshow.css" 		rel="stylesheet" type="text/css" name="slide_css" />

	<script src="<?php echo ABSOLUTE_URL; ?>js/jquery-1.10.2.min.js"  language="javascript"></script>
	<script src="<?php echo ABSOLUTE_URL; ?>js/ICanHaz.min.js" 		  language="javascript"></script>
	<script src="<?php echo ABSOLUTE_URL; ?>js/slideshow.js?v1.0beta" language="javascript"></script>

</head>

<body class="<?php echo $class; ?>" data-name="<?php echo $ecran->nom;?>" data-code-postal="<?php echo $ecran->code_postal; ?>" data-code-meteo="<?php echo $ecran->code_meteo;?>">
	<div class="console">
		<p id="retour"></p>

		<h1><?php //echo $this->ecran->nom; ?></h1>
		<p class="date"><span id="now"></span></p>
		<p class="date"><span id="end"></span></p>
		<p class="info">Informations</p>
		<p><button id="half_button">HALF</button> <button id="exit_button">EXIT</button> <button id="pause_button">PLAY/PAUSE</button></p>
	</div>

	<div id="template"></div>

	<?php

	$this->get_templates();

	?>

</body>
</html>