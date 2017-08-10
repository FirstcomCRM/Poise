<?php 
require('fileupload.php');
$master = new fileupload();
$upload  = (isset($_REQUEST['upload']))? (string)$_REQUEST['upload']:'not';
if($upload=='yes'){
	//print_r($_FILES['fileToUpload']['name']);
	$response 	= array();
	$fileDir 	= 'upload/';
	$totalFiles = count($_FILES['fileToUpload']['name']);
	for($f=0; $f<$totalFiles; $f++) {
		$name 		= $_FILES['fileToUpload']['name'][$f];
		$tmp_name 	= $_FILES['fileToUpload']['tmp_name'][$f];
		$size 		= $_FILES['fileToUpload']['size'][$f];
		$isUpload 	= $master->upload($name, $tmp_name, $size, $fileDir);
		if(is_array($isUpload) && $isUpload['file_status'] == 'uploaded' ){
			$response['fileSuccess'][] = 'Your selected file ['.$_FILES['fileToUpload']['name'][$f].'] has been successfully uploaded.';
		}else{
			switch($isUpload){
				case 1:
				$response['fileErr'][] = 'Your selected file ['.$_FILES['fileToUpload']['name'][$f].'] size is not allowed.';
				break;
				case 2:
				$response['fileErr'][] = 'Your selected file ['.$_FILES['fileToUpload']['name'][$f].'] format is not allowed.';
				break;
				case 3:
				$response['fileErr'][] = 'Please try again.';
				break;
			}
		}
	}
	echo  json_encode($response);
}
?>
