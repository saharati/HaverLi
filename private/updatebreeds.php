<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_GET['del']))
{
	$_GET['del'] = sanitize($_GET['del']);
	if (is_numeric($_GET['del']) && $_GET['del'] > 0 && $_GET['del'] < 100)
	{
		$mysqli->query('UPDATE album SET breedId=0 WHERE breedId=' . $_GET['del']);
		$mysqli->query('DELETE FROM pet_breed WHERE id=' . $_GET['del']);
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
<h2>עדכון גזעים</h2>
<table class="sortable">
<thead><tr><th>שם</th><th>סוג</th><th>עריכה</th><th>מחיקה</th></tr></thead>
<tbody>
<?php
$result = $mysqli->query('SELECT * FROM pet_breed ORDER BY name');
while ($row = $result->fetch_assoc())
{
	echo '<tr>
<input type="hidden" value="' . $row['id'] . '">
<td data-label="שם">' . $row['name'] . '</td>
<td data-label="סוג">' . ($row['isDog'] == 1 ? 'כלב' : 'חתול') . '</td>
<td data-label="עריכה"><a title="עריכה" href="/private/updatebreed.php?id=' . $row['id'] . '">עריכה</a></td>
<td data-label="מחיקה"><a title="מחיקה" href="javascript:void(0);" onclick="del(' . $row['id'] . ');">מחיקה</a></td>
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
			text: 'האם אתה בטוח שברצונך למחוק גזע זה? הוא יימחק גם עבור כל בעלי החיים בעלי אותו גזע.',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			confirmButtonText: 'כן',
			cancelButtonText: 'לא',
			closeOnConfirm: false
		},
		function()
		{
			self.location.href = '/private/updatebreeds.php?del=' + num;
		}
	);
}
<?php
if (isset($_GET['del']))
	echo 'swal("מחיקה", "הגזע נמחק בהצלחה.", "success");';
?>
</script>
</div>
</body>
</html>