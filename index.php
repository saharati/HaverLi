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
		echo '<li>';
		if (empty($row['imageLink']))
			echo '<img src="/images/home/' . $row['image'] . '" alt="">';
		else
			echo '<a href="' . $row['imageLink'] . '"><img src="/images/home/' . $row['image'] . '" alt=""></a>';
		if (!empty($row['imageCaption']))
			echo '<div class="bx-caption">' . $row['imageCaption'] . '</div>';
		echo '</li>';
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
<script>
$(document).ready(function()
{
	$('.bxslider').bxSlider({
		slideWidth: 400,
		minSlides: 2,
		maxSlides: 3,
		moveSlides: 3,
		slideMargin: 15,
		pager: false,
		nextText: 'הבא',
		prevText: 'הקודם',
		auto: true,
		pause: 6000,
		autoHover: true,
		shrinkItems: true
	});
});
</script>
</body>
</html>