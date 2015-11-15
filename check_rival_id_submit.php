<?php
require_once("include.php");
$title = $str['title'];

$style = '';
$content = '';
$mode = 'home';
$include = '';
$script = '';


	$password = $_POST['password'];
	$id = $_POST['id'];
	$req = curl_init('https://p.eagate.573.jp/gate/p/login.html');
	curl_setopt($req, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($req, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($req, CURLOPT_COOKIESESSION, true);
	curl_setopt($req, CURLOPT_COOKIEJAR, 'cookie2');
	curl_setopt($req, CURLOPT_COOKIEFILE, 'cookie2');
	curl_setopt($req, CURLOPT_USERAGENT, $ua);
	curl_setopt($req, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($req, CURLOPT_HEADER, true);
	curl_setopt($req, CURLOPT_POST, TRUE);
	curl_setopt($req, CURLOPT_POSTFIELDS, 'OTP=&KID=' . $id . '&pass=' . $password);
	curl_exec($req);
	curl_setopt($req, CURLOPT_URL, 'http://p.eagate.573.jp/game/ddr/ac/p/playdata/index.html');
	$data = curl_exec($req);
	preg_match('/<tr><th>DDR-CODE<\/th><td>([^<]+)/', $data, $match);
	if ($match[1] == '') {
		$content .= '<h4>' . $str['err_no_account'] . '</h4>';
	} else {
		$content .= '<h4>' . $match[1] . '</h4>';
	}
if (mb_strpos($content, 'complete') === false) {
	$content .= '<a href="#" onclick="history.go(-1)" class="btn btn-primary">' . $str['back'] . '</a>';
}

require_once('template.php');
?>