            <div id="Notes_wrapper">

                <?php if(isset($_GET['fkey'])) {
                    if(!isset($nosuchgroup) && !isset($noaccessright)){?>

                <div id="content_desc_wrapper">
                    <div id="content_name_rs"><?php echo "<span id='current_name_rs'>".$foldername_db."</span> | <span id='gname_fname_rs'><a href='".$group_url."'>".$groupname_db."</a></span>";?></div>
                    <div id="content_desc_main">
                    <?php 
                        if(!empty($fdesc_db)) echo "<b>Description: </b><div id='current_desc_rs'>".$fdesc_db."</div>"; 
                        else if($group_url!='personal') echo '<div class="no_desc_right_sidebar"><b>Description: </b>No one has updated any description for this group. Click Edit to edit desciption/name.</div>';
                        else echo '<div class="no_desc_right_sidebar">You haven\'t updated any desciption for this group.</div>';
                    ?>
                    </div>
                <?php if($group_url!='personal'){ ?>
                    <div id="members_number">
                        <div id="member_of_group_wrapper_rs">
                            <div>
                                <div id="memname_load_rs"><img src="/images/loading2.gif" width="15px"></div>
                            </div>
                        </div>
                        <?php echo "<b>Number Of Members:</b> <span id='".$group_url."' class='memnum_rs'>".$member_numbers." member(s)</span>";?></div>
                    <div id="admin_name_content_desc"><?php echo "<b>Admin: </b>"; if($adminid_db==$uid_db) echo '<span class="self_admin_right_sidebar">You are the admin of this group</span>'; else echo $admin_fullname_db;?></div>
                <?php } ?>
                    <div class="folder_info_edit" id="content_edit_main"><input id="<?php echo $current_url;?>" class="edit_name_rs" type="submit" value="Edit"/></div>
                </div>

                <?php } } else if(isset($_GET['gkey'])){
                    if(!isset($nosuchgroup) && !isset($noaccessright)){
                        ?>

                <div id="content_desc_wrapper">
                    <div id="content_name_rs"><?php echo "<span id='current_name_rs'>".$groupname_db."</span>";?></div>
                    <div id="content_desc_main">

                    <?php 
                        if(!empty($gdesc_db)) echo "<b>Description:</b> <div id='current_desc_rs'>".$gdesc_db."</div>"; 
                        else if($group_url=='personal') echo '<div class="no_desc_right_sidebar"><b>Description: </b>You haven\'t updated any description for this group</div>';
                        else if($adminid_db==$uid_db) echo '<div class="no_desc_right_sidebar"><b>Description: </b>You haven\'t updated any description for this group. Click Edit to edit desciption/name.</div>';
                        else echo '<div class="no_desc_right_sidebar"><b>Description: </b>There is no description updated by the Admin.</div>';
                    ?>
                    </div>
                <?php if($group_url!='personal'){ ?>
                    <div id="members_number">
                        <div id="member_of_group_wrapper_rs">
                            <div>
                                <div id="memname_load_rs"><img src="/images/loading2.gif" width="15px"></div>
                            </div>
                        </div>
                        <?php echo "<b>Number Of Members:</b> <span id='".$group_url."' class='memnum_rs'>".$member_numbers." member(s)</span>";?></div>
                    <div id="admin_name_content_desc"><?php echo "<b>Admin: </b>"; if($adminid_db==$uid_db) echo '<span class="self_admin_right_sidebar">You are the admin of this group</span>'; else echo $admin_fullname_db;?></div>
                <?php } ?>  
                    <?php if($group_url=='personal' || $adminid_db==$uid_db){ ?><div class="group_info_edit" id="content_edit_main"><input class="edit_name_rs" id="<?php echo $group_url; ?>" type="submit" value="Edit"/></div><?php } ?> 
                </div>

                <?php } } ?>
                <div id="notes_content_outer_wrap">
                    <div id="notes_heading_rs">Notifications</div>
                    <?php
 
                        $last_timestamp=$_SESSION['start_timestamp'];

                        $contact_notification_query=mysqli_query($con, "SELECT users.fname as fname, users.lname as lname, users.user_name as user_name, DATE_FORMAT(contacts.timeadd, '%h:%i %p | %D %M \'%y') as time_stamp_ca from contacts INNER JOIN users ON contacts.uid = users.uid where contacts.fellowid=".$uid_db." AND contacts.timeadd > '".$last_timestamp."' ORDER BY contacts.timeadd DESC ");

                        $notification_query=mysqli_query($con, "SELECT users.fname, users.lname, groups.groupname as gname, notifications.old_name, notifications.fparentkey, notifications.user_name, notifications.type, notifications.gkey, notifications.fkey, DATE_FORMAT(notifications.timenote, '%h:%i %p | %D %M \'%y') as time_stamp_ca from usergroups INNER JOIN notifications ON usergroups.gkey = notifications.gkey INNER JOIN groups ON groups.gkey=notifications.gkey INNER JOIN users ON users.user_name = notifications.user_name where usergroups.uid=".$uid_db." AND notifications.user_name <> '".$username_db."' AND notifications.timenote > '".$last_timestamp."' AND usergroups.active=1 ORDER BY notifications.timenote DESC");

                        $cnq_rows=mysqli_num_rows($contact_notification_query);

                    ?>
                    <div id="notes_content_wrap">

                        <?php 
                        if(mysqli_num_rows($notification_query) + $cnq_rows == 0){
                            echo '<div id="no_new_notification">You have no new notifications since your last visit. Any new notification will be displayed over here! To view older notification click on <span class="to_dark">View older notifications.</span></div>';
                        } else {
                            getNotifications($con, $notification_query, $contact_notification_query);
                            echo '<div id="von">View Older Notifications</div>';
                        ?>

                        <?php } ?>

                        <div id="load_old_notes"><img src="/images/loading4.gif" width="25px"></div>

                    </div>
                    <div class="view_all_notes_rs"></div>
                </div>
                <div id="notes_posts_outer_wrap">
                    
                    <div id="notification_notes_wrapper">
                        <div id="slide_back_noti">
                            <a>Posts</a><img src="/images/slideBack.png" width="30px">
                        </div>
                        <div id="notification_posts_wrapper">

                        </div>
                        <div class="view_all_notes_rs"><a href='/posts'>View all Posts</a> ( <span id="new_notH"></span> )</div>
                    </div>
                </div>
            </div>