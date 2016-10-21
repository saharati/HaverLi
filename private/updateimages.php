<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_GET['del']))
{
	$_GET['del'] = sanitize($_GET['del']);
	if (is_numeric($_GET['del']) && $_GET['del'] > 0 && $_GET['del'] < 100)
	{
		$result = $mysqli->query('SELECT image FROM home WHERE imageOrder=' . $_GET['del']);
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
		{
			unlink($_SERVER['DOCUMENT_ROOT'] . '/images/home/' . $row['image']);
			$mysqli->query('DELETE FROM home WHERE imageOrder=' . $_GET['del']);
		}
	}
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
<div id="content" class="fullwidth">
<div id="contentInner">
<h2>עדכון תמונות</h2>
<table class="sortable">
<thead><tr><th>מיקום</th><th>תמונה</th><th>עריכה</th><th>מחיקה</th></tr></thead>
<tbody>
<?php
$result = $mysqli->query('SELECT imageOrder, image FROM home ORDER BY imageOrder');
while ($row = $result->fetch_assoc())
{
	list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/home/' . $row['image']);
	echo '<tr>
<td data-label="מיקום">' . $row['imageOrder'] . '</td>
<td data-label="תמונה"><a href="/images/home/' . $row['image'] . '" class="imageModal" title="הצג תמונה נוכחית" data-width="' . $width . '" data-height="' . $height . '">צפייה</a></td>
<td data-label="עריכה"><a title="עריכה" href="/private/updateimage.php?id=' . $row['imageOrder'] . '">עריכה</a></td>
<td data-label="מחיקה"><a title="מחיקה" href="javascript:void(0);" onclick="del(' . $row['imageOrder'] . ');">מחיקה</a></a></td>
</tr>';
}
$result->free();
?>
</tbody>
</table>
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
			self.location.href = '/private/updateimages.php?del=' + num;
		}
	);
}
<?php
if (isset($_GET['del']))
	echo 'swal("מחיקה", "התמונה נמחקה בהצלחה.", "success");';
?>
</script>
</div>
</body>
</html>