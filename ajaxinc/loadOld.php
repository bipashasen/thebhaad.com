<?php 

include '../inc/connect.php';
include '../inc/functionsFiles.php';
session_start();
$uid_db=$_SESSION['user_uid'];
$username_db=$_SESSION['user_username'];

if(isset($_REQUEST['showOldNote'])){

	$pagenum_notes=trim(mysqli_real_escape_string($con, $_REQUEST['showOldNote']));

	$start_data=$pagenum_notes*$limit_data;

	$last_timestamp=$_SESSION['start_timestamp'];

    $notification_query=mysqli_query($con, "SELECT users.fname, users.lname, groups.groupname as gname, notifications.old_name, notifications.fparentkey, notifications.user_name, notifications.type, notifications.gkey, notifications.fkey, DATE_FORMAT(notifications.timenote, '%h:%i %p | %D %M \'%y') as time_stamp_ca from usergroups INNER JOIN notifications ON usergroups.gkey = notifications.gkey INNER JOIN groups ON groups.gkey=notifications.gkey INNER JOIN users ON users.user_name = notifications.user_name where usergroups.uid=".$uid_db." AND notifications.user_name <> '".$username_db."' AND notifications.type <> 'join' AND notifications.timenote <= '".$last_timestamp."' AND usergroups.active=1 ORDER BY notifications.timenote DESC LIMIT ".$start_data.", ".$limit_data." ");

    if(mysqli_num_rows($notification_query) == 0){
        echo '<div id="no_old_notification">No more notifications!</div>';
    } else {

        getNotifications($con, $notification_query, "");

        if(mysqli_num_rows($notification_query) < $limit_data)
        	echo '<div id="no_old_notification">No more notifications!</div>';
    }

}

?>