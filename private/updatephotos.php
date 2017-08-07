<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] < 0)
{
	header('Location: /private');
	exit;
}
$result = $mysqli->query('SELECT id FROM album WHERE id=' . $_GET['id']);
$row = $result->fetch_assoc();
$result->free();
if (!$row)
{
	header('Location: /private');
	exit;
}
if (isset($_POST['cb']))
{
	foreach ($_POST['cb'] as $cb)
	{
		if (is_numeric($cb) && $cb > 0)
		{
			$result = $mysqli->query('SELECT image FROM album_photo WHERE id=' . $cb);
			$row = $result->fetch_assoc();
			$result->free();
			if ($row)
			{
				unlink($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_GET['id'] . '/' . $row['image']);
				$mysqli->query('DELETE FROM album_photo WHERE id=' . $cb);
			}
		}
	}
}
if (isset($_GET['rotate']) && is_numeric($_GET['rotate']) && $_GET['rotate'] > 0)
{
	$result = $mysqli->query('SELECT image, width, height FROM album_photo WHERE id=' . $_GET['rotate']);
	$row = $result->fetch_assoc();
	$result->free();
	if ($row)
	{
		$extension = strtolower(pathinfo($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_GET['id'] . '/' . $row['image'], PATHINFO_EXTENSION));
		if ($extension == 'jpg' || $extension == 'jpeg')
			$image = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_GET['id'] . '/' . $row['image']);
		elseif ($extension == 'png')
			$image = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_GET['id'] . '/' . $row['image']);
		else
			$image = imagecreatefromgif($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_GET['id'] . '/' . $row['image']);
		$image = imagerotate($image, 90, 0);
		unlink($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_GET['id'] . '/' . $row['image']);
		if ($extension == 'jpg' || $extension == 'jpeg')
			imagejpeg($image, $_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_GET['id'] . '/' . $row['image'], 100);
		elseif ($extension == 'png')
			imagepng($image, $_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_GET['id'] . '/' . $row['image'], 9);
		else
			imagegif($image, $_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_GET['id'] . '/' . $row['image']);
		imagedestroy($image);
	}
}
$_SESSION['albumId'] = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="fullwidth">
<div id="contentInner">
<h2>עדכון תמונות לאלבום</h2>
<form action="/private/updatephotos.php?id=<?php echo $_GET['id']; ?>" method="post">
<table class="sortable">
<caption><input type="submit" value="מחק"></caption>
<thead><tr><th>תמונה</th><th>סטאטוס</th><th>סובב</th><th>מחיקה</th></tr></thead>
<tbody>
<?php
$result = $mysqli->query('SELECT id, image, width, height, cover FROM album_photo WHERE albumId=' . $_GET['id']);
while ($row = $result->fetch_assoc())
{
	echo '<tr>
<td data-label="תמונה"><a class="imageModal" href="/images/albums/' . $_GET['id'] . '/' . $row['image'] . '" data-width="' . $row['width'] . '" data-height="' . $row['height'] . '"><img src="/images/albums/' . $_GET['id'] . '/' . $row['image'] . '"></a></td>
<td data-label="סטאטוס"><select onchange="changestatus(' . $row['id'] . ', this.value);"><option value="0" ' . ($row['cover'] == 0 ? 'selected' : '') . '>רגיל</option><option value="1" ' . ($row['cover'] == 1 ? 'selected' : '') . '>תמונה משנית</option><option value="2" ' . ($row['cover'] == 2 ? 'selected' : '') . '>תמונה ראשית</option></select></td>
<td data-label="סובב"><a title="סובב ימינה" href="/private/updatephotos.php?id=' . $_GET['id'] . '&rotate=' . $row['id'] . '"> ↺ </a></td>
<td data-label="מחיקה"><input title="מחיקה" type="checkbox" name="cb[]" value="' . $row['id'] . '"</td>
</tr>';
}
$result->free();
?>
</tbody>
</table>
</form>
<p><a title="הוסף תמונות לאלבום זה" href="/private/addphotos.php">הוסף תמונות לאלבום זה</a></p>
<p><a title="עדכן פרטיםל אלבום זה" href="/private/updatealbum.php?id=<?php echo $_GET['id']; ?>">עדכן פרטים לאלבום זה</a></p>
<br>
<p><a title="חזרה לעדכון אלבומים" href="/private/updatealbums.php">חזרה לעדכון אלבומים</a></p>
<p><a title="חזרה לעמוד הניהול" href="/private">חזרה לעמוד הניהול</a></p>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
<script>
function del(num)
{
	swal
	(
		{
			title: 'האם אתה בטוח?',
			text: 'האם אתה בטוח שברצונך למחוק תמונה זו?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			confirmButtonText: 'כן',
			cancelButtonText: 'לא',
			closeOnConfirm: false
		},
		function()
		{
			self.location.href = '/private/updatephotos.php?id=<?php echo $_GET['id']; ?>&del=' + num;
		}
	);
}
function changestatus(id, value)
{
	var http = new XMLHttpRequest();
	http.open('POST', '/private/ajax/updatephotos.php', true);
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	http.send('id=' + id + '&value=' + value);
}
<?php
if (isset($_GET['del']))
	echo 'swal("מחיקה", "התמונה נמחקה בהצלחה.", "success");';
?>
</script>
</div>
</body>
</html>