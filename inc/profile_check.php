<?php

if(isset($_GET['u'])){
    $username_url=mysqli_real_escape_string($con, $_GET['u']);
    if(ctype_alnum($username_url)){
        $check=mysqli_query($con,"SELECT * from users where user_name='$username_url'");
        if(mysqli_num_rows($check)==1){
            $user_exists=1;
            while($get=mysqli_fetch_array($check)){
                $profile_view_uid=$get['uid'];
                $profile_view_fname=$get['fname'];
                $profile_view_lname=$get['lname'];
                $profile_view_image=$get['image'];
                $profile_view_fullname=$profile_view_fname." ".$profile_view_lname;
                if(isset($get['profession']))
                	$profile_view_profession=$get['profession'];
                if(isset($get['school']))
                	$profile_view_school=stripslashes($get['school']);
                if(isset($get['undergraduate']))
                	$profile_view_undergraduate=stripslashes($get['undergraduate']);
                if(isset($get['about']))
                	$profile_view_about=stripslashes($get['about']);
                if(isset($get['achievement']))
                	$profile_view_achievement=stripslashes($get['achievement']);
                $title=$profile_view_fullname;
            }
        }
        else{
            $user_exists=0;
            $profile_view_image="default.icon";
            $profile_view_fullname="User doesn't exist";
        }
    }
} else {
    $profile_view_fname=$fname_db;
    $profile_view_lname=$lname_db;
    $profile_view_image=$image_db;
    $profile_view_fullname=$profile_view_fname." ".$profile_view_lname;
    if(isset($profession_db))
        $profile_view_profession=$profession_db;
    if(isset($school_db))
        $profile_view_school=$school_db;
    if(isset($undergraduate_db))
        $profile_view_undergraduate=$undergraduate_db;
    if(isset($about_db))
        $profile_view_about=$about_db;
    if(isset($achievement_db))
        $profile_view_achievement=$achievement_db;
}
?>