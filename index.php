<?php
	session_start();
	ob_start();
	function httpinput($parametr) {
		return htmlspecialchars(htmlspecialchars_decode($_GET[$parametr] ?? ''));
	}
	function redirect($parametr) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$parametr);
		header("Connection: close");
		exit;
	}
	function geturl() {
		if ( !empty( $_SERVER['HTTPS'] ) ) {
			$url = "https://";
		} else {
			$url = "https://";
		}
		$url .= $_SERVER['HTTP_HOST'];
		$request = $_SERVER['REQUEST_URI'];
		$request = explode("/", $request);
		$request[count($request)-1] = false;
		$request = implode("/", $request);
		$url .= $request;
		return $url;
	}
	$url = geturl();
	$filename = basename( $_SERVER['SCRIPT_FILENAME'] );
	if ( !empty( $_SESSION['lang'] ) ) {
		$lang = $_SESSION['lang'];
	} else {
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2);
		$_SESSION['lang'] = $lang;
	}
	$lang_httpinput = httpinput('lang');
	if ( !empty( $lang_httpinput ) ) {
		$lang = $lang_httpinput;
		$_SESSION['lang'] = $lang;
		if ($lang == "cs") {
			redirect("Uvod.html");
		} else {
			redirect("Introduction.html");
		}
	}
	function photo($name, $title, $style) {
		return "<a href=\"img/$name\" title=\"$title\"  rel=\"lightbox\"><img src=\"img/$name\" alt=\"$title\" style=\"$style\" itemprop=\"contentURL\" /></a>\n";
	}
	$page = httpinput("page");
	if (!preg_match('/^[a-zA-Z0-9]+$/', $page)) {
		if ($lang == "cs") {
			$page = "Uvod";
		} else {
			$page = "Introduction";
		}
	}
	$pagepath = "./inc/".$page.".php";
	if (!is_file($pagepath)) {
		redirect("404.html");
	}
	$pagename = str_replace("_", " ", $page);
	echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
		<meta http-equiv="Content-Style-Type" content="text/css" />
  <script src="<?php echo $url; ?>lightbox/js/jquery-1.7.2.min.js"></script>
  <script src="<?php echo $url; ?>lightbox/js/lightbox.js"></script>
  <link href="<?php echo $url; ?>lightbox/css/lightbox.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $url; ?>flags/flags.css" />
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $url; ?>img/kuk.gif" />
		<meta http-equiv="Content-Language" content="cs" />
		<meta name="keywords" content="Pavel Dostál" />
		<title><?php echo $pagename; ?> | Pavel Dostál</title>
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-4280222-9']);
		  _gaq.push(['_trackPageview']);
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
	</head>
	<body>
		<div id="body">
			<div id="header">
        <div id="language"><?php if ($lang == "cs") { ?><a href="?lang=en"><img class="flag" src="<?php echo $url; ?>flags/en.svg" alt="anglická vlajka" /></a><img class="flag" id="selected" src="<?php echo $url; ?>flags/cz.svg" alt="česká vlajka" /><?php } else { ?><img class="flag" id="selected" src="<?php echo $url; ?>flags/en.svg" alt="english flag" /><a href="?lang=cs"><img class="flag" src="<?php echo $url; ?>flags/cz.svg" alt="czech flag" /></a><?php } ?></div>
				<h1><img class="normimg" src="img/motorenovace.png" width="600" height="100" alt="motorenovace" /></h1>
<?php
	if ($lang == "cs") {
?>
				<a href="Uvod.html">Úvod</a>
				<a href="Kontakt.html">Kontakt</a>
				<a href="Foto.html">Fotogalerie</a>
<?php
	} else {
?>
				<a href="Introduction.html">Introduction</a>
				<a href="Contact.html">Contact</a>
				<a href="Galery.html">Galery</a>
<?php
	}
?>
			</div>
<?php
	include($pagepath);
?>
			<div id="footer">
				<hr />
				&copy; <a href="https://pdostal.cz/">Pavel Dostál mladší</a>
			</div>
			<div class="clear"></div>
		</div>
	</body>
</html>
