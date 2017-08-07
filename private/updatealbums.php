<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_GET['del']))
{
	$_GET['del'] = sanitize($_GET['del']);
	if (is_numeric($_GET['del']) && $_GET['del'] > 0)
	{
		rrmdir($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_GET['del']);
		$mysqli->query('DELETE FROM album_video WHERE albumId=' . $_GET['del']);
		$mysqli->query('DELETE FROM album_photo WHERE albumId=' . $_GET['del']);
		$mysqli->query('DELETE FROM album WHERE id=' . $_GET['del']);
	}
}
$sql = '';
if (isset($_GET['text'], $_GET['status'], $_GET['page']))
{
	$_GET = sanitize($_GET);
	if (!empty($_GET['text']))
		$sql .= '(name LIKE "%' . $mysqli->real_escape_string($_GET['text']) . '%" OR postDate LIKE "%' . $mysqli->real_escape_string($_GET['text']) . '%") AND ';
	if ($_GET['status'] == '1' || $_GET['status'] == '0')
		$sql .= 'isAdopted=' . $_GET['status'] . ' AND ';
}
else
{
	$_GET['page'] = 1;
	$_GET['status'] = '';
	$_GET['text'] = '';
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
<h2>עדכון אלבומים</h2>
<form action="/private/updatealbums.php" method="get" class="search-form">
<fieldset>
<select name="status">
<option value="">בחר סטאטוס</option>
<option value="0" <?php echo $_GET['status'] == '0' ? 'selected' : ''; ?>>לאימוץ</option>
<option value="1" <?php echo $_GET['status'] == '1' ? 'selected' : ''; ?>>אומץ</option>
</select>
<input type="text" name="text" placeholder="חלק משם" value="<?php echo $_GET['text']; ?>">
<input type="submit" value="חפש אלבומים">
<input type="hidden" name="page" value="1">
</fieldset>
</form>
<table class="sortable">
<thead><tr><th>עדכון אחרון</th><th>שם</th><th>סטאטוס</th><th>חשיבות</th><th>פעולות</th></tr></thead>
<tbody>
<?php
if (!isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] < 1)
	$_GET['page'] = 1;
$rowsPerPage = 20;
$previousRows = ($_GET['page'] - 1) * $rowsPerPage;
$result = $mysqli->query('SELECT id, DATE_FORMAT(postDate, "%d/%m/%Y") ps, name, isAdopted, important FROM album WHERE ' . $sql . '1=1 ORDER BY postDate DESC LIMIT ' . $previousRows . ', ' . $rowsPerPage);
while ($row = $result->fetch_assoc())
{
	echo '<tr>
<td data-label="עדכון אחרון">' . $row['ps'] . '</td>
<td data-label="שם">' . $row['name'] . '</td>
<td data-label="סטאטוס"><select onchange="changestatus(' . $row['id'] . ', this.value);"><option value="0" ' . ($row['isAdopted'] == 0 ? 'selected' : '') . '>לאימוץ</option><option value="1" ' . ($row['isAdopted'] == 1 ? 'selected' : '') . '>אומץ</option></select></td>
<td data-label="חשיבות"><select onchange="changeimportant(' . $row['id'] . ', this.value);"><option value="0" ' . ($row['important'] == 0 ? 'selected' : '') . '>רגיל</option><option value="1" ' . ($row['important'] == 1 ? 'selected' : '') . '>חשוב</option></select></td>
<td data-label="פעולות"><a href="/private/updatealbum.php?id=' . $row['id'] . '">פרטים</a> | <a href="updatephotos.php?id=' . $row['id'] . '">תמונות</a> | <a href="updatevideos.php?id=' . $row['id'] . '">סרטונים</a> | <a href="javascript:void(0);" onclick="del(' . $row['id'] . ');">מחיקה</a></td>
</tr>';
}
$result->free();
?>
</tbody>
</table>
<?php
$result = $mysqli->query('SELECT COUNT(id) c FROM album WHERE ' . $sql . '1=1');
$row = $result->fetch_assoc();
$result->free();
pagination($_GET['page'], ceil($row['c'] / $rowsPerPage), '/private/updatealbums.php?text=' . $_GET['text'] . '&status=' . $_GET['status'] . '&page=');
?>
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
			text: 'האם אתה בטוח שברצונך למחוק אלבום זה? כל התמונות והסרטונים יימחקו גם כן.',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			confirmButtonText: 'כן',
			cancelButtonText: 'לא',
			closeOnConfirm: false
		},
		function()
		{
			self.location.href = '/private/updatealbums.php?del=' + num;
		}
	);
}
function changestatus(id, value)
{
	var http = new XMLHttpRequest();
	http.open('POST', '/private/ajax/updatealbums.php', true);
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	http.send('id=' + id + '&value=' + value);
}
function changeimportant(id, value)
{
	var http = new XMLHttpRequest();
	http.open('POST', '/private/ajax/updateimportant.php', true);
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	http.send('id=' + id + '&value=' + value);
}
<?php
if (isset($_GET['del']))
	echo 'swal("מחיקה", "האלבום נמחק בהצלחה.", "success");';
?>
</script>
</div>
</body>
</html>