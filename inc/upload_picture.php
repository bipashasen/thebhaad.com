<?php

//include '/inc/user_cred.php';
//include '/inc/form_val_functions.php';
if(isset($_POST['removie_display_input'])){
	$image_db='default.icon';
	$query_removePicture = "UPDATE users set image='default.icon' where user_name='$username_db'";
	mysqli_query($con,$query_removePicture);
	echo '<script>window.top.location.href="edit"</script>';
}
if((isset($_POST['whereGoToSchool']))){
	$goToSchool=$_POST['whereGoToSchool'];
	$goToSchool=mysqli_real_escape_string($con, $goToSchool);
	$goToSchool=trim($goToSchool);
	$query_goToSchool = "UPDATE users set school='$goToSchool' where user_name='$username_db'";
	mysqli_query($con,$query_goToSchool);
}
if((isset($_POST['profession']))){
	$professionValue=$_POST['profession'];
	$professionValue=mysqli_real_escape_string($con, $professionValue);
	if($professionValue==1)
		$query_professionValue = "UPDATE users set profession='Student' where user_name='$username_db'";
	else if($professionValue==2)
		$query_professionValue = "UPDATE users set profession='Working' where user_name='$username_db'";
	else $query_professionValue = "UPDATE users set profession='Student | Working' where user_name='$username_db'";
	mysqli_query($con,$query_professionValue);
}
if((isset($_POST['whereGoToUnder']))){
	$undergraduateValue=$_POST['whereGoToUnder'];
	$undergraduateValue=mysqli_real_escape_string($con, $undergraduateValue);
	$undergraduateValue=trim($undergraduateValue);
	$query_goToUnder = "UPDATE users set undergraduate='$undergraduateValue' where user_name='$username_db'";
	mysqli_query($con,$query_goToUnder);
}
if((isset($_POST['aboutInformation']))){
	$aboutValue=$_POST['aboutInformation'];
	$aboutValue=mysqli_real_escape_string($con, $aboutValue);
	$aboutValue=trim($aboutValue);
	$query_aboutInfo = "UPDATE users set about='$aboutValue' where user_name='$username_db'";
	mysqli_query($con,$query_aboutInfo);
}
if((isset($_POST['achievementInformation']))){
	$achievementValue=$_POST['achievementInformation'];
	$achievementValue=mysqli_real_escape_string($con, $achievementValue);
	$achievementValue=trim($achievementValue);
	$query_achievementInfo = "UPDATE users set achievement='$achievementValue' where user_name='$username_db'";
	mysqli_query($con,$query_achievementInfo);
}
if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0){
  $_SESSION['interm_upload_error'] = "Sorry, your file is just too large. The data couldn't be retained. Please upload a file less than 2MB.";
  echo '<script>window.top.location.href="edit"</script>';
}
if(isset($_FILES['display_pic_input']['name'])){	
	$target_dir = "../profile_images/";
	$uploadOk = 1;
	$imageFileType = pathinfo(basename($_FILES["display_pic_input"]["name"]),PATHINFO_EXTENSION);
	$target_file = $target_dir.$username_db."_temp.".$imageFileType;
	$photo_src=$_FILES["display_pic_input"]["tmp_name"];
	$fileSize=$_FILES["display_pic_input"]["size"];
	// Check if image file is a actual image or fake image
	if(is_file($photo_src)) {
		// Check if file already exists
		if (file_exists($target_file))
    		@unlink($target_file);
		// Check file size	
	    // Allow certain file formats
	    $allowedFormats = array('jpg', 'png', 'jpeg', "gif", "GIF", "JPG", "JPEG", "PNG");
		if(!in_array($imageFileType, $allowedFormats)){
			$_SESSION['interm_upload_error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    	echo '<script>window.top.location.href="edit"</script>';
    		$uploadOk = 0;
		} else if ($fileSize > 2097152) {
			$_SESSION['interm_upload_error'] = "Sorry, your file is too large. Please upload a file less than 2MB.";
			echo '<script>window.top.location.href="edit"</script>';
    		$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if($uploadOk==1) {
	    	if (move_uploaded_file($photo_src, $target_file)) {
        		// call the show_popup_crop function in JavaScript to display the crop popup
        		$temp_file='__'.$username_db.'_temp.'.$imageFileType;
        		echo '<script type="text/javascript">$(window).on("load",function(){window.top.window.hide_gif();window.top.window.showcrop("'.$target_file.'" ,"'.$temp_file.'","'.$username_db.'","'.$imageFileType.'");});</script>';
    		} else {
    			$_SESSION['interm_upload_error'] = "Sorry, there was an error uploading your file. Please try again!";
    			echo '<script>window.top.location.href="edit"</script>';
    		}
    	}
	}
}
if(isset($_POST['submit_extrainfo'])){
	echo '<script>window.top.location.href="'.$username_db.'"</script>';
}
?>