<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<div id="content-wrap">
<div class="center-block">
<article class="clearfix hr">
<h2><i class="fa fa-flag" aria-hidden="true"></i> ברוכים הבאים לעמותת חבר לי!</h2>
<div class="shadowy floated">
<img src="/images/group.jpg" title="חברי העמותה" alt="חברי העמותה">
<p>חברי העמותה (<a title="אודות העמותה" href="/about">קרא עוד</a>)</p>
</div>
<p>עמותת חבר לי היא עמותה לאימוץ כלבים, שהוקמה בינואר 2006 ופועלת בשיתוף פעולה יחודי עם השירותים הוטרינרים של גוש דן בצומת מסובים.</p>
<p>פעילות העמותה הן פועה הורוביץ, דינה בר, נטע ברוק, מוניק רון, שיר יוסף, נטלי זגדון, שובל גלעד ועוד מתנדבים נאמנים.</p>
<p>מאז הקמתה מצאה העמותה בתים טובים לכ-2500 כלבים שהיו אמורים לסיים את חייהם בצורה שונה.</p>
<p>מטרת העמותה לחלץ את הכלבים בעוד מועד ולשכנם בפנסיונים שונים עד למציאת בתים מאמצים הולמים לכולם.</p>
<p>הכלבים כולם בעלי שבב אלקטרוני, מחוסנים בחיסון כלבת ומשושה, הנקבות מעוקרות והזכרים מסורסים.</p>
</article>
<?php
$result = $mysqli->query('SELECT DATE_FORMAT(postDate, "%d/%m/%Y") postDate, hebrewTitle, hebrewDescription, image, UNIX_TIMESTAMP(eventDate) eventDate, DATE_FORMAT(startHour, "%H:%i") startHour, DATE_FORMAT(endHour, "%H:%i") endHour FROM event ORDER BY postDate DESC LIMIT 3');
while ($row = $result->fetch_assoc())
{
	if ($row['eventDate'] != null && $row['eventDate'] + 86400 < time())
		continue;
	echo '<article class="clearfix"><h3>';
	if ($row['eventDate'] == null)
		echo '<i class="fa fa-newspaper-o" aria-hidden="true"></i>';
	else
		echo '<i class="fa fa-calendar" aria-hidden="true"></i>';
	echo $row['hebrewTitle'];
	if ($row['eventDate'] == null)
		echo '<span>פורסם בתאריך ' . $row['postDate'] . '</span>';
	else
		echo '<span>מתקיים בתאריך ' . date('d/m', $row['eventDate']) . ' בין השעות ' . $row['startHour'] . ' - ' . $row['endHour'] . '</span>';
	echo '</h3>';
	if ($row['image'] != null)
	{
		list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/events/' . $row['image']);
		echo '<a class="floated imageModal" href="/images/events/' . $row['image'] . '" title="' . $row['hebrewTitle'] . '" data-width="' . $width . '" data-height="' . $height . '"><img class="' . ($width > $height ? 'wide' : 'height') . '" src="/images/events/' . $row['image'] . '" title="' . $row['hebrewTitle'] . '" alt="' . $row['hebrewTitle'] . '"></a>';
	}
	echo '<p>' . $row['hebrewDescription'] . '</p></article>';
}
$result->free();
?>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>