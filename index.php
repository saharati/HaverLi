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
$result = $mysqli->query('SELECT imageOrder, image, imageLink, imageCaption FROM home ORDER BY imageOrder');
if ($result->num_rows)
{
	while ($row = $result->fetch_assoc())
		if (!empty($row['imageCaption']))
			echo '<span style="display:none" id="a' . $row['imageOrder'] . '">' . $row['imageCaption'] . '</span>';
	$result->data_seek(0);
	echo '<ul class="bxslider">';
	while ($row = $result->fetch_assoc())
	{
		echo '<li>';
		if (empty($row['imageLink']))
		{
			if (empty($row['imageCaption']))
				echo '<img src="/images/home/' . $row['image'] . '" alt="">';
			else
				echo '<img data-caption="a' . $row['imageOrder'] . '" src="/images/home/' . $row['image'] . '" alt="">';
		}
		else
		{
			if (empty($row['imageCaption']))
				echo '<a href="' . $row['imageLink'] . '"><img src="/images/home/' . $row['image'] . '" alt=""></a>';
			else
				echo '<a data-caption="a' . $row['imageOrder'] . '" href="' . $row['imageLink'] . '"><img src="/images/home/' . $row['image'] . '" alt=""></a>';
		}
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
var slider = null;
$(document).ready(function()
{
	slider = $('.bxslider').bxSlider({
		slideWidth: 400,
		minSlides: 2,
		maxSlides: 3,
		moveSlides: 3,
		slideMargin: 15,
		pager: false,
		nextText: 'הבא',
		prevText: 'הקודם',
		auto: true,
		pause: 4000,
		autoHover: true,
		shrinkItems: true,
		captions: true
	});
});
function bxslider()
{
	var width = $(document).width();
	if (width < 400)
	{
		slider.reloadSlider({
			slideWidth: width - 20,
			minSlides: 1,
			maxSlides: 1,
			moveSlides: 1,
			slideMargin: 0,
			pager: false,
			nextText: 'הבא',
			prevText: 'הקודם',
			auto: true,
			pause: 4000,
			autoHover: true,
			shrinkItems: true,
			captions: true
		});
	}
	else
	{
		slider.reloadSlider({
			slideWidth: 400,
			minSlides: 2,
			maxSlides: 3,
			moveSlides: 3,
			slideMargin: 15,
			pager: false,
			nextText: 'הבא',
			prevText: 'הקודם',
			auto: true,
			pause: 4000,
			autoHover: true,
			shrinkItems: true,
			captions: true
		});
	}
}
$(window).on("orientationchange load resize", function()
{
	if (slider != null)
		bxslider();
});
</script>
</body>
</html>