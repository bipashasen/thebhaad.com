<?php

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
include ROOT.'/inc/connect.php';
include_once ROOT.'/inc/functionsFiles.php';

session_start();
$uid_db=$_SESSION['user_uid'];
session_write_close();
$parent_fid="NULL";
$operator=" is ";

if(isset($_GET['gkey'])){
	$group_url=mysqli_real_escape_string($con, $_GET['gkey']);
    if($group_url=='personal'){
        $groupname_db="Personal";
        $mainTitle=$groupname_db;
        $select_personalg_details=mysqli_fetch_array(mysqli_query($con, "SELECT pdesc from users where uid=".$uid_db." LIMIT 1"));
        $gdesc_db=$select_personalg_details['pdesc'];
    } else {
        $selectgroup_query="SELECT * from groups where gkey='".$group_url."'";
        $selectgroup_result=mysqli_query($con, $selectgroup_query);
        $selectgroup_result_count=mysqli_num_rows($selectgroup_result);
        if($selectgroup_result_count>0){
            $groupcontents_db=mysqli_fetch_array($selectgroup_result);
            $groupname_db=$groupcontents_db['groupname'];
            $gid_db=$groupcontents_db['groupid'];
            $adminid_db=$groupcontents_db['adminid'];
            $admin_details_db=mysqli_query($con, "SELECT * from users where uid = ".$adminid_db." ");
            $admin_details_array=mysqli_fetch_array($admin_details_db);
            $admin_fullname_db=$admin_details_array['fname']." ".$admin_details_array['lname'];
            $gdesc_db=$groupcontents_db['gdesc'];
            $member_numbers=$groupcontents_db['memnum'];
            $finalTitle=$groupname_db;
        } else $nosuchgroup="group";
    }
}
if(isset($_GET['fkey']) && !isset($nosuchgroup)){
    $current_url=mysqli_real_escape_string($con, $_GET['fkey']);
    $select_folder_query="SELECT * from folders where fkey='".$current_url."'";
    $select_folder_result=mysqli_query($con, $select_folder_query);
    $select_folder_count=mysqli_num_rows($select_folder_result);
    if($select_folder_count>0){
        $select_folder_arr=mysqli_fetch_array($select_folder_result);
        $foldername_db=$select_folder_arr['fname'];
        $current_fid=$select_folder_arr['fid'];
        $fdesc_db=$select_folder_arr['fdesc'];
        $parentfid_current=$select_folder_arr['parentfid'];
        if(isset($mainTitle)) $mainTitle=$foldername_db;
        else $finalTitle=$foldername_db." | ".$finalTitle;

        if(isset($current_fid)){
            $parent_fid=$current_fid;
            $operator=" = ";
        }
        $update_folder=mysqli_query($con, "UPDATE groupcontent SET visitcount=visitcount+1 where fkey='".$current_url."'");
    } else $nosuchgroup="folder";
}
include ROOT.'/inc/user_cred.php';

if(!isset($nosuchgroup)){
    if($group_url!='personal'){
        $updatevisitcount_query=mysqli_query($con, "UPDATE usergroups SET visitcount=visitcount+1 where uid=".$uid_db." AND gid=".$gid_db." AND active=1");
        $count_rows_right=mysqli_affected_rows($con);
        if($count_rows_right<1)
            $noaccessright=1;
        $insertkey="parentgid";
        $insertvalue=$gid_db;
    } else {
        $insertkey="personaluid";
        $insertvalue=$uid_db;
    }
}

