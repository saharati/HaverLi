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
<li class="adoptdog"><a href="/adopt-dog" title="כלבים לאימוץ">כלבים לאימוץ: <?php echo $pets[2]; ?></a></li>
<li class="adopteddog"><a href="/adopted-dog" title="כלבים שאומצו">כלבים שאומצו: <?php echo $pets[3]; ?></a></li>
</ul>
</div>
<a href="/adopt-dog" title="פינת אימוץ כלבים">
<img src="/images/adoptdog.png" title="פינת אימוץ כלבים" alt="פינת אימוץ כלבים">
</a>
</section>
<section id="header-adoptcat">
<div>
<ul>
<li class="adoptcat"><a href="/adopt-cat" title="חתולים לאימוץ">חתולים לאימוץ: <?php echo $pets[0]; ?></a></li>
<li class="adoptedcat"><a href="/adopted-cat" title="חתולים שאומצו">חתולים שאומצו: <?php echo $pets[1]; ?></a></li>
</ul>
</div>
<a href="/adopt-cat" title="פינת אימוץ חתולים">
<img src="/images/adoptcat.png" title="פינת אימוץ חתולים" alt="פינת אימוץ חתולים">
</a>
</section>
</div>