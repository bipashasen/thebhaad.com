
<?php

if(isset($_POST['submit_logIn'])){

if (!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);

require ROOT.'/inc/form_val_functions.php';
require_once ROOT.'/inc/connect.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){
	$login_email=test_input($_POST["login_email"]);
	$login_email=mysqli_real_escape_string($con,$login_email);
	$login_password=test_input($_POST["login_password"]);
	$login_password=mysqli_real_escape_string($con, $login_password);
	$kmlil = "";
	if(isset($_POST['kmlil'])) $kmlil = test_input($_POST['kmlil']);
	$kmlil = mysqli_real_escape_string($con, $kmlil);

	if((!empty($_POST["login_email"]))&&(!empty($_POST["login_password"]))&&(filter_var($login_email, FILTER_VALIDATE_EMAIL))){
		$sql=mysqli_prepare($con,"SELECT password, fname, uid, user_name, rememberme, lastlogin FROM users WHERE email = ? LIMIT 1");
		mysqli_stmt_bind_param($sql, "s", $login_email);
		mysqli_stmt_execute($sql);
		mysqli_stmt_store_result($sql);
		$count=mysqli_stmt_num_rows($sql);
		if($count==1){
			mysqli_stmt_bind_result($sql, $password_db, $fname_db, $uid_db, $username_db, $setcookiesalt, $lastlogin_db);
			mysqli_stmt_fetch($sql);
			if(password_verify($login_password, $password_db)){
				$_SESSION['user_login']=$login_email;
				$_SESSION['user_uid']=$uid_db;
				$_SESSION['user_username']=$username_db;
				$_SESSION['start_timestamp']=$lastlogin_db;
				$_SESSION['last_timestamp']=$lastlogin_db;
				if($kmlil == 'on') setcookie('user_login', $setcookiesalt, time() + (10 * 365 * 24 * 60 * 60), "/", ".thebhaad.com");
				if(isset($_SESSION['redirected_from'])){
					header("Location:".$_SESSION['redirected_from']);
					exit();
				}else{
					header("Location:".$username_db);
					exit();
				}
			}else $login_error="Password doesn't match.<br>Did you <a class='to_dark_red' href='/recovery'><b>forget your password?</b></a>";
		}
		else $login_error="Email not registered! You might wanna <b>Sign Up!</b>";
	} else $login_error="Invalid password or username. Please try again.";
}


}
?>