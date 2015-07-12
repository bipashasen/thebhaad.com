<?php
	
	include '../inc/connect.php';
	if(isset($_REQUEST['pageMem']) && isset($_REQUEST['gurl']) && !empty($_REQUEST['gurl'])){
		$pagenumber_rs=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['pageMem'])));
		$groupmem_rs=trim(stripslashes(mysqli_real_escape_string($con, $_REQUEST['gurl'])));
		$start_data=$pagenumber_rs*$limit_data;
		$members_query=mysqli_query($con, "SELECT users.fname as fname, users.lname as lname, users.user_name as user_name from users JOIN usergroups ON users.uid = usergroups.uid where usergroups.gkey='".$groupmem_rs."' AND usergroups.active =1 LIMIT ".$start_data.",".$limit_data." ");
		while($member_list=mysqli_fetch_array($members_query))
			echo "<div id='".$member_list['user_name']."'><a class='to_dark'>".$member_list['fname']." ".$member_list['lname']."</a></div>";

		if(mysqli_num_rows($members_query) < $limit_data)
			echo '<input type="hidden" value="false" id="endGM"/>';
		
	} else echo 'There was some error please try again!';


?>