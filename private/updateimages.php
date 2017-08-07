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
<h2>עדכון תמונות בדף הבית</h2>
<table class="sortable">
<thead><tr><th>מיקום</th><th>תמונה</th><th>עריכה</th><th>מחיקה</th></tr></thead>
<tbody>
<?php
$result = $mysqli->query('SELECT imageOrder, image FROM home ORDER BY imageOrder');
while ($row = $result->fetch_assoc())
{
	if (strpos($row['image'], '.svg') !== false)
		$width = $height = 10000;
	else
		list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/home/' . $row['image']);
	echo '<tr>
<input type="hidden" value="' . $row['imageOrder'] . '">
<td data-label="מיקום">' . $row['imageOrder'] . ' <a class="edit" href="javascript:void(0);" onclick="edit(this.parentNode);" title="עריכה">✎</a></td>
<td data-label="תמונה"><a class="imageModal" href="/images/home/' . $row['image'] . '" data-width="' . $width . '" data-height="' . $height . '"><img src="/images/home/' . $row['image'] . '"></a></td>
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
function edit(node)
{
	var imageOrder = node.parentNode.getElementsByTagName('input')[0].value;
	node.innerHTML = '<input type="number" value="' + imageOrder + '" required min="1" max="99"><a class="edit" href="javascript:void(0);" onclick="save(this.parentNode);" title="שמירה">✓</a>';
}
function save(node)
{
	var oldValue = node.innerHTML;
	var newValue = node.getElementsByTagName('input')[0].value;
	
	node.innerHTML = 'טוען...';
	
	var id = node.parentNode.getElementsByTagName('input')[0].value;
	var http = new XMLHttpRequest();
	http.open('POST', '/private/ajax/updateimages.php', true);
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
	http.send('imageOrder=' + id + '&newImageOrder=' + newValue);
}
<?php
if (isset($_GET['del']))
	echo 'swal("מחיקה", "התמונה נמחקה בהצלחה.", "success");';
?>
</script>
</div>
</body>
</html>