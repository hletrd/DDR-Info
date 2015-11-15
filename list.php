<?php
require_once("include.php");
$title = $str['title'];

$style = 'div.center {
	text-align: center;
}';
$content = '';
$mode = 'list';
$include = '';
$script = '';

$cnt_per_page = 20;

if (!isset($_GET['page'])) $page = 1;
else $page = intval($_GET['page']);
$result = mysql_query("SELECT COUNT(*) FROM members WHERE enablelist='1';");
$count = mysql_fetch_row($result)[0];
$pagecount = intval($count / $cnt_per_page) + (($count % $cnt_per_page == 0)?0:1);
if ($page > $pagecount) $page = $pagecount;
else if ($page < 1) $page = 1;

$result = mysql_query("SELECT * FROM members WHERE enablelist='1' LIMIT " . (($page - 1) * $cnt_per_page) . ", $cnt_per_page;");

$content .= '<div class="table-responsive"><table class="table table-striped table-condensed">';
$content .= '<thead><tr><th>' . $str['playername'] . '</th><th>' . $str['alias'] . '</th><th>' . $str['rival_id'] . '</th><th>' . $str['location'] . '</th><th>' . $str['lastupdate'] . '</th><th>' . $str['comment'] . '</th></tr></thead>';
$content .= '<tbody>';
while($data = mysql_fetch_row($result)) {
	if ($data[7] == 1) {
		$pd = unserialize($data[6]);
		$others = unserialize($data[10]);
		$content .= '<tr><td><a href="./' . str_replace(' ', '%20', $data[4]) . '">' . $pd['nickname'] . '</a></td><td><a href="./' . str_replace(' ', '%20', $data[4]) . '">' . $data[4] . '</a></td><td>' . substr($data[3], 0, 4) . '-' . substr($data[3], 4, 4) . '</td><td>' . $pd['location'] . '</td><td>' . $data[8] . '</td><td>' . $others['comment'] . '</td></tr>';
	}
}
$content .= '</tbody></table></div>';

$content .= '<div class="center"><nav>';
$content .= '<ul class="pagination">';
if ($page > 3) {
	$content .= '<li><a href="./_list_1"><span>&laquo;</span></a></li>';
}
if ($page > 2) {
	$content .= '<li><a href="./_list_' . ($page - 2) . '">' . ($page - 2) . '</a></li>';
}
if ($page > 1) {
	$content .= '<li><a href="./_list_' . ($page - 1) . '">' . ($page - 1) . '</a></li>';
}
$content .= '<li class="active"><a href="./_list_' . $page . '">' . $page . '<span class="sr-only">(current)</span></a></li>';
if ($pagecount - $page >= 1) {
	$content .= '<li><a href="./_list_' . ($page + 1) . '">' . ($page + 1) . '</a></li>';
}
if ($pagecount - $page >= 2) {
	$content .= '<li><a href="./_list_' . ($page + 2) . '">' . ($page + 2) . '</a></li>';
}
if ($pagecount - $page >= 3) {
	$content .= '<li><a href="./_list_' . $pagecount . '"><span>&raquo;</span></a></li>';
}

$content .= '</ul>';
$content .= '</nav></div>';

require_once('template.php');
?>