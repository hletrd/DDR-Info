<?php
require_once("include.php");
$title = $str['title'];

$style = 'div.hidden {
	display: none;
}';
$content = '';
$mode = 'check_rival_id';
$include = ''; //<script src="jsencrypt.min.js"></script>';

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
	/*var encrypt = new JSEncrypt();
	encrypt.setPublicKey($("#pubkey").html());
	$("#id").val(encrypt.encrypt($("#id").val()));
	$("#password").val(encrypt.encrypt($("#password").val()));*/
	return true;
};
$(document).ready(function() {
	$("#id").val("");
});';


$content .= '<h3>' . $str['check_rival_id'] . '</h3><div id="pubkey" class="hidden">' . $pubkey . '</div><p>' . $str['will_be_encrypted'] . '</p>';
$content .= '<form id="form" action="./_check_rival_id_submit" method="POST" onsubmit="return crypt()">';

$content .= '<div class="row">';
$content .= '<div class="col-sm-3"><label>' . $str['ea_id'] . '</label></div>';
$content .= '<div class="col-sm-9"><input id="id" name="id" type="text" class="form-control" placeholder="' . $str['ea_id_desc'] . '" maxlength="20"></div>';
$content .= '</div><div class="spacer15"></div>';

$content .= '<div class="row">';
$content .= '<div class="col-sm-3"><label>' . $str['ea_pw'] . '</label></div>';
$content .= '<div class="col-sm-9"><input id="password" name="password" type="password" class="form-control" placeholder="' . $str['ea_pw_desc'] . '" maxlength="50"></div>';
$content .= '</div><div class="spacer15"></div>';

$content .= '<button type="submit" class="btn btn-primary btn-block">' . $str['check'] . '</button></form>';

require_once('template.php');
?>