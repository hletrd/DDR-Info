<!doctype HTML>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"><meta property="og:title" content="<?php if (!isset($title_fb)) echo 'DDR Info'; else echo $title_fb;?>"><meta property="og:description" content="Dance Dance Revolution Info"><meta property="og:image" content="<?php if (!isset($img_fb)) echo 'http://ddrinfo.0101010101.com/img.jpeg'; else echo $img_fb;?>"><title><?php echo $title; ?></title><link rel="stylesheet" type="text/css" href="/site.min.css"><script src="/jquery.js"></script><script src="/bootstrap.min.js"></script><?php echo $include; ?><style type="text/css">@font-face { font-family: 'Open Sans'; font-style: normal; font-weight: 400; src: local('Open Sans'), local('OpenSans'), url(./open_sans.woff2) format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000; } .navbar-brand { color: white !important; } body { background-color: #f0f0f0; } div.main { margin-top: 65px; } div.content { padding: 15px; } h1, h2, h3, h4, h5, h6 { margin-top: 0px !important; } div.spacer15 { height: 15px; } .float-right { float: right; }<?php echo $style; ?></style></head><body><!--[if lte IE 8]><script>alert('<?php echo $str['err_noie']; ?>');</script><![endif]--><nav class="navbar navbar-inverse navbar-fixed-top"><div class="container"><div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand" href="/" hreflang="<?php echo $_SESSION['lang_prop'];?>"><?php echo $str['servicename']; ?></a></div><div class="collapse navbar-collapse"><ul class="nav navbar-nav"><li<?php if ($mode === 'register') echo ' class="active"'; ?>><a href="/<?php if (isset($_SESSION['info_id'])) {
$infoid = mysql_real_escape_string($_SESSION['info_id']);
$result = mysql_query("SELECT * FROM members WHERE userid='$infoid';");
$data = mysql_fetch_row($result);
echo $data[4];
}
else echo '_register';
?>" hreflang="<?php echo $_SESSION['lang_prop'];?>"><?php if (isset($_SESSION['info_id'])) echo $str['myinfo'];
else echo $str['register']; ?></a></li><li<?php if ($mode === 'login') echo ' class="active"'; ?>><a href="/<?php if (isset($_SESSION['info_id'])) echo '_logout'; else echo '_login'; ?>" hreflang="<?php echo $_SESSION['lang_prop'];?>"><?php if (isset($_SESSION['info_id'])) echo $str['logout']; else echo $str['login']; ?></a></li><li<?php if ($mode === 'list') echo ' class="active"'; ?>><a href="/_list" hreflang="<?php echo $_SESSION['lang_prop'];?>"><?php echo $str['userlist']; ?></a></li><li<?php if ($mode === 'status') echo ' class="active"'; ?>><a href="/_status" hreflang="<?php echo $_SESSION['lang_prop'];?>"><?php echo $str['status']; ?></a></li><?php
if (isset($_SESSION['info_id'])) {
echo '<li';
if ($mode == 'myinfo') {
echo ' class="active"';
}
echo '><a href="/_myinfo" hreflang="' . $_SESSION['lang_prop'] . '">' . $str['userinfo'] . '</a></li>';
}?></ul><ul class="nav navbar-nav navbar-right"><?php
if ($_SESSION['lang_prop'] !== 'ko') echo '<li><a hreflang="ko" href="/_lang/ko">' . $str['ko'] . '</a></li>';
if ($_SESSION['lang_prop'] !== 'ja') echo '<li><a hreflang="ja" href="/_lang/ja">' . $str['ja'] . '</a></li>';
if ($_SESSION['lang_prop'] !== 'ya') echo '<li><a hreflang="ko" href="/_lang/ya">' . $str['ya'] . '</a></li>';
if ($_SESSION['lang_prop'] !== 'en') echo '<li><a hreflang="en" href="/_lang/en">' . $str['en'] . '</a></li>';
?></ul></div></diV></nav><div class="container main"><div class="panel content"><?php echo $content; ?></div></div><footer><div class="container">Developed by <a href="/HLETRD">HLETRD</a>(@HLETRD), Special thanks to <a href="/HYEA">HYEA</a>(@mega_kina)<br /><a target="_blank" href="/licenses.txt">Open Source Licenses</a> | Powered by <a target="_blank" href="http://hhvm.com/">HHVM</a> on <a target="_blank" href="http://nginx.org/">nginx</a> w/ <a target="_blank" href="http://www.centos.org/">CentOS</a>. DB powered by <a target="_blank" href="https://mariadb.org/">MariaDB</a> | Page built in <?php echo round(1000*(microtime(true) - $page_start)); ?>ms</div><div class="spacer15"></div></footer><script><?php echo $script;?></script><script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga'); ga('create', 'UA-36880204-3', 'auto'); ga('send', 'pageview');</script></body></html>