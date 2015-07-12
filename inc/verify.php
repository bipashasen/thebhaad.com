<?php

if(isset($_POST['submit_signup'])){

if (!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);

require ROOT.'/inc/form_val_functions.php';
include ROOT.'/inc/mail_func.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 $error = array(0,0,0,0,0);
 if (empty($_POST["fname"])) {
     $totErr = "Please fill in all the details. ";
   } else {
     $fname = test_input($_POST["fname"]);
     if (check_name($fname)===false) {
       $fnameErr = "Doesn't look like a valid name!"; 
     }
     else $error[0]=1;
   }
   
   if (empty($_POST["lname"])) {
     $totErr = "Please fill in all the details.";
   } else {
     $lname = test_input($_POST["lname"]);
     if (check_name($lname)===false) {
       $fnameErr = "Doesn't look like a valid name!"; 
     } else $error[1]=1;
   }

   if($error[1]===1 && $error[0]===1){
    if(check_full_name($fname, $lname) === false){
      $fnameErr = "Doesn't look like a valid name!"; 
      $error[1]=0;
      $error[0]=0;
    }
   }
     
   if (empty($_POST["email-id"])) {
     $totErr = "Please fill in all the details.";
   } else {
     $email = test_input($_POST["email-id"]);
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $emailErr = "The Email is not a valid one!"; 
     } else $error[2]=1;
   }

   if (empty($_POST["password"])) {
     $totErr = "Please fill in all the details.";
   } else {
     $password = test_input($_POST["password"]);
     if (!preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/",$password)) {
       $passErr = "The Password should have at least 8 characters, one upper and one lower case letter and one digit!";
       $checkPass=0;
     }else{$checkPass=1; $error[3]=1;}
   }

   if (empty($_POST["cpassword"])) {
     $totErr = "Please fill in all the details.";
   } else {
     $cpassword = test_input($_POST["cpassword"]);
     if(($cpassword!==$password)&&($checkPass==1)){
       $cpassErr="Passwords do not match.";
     } else $error[4]=1;
   }
   
   if(valOver($error)==1){
    $fname=mysqli_real_escape_string($con,$fname);
    $lname=mysqli_real_escape_string($con,$lname);
    $email=mysqli_real_escape_string($con,$email);
    $password=mysqli_real_escape_string($con,$password);
    $cpassword="";
    $fname=ucwords(strtolower($fname));
    $lname=ucwords(strtolower($lname));

    $search=mysqli_prepare($con,"SELECT email FROM users WHERE email= ?");
    mysqli_stmt_bind_param($search, "s", $email);
    mysqli_stmt_execute($search);
    mysqli_stmt_store_result($search);
    $match=mysqli_stmt_num_rows($search);
    
    if($match>0){
      $totErr="This email already exists!<br> Did you <a class='to_dark' href='#'><b>forget your password?</b></a>";
    }
    else{
      $options = ['cost' => 12,];
      $password=password_hash($password, PASSWORD_BCRYPT, $options);
     //$password=md5($password);
     $newfname = preg_replace('/\s+/', '', $fname);
     $unique_uname=$newfname.$lname;
     $unique_uname=strtolower($unique_uname);
     $select_uname="select * from unames where uname='".$unique_uname."'";
     $rows_unmae=mysqli_query($con,$select_uname);

     if(mysqli_num_rows($rows_unmae)>0){
      while($rows_uname_array=mysqli_fetch_array($rows_unmae))
        $unique_uname_number=$rows_uname_array['namenum']+1;
        mysqli_query($con,"UPDATE unames SET namenum = $unique_uname_number where uname='$unique_uname'");
        $unique_uname=$unique_uname.$unique_uname_number;
     }else mysqli_query($con,"insert into unames (uname, namenum) VALUES ('$unique_uname',0)");

     $setcookiesalt=md5($unique_uname.time());
     setcookie('user_login', $setcookiesalt, time() + (10 * 365 * 24 * 60 * 60), "/", ".thebhaad.com");
        
     $sql="insert into users(fname, lname, user_name, email, password, image, active, rememberme, lastlogin) VALUES (?, ?, ?, ?, ?, 'default.icon', 0, ?, '0000-00-00 00:00:00')";
     $sql=mysqli_prepare($con, $sql);
     mysqli_stmt_bind_param($sql, "ssssss", $fname, $lname, $unique_uname, $email, $password, $setcookiesalt);
     if(mysqli_stmt_execute($sql)){
      $user_id=mysqli_insert_id($con);

      sendConfirmMail($con, $email, $unique_uname, $user_id, $fname, $lname);

      mkdir('../contents/personal__'.$unique_uname);

      $updateUG = mysqli_prepare($con, "UPDATE usergroups SET uid = ?, unreg_email = '' where unreg_email = ?");
      mysqli_stmt_bind_param($updateUG, "is", $user_id, $email );
      mysqli_stmt_execute($updateUG);

      header("Location:./edit");
      exit();
     } else {
       $totErr="Sorry! We couldn't sign you up for some reason. Please try again!";
     }
    } 
   }
}
}
?>