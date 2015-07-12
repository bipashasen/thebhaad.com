<?php
$chechPass=0;
$fnameErr = $emailErr = $passErr = $totErr= $cpassErr=$login_error="";
$fname = $lname = $email = $password = $cpassword =$login_email=$login_password= "";
//ini_set('display_errors',1);
//error_reporting(-1);
if (!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);

include ROOT.'/inc/login.php';
include ROOT.'/inc/verify.php';
?>
<div id="forms">
    <div id="wtb">
        <img src="/featuresscrnshot/thebhaadTour.png">
    </div>
<div id="login_form_wrapper">
    <form id="login_form" method="post" action="">
       <input id="login_fields_em_li" class="login_fields" name="login_email" value="<?php echo $login_email;?>" type="text" placeholder="Email Id"/><br>
        <input class="login_fields" name="login_password" value="<?php echo $login_password;?>" type="password" placeholder="Password"/><br>
        <div id="marg_req">
                <input id="remember_me" name="kmlil" type="checkbox"  checked/>
                <label for="remember_me" id="remember_me_span">Let me stay logged in!<!--Remember me!--></label>
        </div>
        <div class="LogIForSU"><input id="submit_fields" type="submit" name="submit_logIn" value="Log In"/><span id="subnotLoginID" class="subnotLogin"> or <span class="sureqLogin">Sign Up</span></span></div>
        <div id="forgot_pass_login"><a href="/recovery">Can't recall password?</a></div>
    </form>
    <div id="error_disp_signup">
        <div id="login_error"><?php echo $login_error;?></div>
    </div>
    <div id="login_suggest">
        <p>
            Check the <b>Let me stay logged in!</b> button for instant access if accessing from a personal computer.
            <br><br>
            Not a member yet? Dont worry, just <b>Sign Up</b> in just one light step by filling the five simple fields
            and stay updated!
        </p>
    </div>
    <div class="logsuggreadmoreindex"><a class="scrollTNDfeatFPIN" href="#featcwff">Read more</a></div>


    <div id="signup_form_wrapper">
        <form id="signup_form" onsubmit="return checkForm()" method="post" action="">
            <div id="name">
                <input id="fname" name="fname" class="name_login_field login_fields" type="text" value="<?php echo $fname ?>" placeholder="First Name" onchange="valFields('fname','lfname')" onblur="valIfEmpty('fname')"/>
                <input id="lname" name="lname" class="name_login_field login_fields" type="text" value="<?php echo $lname ?>" placeholder="Last Name" onchange="valFields('lname','lfname')" onblur="valIfEmpty('lname')"/>
            </div>
            <input class="login_fields" type="text" name="email-id" value="<?php echo $email ?>" placeholder="Email Id" onchange="valFields('email-id','lemail')" onblur="valIfEmpty('email-id')"/><br>
            <input class="login_fields" type="password" name="password" value="<?php echo $password ?>" placeholder="Password" onchange="valFields('password','lpass')" onblur="valIfEmpty('password')"/><br>
            <input class="login_fields" type="password" name="cpassword" value="<?php echo $cpassword ?>" placeholder="Confirm Password" onchange="valFields('cpassword','lcpass')" onblur="valIfEmpty('cpassword')"/><br>
            <div id="signup_suggest">
                <p>
                    By signing up, you agree to our <a href="/terms"><b>Terms</b></a>.
                </p>
            </div>
            <div class="LogIForSU"><input id="submit_fields" type="submit" name="submit_signup" value="Sign Up"/><span id="subnotSigninID" class="subnotLogin"> or <span class="sureqLogin">Log In</span></span></div>
        </form>
        <div id="error_disp_signup">
            <div id="totError"><?php echo $totErr;?></div>
            <div id="lfname"><?php echo $fnameErr;?></div>
            <div id="lemail"><?php echo $emailErr;?></div>
            <div id="lpass"><?php echo $passErr;?></div>
            <div id="lcpass"><?php echo $cpassErr;?></div>
        </div>
    </div>


</div>
</div>