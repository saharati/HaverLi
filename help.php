<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$metaData = $mysqli->query('SELECT title, description, url, image FROM promote WHERE page="help"');
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
$result = $mysqli->query('SELECT position, title, caption, image FROM help ORDER BY position');
$row = $result->fetch_assoc();
if ($row)
	echo '<div id="section' . $row['position'] . '" class="innerDiv"><h2>' . $row['title'] . '</h2>' . $row['caption'] . '</div>';
echo '<div class="spaceDiv">';
$result2 = $mysqli->query('SELECT image, image2 FROM help_image ORDER BY imageOrder');
if ($result2->num_rows)
{
	echo '<div class="innerDiv">';
	$width = floor(100 / $result2->num_rows);
	while ($row2 = $result2->fetch_assoc())
		echo '<img style="width:' . $width . '%" src="/images/help/' . $row2['image'] . '" alt="" data-src="/images/help/' . $row2['image2'] . '" onmouseover="toggleSrc(this);" onmouseout="toggleSrc(this);">';
	echo '</div>';
}
$result2->free();
echo '</div>';
if ($row)
	echo '<div class="innerDiv"><img src="/images/pages/' . $row['image'] . '" alt=""></div><div class="spaceDiv"></div>';
while ($row = $result->fetch_assoc())
	echo '<div id="section' . $row['position'] . '" class="innerDiv"><h2>' . $row['title'] . '</h2>' . $row['caption'] . '</div><div class="spaceDiv"></div><div class="innerDiv"><img src="/images/pages/' . $row['image'] . '" alt=""></div><div class="spaceDiv"></div>';
$result->free();
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