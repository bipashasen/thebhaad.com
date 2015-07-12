<?php

	if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);

	$mainTitle='contacts';

	include ROOT.'/inc/header.php';

?>

			<div id="contacts_main_wrapper">  	
				<div id="add_more_contact">
					<a onclick="showAddContacts()">Add more contacts +</a>
					<div id="table_wrapper_add_contacts"><table id="add_more_contact_input">
						<tr>
							<td id="add_contact_info" width="30%">
								Enter the Username or Email-id of those people you wanna add in your contact.<br>
								In case you don't have their Username or Email-id, you may instead search for them and then add them to the list.
							</td>
							<td id="add_contact_input" width="70%">
								<div id="main_input_add_contact">
									<textarea id="textarea_add_contact"></textarea>
								</div>
								<div id="submit_add_contact">
									<input id="addcontacts_submit" value="Add" type="submit"/>
								</div>
								<div id="extra_info_add_contact">
									Seperate the Usernames/Email-id with a comma ( , ) for more than one contact.<br>Ex. katnisseverdeen, harrypotter, galehawthrone@gmail.com
								</div>
							</td>
						</tr>
					</table></div>
					<div id="load_contacts"><img src="/images/loading3.gif" width="20px"></div>
				</div>
				<div id="contact_list_wrapper">
					<div id="contact_list_header">
						Contact List | <span id="pwamac">People who added you</span>
					</div>
					<div id="other_contacts_cell_wrapper">
						<ul id="pwamac_ul">
						<?php
							$pwamcontacts=mysqli_query($con, "SELECT fname, lname, user_name, image, profession, school, undergraduate from users inner join contacts on users.uid=contacts.uid where contacts.fellowid = ".$uid_db." ORDER BY contactid DESC LIMIT ".$limit_data_minor." ");
							if(mysqli_num_rows($pwamcontacts) == 0){
								echo '<div id="pwamac_n"> No one added you as contact :(</div>';
							} else {
								pwam($pwamcontacts);
								if(mysqli_num_rows($pwamcontacts)<$limit_data_minor)
            						echo '<input type="hidden" value="pwam_end" id="pwam_input" />';
							}
						?>
						</ul>
					</div>
					<div id="contact_cell_wrapper">
						<?php
							$disp_contact_query=mysqli_query($con, "SELECT fellowid from contacts where uid=".$uid_db."  ORDER BY contactid desc LIMIT ".$limit_data."");
							$query_num_main=mysqli_num_rows($disp_contact_query);
							if($query_num_main>0){
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
								if($query_num_main<$limit_data)
									echo '<input class="contact_loadmore_input" type="hidden" value="false"><div id="list_end_contact">To add more contacts go to the top to select more users.</div>';
							} else echo '<div id="no_contact">You don\'t have anyone in your contact list. Start adding people by clicking on<br>"Add more contacts +" and submiting Username/Email-id.</div><input class="contact_loadmore_input" type="hidden" value="false">';
						?>
					</div>
				</div>
				<div id="contact_loader">
					<img src="/images/loading4.gif" width="30px"/>
				</div>
    		</div>  
    		<input type="hidden" class="contact_loadmore_input" value="true"/> 
    	</div>
    </body>
</html>