<?php

include '../inc/connect.php';
session_start();
$username_db=$_SESSION['user_username'];

if(isset($_REQUEST['newname_rs']) && isset($_REQUEST['newdesc_rs']) && (isset($_REQUEST['folder']) || isset($_REQUEST['group']))){
	if(!empty(trim($_REQUEST['newname_rs'])))
		$updateName=trim(mysqli_real_escape_string($con, $_REQUEST['newname_rs']));
	$updateDesc=trim(mysqli_real_escape_string($con, $_REQUEST['newdesc_rs']));

	if(isset($_REQUEST['folder'])){
		$mainKey=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['folder'])));
		if(isset($updateName)){
			$old_name=mysqli_fetch_array(mysqli_query($con, "SELECT fname from folders where fkey='".$mainKey."' "));
			$old_name_folder=$old_name['fname'];
			$update_desc_query= mysqli_query($con, "UPDATE folders SET fname='".$updateName."', fdesc='".$updateDesc."' where fkey='".$mainKey."' ");
		}
		else $update_desc_query = mysqli_query($con, "UPDATE folders SET fdesc='".$updateDesc."' where fkey='".$mainKey."' ");

		if(isset($updateName)){
			if(mysqli_num_rows(mysqli_query($con, "SELECT * from notifications where fkey='".$mainKey."' AND type='fName' LIMIT 1"))==0){
				$folder_query_noti=mysqli_fetch_array(mysqli_query($con, "SELECT groups.gkey as groupkey from folders INNER JOIN groups ON folders.parentgid=groups.groupid where folders.fkey='".$mainKey."' "));
				$group_key_noti=$folder_query_noti['groupkey'];
				mysqli_query($con, "INSERT into notifications(user_name, old_name, fkey, gkey, type) VALUES('".$username_db."', '".$old_name_folder."', '".$mainKey."', '".$group_key_noti."', 'fName') ");
			} else mysqli_query($con, "UPDATE notifications SET old_name='".$old_name_folder."', timenote=CURRENT_TIMESTAMP() where fkey='".$mainKey."' AND type='fName' ");
		} else {
			if(mysqli_num_rows(mysqli_query($con, "SELECT * from notifications where fkey='".$mainKey."' AND type='fEdit' LIMIT 1")) == 0 ){
				$folder_query_noti=mysqli_fetch_array(mysqli_query($con, "SELECT groups.gkey as groupkey from folders INNER JOIN groups ON folders.parentgid=groups.groupid where folders.fkey='".$mainKey."' "));
				$group_key_noti=$folder_query_noti['groupkey'];
				mysqli_query($con, "INSERT into notifications(user_name, fkey, gkey, type) VALUES ('".$username_db."', '".$mainKey."', '".$group_key_noti."', 'fEdit')");
			} else mysqli_query($con, "UPDATE notifications SET user_name='".$username_db."', timenote=CURRENT_TIMESTAMP() where fkey='".$mainKey."' AND type='fEdit' ");
		}
	}
	else if(isset($_REQUEST['group'])){
		$mainKey=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['group'])));

		if($mainKey=='personal'){
			mysqli_query($con, "UPDATE users SET pdesc='".$updateDesc."' where user_name='".$username_db."' ") or die(mysqli_error($con));
		} else {
			if(isset($updateName)){
				$old_name=mysqli_fetch_array(mysqli_query($con, "SELECT groupname from groups where gkey='".$mainKey."' "));
				$old_name_group=$old_name['groupname'];
				$update_desc_query=mysqli_query($con, "UPDATE groups SET groupname='".$updateName."', gdesc='".$updateDesc."' where gkey='".$mainKey."' ");
			}
			else $update_desc_query=mysqli_query($con, "UPDATE groups SET gdesc='".$updateDesc."' where gkey='".$mainKey."' ");

			if(isset($updateName)){
				if(mysqli_num_rows(mysqli_query($con, "SELECT * from notifications where gkey='".$mainKey."' AND type='gName' LIMIT 1"))==0)
					mysqli_query($con, "INSERT into notifications(user_name, old_name, gkey, type) VALUES('".$username_db."', '".$old_name_group."', '".$mainKey."', 'gName') ");
				else mysqli_query($con, "UPDATE notifications SET old_name='".$old_name_group."', timenote=CURRENT_TIMESTAMP() where gkey='".$mainKey."' AND type='gName' ");
			} else {
				if(mysqli_num_rows(mysqli_query($con, "SELECT * from notifications where gkey='".$mainKey."' AND type='gEdit' LIMIT 1")) == 0 )
					 mysqli_query($con, "INSERT into notifications(user_name, gkey, type) VALUES ('".$username_db."', '".$mainKey."', 'gEdit')");
				else mysqli_query($con, "UPDATE notifications SET user_name='".$username_db."', timenote=CURRENT_TIMESTAMP() where gkey='".$mainKey."' AND type='gEdit' ");
			}
		}
	}
}

if(isset($_POST['changeFileName']) && isset($_POST['fileKey'])){
	$fileKey=trim($_POST['fileKey']);
	$changeFileName=trim($_POST['changeFileName']);

	if(!empty($fileKey) && !empty($changeFileName)){
		$changeName=mysqli_prepare($con, "UPDATE content SET cname= ? where ckey=?");
		mysqli_stmt_bind_param($changeName, "ss", $changeFileName, $fileKey);
		mysqli_stmt_execute($changeName);
	}
}
?>