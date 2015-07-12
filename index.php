<?php
if(!defined('ROOT')) define('ROOT', $_SERVER['DOCUMENT_ROOT']);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>theBhaad.com - The online repository</title>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="http://www.thebhaad.com/favicon.ico?v=4" />
        <link rel="stylesheet" type="text/css" href="/css/style.css">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1" />
        <link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
        <script src="/js/jquery-1.11.1.min.js"></script>
    </head>
    <body>
        <div class="logIndex_fp" id="main_wrapper">
            <div class="indexUWrap">
                <header id="SignUp_page_logo">
                    <div class="indexHeaderHeaderWrapper mainLogoWrapper">
                        <a class="ancMainLogoInd" href="/">theBhaad.com</a>
                        <span id="tagline">the Intercollegiate Repository.</span></a>
                    </div>
                </header>
                <div class="searchFilesMain">
                    <input type="text" class="mainSrchInp inputInS" placeholder="I command you to search ________ for me!"/>
                    <input type="submit" class="mainSrchInp subInS" value="Search"/>
                </div>
            </div>
            <div class="indexLowerWrap">
                <div class="descWrapperInd">
                    theBhaad.com is a platform to share your notes with the world, recieve best notes from the world, study with the world anaonymously! you won't have to know people to share the notes or get notes from them anymore, just know the right keywords and the best notes are yours...for free. 
                </div>
                <div class="oChWrapper">
                    or <label class="oChHomeP">
                        <a class="oChHomePAnc">Upload</a>
                        <input class="anyupHomeP" type="file"/>
                       </label> or <a class="oChHomePAnc" href="/enter">Sign Up/Log In</a>
                    <div class="termsNotes">( We assume you have already read the <a class="readOnPerks" href="/terms">terms</a> before you upload )</div>
                </div>
                <div class="WhySUHPWrap">
                    <div class="reasonSignUp">
                        <div class="whySUHomeP">What are the perks of me signing up?</div>
                    </div>
                    <div class="ansWhySUHP">
                        <ul class="anULHP">
                            <li>Because, maybe you will want to keep track of the files you upload.</li>
                            <li>Because, maybe, slightly, someday you will want to delete the files you uploaded</li>
                            <li>Because, someday, you will want to have your own personal repository</li>
                            <li>Because, maybe you will want to have groups and share personally with people you know</li>
                            <li>Because, maybe it is not just a file storing space, but a network</li>
                            <li>And because, you love me and because we have more excellent features...<a class="readOnPerks" href="/enter">Read On</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <footer>
                <?php include ROOT.'/inc/footer.php';?>
            </footer>
        </div>

    </body>


</html>