<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$page_title = 'עמותת חבר לי - פינת אימוץ כלבים';
$page_description = "הכלבים המהממים שלנו מחכים לכם בפינת האימוץ. \r\nבואו להתאהב!";
$page_url = 'http://imutz.org/dogs';
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
$result = $mysqli->query('SELECT dogdescription FROM adopt');
$row = $result->fetch_assoc();
$result->free();
if ($row)
	echo $row['dogdescription'];
$result = $mysqli->query('SELECT id, name FROM album WHERE isDog=1 AND isAdopted=0 ORDER BY important DESC, postDate DESC');
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