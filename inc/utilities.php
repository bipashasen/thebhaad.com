<div id="utilities_wrapper">
    <div id="utilities">
        <div id="icons">
            <aside id="plus_create_group">
                <span id="close_div"></span>
                <div id="create_group_form_wrapper">
                    <form id="create_group_form" method="post" action="">
                         <?php if(isset($_GET['gkey'])){ ?>
                            <input id="group_name" type="text" name="folder_name" placeholder="Name your folder..."/>
                            <span id="info_create_grp">Just write the name of the folder to create one
                            and then start customizing it!</span>
                            <input id="create_group_submit" type="submit" name="submit_folder" value="Create folder"/>
                        <?php } else { ?>
                            <input id="group_name" type="text" name="group_name" placeholder="Name your group..."/>
                            <span id="info_create_grp">Just write the name of the group and create it. Later you can add
                            members or keep it for yourself.</span>
                            <input id="create_group_submit" type="submit" name="submit_group" value="Create group"/>
                        <?php } ?>
                    </form> 
                </div>
            </aside>
            <div id="plus">
                <img src="/images/plus.png" atl="plus" width="90px" height="90px">
            </div>
            <div id="notifications">
                <img src="/images/notification.png" atl="plus" width="90px" height="90px">
            </div>
            <?php
                $count_notes=mysqli_fetch_array(mysqli_query($con, "SELECT count(*) as countnotes from posts INNER JOIN usergroups ON usergroups.gkey=posts.gkey where usergroups.uid=".$uid_db." AND usergroups.active=1 AND main_timepost > '".$_SESSION['last_timestamp']."'"));
                $count_posts=$count_notes['countnotes'];
            ?>
            <span <?php if($count_posts==0) echo 'style="display:none"' ?> id="noti_numbers"> <?php echo '<a>'.$count_posts.'</a>';?></span>
            <?php
                if(isset($_GET['gkey'])){
            ?>
            <aside id="upload_content_ui">
                <span id="close_div_upload"></span>
                <form class="con_upload" enctype="multipart/form-data" action="" method="post">
                    <div id="upload_content_ui_form_wrapper">
                        <div id="option_upload_wrapper">
                            <div id="make_folder_wrapper" class="upload_opt_wrapper">Make Folder</div>
                            <div id="direct_upload_wrapper" class="upload_opt_wrapper">Direct Upload</div>
                        </div>
                        <div id="error_upload_fum"></div>
                    </div>
                </form>
                 <!-- progress bar -->
                  <div class="progressBarWrapper">
                    <div class="progressWrapper">
                        <div class="progressBar">
                        </div >
                   </div>
                   <div>Don't reload the page!<span id="pDonecent">0%</span></div>
               </div>
            </aside>
            <div id="upload">
                <img id="upload_image" src="/images/upload.png" atl="plus" width="90px" height="90px">
            </div>
            <?php
            } else {
            ?>
            <div id="inactive_upload">
                <img id="inactive_upload_image" src="/images/inactive_upload.png" alt="plus" width="90px" height="90px">
            </div>
            <?php }?>
        </div>
        <div id="underline_icons"></div>
    </div>
</div>