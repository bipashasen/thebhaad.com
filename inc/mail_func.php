<?php

function sendConfirmMail($con, $email, $unique_uname, $user_id, $fname, $lname){
  $_SESSION['user_login']=$email;
  $_SESSION['user_username']=$unique_uname;
  $_SESSION['user_uid']=$user_id;
  $_SESSION['start_timestamp']='0000-00-00 00:00:00';
  $_SESSION['last_timestamp']='0000-00-00 00:00:00';

  $verificationKey=$email.$unique_uname.$user_id;
  $verificationKeymd=md5($verificationKey);

  $to=$email;
  $subjectWelcome='theBhaad.com - Email Confirmation';

  $messageWelcome='<html><body>
    <table style="color:#121212; background:#f1f1f1; font-family:"Segoe UI", Tahoma, sans-serif; padding-top:20px; padding-bottom:30px; padding-left:30px; padding-right:30px;" width="600">

      <tr style="color:#555; font-size:23px;">
        <td align="center">
          <span style="display:block; margin-top:20px; font-family:\'Segoe UI\';">welcome to <b style="font-size:30px;">theBhaad.com</b><br><span style="font-size:15px;">the Online Repository</span></span>
        </td>
      </tr>
      <tr style="font-size:13px;">
        <td align="center">
          <span style="margin-left:30px; margin-right:30px; display:block; margin-bottom: 20px; margin-top:20px; font-family:\'Segoe UI\'; padding-top:10px; color:#363636; border-top:1px solid #bbb;">You have been signed up to <b>theBhaad.com</b> with the following credentials:<span>
          <table style="font-size: 13px; margin-top: 15px;">
            <tr>
              <td style="font-family:\'Segoe UI\';"><b>Name:&nbsp;</b></td>
              <td style="font-family:\'Segoe UI\';">'.$fname.' '.$lname.'</td>
            </tr>
            <tr>
              <td style="font-family:\'Segoe UI\';"><b>Email-Id:&nbsp;</b></td>
              <td style="font-family:\'Segoe UI\';">'.$email.'</td>
            </tr>
            <tr>
              <td style="font-family:\'Segoe UI\';"><b>Username:&nbsp;</b></td>
              <td style="font-family:\'Segoe UI\';">'.$unique_uname.'</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr style="font-size:13px; color:#363636; font-family:\'Segoe UI\';">
        <td align="center">
          <span style="display:block; margin-left:30px; margin-right:30px; font-family: \'Segoe UI\';">You now just need to verify your Email Address by clicking on <b>Verify Email</b> below and be a verified user of our community!</span>
        </td>
      </tr>
      <tr>
        <td align="center">
          <span style="margin-top: 20px; display: block; padding-bottom: 20px;">
            <a target="_blank" href="http://www.thebhaad.com/verifykey.php?verkey='.$verificationKeymd.'" style="font-family: \'Segoe UI\';color: white; text-decoration: none; box-shadow: 0px 0px 7px #888; padding-top: 5px; padding-bottom: 5px; background: rgb(212, 112, 98); padding-left: 15px; padding-right: 15px; border-radius: 3px; border: 1px solid #fff;">Verify Email</a>
          </span>
        </td>
      </tr>
      <tr style="color: #666;">
        <td align="center">
          <span style="margin-left:30px; margin-right:30px; font-size: 12px; font-family: \'Segoe UI\';">or you could copy and paste the following link in your browser\'s address bar :</span>
          <span style=" margin-left:40px; margin-right:40px; font-size: 14px; display:block; margin-top:10px;"><a target="_blank" href="http://www.thebhaad.com/verifykey.php?verkey='.$verificationKeymd.'" style="color:#111; text-decoration:none; font-family: \'Segoe UI\';">http://www.thebhaad.com/verifykey.php?verkey='.$verificationKeymd.'</a></span>
        </td>
      </tr>
      <tr>
        <td align="center">
          <span style="margin-left:30px; margin-right:30px; font-size: 12px; color: #777; display: block; font-family:\'Segoe UI\'; margin-top: 20px; padding-top: 10px; padding-bottom:20px; border-top: 1px solid #ccc;">
            Didn\'t sign up for theBhaad? <a style="color:#555" target="_blank" href="#"><b>Do let us know.</b></a>
          </span>
        </td>
      </tr>

    </table></body></html>';

  $from="theBhaad - the Online Repository <noreply@thebhaad.com>";
  $headerEmail="From: ".$from."\r\n";
  $headerEmail .= "MIME-Version: 1.0\r\n";
  $headerEmail .= "Content-type: text/html\r\n";

  mail($to, $subjectWelcome, $messageWelcome, $headerEmail);
}

