<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$page_title = 'עמותת חבר לי - אודות';
$page_description = "עמותת חבר לי היא עמותה לאימוץ כלבים, שהוקמה ב 2006 ופועלת בשיתוף פעולה ייחודי עם השירותים הוטרינרים של גוש דן. \r\nמאז הקמתה מצאה העמותה בתים טובים ללמעלה מ- 2500 כלבים שהיו אמורים לסיים את חייהם בצורה שונה.";
$page_url = 'http://imutz.org/about';
$page_image = 'http://imutz.org/images/og/about.jpg';
$page_image_width = 1200;
$page_image_height = 630;
require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="about fullwidth">
<div id="contentInner">
<?php
$result = $mysqli->query('SELECT * FROM about');
$row = $result->fetch_assoc();
$result->free();
if ($row)
{
	echo '<div class="innerDiv">' . $row['aboutdescription'] . '</div>
<div class="spaceDiv"></div>';
}
echo '<div class="innerDiv">';
if ($row)
	echo $row['volunteersdescription'];
$result = $mysqli->query('SELECT image, imageName, imageCaption FROM volunteer ORDER BY imageOrder');
while ($row2 = $result->fetch_assoc())
	echo '<div class="rectDiv clearfix"><img src="/images/volunteers/' . $row2['image'] . '" title="' . $row2['imageName'] . '" alt="' . $row2['imageName'] . '"><h3>' . $row2['imageName'] . '</h3>' . $row2['imageCaption'] . '</div>';
$result->free();
echo '</div>
<div class="spaceDiv"></div>';
if ($row)
{
	echo '<div class="innerDiv">';
	if (empty($row['imageLink']))
		echo '<img src="/images/pages/' . $row['image'] . '" alt="">';
	else
		echo '<a href="' . $row['imageLink'] . '"><img src="/images/pages/' . $row['image'] . '" alt=""></a>';
	echo '</div>
<div class="spaceDiv"></div>';
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