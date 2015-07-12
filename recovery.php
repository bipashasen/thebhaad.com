<?php

session_start();
if(isset($_SESSION['user_login']) || isset($_COOKIE['user_login'])){
	header('Location:/');
	exit();
} 
session_write_close();

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
$mainTitle='recovery';
include ROOT.'/inc/guideHeader.php';

?>

<div id="recoverypasswordWrapper">
	<div id="recpassinwr">
		<h2>Password Recovery</h2>
		<div class="recemail">Email Address: <input type="text" name="recEmailInp"/></div>
		<div class="recemailsub"><input type="submit" id="recRecover" value="Recover"/><input type="submit" id="cancelRecov" value="Cancel"/></div>
	</div>
	<div id="footerrecovery"><a href="/">Log In / Sign Up</a> | <a href="/about">About</a> | <a href="/terms">Terms</a> | <a href="/suggestion">Suggestion</a> | <a href="/help">Help</a> | Copyright &copy; theBhaad.com <?php echo date("Y")?></div>
</div>
 
</div>
</body>
</html>
