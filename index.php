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
<article class="clearfix">
<h2><i class="fa fa-flag" aria-hidden="true"></i> ברוכים הבאים לעמותת חבר לי!</h2>
<div class="shadowy floated">
<img class="wide" src="/images/group.jpg" title="חברי העמותה" alt="חברי העמותה">
<p>חברי העמותה (<a title="אודות העמותה" href="#usefulInfo">קרא עוד</a>)</p>
</div>
<p>עמותת חבר לי היא עמותה לאימוץ כלבים, שהוקמה בינואר 2006 ופועלת בשיתוף פעולה יחודי עם השירותים הוטרינרים של גוש דן בצומת מסובים.</p>
<p>פעילות העמותה הן פועה הורוביץ, דינה בר, נטע ברוק, מוניק רון, שיר יוסף, נטלי זגדון, שובל גלעד ועוד מתנדבים נאמנים.</p>
<p>מאז הקמתה מצאה העמותה בתים טובים לכ-2500 כלבים שהיו אמורים לסיים את חייהם בצורה שונה.</p>
<p>מטרת העמותה לחלץ את הכלבים בעוד מועד ולשכנם בפנסיונים שונים עד למציאת בתים מאמצים הולמים לכולם.</p>
<p>הכלבים כולם בעלי שבב אלקטרוני, מחוסנים בחיסון כלבת ומשושה, הנקבות מעוקרות והזכרים מסורסים.</p>
</article>
</div>
<div class="petsBoard">
<section class="petsInner">
<h3>הכלבתולים שלנו <i class="fa fa-gratipay" aria-hidden="true"></i> ממתינים לאימוץ</h3>
<div class="petsDiv">
<?php
$result = $mysqli->query('SELECT hebrewName, albumId, smallpic, smallwidth, smallheight FROM album LEFT JOIN album_photo ON album.id=albumId WHERE isAdopted=0 AND isCover=1 AND smallwidth>smallheight ORDER BY postDate DESC LIMIT 15');
while ($row = $result->fetch_assoc())
{
	if ($row['smallheight'] < 100)
	{
		$newWidth = ($row['smallwidth'] / $row['smallheight']) * 100;
		if ($newWidth > 150)
			$margin = ($newWidth - 150) / 2;
		else
			$margin = 0;
		$style = 'style="width:auto;height:100%;margin-left:-' . $margin . 'px"';
	}
	else
		$style = 'style="margin-top:-' . (($row['smallheight'] - 100) / 2) . 'px"';
	echo '<div class="shadowy"><div><a title="' . $row['hebrewName'] . ' לאימוץ" href="/pet-' . $row['albumId'] . '"><img ' . $style . ' src="/images/albums/' . $row['albumId'] . '/thumbs/' . $row['smallpic'] . '" title="' . $row['hebrewName'] . ' לאימוץ" alt="' . $row['hebrewName'] . ' לאימוץ"></a></div></div>';
}
$result->free();
?>
</div>
</section>
</div>
<div class="center-block">
<h2><i class="fa fa-bullhorn" aria-hidden="true"></i> חדשות ואירועים</h2>
<?php
$result = $mysqli->query('SELECT DATE_FORMAT(postDate, "%d/%m/%Y") postDate, hebrewTitle, hebrewDescription, image, UNIX_TIMESTAMP(eventDate) eventDate, DATE_FORMAT(startHour, "%H:%i") startHour, DATE_FORMAT(endHour, "%H:%i") endHour FROM event ORDER BY postDate DESC LIMIT 3');
if ($result->num_rows)
{
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
}
else
	echo '<p>אין חדשות ואירועים להצגה כרגע.</p>';
