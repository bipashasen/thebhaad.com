<?php
include '../inc/connect.php';
// Target siz
$targ_w = $_POST['targ_w'];
$targ_h = $_POST['targ_h'];

$url_temp = $_POST['photo_url'];

$username_db = $_POST['username'];
$fileType = $_POST['fileTypeImage'];
// quality
$jpeg_quality = 100;

$url_temp="../".$url_temp;

// photo path

if($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'JPG' || $fileType == 'JPEG'){
	$showImg="__".$username_db."_n.jpeg";
	$src = "../../profile_images/".$username_db."_n.jpeg";
	$query="UPDATE users set image='".$username_db."_n.jpeg' where user_name='$username_db'";

	if (file_exists($src))
    	@unlink($src);	
	// create new jpeg image based on the target sizes
	$img_r = imagecreatefromjpeg($url_temp);
	$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
	// crop photo
	imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'], $targ_w,$targ_h,$_POST['w'],$_POST['h']);
	// create the physical photo
	imagejpeg($dst_r,$src,$jpeg_quality);
}
else if($fileType == 'png' || $fileType == 'PNG'){
	$showImg="__".$username_db."_n.png";
	$src = "../../profile_images/".$username_db."_n.png";
	$query="UPDATE users set image='".$username_db."_n.png' where user_name='$username_db'";

	if (file_exists($src))
    	@unlink($src);
	$img_r = imagecreatefrompng($url_temp);
	$dst_r = imagecreatetruecolor( $targ_w, $targ_h );

	// crop photo
	imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'], $targ_w,$targ_h,$_POST['w'],$_POST['h']);
	// create the physical photo
	imagejpeg($dst_r,$src,$jpeg_quality);
}
else if($fileType == 'gif' || $fileType == 'GIF'){
	$showImg="__".$username_db."_n.gif";
	$src = "../../profile_images/".$username_db."_n.gif";
	$query="UPDATE users set image='".$username_db."_n.gif' where user_name='$username_db'";

	if (file_exists($src))
    	@unlink($src);

	$img_r = imagecreatefromgif($url_temp);
	$dst_r = imagecreatetruecolor( $targ_w, $targ_h );		

	// crop photo
	imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'], $targ_w,$targ_h,$_POST['w'],$_POST['h']);
	// create the physical photo
	imagegif($dst_r,$src,$jpeg_quality);
}
if (file_exists($url_temp))
    @unlink($url_temp);

mysqli_query($con,$query);
echo '<img class="display_picture_image" width="180px" height="180px" src="'.$showImg.'?'.time().'">';
exit;
?>