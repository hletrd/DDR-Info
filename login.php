<?php
require_once("include.php");
$title = $str['title'];

$style = 'div.hidden {
	display: none;
}';
$content = '';
$mode = 'login';
$include = ''; //<script src="jsencrypt.min.js"></script>';

if (isset($_GET['logout'])) {
	unset($_SESSION['info_id']);
}

if (!isset($_SESSION['info_id'])) {
	/*$config = array(
		"digest_alg" => "sha512",
		"private_key_bits" => 2048,
		"private_key_type" => OPENSSL_KEYTYPE_RSA,
	);
	$res = openssl_pkey_new($config);
	openssl_pkey_export($res, $privkey);
	$_SESSION['privkey'] = $privkey;
	$pubkey = openssl_pkey_get_details($res);
	$pubkey = $pubkey["key"];
	$_SESSION['pubkey'] = $pubkey;*/

	$script = 'var crypt = function() {
		/*var encrypt = new JSEncrypt();
		encrypt.setPublicKey($("#pubkey").html());
		$("#password").val(encrypt.encrypt($("#password").val()));*/
		return true;
	};'; /*
(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=1558165044466080&version=v2.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));

function login() {
	FB.getLoginStatus(function(response) {
		if (response.status === "connected") {
			console.log(response.authResponse.accessToken);
		}
	});
}';*/


	$content .= '<h3>' . $str['login'] . '</h3><div id="pubkey" class="hidden">' . $pubkey . '</div><div class="spacer15"></div>';
	$content .= '<form id="form" action="./_login_submit" method="POST" onsubmit="return crypt()">';
	$content .= '<div class="row">';
	$content .= '<div class="col-sm-3"><label>' . $str['info_id'] . '</label></div>';
	$content .= '<div class="col-sm-9"><input name="id" type="text" class="form-control" placeholder="' . $str['info_id'] . '" maxlength="20"></div>';
	$content .= '</div><div class="spacer15"></div>';

	$content .= '<div class="row">';
	$content .= '<div class="col-sm-3"><label>' . $str['info_pw'] . '</label></div>';
	$content .= '<div class="col-sm-9"><input id="password" name="password" type="password" class="form-control" placeholder="' . $str['info_pw'] . '"></div>';
	$content .= '</div><div class="spacer15"></div>';

	/*$content .= '<div id="fb-root"></div><div class="row"><div class="col-sm-9 col-sm-offset-3"><div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="false" data-auto-logout-link="false" onlogin="login()"></div></div></div><div class="spacer15"></div>';*/

	$content .= '<button type="submit" class="btn btn-success btn-block">' . $str['login'] . '</button></form>';
} else {
	$content .= '<h4>' . $str['alreadylogined'] . '</h4>';
}


require_once('template.php');
?>