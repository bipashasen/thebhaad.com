<?php
session_start();
if(isset($_SESSION['user_login']) || isset($_COOKIE['user_login'])){
    session_write_close();
    include ROOT.'/inc/header.php';
} else {
    include ROOT.'/inc/redirecturl.php';
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>theBhaad.com | <?php echo $mainTitle;?></title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1" />
            <link rel="stylesheet" type="text/css" href="/ce3b9fef_3ed388765c.css">
            <script src="/js/jquery-1.11.1.min.js"></script>
            <script src="/0775d1bfe_51bf5a742c_bb78f0a6a781f.js"></script>
            <script src="/59e8e138edc_300caf0743367_7ed626dd.js"></script>
        </head>
        <body>
        	 <div id="main_wrapper">
                <header id="guideHeader">
                    <a href="./"><b>theBhaad.com</b></a>
                    <span><?php echo $mainTitle;?></span></a>
                </header>

<?php } ?>