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
if (isset($_POST['link']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['link']))
		$validation['nameempty'] = 'יש להזין קישור ליוטיוב.';
	if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $_POST['link'], $link))
		$link = $link[1];
	else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $_POST['link'], $link))
		$link = $link[1];
	else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $_POST['link'], $link))
		$link = $link[1];
	else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $_POST['link'], $link))
		$link = $link[1];
	else
		$validation['noyoutube'] = 'הסרטון חייב להיות מהיוטיוב.';
	if (empty($validation))
	{
		$link = 'https://www.youtube.com/embed/' . $link;
		$stmt = $mysqli->prepare('INSERT INTO album_video (albumId, video) VALUES (?, ?)');
		$stmt->bind_param('is', $_GET['id'], $link);
		$stmt->execute();
		$stmt->close();
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
<div id="content">
<div id="contentInner">
<form action="/private/addvideos.php?id=<?php echo $_GET['id']; ?>" method="post">
<fieldset>
<h3>הוספת סרטונים לאלבום</h3>
<input type="url" name="link" required placeholder="קישור לסרטון יוטיוב" title="חובה להזין קישור" <?php if (!empty($validation)) echo 'value="' . $_POST['link'] . '"'; ?>>
<input type="submit" value="הוסף סרטון יוטיוב">
</fieldset>
</form>
<p><a title="חזרה לעדכון סרטונים" href="/private/updatevideos.php?id=<?php echo $_GET['id']; ?>">חזרה לעדכון סרטונים</a></p>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
<?php
if (isset($validation))
{
	echo '<script>';
	if (empty($validation))
		echo 'swal("הוספת סרטון", "הסרטון נוסף בהצלחה.", "success");';
	else
	{
		echo 'swal("ההוספה נכשלה", "';
		foreach ($validation as $p)
			echo $p . '\n';
		echo '", "error");';
	}
	echo '</script>';
}
?>
</body>
</html>