<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$metaData = $mysqli->query('SELECT title, description, url, image FROM promote WHERE page="about"');
$md = $metaData->fetch_assoc();
$metaData->free();
$page_title = htmlspecialchars($md['title'], ENT_QUOTES);
$page_description = htmlspecialchars(str_replace(array("\r", "\n"), array('', ' '), $md['description']), ENT_QUOTES);
$page_url = $md['url'];
if (!empty($md['image']))
{
	$page_image = 'http://imutz.org/images/og/' . $md['image'];
	list($page_image_width, $page_image_height) = getimagesize($page_image);
}
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