if(isset($_POST['submit_folder'])&&$_SERVER['REQUEST_METHOD']=='POST'&& !empty($_POST['folder_name'])){

    $fname_new=mysqli_real_escape_string($con, $_POST['folder_name']);
    
    $fname_key=createFolder($con, $fname_new, $insertkey, $insertvalue, $operator, $parent_fid, $username_db, $group_url);

    $dir_url=$group_url;
    if($group_url== 'personal')
        $dir_url.="__".$username_db;

    $directory='../contents/'.$dir_url;

    if(isset($_GET['fkey']))
        $directory=getPath($con, $current_url, $directory);

    $directory.="/".$fname_key;
    
    if(!is_dir($directory))
        mkdir($directory, 0777, true);

    mysqli_query($con, "UPDATE folders SET fsrc = '".$directory."' where fkey = '".$fname_key."' ");

    header("Location:/".$group_url."&".$fname_key);
    exit();

}
if(isset($_POST['leave_group'])){
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $leave_group_query=mysqli_query($con, "UPDATE usergroups SET active=0, rejected=1 where uid=".$uid_db." AND gkey='".$group_url."' ");
        if($leave_group_query){
            $update_number_group=mysqli_query($con, "UPDATE groups SET memnum=memnum-1 where gkey='".$group_url."'");
            if($update_number_group){
                $select_admin_group=mysqli_query($con, "SELECT adminid from groups where gkey='".$group_url."'");
                $select_admin_arr=mysqli_fetch_array($select_admin_group);
                $admin_id_group=$select_admin_arr['adminid'];
                if($admin_id_group==$uid_db){
                    $select_new_admin_query=mysqli_query($con, "SELECT uid from usergroups where gkey='".$group_url."' AND active=1 LIMIT 1");
                    if(mysqli_num_rows($select_new_admin_query)>0){
                        $select_new_admin_arr=mysqli_fetch_array($select_new_admin_query);
                        $new_adminid=$select_new_admin_arr['uid'];
                        mysqli_query($con, "UPDATE groups SET adminid=".$new_adminid." where gkey='".$group_url."' ");
                    } else mysqli_query($con, "UPDATE groups SET adminid=0 where gkey= '".$group_url."'");
                } 
                header("Location:".$username_db);
                exit();
            }
        }
    }
}


if(isset($_POST['submit_np_au']) && $_SERVER['REQUEST_METHOD']=='POST'){
    $new_post=trim(mysqli_real_escape_string($con, $_POST['content_post_au']));
    if(!empty($new_post)){

        if(preg_match('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', $new_post))
            $new_post=preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', '<a href="$1" target="_blank">$1</a>', $new_post." ");
        else if(preg_match('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', $new_post))
            $new_post=preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i', '<a href="http://$1" target="_blank">$1</a>', $new_post." ");

        $insert_post=mysqli_query($con, "INSERT into posts (content, gkey, admin_username) VALUES ('".$new_post."', '".$group_url."', '".$username_db."')");
        $post_id=mysqli_insert_id($con);
        $post_key=md5(uniqid().$post_id);

        $update_post=mysqli_query($con, "UPDATE posts SET postkey='".$post_key."' where pid=".$post_id." ");

        if($update_post){
            echo '<script>window.top.location.href="/posts_'.$post_key.'"</script>';
        }
    }
}

$error_upload_fum="";
if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['upload_contents']))
    include ROOT.'/inc/upload.php';


include ROOT.'/inc/header.php';

?>   

<script type="text/javascript">
    $(document).ready(function(){
        var obj = $("#in_folders");
        obj.on('dragenter', function (e) 
        {
            $('#main_content').css({'background':'#DBDBDB'});
            e.stopPropagation();
            e.preventDefault();
        });
        obj.on('dragover', function (e) 
        {
             e.stopPropagation();
             e.preventDefault();
        });
        obj.on('drop', function (e) 
        {
             e.preventDefault();
             $('#main_content').css({'background':''});

            var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;
            var is_opera = navigator.userAgent.toLowerCase().indexOf("op") > -1;
            if ((is_chrome)&&(is_opera)) {is_chrome=false;}

            if(is_chrome || is_opera){
                var files = e.originalEvent.dataTransfer.files;
                handleFileUpload(files,obj,e);
            } else {
                var ddfunav = "Sorry! But the drag-drop file upload feature is currently only available in Chrome and Opera! Don\'t worry! we are working on it!";
                if($('#download_started').length){
                    clearTimeout(iniSetTimeDownAlert);
                    $('#download_started').html(ddfunav);
                } else $('#main_bottom_wrapper').prepend('<div id="download_started">'+ddfunav+'</div>');
                
                iniSetTimeDownAlert=setTimeout(function(){
                    $('#download_started').fadeOut(500);
                    setTimeout(function(){
                      $('#download_started').remove();
                    }, 500)
                }, 3800);
            }
        });
    });

    function handleFileUpload(files,obj,e){
        $('#upload_content_ui').fadeIn(100).animate({'top':'-105px', 'right':'-225px'});
        $('#upload img').data('shown',true);

        $('#upload').css({'opacity':'1','margin':'-5px -5px 0 5px'});
        $('#upload').find('img').css({'width':'110px','height':'110px'});

         var optDirectUpload='<div id="direct_upload_suwrapper">';
         optDirectUpload+='<input type="file" id="upload_inp" class="upload_content_inp" multiple="multiple" name="upload_contents[]"/>';
         optDirectUpload+='<label class="ducl_label upload_content_label" for="upload_inp">Upload Contents! <img src="/images/uploadIcon.png"></label>';
         optDirectUpload+='<div class="ducl_mf_notes makefolder_notes">';
         optDirectUpload+='Select multiple files and upload to this directory...or you could <span class="soupl_mf switch_upl_opt">make a new folder</span> and then upload it over there.';
         optDirectUpload+='</div>';
         optDirectUpload+='</div>';

        $('#upload_content_ui_form_wrapper').html(optDirectUpload);

        var sizeFile = 0;
        var maxSize=100 * 1024 * 1024;

        for (var i = 0; i < files.length; ++i)
            sizeFile += files[i].size;

        if(sizeFile>maxSize) $('.makefolder_notes').append('<div id="uc_error">No more than 100MB at a time!</div>');
        else {
            var inpU = $('#upload_inp');
          $('#uc_error').remove();
          inpU.prop("files", e.originalEvent.dataTransfer.files);
          inpU.parents('#upload_content_ui_form_wrapper').css('padding-bottom', '0').parents('form').submit();
          inpU.siblings('label').remove(); 
          inpU.remove();
          $('.progressBarWrapper').show();
        }
    }
