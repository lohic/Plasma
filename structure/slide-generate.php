<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>LOOP | Écrans PLASMA</title>

	<link href="<?php echo ABSOLUTE_URL; ?>fonts/GoetheGothic.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo ABSOLUTE_URL; ?>css/slideshow.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo ABSOLUTE_URL; ?>css/slideshow.css" rel="stylesheet" type="text/css" name="slide_css" />

	<script src="<?php echo ABSOLUTE_URL; ?>js/jquery-1.10.2.min.js" language="javascript"></script>
	<script src="<?php echo ABSOLUTE_URL; ?>js/ICanHaz.min.js" ="javascript"></script>
	<script src="<?php echo ABSOLUTE_URL; ?>js/slideshow.js" language="javascript"></script>

</head>

<body class="<?php echo $class; ?>" data-name="<?php echo $ecran->nom;?>" data-code-postal="<?php echo $ecran->code_postal; ?>" data-code-meteo="<?php echo $ecran->code_meteo;?>">
	<div class="console">
		<p id="retour"></p>

		<h1><?php //echo $this->ecran->nom; ?></h1>
		<p class="date"><span id="now"></span></p>
		<p class="date"><span id="start"></span><span id="end"></span><?php //echo $this->ecran->actual_date_json; ?></p>
		<p class="info">Informations</p>
		<p><button id="exit_button">TOGGLE EXIT</button></p>
	</div>

	<div id="template"></div>

	<?php

	$this->get_templates();

	?>

</body>
</html>