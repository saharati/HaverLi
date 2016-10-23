<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="adopt">
<div id="contentInner">
<h2>הכלבים שלנו</h2>
<p>כולם בעלי שבב אלקטרוני, מחוסנים בחיסון כלבת ומשושה, הנקבות מעוקרות והזכרים מסורסים.</p>
<p>האימוץ כרוך בתשלום מסובסד עבור עיקור/סירוס וחיסון כלבת/משושה.</p>
<?php
$result = $mysqli->query('SELECT id, name FROM album WHERE isDog=1 AND isAdopted=0 ORDER BY postDate DESC');
while ($row = $result->fetch_assoc())
{
	$result2 = $mysqli->query('SELECT image, width, height FROM album_photo WHERE albumId=' . $row['id'] . ' ORDER BY cover DESC LIMIT 2');
	$image1 = $result2->fetch_assoc();
	$image2 = $result2->fetch_assoc();
	$result2->free();
	$image1 = calcSize($image1);
	if (empty($image2))
		echo '<div class="gallerybox"><a title="' . $row['name'] . ' לאימוץ" href="/pet-' . $row['id'] . '"><div><img ' . $image1['style'] . ' title="' . $row['name'] . ' לאימוץ" alt="' . $row['name'] . ' לאימוץ" src="/images/albums/' . $row['id'] . '/' . $image1['image'] . '"></div><p>' . $row['name'] . '</p></a></div>';
	else
	{
		$image2 = calcSize($image2);
		// First line of echo is for caching purposes.
		echo '<img src="/images/albums/' . $row['id'] . '/' . $image2['image'] . '" style="display:none" />
<div class="gallerybox"><a title="' . $row['name'] . ' לאימוץ" href="/pet-' . $row['id'] . '"><div><img ' . $image1['style'] . ' title="' . $row['name'] . ' לאימוץ" alt="' . $row['name'] . ' לאימוץ" src="/images/albums/' . $row['id'] . '/' . $image1['image'] . '" data-src="/images/albums/' . $row['id'] . '/' . $image2['image'] . '" data-' . $image2['style'] . ' onmouseover="toggleSrc(this);" onmouseout="toggleSrc(this);"></div><p>' . $row['name'] . '</p></a></div>';
	}
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