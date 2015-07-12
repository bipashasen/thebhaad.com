<?php if(isset($_GET['gkey'])) echo '<div id="sch_res_inner_wrapper"></div>';?>
<div id="<?php if(isset($_GET['gkey'])) echo 'in_folders'; else echo 'folders';?>">
    <div id="folder_content_wrapper">

    <?php if(isset($_GET['gkey'])){

        echo '<input type="hidden" id="beforeand" value="'.$group_url.'">';
        if(isset($_GET['fkey'])) 
            $aftereand=$current_url;
        else $aftereand="false";

        echo '<input type="hidden" id="aftereand" value="'.$aftereand.'">';

        $content_ingroup=mysqli_query($con,"SELECT * from groupcontent where $insertkey = ".$insertvalue." AND pfid".$operator.$parent_fid." ORDER BY visitcount DESC, cid DESC LIMIT ".$limit_data." ");
        $count_folders=mysqli_num_rows($content_ingroup);
        if($count_folders==0){
            echo "<div id='no_content'>There has been no updates in this folder. Start updating by creating folders and uploading contents!</div>";
        } else{
            getInnerFolders($con, $content_ingroup, $group_url);
        }

     } else {?>

        
        <div class="folder_content">
            <a href="personal">
                <div class="folder_icon">
                    <div class="download_icon"></div>  
                    </div>
                <div class="folder_info">
                    Personal
                </div>
            </a>
        </div>
        <?php
            $sql_selectgroup=mysqli_query($con, "SELECT usergroups.gkey as gkey, groups.groupname as groupname from usergroups INNER JOIN groups ON groups.gkey=usergroups.gkey where uid = ".$uid_db." AND active = 1 ORDER BY visitcount DESC LIMIT ".$limit_data." ");

            getOuterFolders($sql_selectgroup);
    } ?>
    </div>
    <div id="end_results">
        There is no more data to be loaded.
    </div>
    <input type="hidden" class="remaining_results" value="true"/>
    
    <div id="loading_result">
        <img src="/images/loading4.gif" width="30px">
    </div>
</div>
