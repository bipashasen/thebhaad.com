<?php

include '../inc/connect.php';
session_start();

if(isset($_SESSION['user_username'])){
	mysqli_query($con, "UPDATE users SET lastlogin=NOW() where user_name='".$_SESSION['user_username']."' ");
}
else {
	if(!isset($_COOKIE['user_login']))
		echo json_encode('Logout');
}

?>