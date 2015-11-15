<?php
require_once("include.php");

$title = $str['title'];

$style = '';
$content = '';
$mode = 'home';
$include = '';
$script = '';

if (!isset($_SESSION['info_id'])) {
	$id = mysql_real_escape_string($_POST['id']);
	$result = mysql_query("SELECT * FROM members WHERE userid='$id';");
	$data = mysql_fetch_row($result);
	if (count($data) <= 2) {
		$content .= '<h3>' . $str['err_login_failed'] . '</h3>';
	} else {
		$password = $_POST['password'];
		$pw = explode('$', $data[2]);
		if (hash('sha512', $pw[0] . $password) === $pw[1]) {
			$_SESSION['info_id'] = $_POST['id'];
			header('Location: ./' . $data[4]);
		} else {
			$content .= '<h4>' . $str['err_login_failed'] . '</h4>';
		}
	}
}

require_once('template.php');
?>