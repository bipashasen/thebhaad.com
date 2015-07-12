<?php

include ROOT.'/inc/connect.php';
include ROOT.'/inc/mail_func.php';

if(isset($_POST['new_folder_cupload']) && !empty(trim($_POST['new_folder_cupload'])))
	$fname_new=trim(mysqli_real_escape_string($con, $_POST['new_folder_cupload']));

$max_size=100*1024*1024;
$total_max_size=500*1024*1024;
$current_size=0;
$imageQualityJPG = 10;
$imageQualityPNG = 1;
$imageQualityGIF = 10;

if($group_url == 'personal')
	$directory='../contents/'.$group_url."__".$username_db;
else $directory='../contents/'.$group_url;

if(isset($current_url))
	$directory=getPath($con, $current_url, $directory);

if(isset($fname_new)){
	$folder_url=createFolder($con, $fname_new, $insertkey, $insertvalue, $operator, $parent_fid, $username_db, $group_url);
	$directory.='/'.$folder_url;
	mysqli_query($con, "UPDATE folders SET fsrc = '".$directory."' where fkey = '".$folder_url."' ");
}

if(!is_dir($directory))
	mkdir($directory, 0777, true);

$max_fvisit_arr=mysqli_fetch_array(mysqli_query($con, "SELECT max(visitcount) as maxfvisit from groupcontent where $insertkey=".$insertvalue." AND pfid".$operator.$parent_fid));
$maxvisitcount=$max_fvisit_arr['maxfvisit']+80;

if($group_url!='personal'){
	$getGSize=mysqli_fetch_array(mysqli_query($con, "SELECT gsize from groups where gkey-'".$group_url."'"));
	$gsize=$getGSize['gsize'];
} else {
	$getGSize=mysqli_fetch_array(mysqli_query($con, "SELECT psize from users where uid =".$uid_db." "));
	$gsize=$getGSize['psize'];
}

foreach($_FILES['upload_contents']['name'] as $i => $name){

	$name = mysqli_real_escape_string($con, $name);
	if(!is_uploaded_file($_FILES['upload_contents']['tmp_name'][$i]))
		continue;

	$file_size=$_FILES['upload_contents']['size'][$i];
	$current_size+=$file_size;
	if($current_size > $max_size || $gsize+$current_size > $total_max_size){
		session_start();
		$_SESSION['error_upload_fum']=1;
		session_write_close();
		$current_size-=$file_size;
		continue;
	}
	
	$_FILES['upload_contents']['name'][$i] = strtolower($_FILES['upload_contents']['name'][$i]);
	$fileType=pathinfo(basename($_FILES['upload_contents']['name'][$i]), PATHINFO_EXTENSION);
	$fileType=strtolower(mysqli_real_escape_string($con, $fileType));

	if(isset($folder_url)){
		$url="'".$folder_url."'";
		$fid=mysqli_fetch_array(mysqli_query($con, "SELECT fid from folders where fkey='".$folder_url."' "));
		$fid_up=$fid['fid'];
	} else if(isset($current_url)){
		$url="'".$current_url."'";
		$fid=mysqli_fetch_array(mysqli_query($con, "SELECT fid from folders where fkey = '".$current_url."' "));
		$fid_up=$fid['fid'];
	} else {
		$url="NULL";
		$fid_up="NULL";
	}

	if($group_url!='personal'){
		$gid=mysqli_fetch_array(mysqli_query($con, "SELECT groupid from groups where gkey='".$group_url."'"));
		$gid_up=$gid['groupid'];
		$gurl="'".$group_url."'";
		$uid_up="NULL";
		$userurl="NULL";
	} else {
		$uid_up=$uid_db;
		$userurl="'".$username_db."'";
		$gid_up="NULL";
		$gurl="NULL";
	}

	mysqli_query($con, "INSERT into content (cname, type, parentgid, personaluid, uid, fid) VALUES ('".$name."', '".$fileType."', ".$gurl.", ".$userurl.", ".$uid_db.", ".$url.")");

	$last_insert_id=mysqli_insert_id($con);

	$content_key=md5(uniqid().$last_insert_id);

	$new_file_name=$content_key.".".$fileType;

	$finaldirectory=$directory . "/" . $new_file_name;

	$final_content_db=mysqli_query($con, "UPDATE content SET csrc= '".$finaldirectory."', ckey='".$content_key."' where cid = ".$last_insert_id."");

	mysqli_query($con, "INSERT into groupcontent (parentgid, personaluid, cid, pfid, visitcount ) VALUES (".$gid_up.", ".$uid_up.", ".$last_insert_id.", ".$fid_up.", ".$maxvisitcount.")");

	move_uploaded_file($_FILES["upload_contents"]["tmp_name"][$i], $finaldirectory);
	if($fileType == 'jpg' || $fileType == 'jpeg'){
		$optImgRes = imagecreatefromjpeg($finaldirectory);
		imagejpeg($optImgRes, "../optimizedImages/".$new_file_name, $imageQualityJPG);
	} else if($fileType == 'png'){
		$optImgRes = imagecreatefrompng($finaldirectory);
		imagealphablending($optImgRes, false);
		imagesavealpha($optImgRes, true);
		imagepng($optImgRes, "../optimizedImages/".$new_file_name, $imageQualityPNG);
	} else if($fileType == "gif"){
		$optImgRes = imagecreatefromgif($finaldirectory);
		imagealphablending($optImgRes, false);
		imagesavealpha($optImgRes, true);
		imagegif($optImgRes, "../optimizedImages/".$new_file_name, $imageQualityGIF);
	}
}

if($group_url!='personal'){
	mysqli_query($con, "UPDATE groups SET gsize = gsize + ".$current_size." where gkey ='".$group_url."' ");
	mysqli_query($con, "INSERT into notifications (gkey, fkey, user_name, type) VALUES ('".$group_url."','".basename($directory)."', '".$username_db."','filesAdd')");
} else mysqli_query($con, "UPDATE users SET psize = psize + ".$current_size." where user_name='".$username_db."' ");

/*
if(isset($folder_url)){
	echo json_encode($folder_url);
}*/


?>		