$result->free();
?>
</div>
<div class="petsBoard">
<form action="/adopt" class="petsInner">
<div class="petsDiv">
<img src="/images/pets.png" title="בעלי חיים לאימוץ" alt="בעלי חיים לאימוץ">
<h3>מצא את
<i>
<label><input type="radio" name="isDog" value="1" checked> כלב</label>
<label><input type="radio" name="isDog" value="0"> חתול</label>
</i>
חלומותיך ב-3 בחירות בלבד!</h3>
<fieldset>
<label for="isAdult" class="special-label">גיל</label>
<div id="isAdult" class="special-select"><div>בחר</div>
<ul class="dropdown">
<li><input type="radio" id="isAdult-1" name="isAdult" value="1"><label for="isAdult-1">בוגר</label></li>
<li><input type="radio" id="isAdult-0" name="isAdult" value="0"><label for="isAdult-0">גור</label></li>
</ul>
</div>
<label for="isMale" class="special-label">מין</label>
<div id="isMale" class="special-select"><div>בחר</div>
<ul class="dropdown">
<li><input type="radio" id="isMale-1" name="isMale" value="1"><label for="isMale-1">זכר</label></li>
<li><input type="radio" id="isMale-0" name="isMale" value="0"><label for="isMale-0">נקבה</label></li>
</ul>
</div>
<label for="size" class="special-label">גודל</label>
<div id="size" class="special-select"><div>בחר</div>
<ul class="dropdown">
<li><input type="checkbox" id="size-1" name="size[]" value="1"><label for="size-1">קטן</label></li>
<li><input type="checkbox" id="size-2" name="size[]" value="2"><label for="size-2">בינוני</label></li>
<li><input type="checkbox" id="size-3" name="size[]" value="3"><label for="size-3">גדול</label></li>
</ul>
</div>
</fieldset>
<button title="חפש"><img title="חפש" alt="חפש" src="/images/search.png"></button>
</div>
</form>
</div>
<div id="usefulInfo" class="center-block">
<h2><i class="fa fa-info-circle" aria-hidden="true"></i> מידע שימושי</h2>
<article>
<h3><i class="fa fa-trophy" aria-hidden="true"></i> מטרת העמותה</h3>
<p>
לחלץ את הכלבים בעוד מועד ולשכנם בפנסיונים שונים עד למציאת בתים מאמצים הולמים לכולם.<br>
מטרה נוספת של העמותה היא לפתח וליישם דרכי חשיפה ייחודיות כדי לקרב בין המשפחות, המחפשות לאמץ כלב\ה, לכלבים עצמם. התאמת הכלב למשפחה נעשית ביסודיות, בקפידה וביחס אישי, הממשיך גם אחרי האימוץ.<br>
*כלבי העמותה כולם בעלי שבב אלקטרוני, מחוסנים בחיסון כלבת ומשושה, הנקבות מעוקרות והזכרים מסורסים. האימוץ כרוך בתשלום מסובסד עבור עיקור\סירוס וחיסון כלבת ומשושה.<br>
*כל פעילי העמותה עובדים בהתנדבות ושכרם הוא הסיפוק שבמציאת בתים טובים לכלבים ובקריאת מכתבי התודה שאנחנו מקבלים ממשפחות מאמצות.
</p>
<p>
מתנדבות העמותה הפעילות הן:פועה הורוביץ, דינה בר, נטע ברוק, רעות אבו, לילי קוזמו, שלומית מילוא, שובל גלעד, שיר יוסף, נטלי זגדון, מוניק רון ועוד.
</p>
</article>
<article>
<h3><i class="fa fa-clock-o" aria-hidden="true"></i> שעות פעילות וימי אימוץ</h3>
<p>
אנו פעילים בכל ימות השבוע בכלביית השירותים הוטרינרים של גוש דן בצומת מסובים. בנוסף, בכל ימות השבוע ניתן לאמץ כלבים בפנסיונים שונים עימם פועלת העמותה בשיתוף פעולה או לאמץ ישירות מהמשפחות האומנות של העמותה. חובה לתאם מראש עם פועה-0507548187 או דינה-0542030203<br>
לידיעת המבקרים באתר, כל יום שישי מתקיים יום אימוץ בשירותים הוטרינרים של גוש דן שבצומת מסובים, בין 9 וחצי ל-11 וחצי. כדאי להתקשר לפני שמגיעים.
</p>
<p>
פעילי העמותה מומחים בשידוך בין כלבים למשפחות מאמצות\אומנות ושמחים תמיד לעזור בסבלנות ובליווי צמוד בתקופה הראשונה.
</p>
<p>
בואו לאמץ כלב או להיות משפחה אומנת לאחד החמודים שלנו, כדי שלא יעברו את החורף הקר בכלבייה!!!
</p>
</article>
<article>
<h3><i class="fa fa-map-marker" aria-hidden="true"></i> דרכי הגעה למחלקה הוטרינרית גוש דן</h3>
<p>
* מגהה דרום - יורדים בצומת מסובים לכיוון תל אביב, עוברים את דור אלון וכ-50 מטר אחרי תחנת הדלק יש תחנת אוטובוס. מטרים ספורים אחרי התחנה יש ירידה ימינה לכביש צדדי. נוסעים עד סופו ובצומת ה-T  פונים שמאלה.<br>
* מגהה צפון - בצומת מסובים פונים ימינה לכיוון תל אביב, נוסעים לאט ומיד אחרי תחנת האוטובוס פונים ימינה לכביש צדדי. נוסעים עד סופו ובצות ה-T פונים שמאלה.<br>
* מדרך לוד - עושים סיבוב פרסה בצומת מסובים, עוברים את תחנת הדלק דור אלון ומיד טחרי תחנת האוטובוס הראשונה, פונים ימינה לכביש צדדי. נוסעים עד לצומת T ופונים שמאלה.<br>
* מצומת סביון - נוסעים לכיוון תל אביב, עוברים את צומת מסובים (יש תחנת דלק דור אלון מימין) ובתחנת האוטובוס יורדים ימינה לכביש צדדי. נוסעים עד הסוף ובצומת ה-T פונים שמאלה. המבנה יהיה בצד שמאל.
</p>
</article>
<article>
<h3><i class="fa fa-phone" aria-hidden="true"></i> צרו קשר ותדעו מתי יתקיים מפגש האימוץ הבא</h3>
<p>
פועה 050-7548187<br>
דינה 050-2204784<br>
</p>
</article>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>