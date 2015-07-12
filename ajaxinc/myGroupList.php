<?php

	include '../inc/connect.php';
	include '../inc/functionsFiles.php';
	session_start();
	$uid_db=$_SESSION['user_uid'];

	if(isset($_REQUEST['pagenum'])){
		$pagenum=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['pagenum'])));

		$star_data=$limit_data*$pagenum;

		$select_admin_g=mysqli_query($con, "SELECT * from groups where adminid = ".$uid_db." LIMIT ".$star_data.", ".$limit_data." ");

		showmyGroups($select_admin_g);

		if(mysqli_num_rows($select_admin_g)<$limit_data){
			echo '<input id="end_input_inf" type="hidden" value="false"/>';
		   if($pagenum>0) echo '<div id="myg_end">Well this wasn\'t much. Create more groups!</div>';
		}

		if(mysqli_num_rows($select_admin_g)==0 && $pagenum==0)
			echo '<div id="myg_nog">You don\'t administer any group. Start creating groups and owning them!</div>';

	}

?>