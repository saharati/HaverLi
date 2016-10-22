<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="about fullwidth">
<div id="contentInner">
<?php
$result = $mysqli->query('SELECT * FROM help');
$row = $result->fetch_assoc();
$result->free();
if ($row)
	echo '<div class="innerDiv"><h2>תרומות</h2>' . $row['donateText'] . '</div>';
echo '<div class="spaceDiv">';
$result = $mysqli->query('SELECT image, image2 FROM help_image ORDER BY imageOrder');
if ($result->num_rows)
{
	echo '<div class="innerDiv">';
	$width = floor(100 / $result->num_rows);
	while ($row2 = $result->fetch_assoc())
		echo '<img style="width:' . $width . '%" src="/images/help/' . $row2['image'] . '" alt="" data-src="/images/help/' . $row2['image2'] . '" onmouseover="toggleSrc(this);" onmouseout="toggleSrc(this);">';
	echo '</div>';
}
$result->free();
echo '</div>';
if ($row)
{
	echo '<div class="innerDiv"><img src="/images/pages/' . $row['image1'] . '" alt=""></div><div class="spaceDiv"></div>
<div class="innerDiv"><h2>אימוץ וירטואלי</h2>' . $row['adoptText'] . '</div><div class="spaceDiv"></div>
<div class="innerDiv"><img src="/images/pages/' . $row['image2'] . '" alt=""></div><div class="spaceDiv"></div>
<div class="innerDiv"><h2>התנדבות</h2>' . $row['volunteerText'] . '</div><div class="spaceDiv"></div>
<div class="innerDiv"><img src="/images/pages/' . $row['image3'] . '" alt=""></div><div class="spaceDiv"></div>
<div class="innerDiv"><h2>אומנה</h2>' . $row['fosterText'] . '</div><div class="spaceDiv"></div>
<div class="innerDiv"><img src="/images/pages/' . $row['image4'] . '" alt=""></div><div class="spaceDiv"></div>';
}
?>
<div class="innerDiv">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/contact.php'; ?>
</div>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>