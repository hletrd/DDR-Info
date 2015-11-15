<?php
require_once("include.php");
$title = $str['title'];

$style = 'div.hidden {
	display: none;
}';
$content = '';
$mode = 'register';
$include = ''; //<script src="jsencrypt.min.js"></script>';

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
		for(var i in a = document.querySelectorAll("#form input")) {
			if (a[i].value == "") {
				alert("' . $str['empty'] . '");
				return false;
			}
		}
		if ($("#password").val() !== $("#password_re").val()) {
			alert("' . $str['err_pwmatch'] . '");
			return false;
		}
		/*var encrypt = new JSEncrypt();
		encrypt.setPublicKey($("#pubkey").html());
		$("#password").val(encrypt.encrypt($("#password").val()));
		$("#password_re").val("");*/
		return true;
	};';


	$content .= '<h3>' . $str['register'] . '</h3><div id="pubkey" class="hidden">' . $pubkey . '</div><div class="spacer15"></div>';
	$content .= '<form id="form" action="./_register_submit" method="POST" onsubmit="return crypt()">';

	$content .= '<div class="row">';
	$content .= '<div class="col-sm-3"><label>' . $str['rival_id'] . '</label></div>';
	$content .= '<div class="col-sm-9"><input name="rid" type="text" class="form-control" placeholder="' . $str['rival_id_desc'] . '" maxlength="20"></div>';
	$content .= '</div><div class="spacer15"></div>';
	$content .= '<div class="row"><div class="col-sm-9 col-sm-offset-3"><a target="_blank" href="./_check_rival_id">' . $str['check_rival_id'] . '</a></div></div><div class="spacer15"></div>';

	$content .= '<div class="row">';
	$content .= '<div class="col-sm-3"><label>' . $str['info_id'] . '</label></div>';
	$content .= '<div class="col-sm-9"><input name="id" type="text" class="form-control" placeholder="' . $str['info_id_desc'] . '" maxlength="20"></div>';
	$content .= '</div><div class="spacer15"></div>';

	$content .= '<div class="row">';
	$content .= '<div class="col-sm-3"><label>' . $str['info_pw'] . '</label></div>';
	$content .= '<div class="col-sm-9"><input id="password" name="password" type="password" class="form-control" placeholder="' . $str['info_pw_desc'] . '"></div>';
	$content .= '</div><div class="spacer15"></div>';

	$content .= '<div class="row">';
	$content .= '<div class="col-sm-3"><label>' . $str['info_pw_re'] . '</label></div>';
	$content .= '<div class="col-sm-9"><input id="password_re" name="password_re" type="password" class="form-control" placeholder="' . $str['info_pw_re_desc'] . '"></div>';
	$content .= '</div><div class="spacer15"></div>';

	$content .= '<div class="row">';
	$content .= '<div class="col-sm-3"><label>' . $str['comment'] . '</label></div>';
	$content .= '<div class="col-sm-9"><input name="comment" type="text" class="form-control" placeholder="' . $str['comment'] . '" maxlength="70"></div>';
	$content .= '</div><div class="spacer15"></div>';

	$content .= '<div class="row">';
	$content .= '<div class="col-sm-3"><label>' . $str['addr'] . '</label></div>';
	$content .= '<div class="col-sm-9"><input name="addr" type="text" class="form-control" placeholder="' . $str['addr_desc'] . '" maxlength="10"></div>';
	$content .= '</div><div class="spacer15"></div>';

	$content .= '<div class="row"><div class="col-sm-3"><label>' . $str['showonlist'] . '</label></div><label class="col-sm-9 toggle"><input name="enablelist" type="checkbox" checked><span class="handle"></span></label>&emsp;<label></label></div>';

	$content .= '<button type="submit" class="btn btn-primary btn-block">' . $str['register'] . '</button></form>';
} else {
	$content .= '<h4>' . $str['alreadylogined'] . '</h4>';
}

require_once('template.php');
?>