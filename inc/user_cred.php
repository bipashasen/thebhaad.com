<?php 

if (!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);

include_once ROOT.'/inc/connect.php';
session_start();

if(isset($_SESSION['user_login']) || isset($_COOKIE['user_login'])){  
   if(isset($_SESSION['user_login'])){
       if(isset($_SESSION['redirected_from']))
            unset($_SESSION['redirected_from']); 
       $email_db=$_SESSION['user_login'];
       $username_db=$_SESSION['user_username'];
       $uid_db=$_SESSION['user_uid'];
       $lastloginValue = $_SESSION['start_timestamp'];
    } else {
        $cookieAuth=$_COOKIE['user_login'];
        $checkcookieholder=mysqli_query($con, "select * FROM users WHERE rememberme='".$cookieAuth."' LIMIT 1");
        if(mysqli_num_rows($checkcookieholder) > 0){
            $checkcookieholder=mysqli_fetch_array($checkcookieholder);
            $email_db=$_SESSION['user_login']=$checkcookieholder['email'];
            $uid_db=$_SESSION['user_uid']=$checkcookieholder['uid'];
            $username_db=$_SESSION['user_username']=$checkcookieholder['user_name'];
            $_SESSION['start_timestamp']=$checkcookieholder['lastlogin'];
            $_SESSION['last_timestamp']=$checkcookieholder['lastlogin'];
        } else {
            setcookie("user_login", "", time()-7600, "/", ".thebhaad.com");
            $_SESSION['redirected_from']="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            header("Location:./log_in");
            exit();
        }
    }
   $data_db_query="select * FROM users WHERE email = '$email_db'";
   $data_db=mysqli_query($con, $data_db_query);
   while($data_row=mysqli_fetch_array($data_db)){
        $active_db=$data_row['active'];
        $fname_db=$data_row['fname'];
        $lname_db=$data_row['lname'];
        $image_db=$data_row['image'];
        if(isset($data_row['profession']))
            $profession_db=$data_row['profession'];
        if(isset($data_row['school']))
            $school_db=stripslashes($data_row['school']);
        if(isset($data_row['undergraduate']))
            $undergraduate_db=stripslashes($data_row['undergraduate']);
        if(isset($data_row['about']))
            $about_db=stripslashes($data_row['about']);
        if(isset($data_row['achievement']))
            $achievement_db=stripslashes($data_row['achievement']);
        $title=$fname_db." ".$lname_db;
   }
   include ROOT.'/inc/redirecturl.php';
}
else  {
    $_SESSION['redirected_from']="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("Location:./log_in");
    exit();
}
?>
<?php
//echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."<br>";
    include ROOT.'/inc/profile_check.php';
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>
                <?php 
                    if(isset($finalTitle))
                        echo $finalTitle;
                    else if(isset($mainTitle))
                        echo $fname_db." | ".$mainTitle;
                    else echo $title;
                ?>
            </title>
            <meta charset="UTF-8">
            <link rel="stylesheet" type="text/css" href="/ce3b9fef_3ed388765c.css">
            <link rel="stylesheet" href="/css/jquery.Jcrop.min.css" type="text/css" />
            <link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
            <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1" />
            <meta http-equiv="X-UA-Compatible" content="IE=10" />
            <script src="/js/jquery-1.11.1.min.js"></script>
            <script src="/js/jquery.form.min.js"></script>
            <script src="/js/jquery.Jcrop.min.js"></script>
            <script src="/fceddc7720d_52dae7793dc_b22febfee3_m"></script>
            <script src="/0775d1bfe_51bf5a742c_bb78f0a6a781f.js"></script>
            <script src="/59e8e138edc_300caf0743367_7ed626dd.js"></script>
            <noscript>
                <style type="text/css">
                    #noscript_warning{
                        display: block;
                    }
                    #main_bottom_wrapper{
                        padding-top: 68px;
                    }
                </style>
            </noscript>
            <?php
                if($active_db==0)
                    echo '<style type="text/css">
                        #warning_to_activate{
                            display:block;
                        }
                    </style> ';
            ?>
        </head>