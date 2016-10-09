<?php
if (!isset($mysqli))
	exit;
?>
<footer>
<div class="center-block">
<section>
<h3><i class="fa fa-leaf" aria-hidden="true"></i> אודות <span>(<a title="אודות עמותת חבר לי" href="/about">קרא עוד</a>)</span></h3>
<ul>
<li>&copy; 2005 - <?php echo date('Y'); ?> כל הזכויות שמורות</li>
<li><a title="עמותת חבר לי - לחברים עוזרים תמיד" href="/">עמותת חבר לי - לחברים עוזרים תמיד</a></li>
<li>ע"ר ללא מטרות רווח מס' 580451821</li>
<li>האתר נבנה ומתוחזק ע"י <a href="http://www.facebook.com/sahar.ati" title="סהר אטיאס" target="_blank" rel="nofollow">סהר אטיאס</a></li>
</ul>
</section>
<section>
<h3><i class="fa fa-certificate" aria-hidden="true"></i> עדכונים אחרונים</h3>
<ul>
<?php
$result = $mysqli->query('SELECT id, UNIX_TIMESTAMP(postDate) postDate, hebrewName, isDog, isMale FROM album WHERE isAdopted=0 ORDER BY postDate DESC LIMIT 4');
while ($row = $result->fetch_assoc())
{
	if ($row['isMale'])
		$text = 'נוסף ' . ($row['isDog'] ? 'כלב' : 'חתול') . ' חדש לאימוץ';
	else
		$text = 'נוספה ' . ($row['isDog'] ? 'כלבה' : 'חתולה') . ' חדשה לאימוץ';
	echo '<li>' . date('d/m', $row['postDate']) . ' - ' . $text . ' - <a href="/pet-' . $row['id'] . '" title="' . $row['hebrewName'] . ' לאימוץ">' . $row['hebrewName'] . '</a></li>';
}
$result->free();
?>
</ul>
</section>
<section>
<h3><i class="fa fa-search-plus" aria-hidden="true"></i> חיפושים אחרונים</h3>
<ul>
<?php
$result = $mysqli->query('SELECT url, hebrewDescription FROM searches ORDER BY searchDate DESC LIMIT 4');
while ($row = $result->fetch_assoc())
{
	$row['shortDescr'] = mb_strlen($row['hebrewDescription']) > 30 ? mb_substr($row['hebrewDescription'], 0, 30) . '...' : $row['hebrewDescription'];
	echo '<li><a href="/adopt-' . $row['url'] . '" title="' . $row['hebrewDescription'] . '">' . $row['shortDescr'] . '</a></li>';
}
$result->free();
?>
</ul>
</section>
<section>
<h3><i class="fa fa-facebook-square" aria-hidden="true"></i> חפשו אותנו בפייסבוק</h3>
<div class="fb-like" data-href="https://www.facebook.com/haverli/" data-layout="standard" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
</section>
</div>
</footer>