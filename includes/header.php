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
if (!isset($page))
	$page = 1;
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
<section id="header-title">
<h1>
<a href="/" title="עמותת חבר לי - לחברים עוזרים תמיד">
<img src="/images/title.png" title="עמותת חבר לי - לחברים עוזרים תמיד" alt="עמותת חבר לי - לחברים עוזרים תמיד">
</a>
</h1>
</section>
<section id="header-adoptdog">
<div>
<ul>
<li class="adoptdog"><a title="כלבים לאימוץ" href="/adopt-dog">כלבים לאימוץ: <?php echo $pets[2]; ?></a></li>
<li class="adopteddog"><a title="כלבים שאומצו" href="/adopted-dog">כלבים שאומצו: <?php echo $pets[3]; ?></a></li>
</ul>
</div>
<a href="/adopt-dog" title="פינת אימוץ כלבים">
<img src="/images/adoptdog.png" title="פינת אימוץ כלבים" alt="פינת אימוץ כלבים">
</a>
</section>
<section id="header-adoptcat">
<div>
<ul>
<li class="adoptcat"><a title="חתולים לאימוץ" href="/adopt-cat">חתולים לאימוץ: <?php echo $pets[0]; ?></a></li>
<li class="adoptedcat"><a title="חתולים שאומצו" href="/adopted-cat">חתולים שאומצו: <?php echo $pets[1]; ?></a></li>
</ul>
</div>
<a href="/adopt-cat" title="פינת אימוץ חתולים">
<img src="/images/adoptcat.png" title="פינת אימוץ חתולים" alt="פינת אימוץ חתולים">
</a>
</section>
</div>
<nav>
<ul id="site-navigation">
<li>
<a <?php if ($page == 1) echo 'class="active"'; ?> href="/" title="דף הבית">
<i class="fa fa-home"></i>
<strong>דף הבית</strong>
<small>אירועים וימי אימוץ</small>
</a>
</li>
<li>
<a <?php if ($page == 2) echo 'class="active"'; ?> href="/adopt" title="פינת אימוץ">
<i class="fa fa-paw"></i>
<strong>פינת אימוץ</strong>
<small>כלבים וחתולים לאימוץ</small>
</a>
</li>
<li>
<a <?php if ($page == 3) echo 'class="active"'; ?> id="last-a" href="/adopted" title="מצאו בית">
<i class="fa fa-font-awesome"></i>
<strong>מצאו בית</strong>
<small>כלבים וחתולים שאומצו</small>
</a>
</li>
<li class="donate-link">
<a <?php if ($page == 4) echo 'class="active"'; ?> href="/donate" title="תרמו לנו">
<i class="fa fa-bullhorn"></i>
<strong>תרמו לנו</strong>
<small>תרומה מצילה חיים</small>
</a>
</li>
<li class="articles-link">
<a <?php if ($page == 5) echo 'class="active"'; ?> href="/articles" title="מאמרים">
<i class="fa fa-book"></i>
<strong>מאמרים</strong>
<small>מידע שימושי למאמצים</small>
</a>
</li>
<li class="videos-link">
<a <?php if ($page == 6) echo 'class="active"'; ?> href="/videos" title="סרטונים">
<i class="fa fa-youtube-play"></i>
<strong>סרטונים</strong>
<small>למידת אילוף ופנאי</small>
</a>
</li>
<li>
<ul id="sub-navigation">
<li>
<a href="javascript:void(0);" title="לחץ לקישורים נוספים" class="fa fa-navicon"></a>
<ul>
<li class="donate-link <?php if ($page == 4) echo 'active'; ?>"><a title="תרמו לנו" href="/donate"><i class="fa fa-bullhorn"></i>תרמו לנו</a></li>
<li class="articles-link <?php if ($page == 5) echo 'active'; ?>"><a title="מאמרים" href="/articles"><i class="fa fa-book"></i>מאמרים</a></li>
<li class="videos-link <?php if ($page == 6) echo 'active'; ?>"><a title="סרטונים" href="/videos"><i class="fa fa-youtube-play"></i>סרטונים</a></li>
<li><a <?php if ($page == 7) echo 'active'; ?> title="אודותינו" href="/about"><i class="fa fa-edit"></i>אודותינו</a></li>
<li><a <?php if ($page == 8) echo 'active'; ?> title="צרו קשר" href="/contact"><i class="fa fa-envelope"></i>יצירת קשר</a></li>
<li><a <?php if ($page == 9) echo 'active'; ?> title="קישורים מומלצים" href="/links"><i class="fa fa-external-link"></i>קישורים מומלצים</a></li>
<li><a <?php if ($page == 10) echo 'active'; ?> title="כניסת משתמשים" href="/login"><i class="fa fa-sign-in"></i>כניסת משתמשים</a></li>
</ul>
</li>
<li><a class="active" title="עברית" href="/">עב</a></li>
<li><a title="English" href="/en/">EN</a></li>
<li><a title="Ру́сский" href="/ru/">РУ</a></li>
</ul>
<form action="/search">​
<input type="search" name="text" placeholder="חפש באתר...">
<button><i class="fa fa-search"></i></button>
</form>
</li>
</ul>
</nav>
</header>