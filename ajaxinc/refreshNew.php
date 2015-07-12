<?php

    include '../inc/connect.php';
    include '../inc/functionsFiles.php';
    include '../inc/form_val_functions.php';
	session_start();

    if(isset($_SESSION['user_username'])){
    	$username_db=$_SESSION['user_username'];
    	$uid_db=$_SESSION['user_uid'];

    	if(isset($_REQUEST['notifications']) && $_REQUEST['notifications']=='true'){

    		$notification_query=mysqli_query($con, "SELECT users.fname, users.lname, groups.groupname as gname, notifications.old_name, notifications.fparentkey, notifications.user_name, notifications.type, notifications.gkey, notifications.fkey, DATE_FORMAT(notifications.timenote, '%h:%i %p | %D %M \'%y') as time_stamp_ca from usergroups INNER JOIN notifications ON usergroups.gkey = notifications.gkey INNER JOIN groups ON groups.gkey=notifications.gkey INNER JOIN users ON users.user_name = notifications.user_name where usergroups.uid=".$uid_db." AND notifications.user_name <> '".$username_db."' AND notifications.timenote > (NOW() - INTERVAL 5 SECOND) AND usergroups.active=1 ORDER BY notifications.timenote DESC") or die(mysqli_error($con));

    		$contact_notification_query=mysqli_query($con, "SELECT users.fname as fname, users.lname as lname, users.user_name as user_name, DATE_FORMAT(contacts.timeadd, '%h:%i %p | %D %M \'%y') as time_stamp_ca from contacts INNER JOIN users ON contacts.uid = users.uid where contacts.fellowid=".$uid_db." AND contacts.timeadd > (NOW() - INTERVAL 5 SECOND) ORDER BY contacts.timeadd DESC ");

            $cnq_rows=mysqli_num_rows($contact_notification_query);

            if(mysqli_num_rows($notification_query) + $cnq_rows > 0){
                getNotifications($con, $notification_query, $contact_notification_query);
            }
    	}
    }

    if(isset($_REQUEST['postsHome']) && $_REQUEST['postsHome']=='true'){
        $select_posts_query=mysqli_query($con, "SELECT users.user_name, users.image, users.fname, users.lname, posts.*, DATE_FORMAT(posts.main_timepost, '%h:%i %p | %D %M \'%y') as timepost from posts INNER JOIN users ON posts.admin_username=users.user_name INNER JOIN usergroups ON usergroups.gkey=posts.gkey where usergroups.uid=".$uid_db." AND usergroups.active=1 AND posts.main_timepost > (NOW() - INTERVAL 5 SECOND) ORDER by posts.main_timepost DESC LIMIT ".$limit_data_minor." ");
        getPostsHome($con, $select_posts_query);

    }

    if(isset($_REQUEST['countPH']) && $_REQUEST['countPH']){
        $countPH=mysqli_fetch_array(mysqli_query($con, "SELECT count(*) as countPH from posts, usergroups where main_timepost>(NOW() - INTERVAL 5 SECOND) AND posts.gkey = usergroups.gkey AND usergroups.uid = ".$uid_db." AND usergroups.active = 1"));
        echo json_encode($countPH['countPH']);
    }

    if(isset($_REQUEST['newReq']) && $_REQUEST['newReq']=='true'){
        $check_greq=mysqli_query($con, "SELECT gid, gkey from usergroups where uid = ".$uid_db." AND active = 0 AND rejected = 0 AND timegreq > (NOW() - INTERVAL 5 SECOND) ORDER BY ugid ") or die(mysqli_error($con));
        
        getGreqCell($con, $check_greq);
    }

    if(isset($_REQUEST['countReq']) && $_REQUEST['countReq'] == 'true'){
        $count_req=mysqli_fetch_array(mysqli_query($con, "SELECT count(*) as cound_req from usergroups where uid = ".$uid_db." AND active = 0 AND rejected = 0 AND timegreq > (NOW() - INTERVAL 5 SECOND)"));
        
        echo json_encode($count_req['cound_req']);

    }

?>