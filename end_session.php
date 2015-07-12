<?php

define('ROOT', $_SERVER['DOCUMENT_ROOT']);

require ROOT.'/inc/connect.php'; //Set connection
session_start();

setcookie('user_login', "", time() - 7600, "/", ".thebhaad.com");

session_destroy();

header("location:./");
exit();
?>