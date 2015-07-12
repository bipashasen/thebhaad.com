<?php

include '../inc/mail_func.php';

if(isset($_POST['resendmail']) && $_POST['resendmail']=='true'){
  include '../inc/user_cred.php';
  call_user_func("sendConfirmMail", $con, $email_db, $username_db, $uid_db, $fname_db, $lname_db);
}

if(isset($_POST['from']) && isset($_POST['message']) && isset($_POST['name'])){
	include '../inc/connect.php';
	include '../inc/form_val_functions.php';
	$from=test_input($_POST['from']);
	$message=test_input($_POST['message']);
	$name=test_input($_POST['name']);
	
	if(!empty($from) && !empty($message) && !empty($name) && check_name($name) && filter_var($from, FILTER_VALIDATE_EMAIL)){
		call_user_func("sendmenewmail", $con, $from, $name, $message);
	}
} 

if(isset($_POST['recoverEmail'])){
	include '../inc/connect.php';
	include '../inc/form_val_functions.php';
	$recoverEmail=test_input($_POST['recoverEmail']);

	if(!empty($recoverEmail) && filter_var($recoverEmail, FILTER_VALIDATE_EMAIL)){
		call_user_func("recoverMail", $con, $recoverEmail);
	}
}

?>