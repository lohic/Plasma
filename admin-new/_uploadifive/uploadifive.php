<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

// subfolder via POST
$subFolder = explode('/', $_POST['subfolder']);

// sécurisation
if(count($subFolder)==3){
	for($i=0; $i<3; $i++){
		$subFolder[$i] = intval($subFolder[$i]);
	}
	$subFolder[1] = sprintf('%02d',$subFolder[1]); // 2 chiffres
	$subFolder = implode('/', $subFolder).'/';
} else {
	$subFolder = '';
}

// Set the uplaod directory
$uploadDir = '../../slides_images/' . $subFolder;

// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'mp4'); // Allowed file extensions

//echo dirname($_SERVER['SCRIPT_FILENAME']) . $uploadDir;

if (!empty($_FILES)) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	//$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$targetFile = $uploadDir . $_FILES['Filedata']['name'];

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {

		// Save the file
		move_uploaded_file($tempFile,$targetFile);
		//echo 1;
		
		// sortie du chemin complet...
		echo str_replace('../../', '', $targetFile); 

	} else {

		// The file type wasn't allowed
		echo 'Invalid file type.';

	}
}
?>