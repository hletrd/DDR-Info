<?php
require_once("include.php");
$title = $str['title'];

$style = 'div.center {
	text-align: center;
}';
$content = '';
$mode = 'status';
$include = '';
$script = '';

$result = mysql_query("SELECT * FROM logs ORDER BY id DESC LIMIT 0,10;");
$content .= '<ul class="list-group list-top">';
while ($data = mysql_fetch_row($result)) {
	$content .= '<li class="list-group-item"><div class="row"><div class="col-sm-10">';
	if (strpos($data[2], 'req') !== false) {
		//if (strlen($data[1]) > 3) {
			$content .= '<strong>' . $str['status_request'] . '</strong>&emsp;' . mb_substr($data[1], 0, 3) . '***';
		//}
	} else {
		$content .= '<strong>' . $str['status_request_finished'] . '</strong>&emsp;' . mb_substr($data[1], 0, 3) . '***, ' . str_replace('%m', intval($data[4] / 60), str_replace('%s', $data[4] % 60, $str['taken']));
	}
	
	$content .= '</div><div class="col-sm-2">' . date('m-d H:i:s', strtotime($data[3])) . '</div></div>';
	$content .= '</li>';
}


require_once('template.php');
?>