function sendmenewmail($con, $from, $name, $message){
  $to='contact@thebhaad.com';
  $subject ='Suggestion from '.$name;
  $header="From: theBhaad - Suggestions <noreply@thebhaad.com>\r\n";
  $header .= "MIME-Version: 1.0\r\n";
  $header .= "Content-type: text/html\r\n";
  $information='<b>Name:</b> '.$name.'<br><b>Email:</b> '.$from.'<br><br>';
  $message=$information.'<b>Message:</b><br>'.$message;

  mail($to, $subject, $message, $header);

  $toThanx=$from;
  $subjectThanx='Thank you for the mail '.$name.' !';
  $headerThanx="From: theBhaad | Greetings <noreply@thebhaad.com>\r\n";
  $headerThanx .= "MIME-Version: 1.0\r\n";
  $headerThanx .= "Content-type: text/html\r\n";
  $messageThanx='Hello <b>'.$name.'</b>! We have recieved your mail! We will try to get back to you ASAP! Till then we hope you are enjoying <b>theBhaad</b> experience.<br>';
  $messageThanx.='Looking Forward to more mails from you!<br><br>Love You!<br>Founder of <b>theBhaad.com</b><br>Bipasha Sen';

  mail($toThanx, $subjectThanx, $messageThanx, $headerThanx);

}

function recoverMail($con, $email){
  $email=mysqli_real_escape_string($con, $email);
  $checkExists=mysqli_query($con, "SELECT user_name, fname from users where email = '".$email."' LIMIT 1");

  if(mysqli_num_rows($checkExists) == 0){
    echo '<div class="recoverError">Sorry! But we do not have any such email registered in here! You might wanna <a href="/">Sign Up?</a> or Try Again.</div>';
  } else {
    $user_name=mysqli_fetch_array($checkExists);
    $fname=$user_name['fname'];
    $user_name=$user_name['user_name'];
    $verifykeyRecover=md5($email.$user_name.uniqid());

    mysqli_query($con, "UPDATE users SET resetkey = '".$verifykeyRecover."' where email = '".$email."'");
    
    $from='theBhaad | Password Reset <noreply@thebhaad.com>';
    $subject='theBhaad.com - Password Reset';
    $message='<html><body>
      <div style="color:#363636; font-family:\'Segoe UI\'; font-size:13px; width:500px;">
        <div style="font-size:23px;">Hello '.$fname.',</div><br>
        We recieved your request for resetting your password. Please ignore this message if it wasn\'t you. If it was indeed you, click on the following link.
        <div>
          <span style="margin-top: 30px; display: block; padding-bottom: 25px;">
            <a target="_blank" href="www.thebhaad.com/recover.php?resetkey='.$verifykeyRecover.'&uname='.$user_name.'" style="color: white; text-decoration: none; padding-top: 5px; padding-bottom: 5px; background: rgb(212, 112, 98); padding-left: 13px; padding-right: 13px; border-radius: 3px; font-size: 12px; font-family: \'Segoe UI\';">Password Reset</a>
          </span>
        </div>
        <div>
          Or, copy-paste the following url to your browser.<br><br>
          <a href="www.thebhaad.com/recover.php?resetkey='.$verifykeyRecover.'&uname='.$user_name.'" style="text-decoration:none; color:rgb(212, 112, 94)">www.thebhaad.com/recover.php?resetkey='.$verifykeyRecover.'&uname='.$user_name.'</a>
        </div>
      </div>
    </body></html>';

    $header="From:".$from."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";

    if(mail($email, $subject, $message, $header)){
      echo '<div class="recoverSuccess">We have sent a mail to <b>'.$email.'</b>, please check your inbox and click on the link we sent you. It might take upto few minutes. Check the spam folder too
      before resending the email. </div>';
    } else {
      echo '<div class="recoverError">Sorry, there was some error, please try again!</div>';
    }

  }
}

