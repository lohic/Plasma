<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

// Set the uplaod directory
//$uploadDir = 'uploads/';

// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png' ,'mp4'); // Allowed file extensions

$verifyToken = md5('sciences_po_plasma' . $_POST['timestamp']);

$retour = new stdClass();

$uploadDir = createPath('../slides_images/' . date('Y/m/'));


if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	//$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$uploadDir  = $uploadDir;
	$targetFile = $uploadDir . str_replace(' ','_',$_FILES['Filedata']['name']);

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {

		// Save the file
		move_uploaded_file($tempFile, $targetFile);

		$retour->error = false;
		$retour->file = $targetFile;
		$retour->ext = strtolower( $fileParts['extension'] );

	} else {

		// The file type wasn't allowed
		$retour->error = true;
		$retour->message = 'Type de fichier non conforme.';

	}
}else{
	$retour->error = true;
	$retour->message = 'Fichier non spécifié ou invalide.';
}

echo json_encode($retour);



function createPath($chemin){	
	if(!is_dir($chemin)){
		mkdir($chemin, 0777, true);
	}
	return $chemin;
}