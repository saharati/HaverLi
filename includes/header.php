<header>
<nav id="topNav">
<div>
<a title="כניסה למערכת" href="/login">כניסה למערכת</a>
<ul>
<li><a title="רשימת משאלות" href="/wishlist">רשימת משאלות <img title="רשימת משאלות" alt="רשימת משאלות" src="/images/heart.svg"></a></li>
<li><a title="לתרומות לחצו כאן" href="/help">לתרומות <img title="לתרומות לחצו כאן" alt="לתרומות לחצו כאן" src="/images/paw.svg"></a></li>
</ul>
</div>
</nav>
<div id="headBorder">
<div>
<h1>
<a href="/" title="עמותת חבר לי - לחברים עוזרים תמיד">
<img id="desktoptitle" src="/images/title.svg" title="עמותת חבר לי - לחברים עוזרים תמיד" alt="עמותת חבר לי - לחברים עוזרים תמיד">
<img id="mobiletitle" src="/images/titlemobile.svg" title="עמותת חבר לי - לחברים עוזרים תמיד" alt="עמותת חבר לי - לחברים עוזרים תמיד">
</a>
</h1>
<nav>
<ul>
<li id="submenuLink"><a title="תפריט" href="javascript:void(0);" onclick="switchContent();"><img title="תפריט" alt="תפריט" src="/images/bars.svg"> תפריט</a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/index.php') echo 'class="active"'; ?> title="דף הבית" href="/">דף הבית</a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/about.php') echo 'class="active"'; ?> title="אודות" href="/about">אודות</a></li>
<li>
<a <?php if ($_SERVER['PHP_SELF'] == '/dogs.php' || $_SERVER['PHP_SELF'] == '/cats.php' || $_SERVER['PHP_SELF'] == '/process.php' || $_SERVER['PHP_SELF'] == '/adopted.php' || $_SERVER['PHP_SELF'] == '/pet.php') echo 'class="active"'; ?> title="פינת אימוץ" href="javascript:void(0);">פינת אימוץ</a>
<nav>
<div>
<ul>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/cats.php') echo 'class="active"'; ?> title="חתולים" href="/cats"><img title="חתולים" alt="חתולים" src="/images/cat.svg"><span>חתולים</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/dogs.php') echo 'class="active"'; ?> title="כלבים" href="/dogs"><img title="כלבים" alt="כלבים" src="/images/dog.svg"><span>כלבים</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/process.php') echo 'class="active"'; ?> title="תהליך אימוץ" href="/process"><img title="תהליך אימוץ" alt="תהליך אימוץ" src="/images/stairs.svg"><span>תהליך אימוץ</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/adopted.php') echo 'class="active"'; ?> title="משפחות מאושרות" href="/adopted"><img title="משפחות מאושרות" alt="משפחות מאושרות" src="/images/home.svg"><span>משפחות מאושרות</span></a></li>
</ul>
</div>
</nav>
</li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/help.php') echo 'class="active"'; ?> title="איך ניתן לעזור?" href="/help">איך ניתן לעזור?</a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/board.php') echo 'class="active"'; ?> title="לוח מודעות" href="/board">לוח מודעות</a></li>
<li>
<a <?php if ($_SERVER['PHP_SELF'] == '/recommand.php' || $_SERVER['PHP_SELF'] == '/lost.php' || $_SERVER['PHP_SELF'] == '/articles.php' || $_SERVER['PHP_SELF'] == '/info.php' || $_SERVER['PHP_SELF'] == '/article.php') echo 'class="active"'; ?> title="מידע שימושי" href="javascript:void(0);">מידע שימושי</a>
<nav>
<div>
<ul>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/info.php') echo 'class="active"'; ?> title="מידע למאמץ" href="/info"><img title="מידע למאמץ" alt="מידע למאמץ" src="/images/mark.svg"><span>מידע למאמץ</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/articles.php' || $_SERVER['PHP_SELF'] == '/article.php') echo 'class="active"'; ?> title="מאמרים" href="/articles"><img title="מאמרים" alt="מאמרים" src="/images/hat.svg"><span>מאמרים</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/lost.php') echo 'class="active"'; ?> title="הצילו! אבד לי הכלב" href="/lost"><img title="הצילו! אבד לי הכלב" alt="הצילו! אבד לי הכלב" src="/images/dog.svg"><span>הצילו! אבד לי הכלב</span></a></li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/recommand.php') echo 'class="active"'; ?> title="המומלצים שלנו" href="/recommand"><img title="המומלצים שלנו" alt="המומלצים שלנו" src="/images/badge.svg"><span>המומלצים שלנו</span></a></li>
</ul>
</div>
</nav>
</li>
<li><a <?php if ($_SERVER['PHP_SELF'] == '/contact.php') echo 'class="active"'; ?> title="צור קשר" href="/contact">צור קשר</a></li>
</ul>
</nav>
</div>
</div>
</header>