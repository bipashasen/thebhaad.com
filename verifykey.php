<?php
if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
include ROOT.'/inc/user_cred.php';

if($active_db==1){
	header("Location:".$username_db);
	exit();
}
if(isset($_GET['verkey'])){
	$error_code=mysqli_real_escape_string($con, $_GET['verkey']);
	$error_to_check=$email_db.$username_db.$uid_db;
	$error_to_check=md5($error_to_check);
		
	if($error_to_check==$error_code){
		$query="UPDATE users SET active=1 WHERE email='".$email_db."'";
		if(mysqli_query($con, $query)){
			header("Location:".$username_db);
			exit();
		}
	}else if(strpos(htmlspecialchars($_SERVER['REQUEST_URI']), 'verifykey.php')) {
		header("Location:error_user");
		exit();
	}
}else if(strpos(htmlspecialchars($_SERVER['REQUEST_URI']), 'verifykey.php')) {
	header("Location:error_user");
	exit();
}

include ROOT.'/inc/header.php';
?>
<div id="error_user">
	<p>
		<img src="/images/exit_group.png" width="40px">
		<span>The link you clicked is not valid for you account. Please try to click on the right link.</span>
		
	</p>
</div>