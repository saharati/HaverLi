<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$metaData = $mysqli->query('SELECT title, description, url, image FROM promote WHERE page="cats"');
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
<div id="content" class="adopt">
<div id="contentInner">
<?php
$result = $mysqli->query('SELECT catdescription FROM adopt');
$row = $result->fetch_assoc();
$result->free();
if ($row)
	echo $row['catdescription'];
$result = $mysqli->query('SELECT id, name FROM album WHERE isDog=0 AND isAdopted=0 ORDER BY important DESC, postDate DESC');
while ($row = $result->fetch_assoc())
{
	$result2 = $mysqli->query('SELECT albumId, image, width, height FROM album_photo WHERE albumId=' . $row['id'] . ' ORDER BY cover DESC LIMIT 2');
	if ($result2->num_rows == 0)
	{
		$result2->free();
		continue;
	}
	$image1 = $result2->fetch_assoc();
	$image2 = $result2->fetch_assoc();
	$result2->free();
	if (empty($image2))
		echo '<div class="gallerybox"><a title="' . $row['name'] . ' לאימוץ" href="/pet-' . $row['id'] . '"><div><img ' . calcStyle($image1, false) . ' title="' . $row['name'] . ' לאימוץ" alt="' . $row['name'] . ' לאימוץ" src="/images/albums/' . $row['id'] . '/' . $image1['image'] . '"></div><p>' . $row['name'] . '</p></a></div>';
	else
		echo '<div class="gallerybox"><a title="' . $row['name'] . ' לאימוץ" href="/pet-' . $row['id'] . '" class="hide"><div ' . calcStyle($image2, true) . '><img ' . calcStyle($image1, false) . ' title="' . $row['name'] . ' לאימוץ" alt="' . $row['name'] . ' לאימוץ" src="/images/albums/' . $row['id'] . '/' . $image1['image'] . '"></div><p>' . $row['name'] . '</p></a></div>';
}
$result->free();
?>
<div class="gallerybox emptybox"></div>
<div class="gallerybox emptybox"></div>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>