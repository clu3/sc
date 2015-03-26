<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Parse Package Scorm</title>
	<!--Khai b�o s? d?ng boostrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!--S?a l?i HTML5 cho IE 8 tr? xu?ng-->
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
</head>
<body>
<?php
if($_FILES["zip_file"]["name"]) {
	$filename = $_FILES["zip_file"]["name"];
	$source = $_FILES["zip_file"]["tmp_name"];
	$type = $_FILES["zip_file"]["type"];
	$name = explode(".", $filename);
	$a="unzip/".$name[0]."/index.php";
	$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
	foreach($accepted_types as $mime_type) {
		if($mime_type == $type) {
			$okay = true;
			break;
		} 
	}
	$continue = strtolower($name[1]) == 'zip' ? true : false;
	if(!$continue) {
		$message = "The file you are trying to upload is not a .zip file. Please try again.";
	}

	$target_path = "unzip/".$filename;  // change this to the correct site path
	//var_dump($target_path);die();
	if(move_uploaded_file($source, $target_path)) {
		$zip = new ZipArchive();
		$x = $zip->open($target_path);
		if ($x === true) {
			$zip->extractTo("unzip/"); // change this to the correct site path
			$zip->close();
			unlink($target_path);
			$parsexml="parsexml/index.php";
			copy($parsexml,$a);
		}
		$message = "Your .zip file was uploaded and unpacked.";
		echo  $message;
		echo '<a href="'.$a.'">Click to view file</a>';
	} else {	
		$message = "There was a problem with the upload. Please try again.";
		echo $message;
	}
}
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Khai b�o s? d?ng thu vi?n javascript c?a bootstrap -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
