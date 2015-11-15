<?php
require_once('include.php');

$style = '.s404 { width: 720px; height: 540px; position: relative; left: calc(50% - 360px); background: black url(\'/404.jpeg\') no-repeat center; background-size: cover; overflow:hidden; cursor:pointer;} .center {text-align: center;}';
$content = '모바일 배려 그런 거 없음<h6><del>귀찮아</del></h6><div onclick="play();" id="v404" class="s404"></div>';
$mode = 'home';
$include = '';
$script = 'var play = function() { $("#v404").html(\'<iframe width="720" height="540" src="http://www.youtube.com/embed/HbNUR1P0fTM?autoplay=1&rel=0&color=white&autohide=0&start=94" frameborder="0"></iframe>\');};';
$img_fb = 'http://ddrinfo.0101010101.com/404.jpeg';

require_once('template.php');
?>