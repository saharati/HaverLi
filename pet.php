<?php
if (!isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] < 0)
{
	header('Location: /notfound');
	exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$result = $mysqli->query('SELECT name, description, isDog, isMale, breedId, size, bornDate FROM album WHERE id=' . $_GET['page']);
$row = $result->fetch_assoc();
$result->free();
if (!$row)
{
	header('Location: /notfound');
	exit;
}
?>
<!DOCTYPE html>
<html lang="he">
<?php
$page_title = ($row['isDog'] == 1 ? 'כלבים לאימוץ' : 'חתולים לאימוץ') . ' - ' . $row['name'];
$page_description = strip_tags($row['description']);
$page_description = str_replace(array('<br>', "\r", "\n"), array(' ', '', ''), $page_description);
$page_description = htmlspecialchars($page_description, ENT_QUOTES);
$page_url = 'http://v2.imutz.org/pet-' . $_GET['page'];
$result = $mysqli->query('SELECT albumId, image, width, height FROM album_photo WHERE albumId=' . $_GET['page'] . ' ORDER BY cover DESC');
$row2 = $result->fetch_assoc();
$result->data_seek(0);
$page_image = 'http://v2.imutz.org/images/albums/' . $row2['albumId'] . '/' . $row2['image'];
$page_image_width = $row2['width'];
$page_image_height = $row2['height'];
require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="pet fullwidth">
<div id="contentInner">
<?php
echo '<h2>' . $row['name'] . '</h2>
<ul class="bxslider">';
$result2 = $mysqli->query('SELECT video FROM album_video WHERE albumId=' . $_GET['page']);
while ($row2 = $result2->fetch_assoc())
	echo '<li><iframe src="' . $row2['video'] . '" frameborder="0" allowfullscreen></iframe></li>';
$result2->free();
while ($row2 = $result->fetch_assoc())
	echo '<li><img ' . calcStyle($row2, false, 400) . ' src="/images/albums/' . $row2['albumId'] . '/' . $row2['image'] . '" alt=""></li>';
$result->free();
echo '</ul>
<div class="innerDiv petDescr">
<h4>' . $row['name'] . '</h4>
<p>
מין: ' . ($row['isMale'] == 1 ? 'זכר' : 'נקבה') . '<br>
גיל: ';
$from = new DateTime($row['bornDate']);
$to = new DateTime('today');
if ($from->diff($to)->y > 0)
	echo $from->diff($to)->y . ' שנים';
else
	echo $from->diff($to)->m . ' חודשים';
echo '<br>
גודל: ';
switch ($row['size'])
{
	case 0:
		echo 'לא ידוע';
		break;
	case 1:
		echo 'קטן';
		break;
	case 2:
		echo 'בינוני';
		break;
	case 3:
		echo 'גדול';
		break;
}
echo '<br>
גזע: ';
if ($row['breedId'] == 0)
	echo 'מעורב';
else
{
	$result = $mysqli->query('SELECT name FROM pet_breed WHERE id=' . $row['breedId']);
	$row2 = $result->fetch_assoc();
	$result->free();
	echo $row2['name'];
}
echo '<br>
תאור: ' . $row['description'] . '</p>
</div>';
?>
<div class="spaceDiv"></div>
<div class="innerDiv petDescr">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/contact.php'; ?>
<div class="social">
<ul>
<li class="send"><a title="שלח לחבר" href="/send-<?php echo $_GET['page']; ?>">שלח לחבר</a></li>
<li class="share"><a title="שתף בפייסבוק" href="javascript:void(0);" onclick="window.open('http://www.facebook.com/share.php?u=<?php echo $page_url; ?>' , 'sharer', 'toolbar=0, status=0, width=675, height=475');">שתף</a></li>
<li class="addwish"><a title="הוסף לרשימת המשאלות" href="javascript:void(0);" onclick="addToWishlist(<?php echo $_GET['page']; ?>);">הוסף לרשימת המשאלות</a></li>
<li class="virtualadopt"><a title="אימוץ וירטואלי" href="/help">אימוץ וירטואלי</a></li>
</ul>
</div>
</div>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
<script>
var slider = null;
var state = 0;
var prevWidth = 0;
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
		video: true,
		onSliderLoad: function()
		{
			var width = $('.bxslider li').width();
			$('.bxslider li').css('height' , width + 'px');
		},
		onSliderResize: function()
		{
			var width = $('.bxslider li').width();
			$('.bxslider li').css('height' , width + 'px');
		}
	});

	state = 2;
});
function bxslider()
{
	var width = $(document).width();
	if (prevWidth == width)
		return;
	
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
			video: true,
			onSliderLoad: function()
			{
				var width = $('.bxslider li').width();
				$('.bxslider li').css('height' , width + 'px');
			},
			onSliderResize: function()
			{
				var width = $('.bxslider li').width();
				$('.bxslider li').css('height' , width + 'px');
			}
		});

		state = 1;
	}
	else
	{
		if (state == 2)
			return;
		
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
			video: true,
			onSliderLoad: function()
			{
				var width = $('.bxslider li').width();
				$('.bxslider li').css('height' , width + 'px');
			},
			onSliderResize: function()
			{
				var width = $('.bxslider li').width();
				$('.bxslider li').css('height' , width + 'px');
			}
		});

		state = 2;
	}

	prevWidth = width;
}
$(window).on("orientationchange load resize", function()
{
	if (slider != null && state != 0)
		bxslider();
});
function addToWishlist(num)
{
	var http = new XMLHttpRequest();
	http.open('POST', '/addtowishlist.php', true);
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = function()
	{
		if (http.readyState == 4 && http.status == 200)
		{
			var arr = http.responseText.split('sys');
			swal('רשימת המשאלות', arr[0], arr[1]);
		}
	}
	http.send('petId=' + num);
}
</script>
</body>
</html>