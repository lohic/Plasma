<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>UploadiFive Test</title>
<script src="jquery.min.js" type="text/javascript"></script>
<script src="jquery.uploadifive.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="uploadifive.css">
<style type="text/css">
body {
	font: 13px Arial, Helvetica, Sans-serif;
}
.uploadifive-button {
	float: left;
	margin-right: 10px;
}
#queue,#queue2 {
	border: 1px solid #E5E5E5;
	height: 177px;
	overflow: auto;
	margin-bottom: 10px;
	padding: 0 3px 3px;
	width: 300px;
}
.reset{
	clear: both;
	height: 40px;
	width: 100%;
}
</style>
</head>

<body>
	<h1>UploadiFive Demo</h1>
	<form>
		<div id="queue"></div>
		<input id="file_upload" name="file_upload" type="file" multiple="false">
		<a style="position: relative; top: 8px;" href="javascript:$('#file_upload').uploadifive('upload')">Upload Files</a>
		<div class="reset"><div/>
		<div id="queue2"></div>
		<input id="file_upload2" name="file_upload2" type="file" multiple="false">
		<a style="position: relative; top: 8px;" href="javascript:$('#file_upload2').uploadifive('upload')">Upload Files</a>


		<a style="position: relative; top: 8px;" href="javascript:$('#file_upload2').uploadifive('upload');javascript:$('#file_upload').uploadifive('upload')">UPLOAD</a>
	</form>

	<script type="text/javascript">
		<?php $timestamp = time();?>
		$(function() {
			$('#file_upload').uploadifive({
				'auto'             : false,
				'checkScript'      : 'check-exists.php',
				'formData'         : {
									   'timestamp' : '<?php echo $timestamp;?>',
									   'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				                     },
				'queueID'          : 'queue',
				'uploadScript'     : 'uploadifive.php',
				'onUploadComplete' : function(file, data) { console.log(data); }
			});

			$('#file_upload2').uploadifive({
				'auto'             : false,
				'checkScript'      : 'check-exists.php',
				'formData'         : {
									   'timestamp' : '<?php echo $timestamp;?>',
									   'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				                     },
				'queueID'          : 'queue2',
				'uploadScript'     : 'uploadifive.php',
				'onUploadComplete' : function(file, data) { console.log(data); }
			});
		});
	</script>
</body>
</html>