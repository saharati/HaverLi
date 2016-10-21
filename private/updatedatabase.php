<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_GET['del']))
{
	$_GET['del'] = sanitize($_GET['del']);
	$result = $mysqli->query('SELECT image FROM image WHERE imageName="' . $mysqli->real_escape_string($_GET['del']) . '"');
	$row = $result->fetch_assoc();
	$result->free();
	if ($row)
	{
		unlink($_SERVER['DOCUMENT_ROOT'] . '/images/data/' . $row['image']);
		$mysqli->query('DELETE FROM image WHERE imageName="' . $mysqli->real_escape_string($_GET['del']) . '"');
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
<thead><tr><th>תמונה</th><th>מחיקה</th></tr></thead>
<tbody>
<?php
$result = $mysqli->query('SELECT * FROM image ORDER BY imageName');
while ($row = $result->fetch_assoc())
{
	if (strpos($row['image'], '.svg') !== false)
		$width = $height = 10000;
	else
		list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/data/' . $row['image']);
	echo '<tr>
<td data-label="תמונה"><a class="imageModal" href="/images/data/' . $row['image'] . '" data-width="' . $width . '" data-height="' . $height . '"><img src="/images/data/' . $row['image'] . '"></a></td>
<td data-label="מחיקה"><a title="מחיקה" href="javascript:void(0);" onclick="del(\'' . $row['imageName'] . '\');">מחיקה</a></a></td>
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
			self.location.href = '/private/updatedatabase.php?del=' + num;
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