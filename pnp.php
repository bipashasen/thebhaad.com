<?php
$finalTitle="404 - Page Not Found";

if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);
$mainTitle='404 Not Found';
include ROOT.'/inc/guideHeader.php';
?>

<div id="pnp_wrapper">
	<div id="content_pnp_wrapper">
		Have you listened to 
		<div id="important_pnp">Chandelier by Sia?</div>
		Its a really nice song! BTW its 
		<div id="main_error"><b>404. The page you are trying to find doesn't exist.</b></div>
		<div id="pnp_fap"><b>Do</b> listen to that song!</div>
		or you could
		<div id="back_to_root_link_pnp">
		<?php if(isset($username_db)){?>
			<a href="<?php echo "/".$username_db;?>"><b>Go back to the root</b></a>
		<?php } else {?>
			<a href="/"><b>Go back to the home page</b></a>
		<?php } ?> 
		</div>
	</div>
</div>