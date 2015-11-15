<?php
require_once('config.inc.php');
$page_start = microtime(true);
date_default_timezone_set("Asia/Seoul");
mysql_connect($db_host, $db_user, $db_password);
mysql_select_db($db_name);
mysql_query('SET GLOBAL max_allowed_packet=1073741824;');
mysql_query("SET session character_set_connection=utf8;");
mysql_query("SET session character_set_results=utf8;");
mysql_query("SET session character_set_client=utf8;");
mysql_query("SET time_zone='+9:00';");

session_start();

if (mb_strpos($_SERVER['REQUEST_URI'], '.php') !== false || mb_strpos($_SERVER['REQUEST_URI'], '?') !== false) {
	header('Location: /_404');
	exit();
}

if (!isset($_SESSION['lang_prop'])) {
	if (strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'ko') !== false) {
		$_SESSION['lang_prop'] = 'ko';
	} else if (strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'ja') !== false) {
		$_SESSION['lang_prop'] = 'ja';
	} else {
		$_SESSION['lang_prop'] = 'en';
	}
}

if (isset($_GET['lang'])) {
	if ($_GET['lang'] === 'ya') {
		$_SESSION['lang_prop'] = 'ya';
	} else if ($_GET['lang'] === 'ja') {
		$_SESSION['lang_prop'] = 'ja';
	} else if ($_GET['lang'] === 'en') {
		$_SESSION['lang_prop'] = 'en';
	} else {
		$_SESSION['lang_prop'] = 'ko';
	}
	header('Location: ../');
	exit;
}

$strings = file_get_contents('strings-' . $_SESSION['lang_prop'] . '.ini');
$strings_each = explode("\n", $strings);
foreach($strings_each as $i) {
	if (preg_match('/^ *([a-z_-]+) *= *(.*)$/', $i, $match)) {
		$str[$match[1]] = $match[2];
	}
}
?>
