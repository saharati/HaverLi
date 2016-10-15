<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_GET['del']))
{
	$_GET['del'] = sanitize($_GET['del']);
	if (is_numeric($_GET['del']) && $_GET['del'] > 0 && $_GET['del'] < 10000)
	{
		$result = $mysqli->query('SELECT image FROM event WHERE id=' . $_GET['del']);
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
		{
			if (!empty($row['image']))
				unlink($_SERVER['DOCUMENT_ROOT'] . '/images/events/' . $row['image']);
			$mysqli->query('DELETE FROM event WHERE id=' . $_GET['del']);
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
<div id="content-wrap">
<div class="center-block">
<h2><i class="fa fa-calendar-minus-o" aria-hidden="true"></i> עריכת אירועים</h2>
<table class="sortable">
<thead><tr><th>כותרת</th><th>עריכה</th><th>מחיקה</th></tr></thead>
<tbody>
<?php
$result = $mysqli->query('SELECT id, hebrewTitle FROM event ORDER BY postDate DESC');
while ($row = $result->fetch_assoc())
	echo '<tr>
<td data-label="כותרת">' . $row['hebrewTitle'] . '</td>
<td data-label="עריכה"><a href="/private/updateEvent.php?id=' . $row['id'] . '" title="עריכה"><i class="fa fa-pencil" aria-hidden="true"></i> עריכה</a></td>
<td data-label="מחיקה"><a href="javascript:void(0);" onclick="del(' . $row['id'] . ');" title="מחיקה"><i class="fa fa-trash-o" aria-hidden="true"></i> מחיקה</a></a></td>
</tr>';
$result->free();
?>
</tbody>
</table>
<p><a href="/private" title="חזרה לעמוד הניהול"><i class="fa fa-undo" aria-hidden="true"></i> חזרה לעמוד הניהול</a></p>
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
			text: 'האם אתה בטוח שברצונך למחוק אירוע זה?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			confirmButtonText: 'כן',
			cancelButtonText: 'לא',
			closeOnConfirm: false
		},
		function()
		{
			self.location.href = '/private/updateEvents.php?del=' + num;
		}
	);
}
<?php
if (isset($_GET['del']))
	echo 'swal("מחיקה", "האירוע נמחק בהצלחה.", "success");';
?>
</script>
</div>
</body>
</html>