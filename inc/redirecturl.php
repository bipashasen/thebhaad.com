<?php

$url=htmlspecialchars($_SERVER['REQUEST_URI']);
if(substr_compare($url, "/", strlen($url)-strlen("/"), strlen("/")) === 0){
	$url=rtrim($url, '/');
	if(strpos($url,'group.php')!==false){
		if(ctype_alnum(basename($url))){
			$username_profile=basename($url);
			header("Location:/".$username_profile);
			exit();
			} else {
				header("Location:/".$username_db);
			exit();
		}
	} else if(strpos($url, 'stuffs.php')){
		header("Location:/personal");
		exit();
	} else if(strpos($url,'interm.php')!==false){
		header("Location:/edit");
		exit();
	} else if(strpos($url,'settings.php')!==false){
		header("Location:/settings");
		exit();
	} else if(strpos($url, 'contacts.php')!==false){
		header("Location:/contacts");
		exit();
	} else if(strpos($url, 'archives.php')!==false){
		header("Location:/archives");
		exit();
	} else if(strpos($url, 'posts.php')!==false){
		header("Location:/posts");
		exit();
	} else if(strpos($url, 'help.php')!==false){
		header("Location:/help");
		exit();
	} else if(strpos($url, 'about.php')!==false){
		header("Location:/about");
		exit();
	} else if(strpos($url, 'recovery.php')!==false){
		header("Location:/recovery");
		exit();
	} else if(strpos($url, 'suggestion.php')!==false){
		header("Location:/suggestion");
		exit();
	} else if(strpos($url, 'terms.php')!==false){
		header("Location:/terms");
		exit();
	} else{
		header("Location:".$url);
		exit();
	}
}

if(strpos($url,'group.php')){
	$_SERVER['basename']=basename($url);
	if(ctype_alnum(basename($url))){
		$username_profile=basename($url);
		header("Location:/".$username_profile);
		exit();
	} else {
		header("Location:/".$username_db);
		exit();
	}
} else if(strpos($url, 'stuffs.php')){
	header("Location:/personal");
	exit();
} else if(strpos($url,'interm.php')){
	header("Location:/edit");
	exit();
} else if(strpos($url,'settings.php')){
	header("Location:/settings");
	exit();
} else if(strpos($url, 'contacts.php')){
	header("Location:/contacts");
	exit();
} else if(strpos($url, 'archives.php')){
	header("Location:/archives");
	exit();
} else if(strpos($url, 'posts.php')){
	header("Location:/posts");
	exit();
} else if(strpos($url, 'help.php')!==false){
	header("Location:/help");
	exit();
} else if(strpos($url, 'about.php')!==false){
	header("Location:/about");
	exit();
} else if(strpos($url, 'recovery.php')!==false){
	header("Location:/recovery");
	exit();
} else if(strpos($url, 'suggestion.php')!==false){
	header("Location:/suggestion");
	exit();
} else if(strpos($url, 'terms.php')!==false){
	header("Location:/terms");
	exit();
}

?>