</script>
<div id="main_inner_wrapper">
<div class="totHeight">
        <?php include ROOT.'/inc/right_sidebar.php';?>
            <div id="main_content">
                <?php if(isset($nosuchgroup) || isset($noaccessright)){
                    if(isset($noaccessright)){
                        echo '<div id="no_content"><p>Sorry...but it seems like you don\'t have the right to access this group! If you were a member of this group
                        and if you haven\'t yet deleted this group from your <b><a class="to_dark" href="/archives">archive</a></b>.</p>
                            <a class="to_dark" href='.$username_db.'><b>Go back to the root</b></a></div>';
                    } else {
                        echo '<div id="no_content" style="margin-top:30px;">No such '.$nosuchgroup.' exists! You might wanna create your own '.$nosuchgroup.'.
                            <p><b>Suggestion:</b> You might wanna check the url. There might be some error. Make sure it is correct.</p>
                            <a class="to_dark" href='.$username_db.'><b>Go back to the root</b></a>';
                        if($nosuchgroup=='folder'){ echo '&nbsp;|&nbsp;<a class="to_dark" href='.$group_url.'><b>Go back to the base group.</b></a>';}
                        echo '</div>';
                    }  
                } else { ?>
                	<div id="stuffs_additional_editing">
                		<div id="path">
                            <?php
                                if(isset($_GET['fkey'])){
                                    echo "<span class='current_folder'>".$foldername_db."&nbsp;|</span>";
                                    $parent_search=$parentfid_current;
                                    while($parent_search!==NULL){
                                        $parent_search_query=mysqli_query($con,"SELECT * FROM folders where fid=".$parent_search);
                                        $parent_search_result=mysqli_fetch_array($parent_search_query);
                                        $parent_search=$parent_search_result['parentfid'];
                                        $folder_url=$parent_search_result['fkey'];
                                        $folder_name=$parent_search_result['fname'];
                                        echo "<span><a href=/".$group_url."&".$folder_url.">&nbsp;".$folder_name."&nbsp;|</a></span>";
                                    }
                                    echo "<span><a href=/".$group_url.">&nbsp;".$groupname_db."</a></span>";
                                } else echo "<span class='current_folder'>".$groupname_db."</span>";
                            ?>
                		</div>
                        <div id="addtitional_utilities_wrapper">
                            <div id="additional_utilities">
                                <table>
                                <tr>
                                <td><span id="<?php echo $group_url; ?>"  class="search_inner_SPFO"><input type="text" id="search_inner" autocomplete="off" placeholder="Search..."/><?php if(isset($current_url)) echo '<input type="hidden" class="fkey_sch_inner" id="'.$current_url.'"/>';?></span></td>
                                <?php if($group_url!='personal'){?>
                                    <td id="add_group_wrapper">
                                        <span id="add_group">
                                            <span id="paste_post_span">
                                                <img src="/images/discussion.png" id="paste_post_img" title="Paste New Post" width="20px" height="20px"> 
                                                <div id="paste_post_wrapper">
                                                    <form method='post' action='' target='newpost_au' onsubmit='return newpost()'>
                                                        <div id="paste_new_post_h">Paste New Post</div>
                                                        <div id="post_details">
                                                            <textarea name="content_post_au" id="content_post_au" placeholder="Enter Content Here..."></textarea>
                                                        </div>
                                                        <div id="ex_inf_pp_au">Paste a new post and start discussion for this group!</div>
                                                        <div id="paste_post_submit">
                                                            <input type="submit" name='submit_np_au' value="Paste"/>
                                                        </div>
                                                    </form>
                                                    <iframe id="newpost_au" name="newpost_au"></iframe>
                                                </div>
                                            </span>
                                            <span id="discussion_group">
                                                <a href='/<?php echo $group_url;?>&posts'><img src="/images/gdiscuss.png" id="gdiscuss_img" title="Group Posts" width="20px" height="20px"></a>
                                            </span>
                                            <span id="add_users_wrapper_span">
                                                <img src="/images/add_user.png" id="add_users_img" title="Add users" width="20px" height="20px">
                                                <div id="add_users_main">
                                                    <div id="add_users_header">Add users</div>
                                                    <div id="add_users_wrapper">
                                                        <div id="put_username">
                                                            <textarea></textarea>
                                                            <div id="info_add_users_group">
                                                                Seperate the Usernames/Email-id with a comma ( , ) for more than one contact.<br>Ex. katnisseverdeen, harrypotter, galehawthrone@gmail.com
                                                            </div>
                                                        </div>
                                                        <div id="your_contacts_add_users">
                                                            <div id="your_conts_add_users_heading">Your contacts:<span><img src="/images/loading5.gif" width="10px"></span></div>
                                                            <div id="contacts_list_add_users">
                                                                <?php
                                                                    $add_user_contacts=mysqli_query($con, "SELECT users.user_name, users.lname, users.fname from contacts INNER JOIN users on contacts.fellowid = users.uid where contacts.uid = ".$uid_db." ORDER BY contacts.contactid DESC LIMIT ".$limit_data_minor." ");
                                                                    if(mysqli_num_rows($add_user_contacts)>0){
                                                                            addUsersGrp($add_user_contacts);
                                                                        ?>
                                                                        </div>
                                                                        <input type="hidden" id="add_users_beforeand" value="<?php echo $group_url;?>"/>
                                                                        <div id="add_users_submit"><input id="add_users_input" value="Add Users" class="add_users_submit_inner" type="submit"/></div>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <div id="no_contacts_to_add">
                                                                            You do not have any contacts in your contacts list.
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" id="add_users_beforeand" value="<?php echo $group_url;?>"/>
                                                                    <div id="add_users_submit">
                                                                        <input id="add_users_input" value="Add Users" class="add_users_submit_inner" type="submit"/> | <a href="/contacts" target="_blank" class="add_users_submit_inner">Add contacts</a>
                                                                    </div>
                                                                    <?php
                                                                    }
                                                                    if(mysqli_num_rows($add_user_contacts) < $limit_data_minor)
                                                                        echo '<input type="hidden" value="false" id="addUserEnd"/>';
                                                                ?>
                                                        </div>                                                        
                                                    </div>
                                                </div>
                                            </span>
                                            <span id="leave_group_span">
                                                <img id="leave_group_img" src="/images/exit_group.png" title="Leave group" width="20px" height="20px">
                                                <div id="leave_group_main">
                                                    Do you wanna leave this group? You will find this group in your archives once you leave this group.
                                                    <div id="leave_group_input_wrappe">
                                                        <form id="leave_group_form" method="POST" action="">
                                                            <input type="submit" name="leave_group" value="Yes"/>
                                                        </form>
                                                        <input type="submit" value="No"/>
                                                    </div>
                                                </div>
                                            </span>
                                        </span>
                                    </td>
                                <?php } ?>
                                </tr>
                                </table>
                            </div>  
                        </div>
                	</div>
                    <?php if(isset($_SESSION['error_upload_fum'])){
                        unset($_SESSION['error_upload_fum']);
                     ?>
                    <div class="eu_fum_fadeo"><div class="eu_fum_fadeo_iw"><div>Sorry! but some files could not be uploaded.</div><div><b>NOTE:</b> No more than 500 MB can be uploaded
                    in the same group. Delete the things that are not needed and clean it up! At a time, maximum 100 MB can be uploaded!</div></div></div>
                    <?php } ?>

                    <?php include ROOT.'/inc/folders.php';?>

                <?php } ?>

                <?php include ROOT.'/inc/utilities.php';?>
            </div>
            <?php include ROOT.'/inc/left_sidebar.php';?>
        </div>   
    </div>
        </div>
        </body>
    </html>