<?php

	include '../inc/connect.php';
	include '../inc/mail_func.php';

	if(isset($_REQUEST['toaddUsersValue']) && isset($_REQUEST['gname']) && !empty($_REQUEST['toaddUsersValue']) && !empty($_REQUEST['gname'])){
		$tosendreq_raw=$_REQUEST['toaddUsersValue'];
		$tosendreq_array=explode(',',$tosendreq_raw);

		$add_users_gkey=$_REQUEST['gname'];

		$find_groupdetails=mysqli_query($con, "SELECT groupid, groupname, gkey from groups where gkey = '".$add_users_gkey."' ") or die(mysqli_error($con));

		if(mysqli_num_rows($find_groupdetails)>0){

			$find_groupdetails_result=mysqli_fetch_array($find_groupdetails);

			$group_id_add=mysqli_real_escape_string($con, $find_groupdetails_result['groupid']);
			$groupname = $find_groupdetails_result['groupname'];
			$gkey = $find_groupdetails_result['gkey'];

			foreach ($tosendreq_array as $tosendreq_value){
				$current_adduser=trim(stripslashes(mysqli_real_escape_string($con, $tosendreq_value)));

				if(!empty($current_adduser)){

					$format=1;

					if(ctype_alnum($current_adduser))
						$insertkey_adduser='user_name';
					else if(filter_var($current_adduser, FILTER_VALIDATE_EMAIL))
						$insertkey_adduser='email';
					else $format=0;

					if($format==1){
						$add_user_details_query=mysqli_query($con, "SELECT * from users where ".$insertkey_adduser."='".$current_adduser."' ");

						if(mysqli_num_rows($add_user_details_query) > 0){

							$add_user_details=mysqli_fetch_array($add_user_details_query);

							$add_user_uid=$add_user_details['uid'];
							$add_user_fullname=$add_user_details['fname']." ".$add_user_details['lname'];
							$add_user_email = $add_user_details['email'];

							$search_ifexists=mysqli_query($con, "SELECT active, rejected from usergroups where uid = ".$add_user_uid." and gid=".$group_id_add." ");

							$add_user_rejct=0;

							$ifexist_numrows=mysqli_num_rows($search_ifexists);
							if($ifexist_numrows>0){
								$add_user_cred=mysqli_fetch_array($search_ifexists);
								$add_user_accept=$add_user_cred['active'];
								$add_user_rejct=$add_user_cred['rejected'];
							} 

							if($ifexist_numrows==0 || $add_user_rejct==1){

								$maxvisit_adduser=mysqli_query($con, "SELECT max(visitcount) as maxv_adduser from usergroups where uid = ".$add_user_uid." ");
								$maxvisit_final=mysqli_fetch_array($maxvisit_adduser);
								$maxvsit=$maxvisit_final['maxv_adduser']+50;

								if($add_user_rejct==1)
									$send_req_query="UPDATE usergroups SET rejected = 0, visitcount=".$maxvsit." where uid = ".$add_user_uid." and gid=".$group_id_add." ";
								else $send_req_query= "INSERT into usergroups (uid, gid, gkey, visitcount, active, rejected) VALUES (".$add_user_uid.", ".$group_id_add.", '".$add_users_gkey."', ".$maxvsit.", 0, 0)";
								
								$send_req=mysqli_query($con,$send_req_query) or die(mysqli_error($con));

								sendReqMailForGrp($add_user_email, $add_user_fullname, $groupname);

								echo '<div id="processed_addusers" class="success_addusers"><span id="added_user"></span>'.$add_user_fullname.' [ <b>'.$current_adduser.'</b> ]</div>';
							} else{	
								if($add_user_accept==1)
									$toshow_erroradduser='The user is already a member of this group.';
								else $toshow_erroradduser='A request has already been sent to this user for this group.';
								echo '<div id="processed_addusers" class="fail_addusers"><span id="added_user"></span>'.$add_user_fullname.' [ <b>'.$current_adduser.'</b> ]</div>
									<div class="fail_adduser_error">'.$toshow_erroradduser.'</div>';
							}
						} else {

							if($insertkey_adduser=='email'){

								session_start();
								$username_db=$_SESSION['user_username'];
								session_write_close();

								$name = mysqli_fetch_array(mysqli_query($con, "SELECT concat (fname, ' ',lname) as name from users where user_name = '".$username_db."' "));
								$name = $name['name'];
								sendInvitationMail($current_adduser, $name, $groupname);

								$check_if_exists_unreg = mysqli_query($con, "SELECT * from usergroups where unreg_email = '".$current_adduser."' AND gid = ".$group_id_add." LIMIT 1");
								if(mysqli_num_rows($check_if_exists_unreg) == 0){
									mysqli_query($con, "INSERT into usergroups (unreg_email, gid, gkey, visitcount, active, rejected) VALUES ('".$current_adduser."', ".$group_id_add.", '".$gkey."', 50, 0, 0)");
								}

								echo '<div id="processed_addusers" class="fail_addusers"><span id="added_user"></span><b>'.$current_adduser.'</b></div>
								<div class="fail_adduser_error">The user with this Email <b>('.$current_adduser.')</b> isn\'t registered. However, an invitation for this group has been sent to his/her Email.</div>';

							} else 
							echo '<div id="processed_addusers" class="fail_addusers"><span id="added_user"></span><b>'.$current_adduser.'</b></div>
								<div class="fail_adduser_error">The user doesn\'t exist. Please check the username and try again.</div>';
						}
					} else {
						echo '<div id="processed_addusers" class="fail_addusers"><span id="added_user"></span><b>'.$current_adduser.'</b></div>
						<div class="fail_adduser_error">The user doesn\'t exist. Please check the username/email and try again.</div>';
					}
				}
			}
		} else {
			echo 'There was some error. Please refresh and try again.';
		}
	}

	if(isset($_REQUEST['inviteToGroupsOptions']) && isset($_REQUEST['usernameToInvite'])){
		$successGroups = 0;
		$listToInvite=trim(mysqli_real_escape_string($con, $_REQUEST['inviteToGroupsOptions']));
		$userToInvite=trim(mysqli_real_escape_string($con, $_REQUEST['usernameToInvite']));

		$getUserId=mysqli_fetch_array(mysqli_query($con, "SELECT uid, fname, email from users where user_name = '".$userToInvite."' "));
		$fnameUserId = $getUserId['fname'];
		$email = $getUserId['email'];
		$getUserId=$getUserId['uid'];

		session_start();
		$username_db = $_SESSION['user_username'];
		session_write_close();

		$getSendName = mysqli_fetch_array(mysqli_query($con, "SELECT concat(fname, ' ', lname) as name from users where user_name = '".$username_db."' LIMIT 1"));
		$getSendName = $getSendName['name'];

		$listToInviteArray=explode(',', $listToInvite);

		echo '<div class="invitationResWrapper">';
					
		foreach ($listToInviteArray as $optionToInvite) {
			$optionToInvite=trim($optionToInvite);
			if(!empty($optionToInvite)){

				$checkGroup=mysqli_query($con, "SELECT groupname from groups where gkey='".$optionToInvite."' LIMIT 1");

				if(mysqli_num_rows($checkGroup)>0){
					$groupname=mysqli_fetch_array($checkGroup);
					$groupname=$groupname['groupname'];
					$checkIfBelongs=mysqli_query($con, "SELECT * from usergroups INNER JOIN users ON users.uid = usergroups.uid where users.user_name = '".$userToInvite."' AND usergroups.gkey='".$optionToInvite."' AND usergroups.rejected=0 LIMIT 1");

					if(mysqli_num_rows($checkIfBelongs) == 0){

						$checkCell=mysqli_query($con, "SELECT * from usergroups INNER JOIN users ON users.uid = usergroups.uid where users.user_name = '".$userToInvite."' AND usergroups.gkey='".$optionToInvite."' AND usergroups.rejected=1 LIMIT 1");

						$maxvisit_adduser=mysqli_query($con, "SELECT max(visitcount) as maxv_adduser from usergroups where uid = ".$getUserId." ");
						$maxvisit_final=mysqli_fetch_array($maxvisit_adduser);
						$maxvsit=$maxvisit_final['maxv_adduser']+50;

						if(mysqli_num_rows($checkCell) == 0){
							$groupIdInv=mysqli_fetch_array(mysqli_query($con, "SELECT groupid from groups where gkey = '".$optionToInvite."' "));
							$groupIdInv=$groupIdInv['groupid'];

							mysqli_query($con, "INSERT into usergroups (uid, gid, gkey, visitcount, active, rejected) VALUES (".$getUserId.", ".$groupIdInv.", '".$optionToInvite."', ".$maxvsit.", 0, 0)");
						} else mysqli_query($con, "UPDATE usergroups SET rejected = 0, visitcount=".$maxvsit." where uid = ".$getUserId." and gkey='".$optionToInvite."' ");

						echo '<div class="successInv resultPriorInv resultInv"><span></span><b>'.$groupname.'</b></div>';

						$successGroups = 1;

					} else echo '<div class="fail_addusers resultPriorInv resultInv"><span></span>The user is either already a member of the group <b>'.$groupname.'</b> or a request has already been sent for this group to the user. </div>';			
				}
			}
		}

		if($successGroups == 1) sendIndMail($email, $fnameUserId, $getSendName);

		echo '</div>';
	}


?>