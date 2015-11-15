<?php
require_once("include.php");
set_time_limit(0);
mysql_query("CREATE TABLE songs(id INT NOT NULL auto_increment, name TEXT NOT NULL, artist TEXT NOT NULL, levels TEXT NOT NULL, PRIMARY KEY (id));");
mysql_query("ALTER TABLE songs CONVERT TO CHARSET utf8;");
$args = getopt("", array("id:"));
header('Content-Type: application/json');
if (isset($_SERVER['REQUEST_METHOD'])) $id = $_GET['id'];
else {
	if (strpos($args['id'], '_') === false) {
		$id = $args['id'];
		$type = 'manual';
		mysql_query("INSERT INTO logs SET name='" . mysql_real_escape_string($id) . "', type='manual_req', time=NOW();");
	} else {
		$id = explode('_', $args['id'])[0];
		$type = 'auto';
		mysql_query("INSERT INTO logs SET name='" . mysql_real_escape_string($id) . "', type='auto_req', time=NOW();");
	}
}
if ($id == '') {
	echo '{"result":"error_invalid_id"}';
	exit;
}
if (date('H') >= 5 && date('H') < 7) {
	echo '{"result":"error_time"}';
	exit;
}
if (preg_match('/^[a-zA-Z0-9가-힣ㄱ-ㅎㅏ-ㅣ\-* ]+$/', $id)) {
	$id = mysql_real_escape_string($id);
	$result = mysql_query("SELECT * FROM members WHERE addr='$id';");
	$data = mysql_fetch_row($result);
	$rid = $data[3];
	$userdata = unserialize($data[6]);
	if (count($data) <= 2) {
		echo '{"result":"error_no_user"}';
	} else if ($data[5] !== 'completed') {
		if (time() - strtotime($data[5]) < $refresh_timeout) {
			echo '{"result":"error_already_refreshing"}';
			exit();
		} else {
			goto just_continue;
		}
	} else {
		just_continue:
		if (isset($_SERVER['REQUEST_METHOD'])) {
			exec('php ' . $_SERVER['DOCUMENT_ROOT'] . '/refresh.php "--id=' . $id . '" > /dev/null &');
			exit;
		}
		mysql_query("UPDATE members SET recent=NOW() WHERE addr='$id';");
		$musicdata = str_replace('&#9825;', '♡', file_get_contents('http://skillattack.com/sa4/data/master_music.txt'));
		$musiclist = explode("\r\n", $musicdata);
		$cnt = 1;
		foreach($musiclist as $each) {
			$song = explode("\t", $each);
			if ($song[0] == '') break;
			$songname = base64_encode(trim($song[11]));
			$artist = mysql_real_escape_string($song[12]);
			$levels = '';
			for($i = 2; $i < 11; $i++) {
				$levels .= intval($song[$i]) . ',';
			}
			$result = mysql_query("SELECT * FROM songs WHERE name='$songname';");
			$data = mysql_fetch_row($result);
			if (count($data) < 2) {
				mysql_query("INSERT INTO songs SET name='$songname', artist='$artist', levels='$levels';");
			}
			/*$es = curl_init('http://localhost:9200/songs/songs/' . $cnt);
			curl_setopt($es, CURLOPT_CUSTOMREQUEST, 'PUT');
			$data = array('name' => base64_decode($songname), 'tags' => array_push(explode(' ', $songname), $artist));
			$data = json_encode($data);
			curl_setopt($es, CURLOPT_POSTFIELDS, $data);
			curl_setopt($es, CURLOPT_RETURNTRANSFER, true);
			curl_exec($es);
			$cnt++;*/
		}

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
		curl_setopt($req, CURLOPT_POSTFIELDS, 'OTP=&KID=' . $konami_id . '&pass=' . $konami_pass . '&key=31063471661419031122732959445926&imgstr=DTSBBN');
		curl_exec($req);

		curl_setopt($req, CURLOPT_URL, 'http://p.eagate.573.jp/game/ddr/ac/p/rival/rival_status.html?rival_id=' . $rid);
		$data = mb_convert_encoding(curl_exec($req), 'UTF-8', 'SJIS');
		preg_match('/&name=([^"]+)/', $data, $match);
		if ($match[1] != '') $userdata['nickname'] = $match[1];
		preg_match('/<img src="\/game\/ddr\/ac\/p\/playdata\/radar_chart\.html\?m0=([0-9]+)&m1=([0-9]+)&m2=([0-9]+)&m3=([0-9]+)&m4=([0-9]+)">/', $data, $match);
		for($i = 1; $i <= 5; $i++) {
			if ($match[$i] != '') $userdata['radar']['single'][$i-1] = $match[$i];
		}

		preg_match('/<img src="\/game\/ddr\/ac\/p\/playdata\/radar_chart\.html\?s0=([0-9]+)&s1=([0-9]+)&s2=([0-9]+)&s3=([0-9]+)&s4=([0-9]+)">/', $data, $match);
		for($i = 1; $i <= 5; $i++) {
			if ($match[$i] != '') $userdata['radar']['double'][$i-1] = $match[$i];
		}

		preg_match('/<tr><th>所属都道府県<\/th><td>([^<]+)/', $data, $match);
		if ($match[1] != '') $userdata['location'] = $match[1];
		/*$url = 'http://p.eagate.573.jp/game/ddr/ac/p/playdata/radar_chart.html?';
		for($i = 0; $i < 5; $i++) {
			$url .= 's' . $i . '=' . $userdata['radar']['double'][$i] . '&';
		}
		$radar_s = imagecreatefrompng($url);
		$radar_s = imagecrop($radar_s, array('x' => 2 , 'y' => 47, 'width' => 248, 'height'=> 150));
		file_put_contents('./_cache/' . $id . '_d.png');*/

		if (isset($userdata['playdata_single'])) $playdata = $userdata['playdata_single'];

		$page = 0;
		while($page < 50) {
			curl_setopt($req, CURLOPT_URL, 'http://p.eagate.573.jp/game/ddr/ac/p/rival/rival_musicdata_single.html?offset=' . $page . '&rival_id=' . $rid);
			$data = mb_convert_encoding(curl_exec($req), 'UTF-8', 'SJIS');
			$curldata = $data;
			$songs = explode('<tr class="data">', $data);
			$cnt = 0;
			foreach($songs as $each) {
				if ($cnt == 0) {
					$cnt = 1;
					continue;
				}
				preg_match('/class="music_info cboxelement">([^<]+)<\/a>/', $each, $songname);
				if ($songname[1] === 'ATHER') {
					$songname[1] = 'ÆTHER';
				}
				$songname[1] = base64_encode(trim($songname[1]));
				preg_match_all('/style="display:none;">([0-9]+)/', $each, $scores);
				preg_match_all('/"\/game\/ddr\/ac\/p\/images\/playdata\/hyouka_([a-z]+)/', $each, $ranks);
				$more = false;
				for($i = 0; $i < 5; $i++) {
					unset($playdata[$songname[1]][$i]['score']);
					$playdata[$songname[1]][$i]['rank'] = $ranks[1][$i];
					if ($ranks[1][$i] !== 'none') {
						$playdata[$songname[1]][$i]['score'] = $scores[1][$i];
						preg_match('/index=([0-9]+)/', $each, $match);
						curl_setopt($req, CURLOPT_URL, 'http://p.eagate.573.jp/game/ddr/ac/p/rival/music_detail.html?index=' . $match[1] . '&diff=' . $i . '&rival_id=' . $rid);
						$data = mb_convert_encoding(curl_exec($req), 'UTF-8', 'SJIS');
						preg_match_all('/<\/th><th>:<\/th><td>([^<]+)/', $data, $match);
						$artistname = $match[1][1];
						preg_match_all('/<\/th><td>([^<]+)/', $data, $match);
						if (mb_strpos($data, 'マーベラスフルコンボ') !== false) {
							$playdata[$songname[1]][$i]['fc'] = 'MFC';
						} else if (mb_strpos($data, 'パーフェクトフルコンボ') !== false) {
							$playdata[$songname[1]][$i]['fc'] = 'PFC';
						} else if (mb_strpos($data, 'フルコンボ<') !== false) {
							$playdata[$songname[1]][$i]['fc'] = 'FC';
						} else {
							$playdata[$songname[1]][$i]['fc'] = false;
						}
						$playdata[$songname[1]][$i]['playcnt'] = $match[1][8];
						$playdata[$songname[1]][$i]['clearcnt'] = $match[1][9];
						$playdata[$songname[1]][$i]['lastplayed'] = $match[1][7];

						$songname2 = mysql_real_escape_string($songname[1]);
						$result = mysql_query("SELECT * FROM songs WHERE name='$songname2';");
						$data = mysql_fetch_row($result);
						if (count($data) < 2) {
							mysql_query("INSERT INTO songs SET name='$songname2', artist='$artistname';");
						}
					}
				}
			}
			if (mb_strpos($curldata, '<next_page>') === false) break;
			$page++;
		}

		$userdata['playdata_single'] = $playdata;
		unset($playdata);

		if (isset($userdata['playdata_double'])) $playdata = $userdata['playdata_double'];

		$page = 0;
		while($page < 50) {
			curl_setopt($req, CURLOPT_URL, 'http://p.eagate.573.jp/game/ddr/ac/p/rival/rival_musicdata_double.html?offset=' . $page . '&rival_id=' . $rid);
			$data = mb_convert_encoding(curl_exec($req), 'UTF-8', 'SJIS');
			$curldata = $data;
			$songs = explode('<tr class="data">', $data);
			$cnt = 0;
			foreach($songs as $each) {
				if ($cnt == 0) {
					$cnt = 1;
					continue;
				}
				preg_match('/class="music_info cboxelement">([^<]+)<\/a>/', $each, $songname);
				if ($songname[1] === 'ATHER') {
					$songname[1] = 'ÆTHER';
				}
				$songname[1] = base64_encode($songname[1]);
				preg_match_all('/style="display:none;">([0-9]+)/', $each, $scores);
				preg_match_all('/"\/game\/ddr\/ac\/p\/images\/playdata\/hyouka_([a-z]+)/', $each, $ranks);
				$more = false;
				for($i = 5; $i < 9; $i++) {
					$playdata[$songname[1]][$i]['score'] = $scores[1][$i-5];
					$playdata[$songname[1]][$i]['rank'] = $ranks[1][$i-5];
					if ($ranks[1][$i-5] !== 'none') {
						preg_match('/index=([0-9]+)/', $each, $match);
						curl_setopt($req, CURLOPT_URL, 'http://p.eagate.573.jp/game/ddr/ac/p/rival/music_detail.html?index=' . $match[1] . '&diff=' . $i . '&rival_id=' . $rid);
						$data = mb_convert_encoding(curl_exec($req), 'UTF-8', 'SJIS');
						preg_match_all('/<\/th><th>:<\/th><td>([^<]+)/', $data, $match);
						$artistname = $match[1][1];
						preg_match_all('/<\/th><td>([^<]+)/', $data, $match);
						if (mb_strpos($data, 'マーベラスフルコンボ') !== false) {
							$playdata[$songname[1]][$i]['fc'] = 'MFC';
						} else if (mb_strpos($data, 'パーフェクトフルコンボ') !== false) {
							$playdata[$songname[1]][$i]['fc'] = 'PFC';
						} else if (mb_strpos($data, 'フルコンボ<') !== false) {
							$playdata[$songname[1]][$i]['fc'] = 'FC';
						} else {
							$playdata[$songname[1]][$i]['fc'] = false;
						}
						$playdata[$songname[1]][$i]['playcnt'] = $match[1][8];
						$playdata[$songname[1]][$i]['clearcnt'] = $match[1][9];
						$playdata[$songname[1]][$i]['lastplayed'] = $match[1][7];

						$songname2 = mysql_real_escape_string($songname[1]);
						$result = mysql_query("SELECT * FROM songs WHERE name='$songname2';");
						$data = mysql_fetch_row($result);
						if (count($data) < 2) {
							mysql_query("INSERT INTO songs SET name='$songname2', artist='$artistname';");
						}
					}
				}
			}
			if (mb_strpos($curldata, '<next_page>') === false) break;
			$page++;
		}
		$userdata['playdata_double'] = $playdata;
		$userdata['updated'] = date('Y-m-d H:i:s');
		$final = serialize($userdata);
		$final = mysql_real_escape_string($final);
		$duration = round(microtime(true) - $page_start);
		mysql_query("UPDATE members SET recent='completed', DATA='$final', lastp=NOW() WHERE addr='$id';");
		mysql_query("INSERT INTO logs SET name='$id', type='" . $type . "_finished', time=NOW(), duration='$duration';");
		echo '{"result":"succeed"}';
	}
} else {
	echo '{"result":"error_invalid_id"}';
}
?>