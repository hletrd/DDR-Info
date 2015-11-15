<?php
require_once("include.php");

$title = $str['title'];

$style = '';
$content = '';
$mode = 'home';
$include = '';
$script = '';

if (isset($_SESSION['info_id'])) {
	$id = mysql_real_escape_string($_SESSION['info_id']);
	if (isset($_GET['delete'])) {
		mysql_query("DELETE FROM members WHERE userid='$id'");
		unset($_SESSION['info_id']);
		header('Location: /');
		exit();
	}
	$password = $_POST['password'];
	//openssl_private_decrypt(base64_decode($_POST['password']), $password, $_SESSION['privkey']);
	if ($password != '') {
		$result = mysql_query("SELECT * FROM members WHERE userid='$id';");
		$data = mysql_fetch_row($result);
		$pw = explode('$', $data[2]);
		$newpw = $pw[0] . '$' . hash('sha512', $pw[0] . $password);
		mysql_query("UPDATE members SET password='$newpw' WHERE userid='$id';");
	}
	if ($_POST['addr'] !== '') {
		$addr = mysql_real_escape_string($_POST['addr']);
		if (mb_strlen($addr) > 10) {
			$content .= '<h4>' . $str['err_addr_toolong'] . '</h4>';
			goto error;
		} else if (!preg_match('/^[a-zA-Z0-9가-힣ㄱ-ㅎㅏ-ㅣ\-* ]+$/', $addr)) {
			$content .= '<h4>' . $str['err_addr_character'] . '</h4>';
			goto error;
		} else if (mb_strlen($addr) < 2) {
			$content .= '<h4>' . $str['err_addr_tooshort'] . '</h4>';
			goto error;
		} else {
			$result = mysql_query("SELECT * FROM members WHERE addr='$addr';");
			$data = mysql_fetch_row($result);
			if (count($data) >= 2) {
				$content .= '<h4>' . $str['err_duplicate_addr'] . '</h4>';
				goto error;
			} else {
				mysql_query("UPDATE members SET addr='$addr' WHERE userid='$id';");
			}
		}
	}
	if (!isset($_POST['enablelist'])) {
		mysql_query("UPDATE members SET enablelist='0' WHERE userid='$id';");
	} else {
		mysql_query("UPDATE members SET enablelist='1' WHERE userid='$id';");
	}

	$id = mysql_real_escape_string($_SESSION['info_id']);
	$result = mysql_query("SELECT * FROM members WHERE userid='$id';");
	$data = mysql_fetch_row($result);
	$others = unserialize($data[10]);
	if (mb_strlen($_POST['comment']) < 140) {
		$others['comment'] = htmlspecialchars($_POST['comment']);
	}
	$others = serialize($others);
	$others = mysql_real_escape_string($others);
	mysql_query("UPDATE members SET others='$others' WHERE userid='$id';");

	$content .= '<h4>' . $str['myinfo_changed'] . '</h4>';
	error:
} else {
	header('Location: ./_login');
}
require_once('template.php');
?>