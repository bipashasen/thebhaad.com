<?php

	include '../inc/connect.php';
	session_start();
	$uid_db=$_SESSION['user_uid'];
	$username_db=$_SESSION['user_username'];

	if(isset($_REQUEST['beforeand']) && !empty($_REQUEST['beforeand']) && isset($_REQUEST['gname']) && !empty($_REQUEST['gname']) && isset($_REQUEST['job']) && !empty($_REQUEST['job'])){
		$gkey_request=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['beforeand'])));
		$gname_request=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['gname'])));
		$job_request=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['job'])));

		if($job_request=='Accept'){
			if(mysqli_num_rows(mysqli_query($con, "SELECT * from usergroups WHERE uid=".$uid_db." AND gkey= '".$gkey_request."' AND active=1 LIMIT 1")) == 0){
				$updated_group = mysqli_query($con, 'UPDATE usergroups SET active = 1, rejected = 0 where uid='.$uid_db.' AND gkey = "'.$gkey_request.'" ');
				$updated_group_number = mysqli_query($con, 'UPDATE groups SET memnum=memnum+1 where gkey = "'.$gkey_request.'" ');
				$check_adminid_req = mysqli_query($con, "SELECT adminid from groups where gkey='".$gkey_request."' LIMIT 1");
				$check_adminid_req_arr=mysqli_fetch_array($check_adminid_req);
				$adminid_req=$check_adminid_req_arr['adminid'];
				if($adminid_req==0)
					mysqli_query($con, "UPDATE groups SET adminid=".$uid_db." WHERE gkey='".$gkey_request."'");
			}
			if($updated_group){

				echo '<div class="folder_content">
						<a href="'.$gkey_request.'">
			                <div class="folder_icon">
			                    <div class="download_icon"></div>  
			                </div>
			                <div class="folder_info">
			                    '.$gname_request.'
			                </div>
		                </a>
		            </div>';


		         if(mysqli_num_rows(mysqli_query($con, "SELECT * from notifications where user_name='".$username_db."' AND gkey= '".$gkey_request."' AND type='join' ")) == 0){
		         	mysqli_query($con, "INSERT into notifications (user_name, gkey,type) VALUES ('".$username_db."','".$gkey_request."','join')");
		         } else mysqli_query($con, "UPDATE notifications SET timenote=NOW() where gkey='".$gkey_request."' AND user_name='".$username_db."' AND type='join' ");

			}
		} else {
			$updated_group = mysqli_query($con, 'UPDATE usergroups SET active = 0, rejected = 1 where uid='.$uid_db.' AND gkey = "'.$gkey_request.'" ');
		}
	}

	if(isset($_REQUEST['groupIdentity']) && !empty($_REQUEST['groupIdentity']) && isset($_REQUEST['groupname']) && !empty($_REQUEST['groupname']) && isset($_REQUEST['job']) && !empty($_REQUEST['job'])){
		$gkey_archive=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['groupIdentity'])));
		$gname_archive=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['groupname'])));
		$job_archive=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['job'])));

		if($job_archive=='Archives_Accept'){
			if(mysqli_num_rows(mysqli_query($con, "SELECT * from usergroups WHERE uid=".$uid_db." AND gkey= '".$gkey_archive."' AND active=1 LIMIT 1")) == 0){
				$updated_group_archive = mysqli_query($con, 'UPDATE usergroups SET active = 1, rejected = 0 where uid='.$uid_db.' AND gkey = "'.$gkey_archive.'" ');
				$updated_group_archive_number = mysqli_query($con, 'UPDATE groups SET memnum=memnum+1 where gkey = "'.$gkey_archive.'" ');
				$check_adminid_req = mysqli_query($con, "SELECT adminid from groups where gkey='".$gkey_archive."' LIMIT 1");
				$check_adminid_req_arr=mysqli_fetch_array($check_adminid_req);
				$adminid_req=$check_adminid_req_arr['adminid'];
				if($adminid_req==0)
					mysqli_query($con, "UPDATE groups SET adminid=".$uid_db." WHERE gkey='".$gkey_archive."'");

				if(mysqli_num_rows(mysqli_query($con, "SELECT * from notifications where user_name='".$username_db."' AND gkey= '".$gkey_archive."' AND type='join' ")) == 0){
		         	mysqli_query($con, "INSERT into notifications (user_name, gkey,type) VALUES ('".$username_db."','".$gkey_archive."','join')");
		         } else mysqli_query($con, "UPDATE notifications SET timenote=NOW() where gkey='".$gkey_archive."' AND user_name='".$username_db."' AND type='join' ");
			}
		} else {
			$delet_req=mysqli_query($con, 'DELETE from usergroups WHERE uid='.$uid_db.' AND gkey="'.$gkey_archive.'" ');
			$check_members=mysqli_query($con, 'SELECT memnum, groupid from groups where gkey="'.$gkey_archive.'" LIMIT 1');
			$check_members_arr=mysqli_fetch_array($check_members);
			$check_members_res=$check_members_arr['memnum'];
			$groupid_archive=$check_members_arr['groupid'];

			if($check_members_res==0){
				$check_archives_query=mysqli_query($con, "SELECT uid from usergroups where gkey='".$gkey_archive."' LIMIT 1");
				if(mysqli_num_rows($check_archives_query)==0){
					include '../inc/secureDownload.php';
					deleteFolder($con, '../../contents/'.$gkey_archive, $gkey_archive, $username_db);
					mysqli_query($con, "DELETE from groups where gkey='".$gkey_archive."' ");
				}
			}
		}
	}

?>