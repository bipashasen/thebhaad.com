<?php 

	define('ROOT', $_SERVER['DOCUMENT_ROOT']);

	include ROOT.'/inc/secureDownload.php';
	include ROOT.'/inc/connect.php';

	session_start();
	$username_db= $_SESSION['user_username'];
	$uid_db=$_SESSION['user_uid'];
	$headerOk=0;
	$checkDownload=false;
	session_write_close();

	if(isset($_GET['file_key'])){
		$fileKey=trim(mysqli_real_escape_string($con, $_REQUEST['file_key']));
		if(!empty($fileKey)){
			$detailsArray=checkAuthenticationFile($con, $uid_db, $username_db, $fileKey);
			$csrc=$detailsArray[0];
			$cname=$detailsArray[2];
			if($csrc!==0 && !empty($csrc)){			
				downloadFile($csrc, $cname);
				$headerOk=1;
			} 
		} 
	} else if(isset($_GET['folder_download'])){
		$zipName=trim(mysqli_real_escape_string($con, $_REQUEST['folder_download']));
		if($zipName == 'personal') $zipName.='__'.$username_db;
		$zipName='../zips/'.$zipName.'.zip';

		if(file_exists($zipName)){
			$headerOk=1;
			downloadFile($zipName, basename($zipName));
			unlink($zipName);
		}
	}

	if(isset($_POST['folder_key'])){

		$folder_key=trim($_REQUEST['folder_key']);
		if(!empty($folder_key)){

			$getRouteFolder = mysqli_prepare($con, "SELECT parentgid, personaluid, fsrc from folders where fkey = ?");
			mysqli_stmt_bind_param($getRouteFolder, "s", $folder_key);
			mysqli_stmt_execute($getRouteFolder);
			mysqli_stmt_store_result($getRouteFolder);

			if(mysqli_stmt_num_rows($getRouteFolder) > 0){

				mysqli_stmt_bind_result($getRouteFolder, $parentgid, $personaluid, $fsrc);
				mysqli_stmt_fetch($getRouteFolder);

				if(folderAuthenticity($con, $parentgid, $personaluid, $uid_db)) {
					downloadFolder($fsrc, $folder_key);
					$headerOk=1;
				}
			} else{
				if($folder_key == 'personal'){
					$folder_key.='__'.$username_db;
					$groupsrc='../contents/'.$folder_key;
					downloadFolder($groupsrc, $folder_key);
					$headerOk=1;
				} else {
					$getRouteGroup = mysqli_prepare($con, "SELECT * from groups where gkey = ?");
					mysqli_stmt_bind_param($getRouteGroup, "s", $folder_key);
					mysqli_stmt_execute($getRouteGroup);
					mysqli_stmt_store_result($getRouteGroup);

					if(mysqli_stmt_num_rows($getRouteGroup) > 0){
						if(mysqli_num_rows(mysqli_query($con, "SELECT * from usergroups where gkey='".$folder_key."' AND uid=".$uid_db." AND active=1 LIMIT 1 ")) > 0){
							$groupsrc='../contents/'.$folder_key;
							downloadFolder($groupsrc, $folder_key);
							$headerOk=1;
						}
					} 
				}
			}
		}
	} 

	if(isset($_POST['fileDelUrl']) && isset($_POST['groupDelUrl'])){
		$fileDelUrl=trim(mysqli_real_escape_string($con, $_POST['fileDelUrl']));
		$groupDelUrl=trim(mysqli_real_escape_string($con, $_POST['groupDelUrl']));

		if(!empty($fileDelUrl)){
			$contentArray= checkAuthenticationFile($con, $uid_db, $username_db, $fileDelUrl);
			$csrc=$contentArray[0];
			$cid=$contentArray[1];
			$type = $contentArray[3];
			$optURL = "../optimizedImages/".$fileDelUrl.".".$type;
			if($csrc!==0){			
				if(file_exists($csrc)){
					$file_size=filesize($csrc);
					if(file_exists($csrc)) unlink($optURL);
					unlink($csrc);
					if($groupDelUrl!='personal') mysqli_query($con, "UPDATE groups SET gsize = gsize - ".$file_size." where gkey = '".$groupDelUrl."' ");
					else mysqli_query($con, "UPDATE users SET psize = psize - ".$file_size." where user_name = '".$username_db."'");
					mysqli_query($con, "DELETE from content where csrc='".$csrc."'");
					mysqli_query($con, "DELETE from groupcontent where cid= ".$cid." ");
					$headerOk=1;
				}
			} 
		}
	}

	if(isset($_POST['folderDelUrl'])){
		$folderDelUrl=trim($_POST['folderDelUrl']);

		if(!empty($folderDelUrl)){
			$getRouteFolder = mysqli_prepare($con, "SELECT groups.gkey, folders.parentgid, folders.personaluid, folders.fsrc from folders INNER JOIN groups ON folders.parentgid = groups.groupid where fkey = ?");
			mysqli_stmt_bind_param($getRouteFolder, "s", $folderDelUrl);
			mysqli_stmt_execute($getRouteFolder);
			mysqli_stmt_store_result($getRouteFolder);

			if(mysqli_stmt_num_rows($getRouteFolder) > 0){
				mysqli_stmt_bind_result($getRouteFolder, $gkey, $parentgid, $personaluid, $fsrc);
				mysqli_stmt_fetch($getRouteFolder);

				if(folderAuthenticity($con, $parentgid, $personaluid, $uid_db)) {
					deleteFolder($con, $fsrc, $gkey, $username_db);
					$headerOk=1;
				}
			} else{
				$getRouteFolder = mysqli_prepare($con, "SELECT personaluid, fsrc from folders where fkey = ?");
				mysqli_stmt_bind_param($getRouteFolder, "s", $folderDelUrl);
				mysqli_stmt_execute($getRouteFolder);
				mysqli_stmt_store_result($getRouteFolder);

				if(mysqli_stmt_num_rows($getRouteFolder) > 0){
					mysqli_stmt_bind_result($getRouteFolder, $personaluid, $fsrc);
					mysqli_stmt_fetch($getRouteFolder);

					if($personaluid == $uid_db) {
						deleteFolder($con, $fsrc, 'personal', $username_db);
						$headerOk=1;
					}
				}
			}
		}
	}

	if($headerOk==0){
		header('HTTP/1.0 404 NOT FOUND');
		include 'pnp.php';
	}

?>