function sendInvitationMail($email, $name, $group){
  $message = '<div style="font-family:\'Segoe UI\'; color:#555; font-size: 13px; width:500px; margin-top:20px;">';

 $message .= '<div style="font-size: 15px; padding-bottom: 5px; border-bottom: 1px solid #ddd; margin-bottom: 15px;">';
  $message .= '<span style="font-size: 35px; font-family: \'calibri\'; font-weight: bold;">theBhaad.com</span> - the Online Repository</div>';

  $message .= '<div style="line-height:22px;"><div style="margin-bottom:20px;"><span style="font-size: 25px;">'.$name.',</span> has invited you to join the group <span style="font-size: 20px; font-weight: lighter;">'.$group.'</span> in <span style="font-weight: bold;">theBhaad.com - the online repository.</span> <br><br><b>theBhaad.com</b> is an online repository for you or your entire class/college to store stuffs assignments and printouts, images aranged into folders. </div><div>Click the below link to sign up! </div></div>';

$message .= '<div style="margin-top:20px; margin-left:5px;margin-bottom:20px;"><a style="text-decoration:none; padding-top:5px; padding-bottom:5px; padding-left:10px; padding-right:10px; background:rgb(212, 112, 94); color:#f1f1f1; border-radius:2px; font-size:12px;" href="http://www.thebhaad.com">Sign Up</a></div>';

$message .= '<div>or copy paste the following url to your browser!</div>';

$message .= '<div style="margin-top:10px;"><a style="text-decoration:none; color:rgb(212, 112, 94);" href="http://www.thebhaad.com">http://www.thebhaad.com</a></div>';

$message .= '<div style="font-size: 11px; margin-top: 15px; padding-top: 7px; padding-bottom: 10px; color: #777; border-top: 1px solid #ddd;">';
  $message .= 'Read more about theBhaad.com ';

$message .= '<div><a style="text-decoration:none; color:#555" href="http://www.thebhaad.com/help">Help</a> | <a style="text-decoration:none; color:#555" href="http://www.thebhaad.com/about">About</a> | <a style="text-decoration:none; color:#555" href="http://www.thebhaad.com/suggestion">Suggestion</a> | <a style="text-decoration:none; color:#555" href="http://www.thebhaad.com/terms">Terms</a></div>';
$message .= '</div>';
$message .= '</div>';

$header="From: theBhaad.com | the Online Repository <noreply@thebhaad.com>\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html\r\n";

$subject = 'theBhaad.com | Invitation';

mail($email, $subject, $message, $header);
}

function sendIndMail($email, $fname, $sendername){

$message = getMessageFInv(1, $fname, $sendername);

$header="From: theBhaad.com | the Online Repository <noreply@thebhaad.com>\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html\r\n";

$subject = 'theBhaad.com | Group Invitation';

mail($email, $subject, $message, $header);
}

function sendReqMailForGrp($email, $name, $groupname){

$message =getMessageFInv(2, $name, $groupname);

$header="From: theBhaad.com | the Online Repository <noreply@thebhaad.com>\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html\r\n";

$subject = 'theBhaad.com | Group Invitation';

mail($email, $subject, $message, $header);

}

function getMessageFInv($x, $name, $sendername){
  $message = '<div style="font-family:\'Segoe UI\'; color:#555; font-size: 13px; width:500px; margin-top:20px;">';

  $message .= '<div style="line-height:22px;"><div><div style="font-size: 25px; margin-bottom:10px;">';
  if($x == 1) $message.= 'Hello '.$name.',</div>You have got new group invitations from <b>'.$sendername.'</b>.';
  else $message.= 'Hello '.$name.',</div>You have got new group invitations for the group <b>'.$sendername.'</b>.';
  $message .= '</div><div>Click the below link to log! </div></div>';

$message .= '<div style="margin-top:20px; margin-left:5px;margin-bottom:20px;"><a style="text-decoration:none; padding-top:5px; padding-bottom:5px; padding-left:10px; padding-right:10px; background:rgb(212, 112, 94); color:#f1f1f1; border-radius:2px; font-size:12px;" href="http://www.thebhaad.com">Log In</a></div>';

$message .= '<div>or copy paste the following url to your browser!</div>';

$message .= '<div style="margin-top:10px;"><a style="text-decoration:none; color:rgb(212, 112, 94);" href="http://www.thebhaad.com">http://www.thebhaad.com</a></div>';

$message .= '<div style="font-size: 11px; margin-top: 15px; padding-top: 7px; padding-bottom: 10px; color: #777; border-top: 1px solid #ddd;">';
  $message .= 'Read more about theBhaad.com';

$message .= '<div><a style="text-decoration:none; color:#555" href="http://www.thebhaad.com/help">Help</a> | <a style="text-decoration:none; color:#555" href="http://www.thebhaad.com/about">About</a> | <a style="text-decoration:none; color:#555" href="http://www.thebhaad.com/suggestion">Suggestion</a> | <a style="text-decoration:none; color:#555" href="http://www.thebhaad.com/terms">Terms</a></div>';
$message .= '</div>';
$message .= '</div>';

return $message;
}

?>