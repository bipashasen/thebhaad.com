<?php

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
include ROOT.'/inc/connect.php';

session_start();
if(isset($_SESSION['user_login']) || isset($_COOKIE['user_login'])){
	header('Location:/');
	exit();
} 
session_write_close();

if(isset($_GET['uname']) && isset($_GET['resetkey']) && !empty(trim($_GET['uname'])) && !empty(trim($_GET['resetkey']))){

	$mainTitle='Password Reset';
	include ROOT.'/inc/guideHeader.php';

	$username=trim(mysqli_real_escape_string($con, $_GET['uname']));
	$resetkey=trim(mysqli_real_escape_string($con, $_GET['resetkey']));

	$resetkeyCheck=mysqli_fetch_array(mysqli_query($con, "SELECT resetkey from users where user_name='".$username."' LIMIT 1"));
	$resetkeyCheck=$resetkeyCheck['resetkey'];

	if($resetkey == $resetkeyCheck) {

		if(isset($_POST['resetPassSubmit'])){
			$resetPass=trim(mysqli_real_escape_string($con, $_POST['recPassword']));
			$resetCPass=trim(mysqli_real_escape_string($con, $_POST['recCPassword']));

			$resetDone=0;
			if(!empty($resetPass) && !empty($resetCPass) && ($resetCPass===$resetPass)){
				if(strlen($resetPass) >= 8){
					$options = ['cost' => 12,];
		     		$password=password_hash($resetPass, PASSWORD_BCRYPT, $options);
					$updatePass=mysqli_query($con, "UPDATE users SET password = '".$password."', resetkey='' where user_name = '".$username."'");
					if($updatePass) $resetDone=1;
				}
			} ?>
			<div id="recoverypasswordWrapper">
				<div id="recpassinwr">
					<h2>Password Reset</h2>
			<?php if($resetDone == 1){?> 
						<div class="recemail">Your password was successfully reset. Go back to <a class="tyrecov" href="/">Log In</a> Page</div>
			<?php } else {?>
						<div class="recemail">Ummm...Sorry, there was some error! How about <a class="tyrecov" href="/recovery">Trying Again?</a></div>
			<?php }?>
				</div>
				<div id="footerrecovery"><a href="/">Log In / Sign Up</a> | <a href="/about">About</a> | <a href="/terms">Terms</a> | <a href="/suggestion">Suggestion</a> | <a href="/help">Help</a> | Copyright &copy; theBhaad.com <?php echo date("Y")?></div>
			</div>

		<?php } else { ?>

			<div id="recoverypasswordWrapper">
				<form id="recpassinwr" method="post" action="">
					<h2>Password Reset</h2>
					<div class="recemail">New Password: <input type="password" id="recPassword" name="recPassword"/></div>
					<div class="recemail">Confirm Password: <input type="password" id="recCPassword" name="recCPassword"/></div>
					<div class="recemailsub"><span class="resetPassError"></span><input type="submit" id="resetPassSubmit" name="resetPassSubmit" value="Reset"/><input type="submit" id="cancelRecov" value="Cancel"/></div>
				</form>
				<div id="footerrecovery"><a href="/">Log In / Sign Up</a> | <a href="/about">About</a> | <a href="/terms">Terms</a> | <a href="/suggestion">Suggestion</a> | <a href="/help">Help</a> | Copyright &copy; theBhaad.com <?php echo date("Y")?></div>
			</div>


			</div>
			</body>
			</html>

		<?php } 
	} else {?>
		<div id="recoverypasswordWrapper">
			<div id="recpassinwr">
				<h2>Password Reset</h2>
				<div class="recemail">Ummm...Sorry, but looks like you got an invalid link. Were you trying to reset your password? How about <a class="tyrecov" href="/recovery">Trying Again?</a></div>
			</div>
			<div id="footerrecovery"><a href="/">Log In / Sign Up</a> | <a href="/about">About</a> | <a href="/terms">Terms</a> | <a href="/suggestion">Suggestion</a> | <a href="/help">Help</a> | Copyright &copy; theBhaad.com <?php echo date("Y")?></div>
		</div>
	<?php } 
} else {
	header("HTTP/1.0 404 Not Found");
	include 'pnp.php';
	exit();
}
?>