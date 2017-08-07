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
if (isset($_GET['del']) && is_numeric($_GET['del']) && $_GET['del'] > 0)
	$mysqli->query('DELETE FROM album_video WHERE id=' . $_GET['del']);
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
<h2>עדכון סרטונים לאלבום</h2>
<table class="sortable">
<thead><tr><th>קישור</th><th>מחיקה</th></tr></thead>
<tbody>
<?php
$result = $mysqli->query('SELECT id, video FROM album_video WHERE albumId=' . $_GET['id']);
while ($row = $result->fetch_assoc())
{
	echo '<tr>
<td data-label="קישור"><a href="' . $row['video'] . '" target="_blank">' . $row['video'] . '</a></td>
<td data-label="מחיקה"><a title="מחיקה" href="javascript:void(0);" onclick="del(' . $row['id'] . ');">מחיקה</a></td>
</tr>';
}
$result->free();
?>
</tbody>
</table>
<p><a title="הוסף סרטונים לאלבום זה" href="/private/addvideos.php?id=<?php echo $_GET['id']; ?>">הוסף סרטונים לאלבום זה</a></p>
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
			text: 'האם אתה בטוח שברצונך למחוק סרטון זה?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			confirmButtonText: 'כן',
			cancelButtonText: 'לא',
			closeOnConfirm: false
		},
		function()
		{
			self.location.href = '/private/updatevideos.php?id=<?php echo $_GET['id']; ?>&del=' + num;
		}
	);
}

<?php
if (isset($_GET['del']))
	echo 'swal("מחיקה", "הסרטון נמחק בהצלחה.", "success");';
?>
</script>
</div>
</body>
</html>