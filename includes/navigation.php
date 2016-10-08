<?php
if (!isset($page))
	$page = 1;
?>
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
<li><a class="active" href="/" title="עברית">עב</a></li>
<li><a href="/en/" title="English">EN</a></li>
<li><a href="/ru/" title="Ру́сский">РУ</a></li>
</ul>
<form action="/search">​
<input type="search" name="text" placeholder="חפש באתר...">
<button><i class="fa fa-search"></i></button>
</form>
</li>
</ul>
</nav>