<?php 

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	$mainTitle="edit";
	include ROOT.'/inc/header.php';
	include ROOT.'/inc/form_val_functions.php';
	//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	$professionValue=1;
	$error_upload=$goToSchool='';
	$undergraduateValue=$aboutValue=$achievementValue="";
	$data_db_query="select school, undergraduate, about, achievement, profession, lastlogin FROM users WHERE email = '$email_db'";
   	$data_db=mysqli_query($con, $data_db_query);
   	if(isset($_SESSION['interm_upload_error'])){
   		$error_upload=$_SESSION['interm_upload_error'];
   		unset($_SESSION['interm_upload_error']);
   	} else $error_upload="";
   	//ini_set('display_errors',1);
	//error_reporting(-1);
	while($data_row=mysqli_fetch_array($data_db)){
		if(isset($data_row['school']))
			$goToSchool=stripslashes($data_row['school']);
		if(isset($data_row['undergraduate']))
			$undergraduateValue=stripslashes($data_row['undergraduate']);
		if(isset($data_row['about']))
			$aboutValue=stripslashes($data_row['about']);
		if(isset($data_row['achievement']))
			$achievementValue=stripslashes($data_row['achievement']);
		if(isset($data_row['profession'])){
			if($data_row['profession']=='Student')
				$professionValue=1;
			else if($data_row['profession']=='Working')
				$professionValue=2;
			else $professionValue=3;
		}

	}
	if($_SERVER['REQUEST_METHOD'] == 'POST') include ROOT.'/inc/upload_picture.php';
	else {
?>

		<div id="interm_main_content">
			<div id="why_edit_info">
				<p><?php if($lastloginValue=="0000-00-00 00:00:00"){?>You might wanna add some information about yourself. It will be easier for your friends to recognize you.<br>
				Don't worry, it's not too much information!<?php } else{ ?>More the information, easier for your friends to recognize you!<?php }?><br>PS: All the information you enter here is public.</p>
			</div>
			<div id="show_username">
				<?php
					echo 'Your Username: <span>'.$username_db.'</span>';
				?>
			</div>
			<iframe name="upload_frame" id="upload_frame_wrapper"></iframe>
			<form  id="add_infomation_form" action="" target="upload_frame" method="post" enctype="multipart/form-data">
				<?php
				if($image_db!='default.icon'){
					echo '<label class="remove_display">
							<input type="submit" name="removie_display_input" onclick="javascript:this.form.submit();">
							<p>Remove Picture</p>
						  </label>';
				}
				?>
				<div id="fill_info_first_wrapper">
					<div id="profile_pic_upload_interm">
						<div id="profile_picture_image">
                			<img class="display_picture_image" width="180px" height="180px" src="<?php echo '__'.$image_db;?>">
            			</div>
					</div>
				</div>
				<div id="error_upload"><?php echo $error_upload;?></div>
				<div id="display_pic_upload_first">
					<img class="loading" width="30px" src="/images/loading4.gif">
    				<label id="display_pic_fu_label" title="Click to upload!">
    					<input type="file" name="display_pic_input" id="display_pic_input_first">
    					Upload a new picture!&nbsp;<img src="/images/change.png" width="50px" height="50px">
    				</label>
				</div>
				<div id="radio_info_extra">
					<label class="profession_extra_wrapper">
						<input value="1" name="profession" type="radio" <?php echo ($professionValue== 1) ?  "checked" : "" ;  ?>>
						Student
					</label>
					<label class="profession_extra_wrapper">
						<input value="2" name="profession" type="radio" <?php echo ($professionValue== 2) ?  "checked" : "" ;  ?> >
						Working
					</label>
					<label class="profession_extra_wrapper">
						<input value="3" name="profession" type="radio" <?php echo ($professionValue== 3) ?  "checked" : "" ;  ?>>
						Both
					</label>
				</div>
				<div id="extra_information_extra_wrapper">
					<div><input type="text" name="whereGoToSchool" class="add_information_text" value="<?php echo $goToSchool;?>" placeholder="Where did you go for school?"></div>
					<div><input type="text" name="whereGoToUnder" class="add_information_text" value="<?php echo $undergraduateValue;?>" placeholder="Where did you go for your undergraduate?"></div>
					<div><textarea name="aboutInformation" class="add_information_textarea" placeholder="Wanna tell us something more about yourself?"><?php echo $aboutValue;?></textarea></div>
					<div><textarea name="achievementInformation" class="add_information_textarea" placeholder="Some achievements you wanna brag about?"><?php echo $achievementValue;?></textarea></div>
				</div>
				<div><input id="submit_fields" name="submit_extrainfo" type="submit" value="Save"></div>
			</form>
			<div id="skip_extra_info_form">
				<?php if($lastloginValue=="0000-00-00 00:00:00"){?>
					<p>or you could totaly <a href="<?php echo $username_db;?>"><span>skip</span></a> this step!</p>
				<?php }else { ?>
					<p>or go back to the </span><a href="<?php echo $username_db;?>"><span>root.</a></p>
				<?php } ?>
			</div>


			<!-- The popup for crop the uploaded photo -->
    		<div id="popup_crop">
        		<div class="form_crop">
            		<span class="close" onclick="close_popup('popup_crop')"></span>
            		<h2>Crop photo</h2>
            		<!-- This is the image we're attaching the crop to -->
            		<div id="cropbox"><img/></div>
            
            		<!-- This is the form that our event handler fills -->
            		<form>
                		<input type="hidden" id="x" name="x" />
                		<input type="hidden" id="y" name="y" />
                		<input type="hidden" id="w" name="w" />
                		<input type="hidden" id="h" name="h" />
                		<input type="hidden" id="photo_url" name="photo_url" />
                		<input type="hidden" id="username" name="username" />
                		<input type="hidden" id="imageFileType" name="imageFileType" />
                		<input type="button" value="Crop Image" id="crop_btn" onclick="crop_photo()" />
            		</form>
        		</div>
    		</div>
    		<!--popup!-->

		</div>

		<?php } ?>
		</div>
	</body>
</html>