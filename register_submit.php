<?php
require_once("include.php");
mysql_query("CREATE TABLE members(id INT NOT NULL auto_increment, userid TEXT NOT NULL, password TEXT NOT NULL, rid TEXT NOT NULL, addr TEXT NOT NULL, recent MEDIUMTEXT NOT NULL, DATA LONGTEXT NOT NULL, enablelist BOOLEAN NOT NULL, lastp DATETIME NOT NULL, lastr DATETIME NOT NULL, PRIMARY KEY (id));");
mysql_query("ALTER TABLE members CONVERT TO CHARSET utf8;");

$title = $str['title'];

$style = '';
$content = '';
$mode = 'home';
$include = '';
$script = '';

if (!isset($_SESSION['info_id'])) {
	/*if (!isset($_SESSION['privkey'])) {
		$content .= '<h4>' . $str['sessionexpired'] . '</h4>';
	} else {*/
		$password = $_POST['password'];
		$id = $_POST['id'];
		$rid = $_POST['rid'];
		$addr = $_POST['addr'];
		if (mb_strlen($addr) > 10) {
			$content .= '<h4>' . $str['err_addr_toolong'] . '</h4>';
		} else if (mb_strlen($rid) > 20) {
			$content .= '<h4>' . $str['err_rid_toolong'] . '</h4>';
		} else if (!preg_match('/^[0-9]+$/', $rid)) {
			$content .= '<h4>' . $str['err_rid_onlynum'] . '</h4>';
		} else if (mb_strlen($id) > 20) {
			$content .= '<h4>' . $str['err_id_toolong'] . '</h4>';
		} else if (mb_strlen($id) < 4) {
			$content .= '<h4>' . $str['err_id_tooshort'] . '</h4>';
		} else if (!preg_match('/^[a-zA-Z0-9가-힣ㄱ-ㅎㅏ-ㅣ\-* ]+$/', $addr)) {
			$content .= '<h4>' . $str['err_addr_character'] . '</h4>';
		} else if (mb_strlen($addr) < 2) {
			$content .= '<h4>' . $str['err_addr_tooshort'] . '</h4>';
		} else if (!preg_match('/^[a-z0-9A-Z가-힣ㄱ-ㅎㅏ-ㅣ]+$/', $id)) {
			$content .= '<h4>' . $str['err_id_character'] . '</h4>';
		} else {
			$req = curl_init('https://p.eagate.573.jp/gate/p/login.html');
			curl_setopt($req, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($req, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($req, CURLOPT_COOKIESESSION, true);
			curl_setopt($req, CURLOPT_COOKIEJAR, 'cookie');
			curl_setopt($req, CURLOPT_COOKIEFILE, 'cookie');
			curl_setopt($req, CURLOPT_USERAGENT, $ua);
			curl_setopt($req, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($req, CURLOPT_HEADER, true);
			curl_setopt($req, CURLOPT_POST, TRUE);
			curl_setopt($req, CURLOPT_POSTFIELDS, 'OTP=&KID=' . $konami_id . '&pass=' . $konami_pass);
			curl_exec($req);
			curl_setopt($req, CURLOPT_URL, 'http://p.eagate.573.jp/game/ddr/ac/p/rival/rival_musicdata_single.html?rival_id=' . $rid);
			$data = curl_exec($req);
			if (strpos($data, '<h1 id="error_top">') !== false) {
				$content .= '<h4>' . $str['err_rid_invalid'] . '</h4>';
			} else {
				$result = mysql_query("SELECT * FROM members WHERE userid='$id';");
				$data = mysql_fetch_row($result);
				if (count($data) >= 2) {
					$content .= '<h4>' . $str['err_duplicate_id'] . '</h4>';
				} else {
					$result = mysql_query("SELECT * FROM members WHERE addr LIKE '$addr';");
					$data = mysql_fetch_row($result);
					if (count($data) >= 2) {
						$content .= '<h4>' . $str['err_duplicate_addr'] . '</h4>';
					} else {
						$result = mysql_query("SELECT * FROM members WHERE rid='$rid';");
						$data = mysql_fetch_row($result);
						if (count($data) >= 2) {
							$content .= '<h4>' . $str['err_duplicate_rid'] . '</h4>';
						} else {
							$rand_base = 'abcdefghijklmnopqrstuvwxyz0123456789';
							$salt = '';
							for($i = 0; $i < 40; $i++) $salt .= $rand_base[rand(0, 35)];

							$password = $salt . '$' . hash('sha512', $salt . $password);
							$id = mysql_real_escape_string($id);
							$rid = mysql_real_escape_string($rid);
							$addr = mysql_real_escape_string($addr);
							if (mb_strlen($_POST['comment']) < 140) {
								$others['comment'] = htmlspecialchars($_POST['comment']);
							}
							$others = serialize($others);
							$others = mysql_real_escape_string($others);
							if (isset($_POST['enablelist'])) {
								$enablelist = '1';
							} else {
								$enablelist = '0';
							}
							mysql_query("INSERT INTO members SET userid='$id', password='$password', rid='$rid', addr='$addr', recent='completed', enablelist='$enablelist', lastr=NOW(), others='$others';");
							$content .= '<h4>' . $str['complete'] . '</h4><input type="hidden" value="complete">';
							$_SESSION['info_id'] = $id;
							$script = '$(document).ready(function() {
	setTimeout(function() {
		location.href = "./' . $addr . '";
	},2000);
							});';
							exec('php ' . $_SERVER['DOCUMENT_ROOT'] . '/refresh.php "--id=' . $id . '" > /dev/null &');
						}
					}
				}
			}
		}
	//}
	if (mb_strpos($content, 'complete') === false) {
		$content .= '<a href="#" onclick="history.go(-1)" class="btn btn-primary">' . $str['back'] . '</a>';
	}
} else {
	$content .= '<h4>' . $str['alreadylogined'] . '</h4>';
}

require_once('template.php');
?>