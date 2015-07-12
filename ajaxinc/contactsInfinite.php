<?php
	include '../inc/user_cred.php';

	if(isset($_REQUEST['infinitecontacts']) && isset($_REQUEST['page']) && $_REQUEST['infinitecontacts']=='true' && !empty($_REQUEST['page'])){
		$pagenumber=$_REQUEST['page'];

		$start_data=$pagenumber*$limit_data;

		$disp_contact_query=mysqli_query($con, "SELECT fellowid from contacts where uid=".$uid_db."  ORDER BY contactid DESC LIMIT ".$start_data.", ".$limit_data." ");
		$num_queries = mysqli_num_rows($disp_contact_query);
		if($num_queries>0){
			while($fellowid_db_result=mysqli_fetch_array($disp_contact_query)){
				$fellowid_db=$fellowid_db_result['fellowid'];
				$select_contact_uid_query=mysqli_query($con, "SELECT * from users where uid=".$fellowid_db."");
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

                	<div id="contact_disp_cell">
                		<table>
                			<tr>
                				<td id="info_display_contact_wrapper" width="20%">
                					<img id="contact_picture" src="<?php echo "__".$contact_view_image;?>" width="100px">
                					<div id="remove_contact_display">
                						<a id="addtocontactsanchor_<?php echo $contact_view_username?>" onclick="removeContact('<?php echo $contact_view_username?>')">Remove contact</a>
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
			}

			if($num_queries<$limit_data)
				echo '<input type="hidden" class="contact_loadmore_input" value="false"/><div id="list_end_contact">To add more contacts go to the top to select more users.</div>';

		} else echo '<input type="hidden" class="contact_loadmore_input" value="false"/><div id="list_end_contact">To add more contacts go to the top to select more users.</div>';

	}
?>