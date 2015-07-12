<?php
	
	include '../inc/connect.php';
	session_start();
	$uid_db=$_SESSION['user_uid'];

	$arrayRejectedNames=array();

	if(isset($_REQUEST['toaddcontact']) && !empty($_REQUEST['toaddcontact'])){
		$username_fellow=htmlspecialchars($_REQUEST['toaddcontact']);
		call_user_func("addcontact", $con, $username_fellow);
	}
	if(isset($_REQUEST['toremovecontact']) && !empty($_REQUEST['toremovecontact'])){
		$username_fellow=htmlspecialchars($_REQUEST['toremovecontact']);
		call_user_func("removecontact", $con, $username_fellow);
	}

	function addcontact($con, $username_fellow){
		$fellow_query=mysqli_query($con,"SELECT uid from users where user_name='".$username_fellow."'") or die(mysqli_error($con));
		$fellow_query_result=mysqli_fetch_array($fellow_query);
		$fellow_uid=$fellow_query_result['uid'];

		$uid_db=$_SESSION['user_uid'];
		if(mysqli_num_rows(mysqli_query($con, "SELECT * from contacts where uid=".$uid_db." AND fellowid=".$fellow_uid.""))==0)
			mysqli_query($con, "INSERT into contacts(uid, fellowid) VALUES (".$uid_db.", ".$fellow_uid.")") or die(mysqli_error($con));
	}

	function removecontact($con, $username_fellow){
		$fellow_query=mysqli_query($con,"SELECT uid from users where user_name='".$username_fellow."'") or die(mysqli_error($con));
		$fellow_query_result=mysqli_fetch_array($fellow_query);
		$fellow_uid=$fellow_query_result['uid'];

		$uid_db=$_SESSION['user_uid'];

		mysqli_query($con, "DELETE from contacts where uid=".$uid_db." AND fellowid=".$fellow_uid."");
	}

	if(isset($_REQUEST['addcontacts']) && !empty($_REQUEST['addcontacts'])){
		$userToAddraw=$_REQUEST['addcontacts'];
		$userToAdd=explode(",", $userToAddraw);
		foreach ($userToAdd as $values){
			$currentname=trim(stripslashes(mysqli_real_escape_string($con,$values)));

			$format=1;

			if(ctype_alnum($currentname))
				$insertkey_contact="user_name";
			else if(filter_var($currentname, FILTER_VALIDATE_EMAIL))
				$insertkey_contact="email";
			else $format=0;

			if($format==1){
				$fellow_querymore=mysqli_query($con,"SELECT uid from users where ".$insertkey_contact."='".$currentname."'");
				if(mysqli_num_rows($fellow_querymore)>0){
					$fellow_querymore_result=mysqli_fetch_array($fellow_querymore);
					$fellow_uidmore=$fellow_querymore_result['uid'];

					if($fellow_uidmore==$uid_db)
						call_user_func("check_error_contact", $arrayRejectedNames, "[ ".$currentname." ]", "Can't see the point, why would you try and add yourself to your own contact list?");
					else{

						if(mysqli_num_rows(mysqli_query($con, "SELECT * from contacts where uid=".$uid_db." AND fellowid=".$fellow_uidmore.""))==0)
							$new_added=mysqli_query($con, "INSERT into contacts(uid, fellowid) VALUES (".$uid_db.", ".$fellow_uidmore.")") or die(mysqli_error($con));
						else $new_added=false;

						if($new_added){
							$select_contact_uid_query=mysqli_query($con, "SELECT * from users where uid=".$fellow_uidmore."");
							$fellowid_db_contact_result=mysqli_fetch_array($select_contact_uid_query);
							$contact_view_username=$fellowid_db_contact_result['user_name'];
							$contact_view_fname=$fellowid_db_contact_result['fname'];
			                $contact_view_lname=$fellowid_db_contact_result['lname'];
			                $contact_view_image=$fellowid_db_contact_result['image'];
			                $contact_view_fullname=$contact_view_fname." ".$contact_view_lname;
			                if(isset($fellowid_db_contact_result['profession']))
			                	$contact_view_profession=stripslashes($fellowid_db_contact_result['profession']);
			                if(isset($fellowid_db_contact_result['school']))
			                	$contact_view_school=stripslashes($fellowid_db_contact_result['school']);
			                if(isset($fellowid_db_contact_result['undergraduate']))
			                	$contact_view_undergraduate=stripslashes($fellowid_db_contact_result['undergraduate']);
			                if(isset($fellowid_db_contact_result['about']))
			                	$contact_view_about=stripslashes($fellowid_db_contact_result['about']);

			                ?>

			                	<div id="contact_disp_cell" class="new_contact_cell_wrapper">
			                		<table>
			                			<tr>
			                				<td id="info_display_contact_wrapper" width="20%">
			                					<img id="contact_picture" src="<?php echo "__".$contact_view_image;?>" width="100px">
			                					<div id="remove_contact_display">
			                						<a id="contact_added">Added</a>
			                					</div>
			                				</td>
			                				<td id="information_contact" width="80%">
			                					<div id="main_info_contact">
			                						<?php echo "<a href='$contact_view_username'><b>".$contact_view_fullname."</b></a> | ".$contact_view_username;?>
			                					</div>
			                					<?php 
				                					if(empty($contact_view_profession) && empty($contact_view_school) && empty($contact_view_undergraduate) && empty($contact_view_about)){
				                						echo '<div id="no_update_contact">Your friend hasn\'t updated any information about himself/herself.</div>';
				                					} else{
			                					?>
				                					<div id="contact_disp_extra">
				                						<?php 
				                							if(!empty($contact_view_profession))
				                								echo $contact_view_profession;
				                							if(!empty($contact_view_school))
				                								echo " | ".$contact_view_school." ";
				                							if(!empty($contact_view_undergraduate))
				                								echo " | ".$contact_view_undergraduate;
				                						?>
				                					</div>
				                					<div>
				                						<?php
				                							if(!empty($contact_view_about))
				                								echo '<b>About: </b>'.$contact_view_about;
				                						?>
				                					</div>
				                				<?php } ?>
			                				</td>
			                			</tr>
			                		</table>
			                	</div>

			                <?php
						} else call_user_func("check_error_contact", $arrayRejectedNames, $currentname, "is already in your contact list!");
					}
				} else call_user_func("check_error_contact", $arrayRejectedNames, $currentname, "doesn't exists. Check the username/Email and try again!");
			} else call_user_func("check_error_contact", $arrayRejectedNames, $currentname, "doesn't exists. Check the username/Email and try again!");
		}
	}

	function check_error_contact($arrayRejectedNames, $currentname, $to_disp_contact_error){
		$flag=1;
	 	foreach ($arrayRejectedNames as $valuesReject){
	 		if($valuesReject==$currentname){
	 			$flag=0;
	 			break;
	 		}
	 	}
	 	if($flag==1){
	 		array_push($arrayRejectedNames, $currentname);
	 	?>
	 		<div class="no_exist">
	 			<?php echo "<b>".$currentname."</b> ".$to_disp_contact_error;?>
	 		</div>
	 	<?php
	 	}
	}

?>