<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && $_GET['remove'] > 0 && isset($_COOKIE['pet-' . $_GET['remove']]))
{
	setcookie('pet-' . $_GET['remove'], '', time() - 3600);
	unset($_COOKIE['pet-' . $_GET['remove']]);
	$removed = true;
}
?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="about wishlist fullwidth">
<div id="contentInner">
<div class="innerDiv">
<h2>רשימת משאלות <img src="/images/addwish.svg" alt=""></h2>
</div>
<?php
foreach ($_COOKIE as $key => $cookie)
{
	if (strpos($key, 'pet-') !== false && is_numeric($cookie) && $cookie > 0)
	{
		$result = $mysqli->query('SELECT name, description, isMale, bornDate, size, breedId FROM album WHERE id=' . $cookie);
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
		{
			echo '<div class="spaceDiv"><div class="innerDiv clearfix">';
			$result = $mysqli->query('SELECT image, width, height FROM album_photo WHERE albumId=' . $cookie . ' ORDER BY cover DESC LIMIT 1');
			$row2 = $result->fetch_assoc();
			$result->free();
			echo '<a class="floated imageModal" href="/images/albums/' . $cookie . '/' . $row2['image'] . '" data-width="' . $row2['width'] . '" data-height="' . $row2['height'] . '"><img src="/images/albums/' . $cookie . '/' . $row2['image'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '"></a>
<ul class="specs">
<li>שם: ' . $row['name'] . '</li>
<li>מין: ' . ($row['isMale'] == 1 ? 'זכר' : 'נקבה') . '</li>
<li>גיל: ';
$from = new DateTime($row['bornDate']);
$to = new DateTime('today');
$diff = $from->diff($to);
if ($diff->y > 0)
	echo $diff->y . ' שנים';
else
	echo $diff->m . ' חודשים';
echo '</li>
<li>גודל: ';
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
echo '</li>
<li>גזע: ';
if ($row['breedId'] == 0)
	echo 'מעורב';
else
{
	$result = $mysqli->query('SELECT name FROM pet_breed WHERE id=' . $row['breedId']);
	$row2 = $result->fetch_assoc();
	$result->free();
	echo $row2['name'];
}
echo '</li>
</ul>
' . $row['description'] . '
<div class="social">
<ul>
<li class="send"><a title="שלח לחבר" href="/send-' . $cookie . '">שלח לחבר</a></li>
<li class="share"><a title="שתף בפייסבוק" href="javascript:void(0);" onclick="window.open(\'http://www.facebook.com/share.php?u=http://imutz.org/pet-' . $cookie . '\', \'sharer\', \'toolbar=0, status=0, width=675, height=475\');">שתף</a></li>
<li class="virtualadopt"><a title="אימוץ וירטואלי" href="/help">אימוץ וירטואלי</a></li>
<li class="garbage"><a title="מחק" href="/wishlist?remove=' . $cookie . '">מחק</a></li>
</ul>
</div>
</div></div>';
		}
	}
}
?>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
<?php
if (isset($removed))
	echo '<script>swal("רשימת המשאלות", "הסרת בעל חיים מרשימת המשאלות שלך.", "success");</script>';
?>
</body>
</html>