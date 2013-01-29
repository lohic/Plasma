<?php

//include_once("../classe/classe_newsletter.php");
//include_once("../vars/statics_vars.php");
//include_once('../vars/constantes_vars.php');

//$news = new newsletter($id_newsletter,'show');

//$template =				$news->get_template($id_newsletter);
//$templateFolder =		$news->get_template($id_newsletter,'short');
//$ladate =				$news->get_date($id_newsletter);
//$GoogleAnalyticsCode =	$news->get_google_analytics_code($id_newsletter);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SciencesPo | Écrans PLASMA</title>

<link href="<?php //echo $template;?>../css/slideshow.css" rel="stylesheet" type="text/css" />

<?php /*<style type="text/css">
<?php //include('../template/'.$templateFolder.'/style.css');?>
</style>*/ ?>

<script language="javascript" src="<?php echo ABSOLUTE_URL; ?>js/jquery-1.7.2.min.js"></script>
<script language="javascript" src="<?php echo ABSOLUTE_URL; ?>js/slideshow.js"></script>

</head>

<body<?php if($ispreview){echo ' class="preview"';} ?>>

<div id="template">
	<?php echo $le_slide; ?>
</div>

<?php //echo $GoogleAnalyticsCode;?>
</body>
</html>