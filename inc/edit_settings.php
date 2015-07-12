<?php

if (!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);

include ROOT.'/inc/form_val_functions.php';

if(isset($_POST['settings_submit'])){
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$name_change_success=$pass_change_success=0;
		if(!empty($_POST['edit_first_name'])){
			$fname_edit=test_input($_POST['edit_first_name']);
			$settings_fname_value=$fname_edit;
			$lname_edit=test_input($_POST['edit_last_name']);
			$settings_lname_value=$lname_edit;
			if(empty($lname_edit) || empty($fname_edit))
				$edit_name_error="You will have to fill both the fields if you wanna edit your name.";
			else if(check_name($fname_edit)&&check_name($lname_edit)&&check_full_name($fname_edit, $lname_edit)){
				$updateName="UPDATE users SET fname='".$fname_edit."', lname='".$lname_edit."' where user_name='".$username_db."'";
				if(mysqli_query($con, $updateName)){
					$settings_lname_value="";
					$settings_fname_value="";
					$name_change_success=1;
				} else $edit_name_error="Sorry your name couldn't be updated! Please Try again!";
			} else $edit_name_error="Doesn't look like a valid name!";
		} else if(!empty($_POST['edit_last_name'])){
			$settings_lname_value=test_input($_POST['edit_last_name']);
			if(empty($_POST['edit_first_name']))
				$edit_name_error="You will have to fill both the fields if you wanna edit your name.";
		}

		if(!empty($_POST['edit_check_pass'])){
			$settings_oldpass_value=test_input($_POST['edit_check_pass']);
			if(empty($_POST['edit_new_pass'])||empty($_POST['edit_confirm_pass'])){
				$edit_password_error="Do you wanna edit the password? If so then please enter the new password and confirm it!";
				if(!empty($_POST['edit_new_pass']))
					$settings_newpass_value=test_input($_POST['edit_new_pass']);
			}
			else{
				$settings_newpass_value=test_input($_POST['edit_new_pass']);
				$old_password_edit=test_input($_POST['edit_check_pass']);
				$new_password_edit=test_input($_POST['edit_new_pass']);
				$confirm_password_edit=test_input($_POST['edit_confirm_pass']);
				//if(!preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/",$new_password_edit))
				if(strlen($new_password_edit) < 8)
					$edit_password_error="The Password must have at least 8 characters!";
				else if($confirm_password_edit!=$new_password_edit)
					$edit_password_error="Oops! Passwords do not match! Try again!";
				else{
					$get_pass_edit="SELECT password from users where user_name='".$username_db."'";
					$array_get_password=mysqli_fetch_array(mysqli_query($con, $get_pass_edit));
					$stored_password=$array_get_password['password'];
					if(password_verify($old_password_edit, $stored_password)){

						if($old_password_edit==$new_password_edit)
							$edit_password_error="Your new password cannot be the same as the old password!";
						else{	
							$options = ['cost' => 12,];
      						$new_password_edit=password_hash($new_password_edit, PASSWORD_BCRYPT, $options);
							$update_query="UPDATE users SET password='".$new_password_edit."' where user_name='".$username_db."'";
							if(mysqli_query($con, $update_query)){
								$settings_oldpass_value="";
								$settings_newpass_value="";
								$pass_change_success=1;
							} else $edit_password_error="Sorry password couldn't be updated! Please Try again!";
						}
					}else $edit_password_error="The password you entered doesn't match with the registered password!";
				}
			}

		} else if(!empty($_POST['edit_new_pass'])){
			$settings_newpass_value=test_input($_POST['edit_new_pass']);
			$edit_password_error="Do you wanna edit the password? If so then please enter old password and confirm the new password as well!";
		} else if(!empty($_POST['edit_confirm_pass'])){
			$edit_password_error="Do you wanna edit the password? If so then please enter old password and the new password as well and then confirm the password!";
		}

		if($name_change_success==1)
			$edit_name_success="Your name has be updated to <b>".$fname_edit." ".$lname_edit."</b>!";
		if($pass_change_success==1)
			$edit_password_success="Your <b>password</b> has been updated!";
	}
}
?>