<?php require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php'; ?>
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
<h2>קידום האתר</h2>
<table class="sortable">
<thead><tr><th>דף</th><th>כותרת</th><th>תמונה</th><th>עריכה</th></tr></thead>
<tbody>
<?php
$result = $mysqli->query('SELECT page, pageHebrew, title, image FROM promote');
while ($row = $result->fetch_assoc())
{
	echo '<tr>
<td data-label="דף">' . $row['pageHebrew'] . '</td>
<td data-label="כותרת">' . $row['title'] . '</td>';
	if (!empty($row['image']))
	{
		if (strpos($row['image'], '.svg') !== false)
			$width = $height = 10000;
		else
			list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/og/' . $row['image']);
		echo '<td data-label="תמונה"><a class="imageModal" href="/images/og/' . $row['image'] . '" data-width="' . $width . '" data-height="' . $height . '"><img src="/images/og/' . $row['image'] . '"></a></td>';
	}
	else
		echo '<td data-label="תמונה"> </td>';
echo '<td data-label="עריכה"><a title="עריכה" href="/private/promotepage.php?page=' . $row['page'] . '">עריכה</a></td>
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
</div>
</body>
</html>