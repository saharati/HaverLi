<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_GET['del']))
{
	$_GET['del'] = sanitize($_GET['del']);
	if (is_numeric($_GET['del']) && $_GET['del'] > 0 && $_GET['del'] < 100)
	{
		$result = $mysqli->query('SELECT image, image2 FROM help_image WHERE imageOrder=' . $_GET['del']);
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
		{
			unlink($_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $row['image']);
			unlink($_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $row['image2']);
			$mysqli->query('DELETE FROM help_image WHERE imageOrder=' . $_GET['del']);
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
<h2>עדכון תמונות בתרומות</h2>
<table class="sortable">
<thead><tr><th>מיקום</th><th>תמונה ראשית</th><th>תמונה משנית</th><th>עריכה</th><th>מחיקה</th></tr></thead>
<tbody>
<?php
$result = $mysqli->query('SELECT imageOrder, image, image2 FROM help_image ORDER BY imageOrder');
while ($row = $result->fetch_assoc())
{
	if (strpos($row['image'], '.svg') !== false)
		$width = $height = 10000;
	else
		list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $row['image']);
	if (strpos($row['image2'], '.svg') !== false)
		$width2 = $height2 = 10000;
	else
		list($width2, $height2) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $row['image2']);
	echo '<tr>
<input type="hidden" value="' . $row['imageOrder'] . '">
<td data-label="מיקום">' . $row['imageOrder'] . ' <a class="edit" href="javascript:void(0);" onclick="edit(this.parentNode);" title="עריכה">✎</a></td>
<td data-label="תמונה ראשית"><a class="imageModal" href="/images/help/' . $row['image'] . '" data-width="' . $width . '" data-height="' . $height . '"><img src="/images/help/' . $row['image'] . '"></a></td>
<td data-label="תמונה משנית"><a class="imageModal" href="/images/help/' . $row['image2'] . '" data-width="' . $width . '" data-height="' . $height . '"><img src="/images/help/' . $row['image2'] . '"></a></td>
<td data-label="עריכה"><a title="עריכה" href="/private/updatehelpimage.php?id=' . $row['imageOrder'] . '">עריכה</a></td>
<td data-label="מחיקה"><a title="מחיקה" href="javascript:void(0);" onclick="del(' . $row['imageOrder'] . ');">מחיקה</a></td>
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
			self.location.href = '/private/updatehelpimages.php?del=' + num;
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
	http.open('POST', '/private/ajax/updatehelpimages.php', true);
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