<?php

function checkAuthenticationFile($con, $uid_db, $username_db, $fileKey){

	$getRouteFile = mysqli_prepare($con, "SELECT parentgid, cname, personaluid, csrc, cid, type from content where ckey = ?");
	mysqli_stmt_bind_param($getRouteFile, "s", $fileKey);
	mysqli_stmt_execute($getRouteFile);
	mysqli_stmt_store_result($getRouteFile);
	
	if(mysqli_stmt_num_rows($getRouteFile) > 0 ){
		mysqli_stmt_bind_result($getRouteFile, $parentgid, $cname, $personaluid, $csrc, $cid, $type);
		mysqli_stmt_fetch($getRouteFile);

		if(isset($parentgid)){
			if(mysqli_num_rows(mysqli_query($con, "SELECT * from usergroups where gkey='".$parentgid."' AND uid=".$uid_db." AND active=1 LIMIT 1 ")) ==0 ) 
				return 0;
		} else if($personaluid!=$username_db) return 0;

		mysqli_query($con, "UPDATE groupcontent SET visitcount=visitcount+1 where cid=".$cid);
		return array($csrc, $cid, $cname, $type);

	} else return 0;	
}

function folderAuthenticity($con, $parentgid, $personaluid, $uid_db){
	if(isset($parentgid)){
		if(mysqli_num_rows(mysqli_query($con, "SELECT * from usergroups where gid='".$parentgid."' AND uid=".$uid_db." AND active=1 LIMIT 1 ")) ==0 ) 
			return false;
	} else if($personaluid!=$uid_db) return false;

	return true;
}

function downloadFile($routeFile, $cname){
	
	if (file_exists($routeFile)) {
		header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.$cname);
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($routeFile));
	    ob_clean();
        flush();
	    readfile($routeFile);
	  //  exit;
	}
}

function downloadFolder($routeFolder, $zipName){

	$mainFolder=basename($routeFolder);
    if(!file_exists('../zips'))
		mkdir('../zips', 0744);

	$zipName="../zips/".$zipName.".zip";
	
	$zip = new ZipArchive;
	$firstres=$zip->open($zipName, ZipArchive::OVERWRITE);

	$files = new RecursiveIteratorIterator(
	    new RecursiveDirectoryIterator($routeFolder),
    	RecursiveIteratorIterator::SELF_FIRST
	);

	foreach ($files as $name => $file) {

	    if(is_file($file) && file_exists($file)){
		    $new_filename = substr($file, strpos($file, $mainFolder));
			$zip->addFile($file,$new_filename);
		}
	}

	echo json_encode($zip->numFiles);

	$zip->close();
}

function deleteFolder($con, $fsrc, $gkey, $username_db){

	$curren_size=0;
	$it = new RecursiveDirectoryIterator($fsrc, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($it,
	             RecursiveIteratorIterator::CHILD_FIRST);

	foreach ($files as $name => $file) {
		if(is_file($file)){
			$fileKey=explode(".", basename($file))[0];
			$findId=mysqli_fetch_array(mysqli_query($con, "SELECT cid, type from content where ckey = '".$fileKey."'"));
			$cid = $findId['cid'];
			$type = $findId['type'];
			$fileOPT = "../optimizedImages/".$fileKey.".".$type;

			$fileRealPath=$file->getRealPath();
			$curren_size+=filesize($fileRealPath);
			mysqli_query($con, "DELETE from content where cid = ".$cid."");
			mysqli_query($con, "DELETE from groupcontent where cid = ".$cid." ");
	     	unlink($fileRealPath);
	     	if(file_exists($fileOPT)) unlink($fileOPT);

		} else if(is_dir($file)){

			$folderKey = explode(".", basename($file))[0];
			mysqli_query($con, "DELETE from folders where fkey= '".$folderKey."'");
			mysqli_query($con, "DELETE from groupcontent where fkey = '".$folderKey."'");
			rmdir($file->getRealPath());
		}
	}

	$mainFolderKey=basename($fsrc);
	if($gkey!='personal') mysqli_query($con, "UPDATE groups SET gsize = gsize - ".$curren_size." where gkey='".$gkey."' ");
	else mysqli_query($con, "UPDATE users SET psize = psize - ".$curren_size." where user_name='".$username_db."' ");
	mysqli_query($con, "DELETE from folders where fkey= '".$mainFolderKey."'");
	mysqli_query($con, "DELETE from groupcontent where fkey = '".$mainFolderKey."'");
	rmdir($fsrc);
}
?>