<?php
require_once("include.php");
$title = $str['title'];

$style = '';
$content = '<h2>' . $str['this_is_info'] . '</h2>';
$mode = 'home';
$script = '(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=1558165044466080&version=v2.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));
window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));';
$include = '';

$result = mysql_query("SELECT COUNT(*) FROM members GROUP BY enablelist ORDER BY enablelist DESC;");
$cnt_open = mysql_fetch_row($result)[0];
$cnt_hidden = mysql_fetch_row($result)[0];

$content .= '<div id="fb-root"></div><div class="fb-like" data-share="true" data-width="450" data-show-faces="true"></div><div class="spacer15"></div><a class="twitter-share-button" href="https://twitter.com/share">Tweet</a>';

if ($_SESSION['lang_prop'] === 'jp') {
	$content .= '<br />DDR Info는 KONAMI의 Dance Dance Revolution의 본인의 플레이 데이터를 열람하실 수 있는 서비스입니다. 베이직 코스 없이도 이용 가능합니다.<br /><br />';
	$content .= '<h4>한편...</h4>';
	$content .= '<ul>';
	$content .= '<li>' . date('Y년 m월 d일 H시 i분') . ' 현재 ' . ($cnt_open + $cnt_hidden) . '명(공개 ' . $cnt_open . '명, 비공개 ' . $cnt_hidden . '명)이 이용하고 있습니다.</li>';
	$content .= '<li><del>코나미 서버가 병신이라</del> 가끔 데이터가 안 긁힐때가 있습니다. 이 경우 갱신 요청을 한번 더 해 주시면 됩니다.</li>';
	$content .= '<li>본 사이트는 사용자가 제작한 사이트로 코나미와는 아무 관련이 없으며, 서비스는 예고 없이 중단될 수 있습니다.</li>';
	$content .= '<li>본인의 점수를 공개하도록 설정해야 이용하실 수 있습니다. 기본 설정은 공개입니다.<br /><div class="row"><img class="col-xs-8" alt="" src="p4.png"></div></li>';
	$content .= '<li><a href="./_api/HLETRD">API</a>를 지원합니다. 곡명은 일부 특수문자의 인코딩 문제로 base64로 인코딩되어 있습니다.</li>';
	$content .= '</ul>';
	$content .= '<br /><br /><p>Developed by HLETRD(01 at 0101010101 . com)<br />Thanks to HYEA(ho94949 at gmail . com) (베이직 코스가 결제된 계정을 제공하고 일본어 번역을 제공했습니다.)</p>';
} else if ($_SESSION['lang_prop'] === 'ya') {
	$content .= '<br />DDR Info는 KONMAI익 Dance Dance Revolution익 본외의 뜰레이 데이터를 열람하실 수 있는 서네스입니다. 베이걱 코스 없이도 이용 가능합니다.<br /><br />';
	$content .= '<h4>한뗜...</h4>';
	$content .= '<ul>';
	$content .= '<li>' . date('Y년 m월 d일 H시 i분') . ' 현재 ' . ($cnt_open + $cnt_hidden) . '띵(공개 ' . $cnt_open . '띵, 네공개 ' . $cnt_hidden . '띵)이 이용하ㅍ 있습니다.</li>';
	$content .= '<li><del>코나미 서버가 병신이라</del> 가끔 데이터가 안 긁힐때가 있습니다. 이 정우 갱신 요청을 한번 티 해 주시면 됩니다.</li>';
	$content .= '<li>본 사이트는 사용자가 제작한 사이트로 코나미와는 아무 관련이 없으댸, 서네스는 예ㅍ 없이 중타틸 수 있습니다.</li>';
	$content .= '<li>본인익 겸수를 공개하도록 설경해야 이용하실 수 있습니다. 기본 설경은 공개입니다.<br /><div class="row"><img class="col-xs-8" alt="" src="p4.png"></div></li>';
	$content .= '<li><a href="./_api/HLETRD">API</a>를 거원합니다. 푸띵은 일부 특수문자익 외코딩 문제로 base64로 외코딩티어 있습니다.</li>';
	$content .= '</ul>';
	$content .= '<br /><br /><p>Developed by HLETRD(01 at 0101010101 . com)<br />Thanks to HYEA(ho94949 at gmail . com) (베이걱 코스가 절계틴 제경을 계공하고 일본어 번역을 해 주었습니다.)</p>';
} else if ($_SESSION['lang_prop'] === 'en') {
	$content .= '<br />You can view your playdata of Dance Dance Revolution on DDR Info. You don\'t need to pay for KONAMI basic course to use the service.<br /><br />';
	$content .= '<h4>However...</h4>';
	$content .= '<ul>';
	$content .= '<li>' . ($cnt_open + $cnt_hidden) . ' people(' . $cnt_open . ' public accounts, ' . $cnt_hidden . ' private accounts) are using the service by ' . date('m/d/Y H:i') . '.</li>';
	$content .= '<li><del>Because the KONAMI is not maintaining their e-AMUSEMENT server properly</del> playdata can sometimes be omitted. Just update your info again to deal with this problem.</li>';
	$content .= '<li>This website is made and maintained by DDR user, and service can be discontinued without any notice.</li>';
	$content .= '<li>You MUST make your playdata public to use the service. Default setting is public. <br /><div class="row"><img class="col-xs-8" alt="" src="p4.png"></div></li>';
	$content .= '<li>We are providing <a href="./_api/HLETRD">API</a> for developers. Because of escaping of some characters in song name, song names are encoded as base64.</li>';
	$content .= '</ul>';

	$content .= '<br /><br /><p>Developed by HLETRD(01 at 0101010101 . com)<br />Thanks to HYEA(ho94949 at gmail . com) (Provided Japanese translation & e-AMUSEMENT basic course accound.)</p>';
} else {
	$content .= '<br />DDR Info는 KONAMI의 Dance Dance Revolution의 본인의 플레이 데이터를 열람하실 수 있는 서비스입니다. 베이직 코스 없이도 이용 가능합니다.<br /><br />';
	$content .= '<h4>한편...</h4>';
	$content .= '<ul>';
	$content .= '<li>' . date('Y년 m월 d일 H시 i분') . ' 현재 ' . ($cnt_open + $cnt_hidden) . '명(공개 ' . $cnt_open . '명, 비공개 ' . $cnt_hidden . '명)이 이용하고 있습니다.</li>';
	$content .= '<li><del>코나미 서버가 병신이라</del> 가끔 데이터가 안 긁힐때가 있습니다. 이 경우 갱신 요청을 한번 더 해 주시면 됩니다.</li>';
	$content .= '<li>아직 베타 버전으로 계속 버그를 수정 중이며, 기능도 계속 추가중입니다. 버그 또는 제안할 기능이 있으시면 하단 이메일로 연락 바랍니다.</li>';
	$content .= '<li>본 사이트는 사용자가 제작한 사이트로 코나미와는 아무 관련이 없으며, 서비스는 예고 없이 중단될 수 있습니다.</li>';
	$content .= '<li>본인의 점수를 공개하도록 설정해야 이용하실 수 있습니다. 기본 설정은 공개입니다.<br /><div class="row"><img class="col-xs-8" alt="" src="p4.png"></div></li>';
	$content .= '<li><a href="./_api/HLETRD">API</a>를 지원합니다. 곡명은 일부 특수문자의 인코딩 문제로 base64로 인코딩되어 있습니다.</li>';
	$content .= '</ul>';

	$content .= '<br /><br /><p>Developed by HLETRD(01 at 0101010101 . com)<br />Thanks to HYEA(ho94949 at gmail . com) (베이직 코스가 결제된 계정을 제공하고 일본어 번역을 해 주었습니다.)</p>';
}

require_once('template.php');
?>