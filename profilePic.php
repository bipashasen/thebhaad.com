<?php

$headerOk=0;

if(isset($_GET['image'])){
	$file = "../profile_images/".$_GET['image'];

	if(file_exists($file)){
		$headerOk=1;
		header("Content-length: ". filesize($file));
		header("Content-type: ". mime_content_type($file));
		readfile($file);
	} 
} else if(isset($_GET['contentImage']) || isset($_GET['optImage'])){
	$optImageSrc = true;
	if(isset($_GET['contentImage'])) $optImageSrc = false;
	include 'inc/connect.php';
	session_start();

	if(isset($_SESSION['user_uid'])){
		$uid_db=$_SESSION['user_uid'];
		$contentImage = $fields = "";
		if($optImageSrc){
			$contentImage = $_GET['optImage'];
			$fields = "ckey, type";
		} else {
			$contentImage=$_GET['contentImage'];
			$fields = "csrc";
		}
		$getContentImage=mysqli_prepare($con, "SELECT ".$fields." from content INNER JOIN usergroups ON content.parentgid = usergroups.gkey where ckey= ? AND usergroups.uid = ".$uid_db." AND usergroups.active=1 LIMIT 1");
		mysqli_stmt_bind_param($getContentImage, 's', $contentImage);
		mysqli_stmt_execute($getContentImage);
		mysqli_stmt_store_result($getContentImage);
		if(mysqli_stmt_num_rows($getContentImage) > 0){
			$csrc = "";
			if($optImageSrc) mysqli_stmt_bind_result($getContentImage, $ckey, $type);
			else mysqli_stmt_bind_result($getContentImage, $csrc);
			mysqli_stmt_fetch($getContentImage);

			if($optImageSrc) $csrc = "../optimizedImages/".$ckey.".".$type;
			if(file_exists($csrc)){
				$headerOk=1;
				header("Content-length: ". filesize($csrc));
				header("Content-type: ". mime_content_type($csrc));
				readfile($csrc);
			} 
		} else {
			$username_db=$_SESSION['user_username'];
			$checkPersonalFolder=mysqli_prepare($con, "SELECT ".$fields." from content where content.personaluid = '".$username_db."' AND content.ckey = ?");
			mysqli_stmt_bind_param($checkPersonalFolder, "s", $contentImage);
			mysqli_stmt_execute($checkPersonalFolder);
			mysqli_stmt_store_result($checkPersonalFolder);
			if(mysqli_stmt_num_rows($checkPersonalFolder) > 0){
				if($optImageSrc) mysqli_stmt_bind_result($checkPersonalFolder, $ckey, $type);
				else mysqli_stmt_bind_result($checkPersonalFolder, $csrc);
				mysqli_stmt_fetch($checkPersonalFolder);

				if($optImageSrc) $csrc = "../optimizedImages/".$ckey.".".$type;
				if(file_exists($csrc)){
					$headerOk=1;
					header("Content-length: ".filesize($csrc));
					header("Content-type: ".mime_content_type($csrc));
					readfile($csrc);
				}
			}
		}
	}
	session_write_close();
} 

if($headerOk==0) {
	header('HTTP/1.0 403 Forbidden');
	include $_SERVER['DOCUMENT_ROOT'].'/pnp.php';
}

?>