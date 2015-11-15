<?php
if ($_GET['type'] === 's') {
	$url = 'http://p.eagate.573.jp/game/ddr/ac/p/playdata/radar_chart.html?';
	for($i = 0; $i < 5; $i++) {
		$url .= 'm' . $i . '=' . $_GET['d' . $i] . '&';
	}
} else {
	$url = 'http://p.eagate.573.jp/game/ddr/ac/p/playdata/radar_chart.html?';
	for($i = 0; $i < 5; $i++) {
		$url .= 's' . $i . '=' . $_GET['d' . $i] . '&';
	}
}

header('Content-Type: image/png');
echo file_get_contents($url);
?>