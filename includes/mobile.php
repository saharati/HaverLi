<nav>
<ul>
<li class="firstLevel"><a <?php if ($_SERVER['PHP_SELF'] == '/index.php') echo 'class="active"'; ?> title="דף הבית" href="/">דף הבית</a></li>
<li class="firstLevel"><a <?php if ($_SERVER['PHP_SELF'] == '/about.php') echo 'class="active"'; ?> title="אודות" href="/about">אודות</a></li>
<li class="firstLevel">
<a <?php if ($_SERVER['PHP_SELF'] == '/dogs.php' || $_SERVER['PHP_SELF'] == '/cats.php' || $_SERVER['PHP_SELF'] == '/process.php' || $_SERVER['PHP_SELF'] == '/adopted.php' || $_SERVER['PHP_SELF'] == '/pet.php') echo 'class="active"'; ?> title="פינת אימוץ" href="javascript:void(0);" onclick="toggleView(this.parentNode);">פינת אימוץ</a>
<ul>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/cats.php') echo 'class="active"'; ?> title="חתולים" href="/cats"><img title="חתולים" alt="חתולים" src="/images/cat.svg"><span>חתולים</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/dogs.php') echo 'class="active"'; ?> title="כלבים" href="/dogs"><img title="כלבים" alt="כלבים" src="/images/dog.svg"><span>כלבים</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/process.php') echo 'class="active"'; ?> title="תהליך אימוץ" href="/process"><img title="תהליך אימוץ" alt="תהליך אימוץ" src="/images/stairs.svg"><span>תהליך אימוץ</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/adopted.php') echo 'class="active"'; ?> title="משפחות מאושרות" href="/adopted"><img title="משפחות מאושרות" alt="משפחות מאושרות" src="/images/home.svg"><span>משפחות מאושרות</span></a></li>
</ul>
</li>
<li class="firstLevel">
<a <?php if ($_SERVER['PHP_SELF'] == '/donate.php' || $_SERVER['PHP_SELF'] == '/volunteer.php' || $_SERVER['PHP_SELF'] == '/foster.php') echo 'class="active"'; ?> title="איך ניתן לעזור?" href="javascript:void(0);" onclick="toggleView(this.parentNode);">איך ניתן לעזור?</a>
<ul>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/donate.php') echo 'class="active"'; ?> title="תרומות" href="/donate"><img title="תרומות" alt="תרומות" src="/images/donate.svg"><span>תרומות</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/volunteer.php') echo 'class="active"'; ?> title="התנדבות" href="/volunteer"><img title="התנדבות" alt="התנדבות" src="/images/volunteer.svg"><span>התנדבות</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/foster.php') echo 'class="active"'; ?> title="בתי אומנה" href="/foster"><img title="בתי אומנה" alt="בתי אומנה" src="/images/foster.svg"><span>בתי אומנה</span></a></li>
</ul>
</li>
<li class="firstLevel"><a <?php if ($_SERVER['PHP_SELF'] == '/board.php') echo 'class="active"'; ?> title="לוח מודעות" href="/board">לוח מודעות</a></li>
<li class="firstLevel">
<a <?php if ($_SERVER['PHP_SELF'] == '/recommend.php' || $_SERVER['PHP_SELF'] == '/lost.php' || $_SERVER['PHP_SELF'] == '/articles.php' || $_SERVER['PHP_SELF'] == '/info.php' || $_SERVER['PHP_SELF'] == '/article.php') echo 'class="active"'; ?> title="מידע שימושי" href="javascript:void(0);" onclick="toggleView(this.parentNode);">מידע שימושי</a>
<ul>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/info.php') echo 'class="active"'; ?> title="מידע למאמץ" href="/info"><img title="מידע למאמץ" alt="מידע למאמץ" src="/images/mark.svg"><span>מידע למאמץ</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/articles.php' || $_SERVER['PHP_SELF'] == '/article.php') echo 'class="active"'; ?> title="מאמרים" href="/articles"><img title="מאמרים" alt="מאמרים" src="/images/hat.svg"><span>מאמרים</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/lost.php') echo 'class="active"'; ?> title="הצילו! אבד לי הכלב" href="/lost"><img title="הצילו! אבד לי הכלב" alt="הצילו! אבד לי הכלב" src="/images/dog.svg"><span>הצילו! אבד לי הכלב</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/recommend.php') echo 'class="active"'; ?> title="המומלצים שלנו" href="/recommend"><img title="המומלצים שלנו" alt="המומלצים שלנו" src="/images/badge.svg"><span>המומלצים שלנו</span></a></li>
</ul>
</li>
<li class="firstLevel"><a <?php if ($_SERVER['PHP_SELF'] == '/contact.php') echo 'class="active"'; ?> title="צור קשר" href="/contact">צור קשר</a></li>
</ul>
</nav>