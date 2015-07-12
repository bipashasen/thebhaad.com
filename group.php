<?php

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);

	include ROOT.'/inc/user_cred.php';
    include ROOT.'/inc/form_val_functions.php';
    $check="SELECT lastlogin from users where user_name='".$username_db."'";
    $query=mysqli_query($con, $check);
    $data_row=mysqli_fetch_array($query);
    if(isset($data_row['lastlogin'])===false){
        $current_timestamp_val=mysqli_fetch_array(mysqli_query($con, "SELECT CURRENT_TIMESTAMP as ct_user"));   
        $todayDate=$current_timestamp_val['ct_user'];
        $query_changedate="UPDATE users SET lastlogin='".$todayDate."' where email='".$email_db."'";
        mysqli_query($con,$query_changedate);
    }
    if(($_SERVER['REQUEST_METHOD']=='POST')&&(isset($_POST['submit_group'])) && (!empty($_POST['group_name']))){
        $gname_new=mysqli_real_escape_string($con, $_POST['group_name']);
        $gname_new=trim($gname_new);
        $newgroup_query="insert into groups(groupname, adminid, memnum) VALUES ('$gname_new',$uid_db, 1)";
        mysqli_query($con, $newgroup_query);

        $newid_group=mysqli_insert_id($con);
        $uniquegkey=uniqid().$newid_group;
        $uniquegkey=md5($uniquegkey);
        $updatevisitcount_divide_query="UPDATE usergroups SET visitcount=visitcount/2 where uid=".$uid_db;
        mysqli_query($con, $updatevisitcount_divide_query);

        $keygroup_query="UPDATE groups set gkey='".$uniquegkey."' where groupid= ".$newid_group."";
        mysqli_query($con,$keygroup_query);

        $maxvisit_query="SELECT max(visitcount) as maxvisitcount from usergroups where uid=".$uid_db;
        $maxvisit_result=mysqli_query($con, $maxvisit_query);
        $maxobj=mysqli_fetch_array($maxvisit_result);
        $visitcount_new=$maxobj['maxvisitcount']+50;
        $updateuser_query="insert into usergroups(uid, gid, gkey, visitcount, active, rejected) VALUES ($uid_db, $newid_group, '$uniquegkey', $visitcount_new, 1, 0)";
        if(mysqli_query($con, $updateuser_query)){
           if(!is_dir("../contents/".$uniquegkey))
            mkdir("../contents/".$uniquegkey, 0777, true);
        
            header("Location:".$uniquegkey);
            exit();
        } else die(mysqli_error($con));
    }
    include ROOT.'/inc/header.php';
?>
<div id="main_inner_wrapper">
<div class="totHeight">          
    <?php include ROOT.'/inc/right_sidebar.php';?>
           <div id="main_content">
                <?php include ROOT.'/inc/folders.php';?>
                <?php include ROOT.'/inc/utilities.php';?>
            </div>
            <?php include ROOT.'/inc/left_sidebar.php';?>
        </div>   
    </div>
        </div>
        </body>
    </html>