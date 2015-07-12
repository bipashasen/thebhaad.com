<?php

if (!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
include_once ROOT.'/inc/connect.php';

function pwam($pwamcontacts){
    while($pwamcontactsarr = mysqli_fetch_array($pwamcontacts)){
        $pwam_image=$pwamcontactsarr['image'];
        $pwam_name=$pwamcontactsarr['fname']." ".$pwamcontactsarr['lname'];
        $pwam_prof=$pwamcontactsarr['profession'];
        $pwam_school=$pwamcontactsarr['school'];
        $pwam_under=$pwamcontactsarr['undergraduate'];
        $pwam_username=$pwamcontactsarr['user_name'];
        ?>  
        <li>
            <table class="pwam_t">
                <tr>
                    <td><div class="pwam_image"><img src="<?php echo "__".$pwam_image; ?>"></div></td>
                    <td class="pwam_desc_w">
                        <div class="pwam_name"><a href="<?php echo $pwam_username; ?>"><?php echo $pwam_name; ?></a> | <span class="oc_username"> <?php echo $pwam_username;?></span></div>
                        <div class="pwam_desc">
                            <div class="pwamprof"><b>Profession:</b> <?php if(empty($pwam_prof)) echo '<span class="no_updates_pwam">No updates!</span>'; else echo $pwam_prof; ?></div>
                            <div class="pwamodesc"><?php if(empty($pwam_school) && empty($pwam_under)) echo '<span class="no_updates_pwam">No education updates!</span>'; else echo '<span> '.$pwam_school.' | '.$pwam_under.'</span>';?></div>
                        </div>
                    </td>
                </tr>
            </table>
        </li>
        <?php
    }
}

function createFolder($con, $fname_new, $insertkey, $insertvalue, $operator, $parent_fid, $username_db, $group_url){
    $folder_insert_query="INSERT into folders(fname, $insertkey, parentfid) VALUES('$fname_new', $insertvalue, $parent_fid)";
    mysqli_query($con, $folder_insert_query) or die("First: ".mysqli_error($con));
    $new_fid=mysqli_insert_id($con);
    $fname_key=uniqid().$new_fid;
    $fname_key=md5($fname_key);
    $fupdate_query="UPDATE folders SET fkey='".$fname_key."' where fid=".$new_fid;
    mysqli_query($con, $fupdate_query) or die("Second: ".mysqli_error($con));

    $updatevisitcount_divide_query="UPDATE groupcontent SET visitcount=visitcount/2 where $insertkey=".$insertvalue."";
    mysqli_query($con, $updatevisitcount_divide_query);    

    $max_fvisit_query="SELECT max(visitcount) as maxfvisit from groupcontent where $insertkey=".$insertvalue." AND pfid".$operator.$parent_fid;
    $max_fvisit_result=mysqli_query($con, $max_fvisit_query) or die("Third: ".mysqli_error($con));
    $max_fvisit_arr=mysqli_fetch_array($max_fvisit_result);
    $maxvisitcount=$max_fvisit_arr['maxfvisit']+50;
    
    $gcontent_query="INSERT into groupcontent ($insertkey, fkey, fname, pfid, visitcount) VALUES ($insertvalue, '$fname_key', '$fname_new', $parent_fid, $maxvisitcount)";
    if($group_url!='personal'){
        if($parent_fid=='NULL'){
            mysqli_query($con, "INSERT into notifications (gkey, fkey, user_name, type) VALUES ('".$group_url."', '".$fname_key."', '".$username_db."', 'fAdd')");
        } else {
            $parent_info=mysqli_fetch_array(mysqli_query($con, "SELECT fname, fkey from folders where fid = ".$parent_fid." LIMIT 1"));
            $parent_name=$parent_info['fname'];
            $parent_key=$parent_info['fkey'];
            mysqli_query($con, "INSERT into notifications (gkey, fkey, fparentkey, user_name, type) VALUES ('".$group_url."', '".$fname_key."', '".$parent_key."', '".$username_db."', 'fAdd')");
        }
    }

    mysqli_query($con, $gcontent_query);
    return $fname_key;
}

function getPath($con, $current_url, $directory){
    $path=array();
    $folder_url=$current_url;
    array_push($path, $folder_url);
    $parentfid_path=mysqli_fetch_array(mysqli_query($con, "SELECT parentfid from folders where fkey = '".$folder_url."' "));
    $parentfid_path=$parentfid_path['parentfid'];

    while($parentfid_path!=NULL){
        $newpath=mysqli_fetch_array(mysqli_query($con, "SELECT fkey, parentfid from folders where fid=".$parentfid_path));
        $parentfid_path=$newpath['parentfid'];
        array_push($path, $newpath['fkey']);
    }

    $path=array_reverse($path);

    foreach ($path as $currentpath) 
        $directory.="/".$currentpath;

    return $directory;
}

function buildNewInviteList($con, $start_data_inv, $limit_data, $username_db){
    $uid_db=$_SESSION['user_uid'];
    $selectgrpsInvite=mysqli_query($con, "SELECT groups.gkey, groups.groupname from groups INNER JOIN usergroups ON groups.gkey=usergroups.gkey where usergroups.uid=".$uid_db." AND usergroups.active=1 ORDER BY usergroups.visitcount DESC LIMIT ".$start_data_inv.", ".$limit_data." ");
    
    ?>

<?php if($start_data_inv == 0) {?>
    <div id="newInviteList">
        <div id="newInviteListInnerWrapper">
            <div id="yougrphead">Your groups:</div>

            <?php } ?>
            <?php if(mysqli_num_rows($selectgrpsInvite) > 0) {
            if($start_data_inv == 0) echo '<div class="grpListInvite">';
                while($currentginv=mysqli_fetch_array($selectgrpsInvite)) {
                    $gkey_inv=$currentginv['gkey'];
                    $gname_inv=$currentginv['groupname'];
                    ?>
                <div class="groupOptionInvite"><div class="group_in_label"><input name="inviteToGroups[]"  type="checkbox" value="<?php echo $gkey_inv; ?>" id="<?php echo $gkey_inv; ?>"/><label for="<?php echo $gkey_inv; ?>"><?php echo $gname_inv;?></label></div></div>
                <?php } 
                if($start_data_inv == 0) echo '</div>';
            } else if($start_data_inv == 0) echo '<div class="nogtinvite">Oops! Looks like you don\'t belong to any group! Don\'t worry, Start creating your own groups and then add your friends!</div>'; ?>
            
        <?php 
        if(mysqli_num_rows($selectgrpsInvite)<$limit_data) echo '<input type="hidden" id="endInviteList" value="false"/>';

        if($start_data_inv==0){ ?>
            <div id="submit_invite"><input type="submit" value="Invite" class="submit_invite_input"/><input type="submit" value="Close" class="submit_invite_input"/></div>
        </div>
    </div>

<?php }
}

function getNotifications($con, $notification_query, $contact_notification_query){

    $cnq_rows = 0;
    if(!empty($contact_notification_query))
        $cnq_rows=mysqli_num_rows($contact_notification_query);
    $cnq_ts_contact="";
    $doneContacts=1;
    if($cnq_rows>0){
        $cnq_array=mysqli_fetch_array($contact_notification_query);
        $cnq_ts_contact=$cnq_array['time_stamp_ca'];
        $doneContacts=0;
    }

    if(mysqli_num_rows($notification_query) > 0){
        while($current_notification = mysqli_fetch_array($notification_query)){
            $current_type=$current_notification['type'];

            $fname_notifications=$current_notification['fname'];
            $lname_notifications=$current_notification['lname'];
            $username_notifications=$current_notification['user_name'];
            $groupkey_notifications=$current_notification['gkey'];
            $groupname_notifications=$current_notification['gname'];
            $cnq_ts=$current_notification['time_stamp_ca'];

            if($doneContacts==0){
                if($cnq_ts_contact>$cnq_ts){
                    $doneContacts=1;

                    if($cnq_rows==1){
                        $cnq_fname=$cnq_array['fname'];
                        $cnq_lname=$cnq_array['lname'];
                        $cnq_username=$cnq_array['user_name'];
                        echo '<div class="notes_box"><a href="'.$cnq_username.'"><b>'.$cnq_fname.' '.$cnq_lname.'</b></a> added you as a contact.<div class="time_stamp_ca">'.$cnq_ts_contact.'</div></div>';
                    } else {
                        echo '<div class="notes_box"><a id="showpwamList_rd"><b>'.$cnq_rows.' people</b></a> added you as a contact.<div class="time_stamp_ca">'.$cnq_ts_contact.'</div></div>';
                    }
                }
            }

            if($current_type=='join'){
                echo '<div class="notes_box"><a href="'.$username_notifications.'"><b>'.$fname_notifications.' '.$lname_notifications.'</b></a> joined group <a href="/'.$groupkey_notifications.'"><b>'.$groupname_notifications.'</b></a><div class="time_stamp_ca">'.$cnq_ts.'</div></div>';

            } else if($current_type=='gEdit'){
                echo '<div class="notes_box"><a href="'.$username_notifications.'"><b>'.$fname_notifications.' '.$lname_notifications.'</b></a> edited the description of group <a href="/'.$groupkey_notifications.'"><b>'.$groupname_notifications.'</b></a><div class="time_stamp_ca">'.$cnq_ts.'</div></div>';

            } else if($current_type=='fEdit'){
                $folderkey_notifications=$current_notification['fkey'];
                $folder_details=mysqli_fetch_array(mysqli_query($con, "SELECT fname from folders where fkey='".$folderkey_notifications."' "));
                $foldername_notifications=$folder_details['fname'];
                echo '<div class="notes_box"><a href="'.$username_notifications.'"><b>'.$fname_notifications.' '.$lname_notifications.'</b></a> edited the description of folder <a href="/'.$groupkey_notifications.'&'.$folderkey_notifications.'"><b>'.$foldername_notifications.'</b></a> in group <a href="/'.$groupkey_notifications.'"><b>'.$groupname_notifications.'</b></a><div class="time_stamp_ca">'.$cnq_ts.'</div></div>';
            
            } else if($current_type=='fAdd'){
                $folderkey_notifications=$current_notification['fkey'];
                $folder_details=mysqli_fetch_array(mysqli_query($con, "SELECT fname from folders where fkey='".$folderkey_notifications."' "));
                $foldername_notifications=$folder_details['fname'];

                if(!empty($current_notification['fparentkey'])){
                    $parent_fkey_not=$current_notification['fparentkey']; 
                    $pfolder_details=mysqli_fetch_array(mysqli_query($con, "SELECT fname from folders where fkey='".$parent_fkey_not."' "));
                    $parent_name_not=$pfolder_details['fname'];

                    echo '<div class="notes_box"><a href="'.$username_notifications.'"><b>'.$fname_notifications.' '.$lname_notifications.'</b></a> added folder <a href="/'.$groupkey_notifications.'&'.$folderkey_notifications.'"><b>'.$foldername_notifications.'</b></a> in folder <a href= "/'.$groupkey_notifications.'&'.$parent_fkey_not.'"><b>'.$parent_name_not.'</b></a> of group <a href=
                    "/'.$groupkey_notifications.'"><b>'.$groupname_notifications.'</b></a><div class="time_stamp_ca">'.$cnq_ts.'</div></div>';

                } else echo '<div class="notes_box"><a href="'.$username_notifications.'"><b>'.$fname_notifications.' '.$lname_notifications.'</b></a> added folder <a href="/'.$groupkey_notifications.'&'.$folderkey_notifications.'"><b>'.$foldername_notifications.'</b></a> in group <a href="/'.$groupkey_notifications.'"><b>'.$groupname_notifications.'</b></a><div class="time_stamp_ca">'.$cnq_ts.'</div></div>';

            } else if($current_type=='fName'){

                $folderkey_notifications=$current_notification['fkey'];
                $folder_details=mysqli_fetch_array(mysqli_query($con, "SELECT fname from folders where fkey='".$folderkey_notifications."' "));
                $foldername_notifications=$folder_details['fname'];
                $old_name= $current_notification['old_name'];

                echo '<div class="notes_box"><a href="'.$username_notifications.'"><b>'.$fname_notifications.' '.$lname_notifications.'</b></a> changed the name of the folder <b>'.$old_name.'</b> to <a href="/'.$groupkey_notifications.'&'.$folderkey_notifications.'"><b>'.$foldername_notifications.'</b></a> of group <a href="/'.$groupkey_notifications.'"><b>'.$groupname_notifications.'</b></a> and updated its description.<div class="time_stamp_ca">'.$cnq_ts.'</div></div>';                                      

            } else if($current_type=='gName'){
                $old_name= $current_notification['old_name'];

                echo '<div class="notes_box"><a href="'.$username_notifications.'"><b>'.$fname_notifications.' '.$lname_notifications.'</b></a> changed the name of the group <b>'.$old_name.'</b> to <a href="/'.$groupkey_notifications.'"><b>'.$groupname_notifications.'</b></a> and updated its description.<div class="time_stamp_ca">'.$cnq_ts.'</div></div>';
            } else if($current_type == 'filesAdd'){

                $folderkey_notifications=$current_notification['fkey'];

                if($folderkey_notifications != $groupkey_notifications){
                    $folder_details=mysqli_fetch_array(mysqli_query($con, "SELECT fname from folders where fkey='".$folderkey_notifications."' "));
                    $foldername_notifications=$folder_details['fname'];
                    echo '<div class="notes_box"><a href="'.$username_notifications.'"><b>'.$fname_notifications.' '.$lname_notifications.'</b></a> uploaded files to <a href="/'.$groupkey_notifications.'&'.$folderkey_notifications.'"><b>'.$foldername_notifications.'</b></a> in group <a href="/'.$groupkey_notifications.'"><b>'.$groupname_notifications.'</b></a><div class="time_stamp_ca">'.$cnq_ts.'</div></div>';
                } else echo '<div class="notes_box"><a href="'.$username_notifications.'"><b>'.$fname_notifications.' '.$lname_notifications.'</b></a> uploaded files to group <a href="/'.$groupkey_notifications.'"><b>'.$groupname_notifications.'</b></a><div class="time_stamp_ca">'.$cnq_ts.'</div></div>';
            }
        }
    } 
    if($doneContacts == 0){
        if($cnq_rows==1){
            $cnq_fname=$cnq_array['fname'];
            $cnq_lname=$cnq_array['lname'];
            $cnq_username=$cnq_array['user_name'];
            echo '<div class="notes_box"><a href="'.$cnq_username.'"><b>'.$cnq_fname.' '.$cnq_lname.'</b></a> added you as a contact.<div class="time_stamp_ca">'.$cnq_ts_contact.'</div></div>';
        } else {
            echo '<div class="notes_box"><a id="showpwamList_rd"><b>'.$cnq_rows.' people</b></a> added you as a contact.<div class="time_stamp_ca">'.$cnq_ts_contact.'</div></div>';
        }
    }
}

function addUsersGrp($add_user_contacts){
    while($add_users_details=mysqli_fetch_array($add_user_contacts)){
        $add_user_name=$add_users_details['fname']." ".$add_users_details['lname'];
        $add_user_username=$add_users_details['user_name'];
    ?>
    <div id="add_users_cells">
        <input name="add_user_input[]" type="checkbox" id="<?php echo $add_user_username;?>" value="<?php echo $add_user_username;?>"/>
        <label for="<?php echo $add_user_username;?>"><?php echo $add_user_name;?></label>
    </div>
   <?php
    }
}

function showmyGroups($select_admin_g){
    while($select_admin_gdetails=mysqli_fetch_array($select_admin_g)){
        $select_admin_gkey=$select_admin_gdetails['gkey'];
        $select_admin_gname=$select_admin_gdetails['groupname'];
        $select_admin_memnum=$select_admin_gdetails['memnum'];

        ?>

            <div id="my_groups_cell"><a href="<?php echo '/'.$select_admin_gkey;?>"><?php echo $select_admin_gname;?></a> | <span id="myg_memnum"><?php echo $select_admin_memnum;?> member(s)</span></div>

        <?php

    }
}

function getOuterFolders($sql_selectgroup){
    while($row_selectgroup=mysqli_fetch_array($sql_selectgroup)){
        $gkey=$row_selectgroup['gkey'];
        $gname=$row_selectgroup['groupname'];
    ?>
    
    <div class="folder_content">
        <a href="<?php echo $gkey;?>">
            <div class="folder_icon">
                <div class="download_icon"></div>  
            </div>
            <div class="folder_info">
                <?php echo $gname;?>
            </div>
        </a>
    </div>
    
    <?php } 
}

function getInnerFolders($con, $content_ingroup, $group_url){
    $group_not_set=0;
    if(empty($group_url)) $group_not_set=1;
    $known_types=array("mp3", "docx", "exe", "ppt", "mp4", "php", "xls", "txt", "jpeg", "html", "avi", "pdf", "gif", "png", "zip", "apk", "rar", "jar", "torrent", "py", "js","psd", "css");
    //$known_types=array("docx", "exe", "gif", "java", "jpg", "jpeg", "log", "odt", "pdf", "php", "png", "psd", "torrent", "txt", "xls", "xml", "zip", "html");
    while($content_ingroup_array=mysqli_fetch_array($content_ingroup)){
        $sizeIcon=65;
        if($group_not_set == 1){
            $sizeIcon=60;
            if(isset($content_ingroup_array['gkey'])) $group_url=$content_ingroup_array['gkey'];
            else $group_url="personal";
        }
        if(isset($content_ingroup_array['fkey'])){
            $fkey_folder=$content_ingroup_array['fkey'];
            $fkey_direct=$group_url."&".$fkey_folder;
            $fkey_name_folder=mysqli_fetch_array(mysqli_query($con, "SELECT fname from folders where fkey = '".$fkey_folder."'"));
            $fkey_name=$fkey_name_folder['fname'];
        ?>
        
        <div class="folder_content">
            <a href="<?php echo $fkey_direct;?>">
                <div class="folder_icon">
                    <div class="delete_content_icon"></div>
                    <div class="download_icon"></div>  
                </div>
                <div class="folder_info">
                    <?php echo $fkey_name;?>
                </div>
            </a>
        </div>

        <?php 
        } else {

            $content_id=$content_ingroup_array['cid'];
            $content_details=mysqli_fetch_array(mysqli_query($con, "SELECT * from content where cid =".$content_id." "));
            $content_name=$content_details['cname'];
            $content_type=$content_details['type'];
            $content_key=$content_details['ckey'];
            if($content_type=='jpg') $content_type='jpeg';

            if(in_array($content_type, $known_types)){
                $indexFormat=array_search($content_type, $known_types)+1;
                $positionx=($indexFormat%5)*$sizeIcon;
                $positiony=intval($indexFormat/5)*$sizeIcon;
            } else {
                $positionx=4*$sizeIcon;
                $positiony=4*$sizeIcon;
            }

        ?>

        <div class="file_contents">
            <a href="<?php echo $content_key;?>">
                <div class="<?php if($content_type == 'pdf') echo 'pdfViewer';?> files_icon" <?php if($content_type!='jpeg' && $content_type!= 'png' && $content_type!='gif') {?> style="background-position: <?php echo "-".$positionx."px -".$positiony."px";?>;"<?php } else { ?> style="background:none" <?php } ?>>
                    <?php if($content_type =='jpeg' || $content_type == 'png' || $content_type =='gif') {?>
                        <img class="pUImgViw" src="OPT_<?php echo $content_key;?>"/>
                    <?php } ?>
                    <div class="delete_content_icon"></div>
                    <div class="download_icon"></div>  
                </div>
                <div class="files_info">
                    <?php echo $content_name;?>
                </div>
            </a>
        </div>

        <?php
        }
    }
}

function getGreqCell($con, $check_greq){
    while($check_greq_array=mysqli_fetch_array($check_greq)){
        $gid_check_req=$check_greq_array['gid'];
        $get_gdetails_query=mysqli_query($con, "SELECT * from groups where groupid = ".$gid_check_req." ");
        $get_gdetails_array=mysqli_fetch_array($get_gdetails_query);
        $get_gdetails_name=$get_gdetails_array['groupname'];
        $get_gdetails_gdesc=$get_gdetails_array['gdesc'];
        $get_gdetails_admin=$get_gdetails_array['adminid'];
        $get_gdetails_key=$get_gdetails_array['gkey'];

        $select_adminname= mysqli_query($con, "SELECT fname, lname from users where uid = ".$get_gdetails_admin." ");
        $select_adminname_array=mysqli_fetch_array($select_adminname);
        $adminname=$select_adminname_array['fname']." ".$select_adminname_array['lname'];
    ?>

    <div id="request_cells">
        <div id="group_name_req">
            <?php echo $get_gdetails_name;?>
        </div>
        <div id="group_req_desc">
            <b>Description: </b> <?php if(empty($get_gdetails_gdesc)) echo '<span class="no_desc_grp">There is no description given</span>'; else echo $get_gdetails_gdesc;?>
        </div>
        <div id="admin_name_req">
            <b>Admin Name: </b><?php echo $adminname; ?>
        </div>
        <div id="select_request">
            <label>
                <input type="hidden" value="<?php echo 'beforeand='.$get_gdetails_key.'&gname='.$get_gdetails_name;?>" id="Accept"/>
                <span>Accept</span>
            </label>
            <label>
                <input type="hidden" value="<?php echo 'beforeand='.$get_gdetails_key.'&gname='.$get_gdetails_name;?>" id="Reject"/>
                <span>Not Interested</span>
            </label>
            <img id="load_group_req_span" src="/images/loading2.gif" width="15px">
        </div>
    </div>

    <?php   }
}

?>