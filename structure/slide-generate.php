<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SciencesPo | Écrans PLASMA</title>

<link href="<?php //echo $template;?>../css/slideshow.css" rel="stylesheet" type="text/css" />

<?php 

?>

<script language="javascript" src="../js/jquery-1.7.2.min.js"></script>
<script language="javascript" src="../js/slideshow.js"></script>

<script>

$(document).ready(function(){
	var actual_date_json	='<?php echo $this->ecran->actual_date_json; ?>';
	var plasma_id			='<?php echo $this->ecran->id; ?>';
	
	$("#retour").text('ok');
	
	function refresh() {
	
		$.ajax({
			type: "POST",
			data: "action=refresh_topic&plasma_id="+ plasma_id +"&actual_date_json=" + actual_date_json,
			url: "tester.php",
			dataType: 'json',
			//async:false,
			success: function(json){
				//countusers=json.countusers;
				//$("#retour").text('ok : '+countusers);
				if(json.update == true){
					actual_date_json = json.actual_date_json;
					console.log(json.json);
					
					$('.date').text(actual_date_json);
				}
				console.log(json);
			}
		});
	}
	
	setInterval(refresh, 5000);
	
	
	$("#test").click(function(){
		alert(actual_date_json);
	});
});

</script>



</head>

<body<?php if($ispreview){echo ' class="preview"';} ?>>

<p id="retour"></p>

<p id="test">clique</p>

<h1><?php echo $this->ecran->nom; ?></h1>
<p class="date"><?php echo $this->ecran->actual_date_json; ?></p>

<div id="template">
	<?php //echo $le_slide; ?>
</div>

</body>
</html>