<?php
require_once("include.php");

$style = '.center {
	text-align: center;
}
h4 {
	display:inline;
}
.twitter-share-button {
	position: relative;
	left: 15px;
	top: 4px;
}
';
$content = '';
$mode = 'home';
$include = '';
$script = 'var refresh = function(a) {
	$.get("./_refresh/" + a);
	alert("' . $str['refresh_request'] . '");
	setTimeout(function() {
		location.reload();
	}, 500);
}
$(document).ready(function() {
	$(\'[data-toggle="tooltip"]\').tooltip();
});
(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=1558165044466080&version=v2.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));
window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
';

if (isset($_GET['my'])) {
	$userid = mysql_real_escape_string($_SESSION['info_id']);
	$result = mysql_query("SELECT * FROM members WHERE userid='$userid'");
	$data = mysql_fetch_row($result);
} else {
	$addr = mysql_real_escape_string($_GET['link']);
	$result = mysql_query("SELECT * FROM members WHERE addr LIKE '$addr'");
	$data = mysql_fetch_row($result);
}
if (count($data) <= 2) {
	header('Location: /_404');
} else if ($data[6] == '') {
	$playdata = unserialize($data[6]);
	if (isset($_GET['api'])) {
		header('Content-Type: application/json');
		echo json_encode($playdata);
		exit;
	}
	if (time() - strtotime($data[5]) > $refresh_timeout && $data[5] !== 'completed') {
		$content .= '<h4>' . $str['refreshfailed'] . ' (' . $data[5] . ')</h4><div class="spacer15"></div>';
		$content .= '<h3>PLAYER ' . $playdata['nickname'] . ' (' . substr($data[3], 0, 4) . '-' . substr($data[3], 4, 4) . ')</h3>';
		$content .= '<h3>' . $str['noinfo'] . '</h3><a class="btn btn-success" href="#" onclick="refresh(\'' . $data[4] . '\');">' . $str['refresh'] . '</a>';
	} else if ($data[5] !== 'completed') {
		$result_time = mysql_query("SELECT AVG(duration) FROM logs WHERE duration!='0';");
		$data_time = mysql_fetch_row($result_time);
		$data_time = intval($data_time[0]);
		$minute = round($data_time / 60);
		$second = round($data_time % 60);
		$content .= '<h4>' . str_replace('%s', $second, str_replace('%m', $minute, $str['refreshing'])) . ' (' . $data[5] . ')</h4><div class="spacer15"></div>';
		$content .= '<h3>PLAYER ' . $playdata['nickname'] . ' (' . substr($data[3], 0, 4) . '-' . substr($data[3], 4, 4) . ')</h3>';
		$content .= '<h3>' . $str['noinfo'] . '</h3>';
	} else {
		$content .= '<h3>PLAYER ' . $playdata['nickname'] . ' (' . substr($data[3], 0, 4) . '-' . substr($data[3], 4, 4) . ')</h3>';
		$content .= '<h3>' . $str['noinfo'] . '</h3><a class="btn btn-success" href="#" onclick="refresh(\'' . $data[4] . '\');">' . $str['refresh'] . '</a>';
	}
	$title = $playdata['nickname'] . "'s DDR Info";
	$title_fb = $playdata['nickname'] . "'s DDR Info";
} else {
	$playdata = unserialize($data[6]);
	$title = $playdata['nickname'] . "'s DDR Info";
	$title_fb = $playdata['nickname'] . "'s DDR Info";
	if (isset($_GET['api'])) {
		header('Content-Type: application/json');
		echo json_encode($playdata);
		exit;
	}
	if (time() - strtotime($data[5]) > $refresh_timeout && $data[5] !== 'completed') {
		$content .= '<h4>' . $str['refreshfailed'] . ' (' . $data[5] . ')</h4><div class="spacer15"></div>';
		$content .= '<h3>PLAYER ' . $playdata['nickname'] . ' (' . substr($data[3], 0, 4) . '-' . substr($data[3], 4, 4) . ')</h3>';
		$content .= '<h4>' . $str['lastrefreshed'] . ': ' . $data[8] . '</h4>';
		$content .= '<div id="fb-root"></div><br /><div class="fb-share-button" data-href="http://ddrinfo.0101010101.com/' . $data[4] . '" data-layout="button_count"></div><a class="twitter-share-button" href="https://twitter.com/share">Tweet</a><br /><br />';
		$content .= '<a class="btn btn-success" href="#" onclick="refresh(\'' . $data[4] . '\');">' . $str['refresh'] . '</a>&emsp;';
	} else if ($data[5] !== 'completed') {
		$result_time = mysql_query("SELECT AVG(duration) FROM logs WHERE duration!='0';");
		$data_time = mysql_fetch_row($result_time);
		$data_time = intval($data_time[0]);
		$minute = round($data_time / 60);
		$second = round($data_time % 60);
		$content .= '<h4>' . str_replace('%s', $second, str_replace('%m', $minute, $str['refreshing'])) . ' (' . $data[5] . ')</h4><div class="spacer15"></div>';
		$content .= '<h3>PLAYER ' . $playdata['nickname'] . ' (' . substr($data[3], 0, 4) . '-' . substr($data[3], 4, 4) . ')</h3>';
		$content .= '<div id="fb-root"></div><div class="fb-share-button" data-href="http://ddrinfo.0101010101.com/' . $data[4] . '" data-layout="button_count"></div><a class="twitter-share-button" href="https://twitter.com/share">Tweet</a><br /><br />';
	} else {
		$content .= '<h3>PLAYER ' . $playdata['nickname'] . ' (' . substr($data[3], 0, 4) . '-' . substr($data[3], 4, 4) . ')</h3>';
		$content .= '<h4>' . $str['lastrefreshed'] . ': ' . $data[8] . '</h4>';
		$content .= '<div id="fb-root"></div><br /><div class="fb-share-button" data-href="http://ddrinfo.0101010101.com/' . $data[4] . '" data-layout="button_count"></div><a class="twitter-share-button" href="https://twitter.com/share">Tweet</a><br /><br />';
		$content .= '<a class="btn btn-success" href="#" onclick="refresh(\'' . $data[4] . '\');">' . $str['refresh'] . '</a>&emsp;';
	}
	$content .= '<a class="btn btn-primary" href="./' . $addr . '_single">SINGLE</a>&emsp;';
	$content .= '<a class="btn btn-primary" href="./' . $addr . '_double">DOUBLE</a>&emsp;';
	if ($_GET['type'] === 'single') $type = 'single';
	else $type = 'double';
	if ($_GET['order'] === 'played') {
		$content .= '<a class="btn btn-default" href="./' . $addr . '_' . $type . '">' . $str['not_playedonly'] . '</a>';
	} else {
		$content .= '<a class="btn btn-default" href="./' . $addr . '_' . $type . '_played">' . $str['playedonly'] . '</a>';
	}

	$singlecnt = 0;
	foreach($playdata['playdata_single'] as $key=>$val) {
		for($i = 0; $i < 5; $i++) {
			$singlecnt += $val[$i]['playcnt'];
		}
	}
	$doublecnt = 0;
	foreach($playdata['playdata_double'] as $key=>$val) {
		for($i = 5; $i < 9; $i++) {
			$doublecnt += $val[$i]['playcnt'];
		}
	}
	
	
	$content .= '<div class="spacer15"></div>';
	$content .= '<h4>' . $str['playedcount'] . ': Single ' . $singlecnt . ' ' . $str['times'] . ', Double ' . $doublecnt . ' ' . $str['times'] . ' (' . $str['totally'] . ' ' . ($singlecnt + $doublecnt) . ' ' . $str['times'] . ')</h4>';

	$content .= '<div class="spacer15"></div>';
	$content .= '<div class="row"><div class="col-sm-6 center"><canvas id="canv_s" width="248" height="170"></canvas></div><div class="col-sm-6 center"><canvas id="canv_d" width="248" height="170"></canvas></div></div><div class="spacer15"></div>';
	$content .= '<h5>SINGLE (STREAM ' . $playdata['radar']['single'][0] . ', CHAOS ' . $playdata['radar']['single'][1] . ', FREEZE ' . $playdata['radar']['single'][2] . ', AIR ' . $playdata['radar']['single'][3] . ', VOLTAGE ' . $playdata['radar']['single'][4] . ')<br />';
	$content .= 'DOUBLE (STREAM ' . $playdata['radar']['double'][0] . ', CHAOS ' . $playdata['radar']['double'][1] . ', FREEZE ' . $playdata['radar']['double'][2] . ', AIR ' . $playdata['radar']['double'][3] . ', VOLTAGE ' . $playdata['radar']['double'][4] . ')</h5>';

	$url = '/_getradar/s';
	for($i = 0; $i < 5; $i++) {
		$url .= '/' . $playdata['radar']['single'][$i];
	}
	$script .= '$(document).ready(function () {
		var cs = document.getElementById("canv_s").getContext("2d");
		var radars = new Image();
		radars.onload = function() {
			cs.drawImage(radars,-3,-37);
			var p = cs.getImageData(0, 0, radars.width, radars.height);
			for(var i = 0, len = p.data.length; i < len; i += 4){
				if(p.data[i] < 224 && p.data[i] > 217 && p.data[i+1] < 224 && p.data[i+1] > 217 && p.data[i+2] < 224 && p.data[i+2] > 217){
					p.data[i+3] = 0;
				}
			}
			cs.putImageData(p, 0, 0);
		};
		radars.src = "' . $url . '";
		radars.onerror = function() {
			radars.src = "' . $url . '";
		}
	';

	$url = '/_getradar/d';
	for($i = 0; $i < 5; $i++) {
		$url .= '/' . $playdata['radar']['double'][$i];
	}
	$script .= 'var cd = document.getElementById("canv_d").getContext("2d");
		var radard = new Image();
		radard.onload = function() {
			cd.drawImage(radard,-3,-37);
			var p = cd.getImageData(0, 0, radard.width, radard.height);
			for(var i = 0, len = p.data.length; i < len; i += 4){
				if(p.data[i] < 224 && p.data[i] > 217 && p.data[i+1] < 224 && p.data[i+1] > 217 && p.data[i+2] < 224 && p.data[i+2] > 217){
					p.data[i+3] = 0;
				}
			}
			cd.putImageData(p, 0, 0);
		};
		radard.src = "' . $url . '";
		radard.onerror = function() {
			radard.src = "' . $url . '";
		}
	});';

	$content .= '<div class="table-responsive"><table class="table table-striped table-condensed">';

	$cnt_per_page = 40;

	if (!isset($_GET['page'])) $page = 1;
	else $page = intval($_GET['page']);

	if ($_GET['type'] === 'single') {
		$content .= '<thead><tr><th>' . $str['songname'] . '</th><th>' . $str['artist'] . '</th><th class="center">BEGINNER</th><th class="center">BASIC</th><th class="center">DIFFICULT</th><th class="center">EXPERT</th><th class="center">CHALLENGE</th></tr></thead>';
		$content .= '<tbody>';

		$count = count($playdata['playdata_single']);
		$pagecount = intval($count / $cnt_per_page) + (($count % $cnt_per_page == 0)?0:1);
		if ($page > $pagecount) $page = $pagecount;
		else if ($page < 1) $page = 1;

		/*if (isset($_GET['search'])) {
			$es = curl_init('http://localhost:9200/songs/_search');
			 . $_GET['search']
			curl_setopt($es, CURLOPT_POST, true);
			curl_setopt($es, CURLOPT_POSTFIELDS, '{"query":{"bool":{"should":[]}}}');
			curl_setopt($es, CURLOPT_RETURNTRANSFER, true);
			$searched = json_decode(curl_exec($es), true);
			$hits = $searched['hits']['hits'];
			$cnt = 0;
			foreach($hits as $hit) {
				$searchedlist[$cnt++] = $hit['_source']['name'];
			}
		}*/

		$cnt = 0;
		//if (!isset($_GET['search'])) {
			foreach($playdata['playdata_single'] as $key=>$val) {
				if (($cnt < ($page - 1) * $cnt_per_page || $cnt >= $page * $cnt_per_page) && $_GET['order'] !== 'played') {
					$cnt++;
					continue;
				}
				$cnt++;
				$content_tmp = '';
				$played = false;
				$content_tmp .= '<tr><td>' . str_replace('&', '&amp;', base64_decode($key)) . '</td><td>';
				$result = mysql_query("SELECT * FROM songs WHERE name='$key';");
				$data = mysql_fetch_row($result);
				$content_tmp .= str_replace('&', '&amp;', $data[2]);
				$content_tmp .= '</td>';
				$levels = explode(',', $data[3]);
				for($i = 0; $i < 5; $i++) {
					$content_tmp .= '<td class="center">';
					if ($levels[$i] != -1) {
						if ($val[$i]['rank'] === 'none') {
							$content_tmp .= 'Not played';
						} else {
							$played = true;
							$content_tmp .= '<img src="./_static/' . $val[$i]['rank'] . '.png" alt="" data-toggle="tooltip" data-placement="top" title="PLAYED: ' .  $val[$i]['playcnt'] . ' / CLEARED:' .  $val[$i]['clearcnt'] . '"><br />';
							$content_tmp .= '<h4>' . $val[$i]['score'] . '</h4><br />';
							$content_tmp .= date('Y-m-d', strtotime($val[$i]['lastplayed']));
							if ($val[$i]['fc']) {
								$content_tmp .= '<br /><strong>' . $val[$i]['fc'] . '</strong>';
							}
						}
						if ($levels[$i] != null) $content_tmp .= '<br />Lv. ' . $levels[$i];
					}
					$content_tmp .= '</td>';
				}
				$content_tmp .= '</tr>';
				if ($played === false && $_GET['order'] === 'played') {
				} else $content .= $content_tmp;
			}
		/*} else {
			foreach($searchedlist as $searched_each) {
				$key = base64_encode($searched_each);
				$val = $playdata['playdata_single'][$key];
				if (!isset($val)) {
					if (isset($playdata['playdata_single'][base64_encode($searched_each . ' ')])) {
						$key = base64_encode($searched_each . ' ');
						$val = $playdata['playdata_single'][$key];
					} else continue;
				}
				$content_tmp = '';
				$played = false;
				$content_tmp .= '<tr><td>' . str_replace('&', '&amp;', base64_decode($key)) . '</td><td>';
				$result = mysql_query("SELECT * FROM songs WHERE name='$key';");
				$data = mysql_fetch_row($result);
				$content_tmp .= str_replace('&', '&amp;', $data[2]);
				$content_tmp .= '</td>';
				$levels = explode(',', $data[3]);
				for($i = 0; $i < 5; $i++) {
					$content_tmp .= '<td class="center">';
					if ($levels[$i] != -1) {
						if ($val[$i]['rank'] === 'none') {
							$content_tmp .= 'Not played';
						} else {
							$played = true;
							$content_tmp .= '<img src="./_static/' . $val[$i]['rank'] . '.png" alt="" data-toggle="tooltip" data-placement="top" title="PLAYED: ' .  $val[$i]['playcnt'] . ' / CLEARED:' .  $val[$i]['clearcnt'] . '"><br />';
							$content_tmp .= '<h4>' . $val[$i]['score'] . '</h4><br />';
							$content_tmp .= date('Y-m-d', strtotime($val[$i]['lastplayed']));
							if ($val[$i]['fc']) {
								$content_tmp .= '<br /><strong>' . $val[$i]['fc'] . '</strong>';
							}
						}
						if ($levels[$i] != null) $content_tmp .= '<br />Lv. ' . $levels[$i];
					}
					$content_tmp .= '</td>';
				}
				$content_tmp .= '</tr>';
				$content .= $content_tmp;
			}
		}*/

		$content .= '</tbody></table></div>';
		if ($_GET['order'] !== 'played') {
			$content .= '<div class="center"><nav>';
			$content .= '<ul class="pagination">';
			if ($page > 3) {
				$content .= '<li><a href="./' . $addr . '_single_1"><span>&laquo;</span></a></li>';
			}
			if ($page > 2) {
				$content .= '<li><a href="./' . $addr . '_single_' . ($page - 2) . '">' . ($page - 2) . '</a></li>';
			}
			if ($page > 1) {
				$content .= '<li><a href="./' . $addr . '_single_' . ($page - 1) . '">' . ($page - 1) . '</a></li>';
			}
			$content .= '<li class="active"><a href="./' . $addr . '_single_' . $page . '">' . $page . '<span class="sr-only">(current)</span></a></li>';
			if ($pagecount - $page >= 1) {
				$content .= '<li><a href="./' . $addr . '_single_' . ($page + 1) . '">' . ($page + 1) . '</a></li>';
			}
			if ($pagecount - $page >= 2) {
				$content .= '<li><a href="./' . $addr . '_single_' . ($page + 2) . '">' . ($page + 2) . '</a></li>';
			}
			if ($pagecount - $page >= 3) {
				$content .= '<li><a href="./' . $addr . '_single_' . $pagecount . '"><span>&raquo;</span></a></li>';
			}

			$content .= '</ul>';
			$content .= '</nav></div>';
		}
	} else if ($_GET['type'] === 'double') {
		$content .= '<thead><tr><th>' . $str['songname'] . '</th><th>' . $str['artist'] . '</th><th class="center">BASIC</th><th class="center">DIFFICULT</th><th class="center">EXPERT</th><th class="center">CHALLENGE</th></tr></thead>';
		$content .= '<tbody>';

		$count = count($playdata['playdata_double']);
		$pagecount = intval($count / $cnt_per_page) + (($count % $cnt_per_page == 0)?0:1);
		if ($page > $pagecount) $page = $pagecount;
		else if ($page < 1) $page = 1;

		$cnt = 0;
		foreach($playdata['playdata_double'] as $key=>$val) {
			if ($cnt < ($page - 1) * $cnt_per_page || $cnt >= $page * $cnt_per_page && $_GET['order'] !== 'played') {
				$cnt++;
				continue;
			}
			$cnt++;
			$content_tmp = '';
			$played = false;
			$content_tmp .= '<tr><td>' . str_replace('&', '&amp;', base64_decode($key)) . '</td><td>';
			$result = mysql_query("SELECT * FROM songs WHERE name='$key';");
			$data = mysql_fetch_row($result);
			$content_tmp .= str_replace('&', '&amp;', $data[2]);
			$content_tmp .= '</td>';
			$levels = explode(',', $data[3]);
			for($i = 5; $i < 9; $i++) {
				$content_tmp .= '<td class="center">';
				if ($levels[$i] != -1) {
					if ($val[$i]['rank'] === 'none') {
						$content_tmp .= 'Not played';
					} else {
						$played = true;
						$content_tmp .= '<img src="./_static/' . $val[$i]['rank'] . '.png" alt="" data-toggle="tooltip" data-placement="top" title="PLAYED: ' .  $val[$i]['playcnt'] . ' / CLEARED:' .  $val[$i]['clearcnt'] . '"><br />';
						$content_tmp .= '<h4>' . $val[$i]['score'] . '</h4><br />';
						$content_tmp .= date('Y-m-d', strtotime($val[$i]['lastplayed']));
						if ($val[$i]['fc']) {
							$content_tmp .= '<br /><strong>' . $val[$i]['fc'] . '</strong>';
						}
					}
					if ($levels[$i] != null) $content_tmp .= '<br />Lv. ' . $levels[$i];
				}
				$content_tmp .= '</td>';
			}
			$content_tmp .= '</tr>';
			if ($_GET['order'] === 'played' && $played === false) {

			} else $content .= $content_tmp;
		}
		$content .= '</tbody></table></div>';
		if ($_GET['order'] !== 'played') {
			$content .= '<div class="center"><nav>';
			$content .= '<ul class="pagination">';
			if ($page > 3) {
				$content .= '<li><a href="./' . $addr . '_double_1"><span>&laquo;</span></a></li>';
			}
			if ($page > 2) {
				$content .= '<li><a href="./' . $addr . '_double_' . ($page - 2) . '">' . ($page - 2) . '</a></li>';
			}
			if ($page > 1) {
				$content .= '<li><a href="./' . $addr . '_double_' . ($page - 1) . '">' . ($page - 1) . '</a></li>';
			}
			$content .= '<li class="active"><a href="./' . $addr . '_double_' . $page . '">' . $page . '<span class="sr-only">(current)</span></a></li>';
			if ($pagecount - $page >= 1) {
				$content .= '<li><a href="./' . $addr . '_double_' . ($page + 1) . '">' . ($page + 1) . '</a></li>';
			}
			if ($pagecount - $page >= 2) {
				$content .= '<li><a href="./' . $addr . '_double_' . ($page + 2) . '">' . ($page + 2) . '</a></li>';
			}
			if ($pagecount - $page >= 3) {
				$content .= '<li><a href="./' . $addr . '_double_' . $pagecount . '"><span>&raquo;</span></a></li>';
			}

			$content .= '</ul>';
			$content .= '</nav></div>';
		}
	} else {
		$content .= $str['err_unknown'];
	}
	
}

require_once('template.php');
?>