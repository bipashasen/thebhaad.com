<?php
    $profile_view_fullname="";
    $profile_view_profession="";
    $profile_view_school="";
    $profile_view_undergraduate="";
    $profile_view_about="";
    $profile_view_achievement="";
    $profile_view_image="";
    $user_exists=1;

    if (!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);

    include ROOT.'/inc/profile_check.php';
?>

<div id="left_sidebar">
    
    <div id="own_profile_desc_wrapper">
        <div id="profile_picture_wrapper">
            <div id="profile_picture_image">
                <img width="180px" height="180px" src="<?php echo '__'.$profile_view_image;?>"/>
            </div>
        </div>
        <div id="profile_desc">
            <div id="profile_desc_name"><?php echo $profile_view_fullname;?></div>
            <div id="profile_desc_post"><?php echo $profile_view_profession;?></div>
            <div id="profile_desc_extras">
                <?php
                    if(($user_exists==1)&&empty($profile_view_profession)&&empty($profile_view_school)&&empty($profile_view_undergraduate)&&empty($profile_view_about)&&empty($profile_view_achievement))
                        if(isset($username_url)){
                            if($username_url==$username_db)
                                echo"<div>You haven't updated any information about yourself!<br>Go ahead and <a class='to_dark' href='edit'><b>update</b></a> your profile!</div>";
                            else echo"<div>$profile_view_fname haven't updated any information.</div>";
                        } else echo"<div>You haven't updated any information about yourself!<br>Go ahead and <a class='to_dark' href='edit'><b>update</b></a> your profile!</div>";
                ?>
                <?php if($profile_view_school!=""){?> <span><p><?php echo $profile_view_school;?></p></span> <?php }?>
                <?php if($profile_view_undergraduate!=""){?> <span><p><?php echo $profile_view_undergraduate;?></p></span> <?php }?>
                <?php if($profile_view_about!=""){?> <span><p><?php if(!empty($profile_view_about))echo "<b>About:&nbsp;</b>".$profile_view_about;?></p></span> <?php }?>
                <?php if($profile_view_achievement!=""){?> <span><p><?php if(!empty($profile_view_achievement))echo "<b>Bragging rights:&nbsp;</b>".$profile_view_achievement;?></p></span> <?php }?>
            </div>
        </div>
        <aside class="profile_info_edit">
            <?php
                if($user_exists==1){
                    if(isset($username_url)){
                        if($username_url==$username_db)
                            echo"<a href='./edit'>Edit</a><a href='contacts'>View all Contacts</a>";
                        else {
                            echo '<a class="inviteToGrp" href="'.$username_url.'">Invite</a>';
                            buildNewInviteList($con, 0, $limit_data, $username_db);
                            $contact_exist=mysqli_query($con, "SELECT * from contacts where uid=".$uid_db." AND fellowid=".$profile_view_uid."") or die(mysqli_error($con));
                            if(mysqli_num_rows($contact_exist)>0){
                                echo "<a id='addtocontactsanchor_".$username_url."' onclick='removeContact(\"".$username_url."\")'>Remove Contact</a>";
                            } else echo "<a id='addtocontactsanchor_".$username_url."' onclick='addtocontacts(\"".$username_url."\")'>Add to Contacts</a>";
                        }
                    }else echo "<a href='edit'>Edit</a><a href='contacts'>View all Contacts</a>";
                }
            ?>
        </aside>
    </div>
    
    <aside id="NMdetailsHoverWrapper"></aside>
    
    <div id="logged_in_footer">
        <a href="/about">About</a> | <a href="/terms">Terms</a><br><a href="/suggestion">Suggestion</a> |  <a href="/help">Help</a>
        <br><span>Copyright &copy; theBhaad.com.</span>
    </div>
</div>