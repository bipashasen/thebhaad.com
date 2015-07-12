<?php

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
$mainTitle='suggestions';
include ROOT.'/inc/guideHeader.php';

?>

<div class="ehelp_wrapper" id="about_wrapper">
	<div id="aboutStart">
		<h2>Suggestion</h2>
		<div class="ehelpContainer">
			<ul><li>Wanna give us a feedback?</li> <li>Wanna give us some suggestions?</li> <li>Got some idea about some more features we can add?</li> <li> Found a bug?</li><li>Or just want to write to us?</li></ul> Write to us! 'Cause we love hearing from you! We will try to respond ASAP!<br> 

			<h4>Fill up the following form:</h4>
			<div class="contactUsForm">
				<div><input id="contactInpName" class="contacFormInp contactUsInp" type="text" placeholder="Name"/></div>
				<div><input id="contactInpEmail" class="contacFormInp contactUsInp" type="text" placeholder="Email Address"/></div>
				<div><textarea id="contactInpContent" class="contacFormInp contactUsInp" placeholder="Enter suggestion"></textarea></div>
				<div><input id="contactInpSubmit" type="submit" class="contactFormSubmit contactUsInp" value="Send"/> <span>All the fields are required!</span></div>
			</div>
			OR send us a mail at <b>contact@thebhaad.com</b>. <br><br>
			<div class="moreexprior">
				For more information about <a href="/">theBhaad.com</a>, read our <a href="/help">Help</a>, <a href="/about">About</a> and <a href="/terms">Terms</a> section.
			</div>
		</div>
	</div>
</div>

