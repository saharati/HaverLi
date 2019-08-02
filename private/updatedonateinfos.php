<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_GET['del']))
{
	$_GET['del'] = sanitize($_GET['del']);
	if (is_numeric($_GET['del']) && $_GET['del'] > 0 && $_GET['del'] < 100)
		$mysqli->query('DELETE FROM donate_info WHERE position=' . $_GET['del']);
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
<h2>עדכון תוכן בתרומות</h2>
<table class="sortable">
<thead><tr><th>מיקום</th><th>טקסט</th><th>עריכה</th><th>מחיקה</th></tr></thead>
<tbody>
<?php
$result = $mysqli->query('SELECT * FROM donate_info ORDER BY position');
while ($row = $result->fetch_assoc())
	echo '<tr>
<input type="hidden" value="' . $row['position'] . '">
<td data-label="מיקום">' . $row['position'] . ' <a class="edit" href="javascript:void(0);" onclick="edit(this.parentNode);" title="עריכה">✎</a></td>
<td data-label="טקסט">' . $row['caption'] . '</td>
<td data-label="עריכה"><a title="עריכה" href="/private/updatedonateinfo.php?id=' . $row['position'] . '">עריכה</a></td>
<td data-label="מחיקה"><a title="מחיקה" href="javascript:void(0);" onclick="del(' . $row['position'] . ');">מחיקה</a></td>
</tr>';
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
			text: 'האם אתה בטוח שברצונך למחוק טקסט זה?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			confirmButtonText: 'כן',
			cancelButtonText: 'לא',
			closeOnConfirm: false
		},
		function()
		{
			self.location.href = '/private/updatedonateinfos.php?del=' + num;
		}
	);
}
function edit(node)
{
	var position = node.parentNode.getElementsByTagName('input')[0].value;
	node.innerHTML = '<input type="number" value="' + position + '" required min="1" max="99"><a class="edit" href="javascript:void(0);" onclick="save(this.parentNode);" title="שמירה">✓</a>';
}
function save(node)
{
	var oldValue = node.innerHTML;
	var newValue = node.getElementsByTagName('input')[0].value;
	
	node.innerHTML = 'טוען...';
	
	var id = node.parentNode.getElementsByTagName('input')[0].value;
	var http = new XMLHttpRequest();
	http.open('POST', '/private/ajax/updatedonateinfos.php', true);
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = function()
	{
		if (http.readyState == 4 && http.status == 200)
		{
			if (http.responseText == '')
			{
				node.parentNode.getElementsByTagName('input')[0].value = newValue;
				node.innerHTML = newValue + ' <a class="edit" href="javascript:void(0);" onclick="edit(this.parentNode);" title="עריכה">✎</a>';
			}
			else
			{
				node.innerHTML = oldValue;
				swal('הפעולה נכשלה', http.responseText, 'error');
			}
		}
	}
	http.send('position=' + id + '&newPosition=' + newValue);
}
<?php
if (isset($_GET['del']))
	echo 'swal("מחיקה", "הטקסט נמחק בהצלחה.", "success");';
?>
</script>
</div>
</body>
</html>