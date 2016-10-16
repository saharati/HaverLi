<?php
if (!isset($mysqli))
	exit;
$result = $mysqli->query('SELECT COUNT(id) c, isDog, isAdopted FROM album GROUP BY isDog, isAdopted');
$pets = array_fill(0, 4, 0);
while ($row = $result->fetch_assoc())
{
	if ($row['isDog'])
		$row['isDog']++;
	$pets[$row['isDog'] + $row['isAdopted']] = $row['c'];
}
$result->free();
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/he_IL/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<header>
<div class="center-block">
<div id="header-title">
<h1>
<a href="/" title="עמותת חבר לי - לחברים עוזרים תמיד">
<img src="/images/title.png" title="עמותת חבר לי - לחברים עוזרים תמיד" alt="עמותת חבר לי - לחברים עוזרים תמיד">
</a>
</h1>
</div>
<div id="header-adoptdog">
<div>
<ul>
<li class="adoptdog"><a title="כלבים לאימוץ" href="/adopt?isDog=1">כלבים לאימוץ: <?php echo $pets[2]; ?></a></li>
<li class="adopteddog"><a title="כלבים שאומצו" href="/adopted?isDog=1">כלבים שאומצו: <?php echo $pets[3]; ?></a></li>
</ul>
</div>
<a href="/adopt?isDog=1" title="פינת אימוץ כלבים">
<img src="/images/adoptdog.png" title="פינת אימוץ כלבים" alt="פינת אימוץ כלבים">
</a>
</div>
<div id="header-adoptcat">
<div>
<ul>
<li class="adoptcat"><a title="חתולים לאימוץ" href="/adopt?isDog=0">חתולים לאימוץ: <?php echo $pets[0]; ?></a></li>
<li class="adoptedcat"><a title="חתולים שאומצו" href="/adopted?isDog=0">חתולים שאומצו: <?php echo $pets[1]; ?></a></li>
</ul>
</div>
<a href="/adopt?isDog=0" title="פינת אימוץ חתולים">
<img src="/images/adoptcat.png" title="פינת אימוץ חתולים" alt="פינת אימוץ חתולים">
</a>
</div>
</div>
<nav>
<ul id="site-navigation">
<li>
<a <?php if ($_SERVER['PHP_SELF'] == '/index.php') echo 'class="active"'; ?> href="/" title="דף הבית">
<i class="fa fa-home" aria-hidden="true"></i>
<strong>דף הבית</strong>
<small>פעילות וימי אימוץ</small>
</a>
</li>
<li>
<a <?php if ($_SERVER['PHP_SELF'] == '/adopt.php') echo 'class="active"'; ?> href="/adopt" title="פינת אימוץ">
<i class="fa fa-paw" aria-hidden="true"></i>
<strong>פינת אימוץ</strong>
<small>כלבים וחתולים לאימוץ</small>
</a>
</li>
<li>
<a <?php if ($_SERVER['PHP_SELF'] == '/adopted.php') echo 'class="active"'; ?> id="last-a" href="/adopted" title="מצאו בית">
<i class="fa fa-font-awesome" aria-hidden="true"></i>
<strong>מצאו בית</strong>
<small>כלבים וחתולים שאומצו</small>
</a>
</li>
<li class="help-link">
<a <?php if ($_SERVER['PHP_SELF'] == '/help.php') echo 'class="active"'; ?> href="/help" title="עזרו לנו">
<i class="fa fa-heartbeat" aria-hidden="true"></i>
<strong>עזרו לנו</strong>
<small>עזרה מצילה חיים</small>
</a>
</li>
<li class="articles-link">
<a <?php if ($_SERVER['PHP_SELF'] == '/articles.php') echo 'class="active"'; ?> href="/articles" title="מאמרים">
<i class="fa fa-book" aria-hidden="true"></i>
<strong>מאמרים</strong>
<small>מידע שימושי למאמצים</small>
</a>
</li>
<li class="videos-link">
<a <?php if ($_SERVER['PHP_SELF'] == '/videos.php') echo 'class="active"'; ?> href="/videos" title="סרטונים">
<i class="fa fa-youtube-play" aria-hidden="true"></i>
<strong>סרטונים</strong>
<small>למידת אילוף ופנאי</small>
</a>
</li>
<li>
<ul id="sub-navigation">
<li>
<a href="javascript:void(0);" title="לחץ לקישורים נוספים" class="fa fa-navicon" aria-hidden="true"></a>
<ul>
<li class="help-link <?php if ($_SERVER['PHP_SELF'] == '/help.php') echo 'active'; ?>"><a title="עזרו לנו" href="/help"><i class="fa fa-heartbeat" aria-hidden="true"></i>עזרו לנו</a></li>
<li class="articles-link <?php if ($_SERVER['PHP_SELF'] == '/articles.php') echo 'active'; ?>"><a title="מאמרים" href="/articles"><i class="fa fa-book" aria-hidden="true"></i>מאמרים</a></li>
<li class="videos-link <?php if ($_SERVER['PHP_SELF'] == '/videos.php') echo 'active'; ?>"><a title="סרטונים" href="/videos"><i class="fa fa-youtube-play" aria-hidden="true"></i>סרטונים</a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/contact.php') echo 'class="active"'; ?> title="צרו קשר" href="/contact"><i class="fa fa-envelope" aria-hidden="true"></i>יצירת קשר</a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/login.php') echo 'class="active"'; ?> title="כניסת משתמשים" href="/login"><i class="fa fa-sign-in" aria-hidden="true"></i>כניסת משתמשים</a></li>
</ul>
</li>
<li><a class="active" title="עברית" href="/">עב</a></li>
<li><a title="English" href="/en/">EN</a></li>
<li><a title="Ру́сский" href="/ru/">РУ</a></li>
</ul>
<form action="/search">​
<input type="search" name="text" placeholder="חפש באתר..."><button title="חפש"><i class="fa fa-search" aria-hidden="true"></i></button>
</form>
</li>
</ul>
</nav>
</header>