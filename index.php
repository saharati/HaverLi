<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="home fullwidth">
<div id="contentInner">
<?php
$result = $mysqli->query('SELECT image, imageLink, imageCaption FROM home ORDER BY imageOrder');
if ($result->num_rows)
{
	echo '<ul class="bxslider">';
	while ($row = $result->fetch_assoc())
	{
		echo '<li><div>';
		if (empty($row['imageLink']))
			echo '<img src="/images/home/' . $row['image'] . '">';
		else
			echo '<a href="imageLink"><img src="/images/home/' . $row['image'] . '"></a>';
		if (!empty($row['imageCaption']))
			echo '<div class="bx-caption">' . $row['imageCaption'] . '</div>';
		echo '</div></li>';
	}
	echo '</ul>';
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