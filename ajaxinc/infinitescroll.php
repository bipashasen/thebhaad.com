<?php

    include_once '../inc/connect.php';
    include '../inc/functionsFiles.php';
	session_start();

    $uid_db=$_SESSION['user_uid'];
    $username_db=$_SESSION['user_username'];

    if(isset($_REQUEST['username'])){
        $username_show=trim(mysqli_real_escape_string($con, $_REQUEST['username']));

        if(!empty($username_show)){

            $fetch_user_details=mysqli_fetch_array(mysqli_query($con, "SELECT * from users where user_name = '".$username_show."'"));
            $user_image=$fetch_user_details['image'];
            $profile_view_fname=$fetch_user_details['fname'];
            $profile_view_fullname=$profile_view_fname." ".$fetch_user_details['lname'];
            $profile_view_profession=$fetch_user_details['profession'];
            $profile_view_school=$fetch_user_details['school'];
            $profile_view_undergraduate=$fetch_user_details['undergraduate'];
            $profile_view_about=$fetch_user_details['about'];
            $profile_view_uid=$fetch_user_details['uid'];
            $profile_view_achievement=$fetch_user_details['achievement'];
            ?>
            <span id="close_div_NMdetails"></span>
            <div id="profile_picture_wrapper">
                <div id="profile_picture_image">
                    <img width="180px" height="180px" src="<?php echo '__'.$user_image;?>"/>
                </div>
            </div>
            <div id="profile_desc">
                <div id="profile_desc_name"><?php echo $profile_view_fullname;?></div>
                <div id="profile_desc_post"><?php echo $profile_view_profession;?></div>
                <div id="profile_desc_extras">
                    <?php
                        if(empty($profile_view_profession)&&empty($profile_view_school)&&empty($profile_view_undergraduate)&&empty($profile_view_about)&&empty($profile_view_achievement))
                            echo"<div>$profile_view_fname haven't updated any information.</div>";
                        ?>
                    <?php if($profile_view_school!=""){?> <span><p><?php echo $profile_view_school;?></p></span> <?php }?>
                    <?php if($profile_view_undergraduate!=""){?> <span><p><?php echo $profile_view_undergraduate;?></p></span> <?php }?>
                    <?php if($profile_view_about!=""){?> <span><p><?php if(!empty($profile_view_about))echo "<b>About:&nbsp;</b>".$profile_view_about;?></p></span> <?php }?>
                    <?php if($profile_view_achievement!=""){?> <span><p><?php if(!empty($profile_view_achievement))echo "<b>Bragging rights:&nbsp;</b>".$profile_view_achievement;?></p></span> <?php }?>
                </div>
            </div>
            <aside class="profile_info_edit">
                <?php
                    if($username_show == $username_db) {
                        echo"<a href='./edit'>Edit</a><a href='contacts'>View all Contacts</a>";
                    } else {
                        echo '<a class="inviteToGrp" href="'.$username_show.'">Invite</a>';
                        buildNewInviteList($con, 0, $limit_data, $username_db);
                        $contact_exist=mysqli_query($con, "SELECT * from contacts where uid=".$uid_db." AND fellowid=".$profile_view_uid."") or die(mysqli_error($con));
                        if(mysqli_num_rows($contact_exist)>0){
                            echo "<a id='addtocontactsanchor_".$username_show."' onclick='removeContact(\"".$username_show."\")'>Remove Contact</a>";
                        } else echo "<a id='addtocontactsanchor_".$username_show."' onclick='addtocontacts(\"".$username_show."\")'>Add to Contacts</a>";
                    }
                ?>
            </aside>

            <?php
        }
    }

	if(isset($_REQUEST['grouppagenumber']) && !empty($_REQUEST['grouppagenumber'])){
		call_user_func("groupScroll",$con, $limit_data);
	} else if(isset($_REQUEST['beforeand']) && isset($_REQUEST['afterand']) && isset($_REQUEST['pagenumber']) && !empty($_REQUEST['beforeand']) && !empty($_REQUEST['afterand']) && !empty($_REQUEST['pagenumber'])){
		$pagenumber=$_REQUEST['pagenumber'];
		$beforeand=$_REQUEST['beforeand'];
		$afterand=$_REQUEST['afterand'];
		call_user_func("stuffScroll",$con, $limit_data, $beforeand, $afterand, $pagenumber);
	}

    if(isset($_REQUEST['pwam_load_more']) && isset($_REQUEST['pwam_page'])){
        $pagenumber_pwam=trim(mysqli_real_escape_string($con, $_REQUEST['pwam_page']));
        $start_data_pwam=$limit_data_minor*$pagenumber_pwam;

        $pwamcontacts=mysqli_query($con, "SELECT fname, lname, user_name, image, profession, school, undergraduate from users inner join contacts on users.uid=contacts.uid where contacts.fellowid = ".$uid_db." ORDER BY contactid DESC LIMIT ".$start_data_pwam.", ".$limit_data_minor." ");
    
        pwam($pwamcontacts);

        if(mysqli_num_rows($pwamcontacts)<$limit_data_minor)
            echo '<input type="hidden" value="pwam_end" id="pwam_input" />';

    }

    if(isset($_REQUEST['greqMore']) && isset($_REQUEST['pageGreq'])){
        $curr_page_greq=trim(mysqli_real_escape_string($con, $_REQUEST['pageGreq']));
        $start_data_greq=$curr_page_greq*$limit_data_lmc_m;

        $check_greq=mysqli_query($con, "SELECT gid, gkey from usergroups where uid = ".$uid_db." AND active = 0 AND rejected = 0 ORDER BY ugid DESC LIMIT ".$start_data_greq.", ".$limit_data_lmc_m."");
        $check_greq_number=mysqli_num_rows($check_greq);

        getGreqCell($con, $check_greq);

        if($check_greq_number < $limit_data_lmc_m)
            echo '<input type="hidden" value="false" id="gReqEnd"/>';
    }

    if(isset($_REQUEST['addUsermore']) && isset($_REQUEST['pageNumaddUser'])){
        $currAddUser=trim(mysqli_real_escape_string($con, $_REQUEST['pageNumaddUser']));
        $start_data_auser=$currAddUser*$limit_data_minor;

        $add_user_contacts=mysqli_query($con, "SELECT users.user_name, users.lname, users.fname from contacts INNER JOIN users on contacts.fellowid = users.uid where contacts.uid = ".$uid_db." ORDER BY contacts.contactid DESC LIMIT ".$start_data_auser.", ".$limit_data_minor." ");
        addUsersGrp($add_user_contacts);        

        if(mysqli_num_rows($add_user_contacts) < $limit_data_minor)
            echo '<input type="hidden" value="false" id="addUserEnd"/>';
    }

    if(isset($_REQUEST['inviteUsers'])){
        $pagenumInv=trim(mysqli_real_escape_string($con, $_REQUEST['inviteUsers']));
        $start_data_inv=$limit_data*$pagenumInv;

        buildNewInviteList($con, $start_data_inv, $limit_data, $username_db);
    }

	function groupScroll($con, $limit_data){
		$uid_db=$_SESSION['user_uid'];

		$pagenumber=$_REQUEST['grouppagenumber'];
		$start_data=$pagenumber*$limit_data;

		$sql_selectgroup=mysqli_query($con, "SELECT usergroups.gkey as gkey, groups.groupname as groupname from usergroups INNER JOIN groups ON groups.gkey=usergroups.gkey where uid = ".$uid_db." AND active = 1 ORDER BY visitcount DESC LIMIT ".$start_data.", ".$limit_data." ");

        getOuterFolders($sql_selectgroup);

        if(mysqli_num_rows($sql_selectgroup)<$limit_data)
        	echo '<input type="hidden" class="remaining_results" value="false"/>';
	}

	function stuffScroll($con, $limit_data, $beforeand, $afterand, $pagenumber){

		 $uid_db=$_SESSION['user_uid'];
		 $start_data=$pagenumber*$limit_data;
         
         if($beforeand=='personal'){
         	$insertkey="personaluid";
        	$insertvalue=$uid_db;
         } else {
         	$insertkey="parentgid";
         	$query_group=mysqli_query($con, "SELECT groupid from groups where gkey= '".$beforeand."' ");
         	$gid_db=mysqli_fetch_array($query_group);
         	$insertvalue=$gid_db['groupid'];
         }

         if($afterand=='false'){
         	$operator=" is ";
         	$parent_fid=" NULL ";
         } else{
         	$operator=" = ";
         	$parent_fid= $afterand;
         }

		 $content_ingroup=mysqli_query($con,"SELECT * from groupcontent where $insertkey = ".$insertvalue." AND pfid".$operator.$parent_fid." ORDER BY visitcount DESC LIMIT ".$start_data." ,".$limit_data." ") or die(mysqli_error($con));
         $count_folders=mysqli_num_rows($content_ingroup);

         getInnerFolders($con, $content_ingroup, $beforeand);

         if($count_folders<$limit_data)
        	echo '<input type="hidden" class="remaining_results" value="false"/>';
	}

?>