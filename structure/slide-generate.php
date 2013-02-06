<?php


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SciencesPo | Écrans PLASMA</title>

<link href="<?php //echo $template;?>../css/slideshow.css" rel="stylesheet" type="text/css" />

<?php 

?>

<script language="javascript" src="../js/jquery-1.7.2.min.js"></script>
<script language="javascript" src="../js/slideshow.js"></script>

</head>

<body<?php if($ispreview){echo ' class="preview"';} ?>>

<div id="template">
	<?php echo $le_slide; ?>
</div>

</body>
</html>