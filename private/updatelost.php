<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT * FROM lost');
$row = $result->fetch_assoc();
$result->free();
if (isset($_POST['description']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	$_POST['description'] = htmlspecialchars_decode($_POST['description'], ENT_QUOTES);
	if (empty($_POST['description']))
		$validation['descriptionempty'] = 'חובה להזין טקסט.';
	if (empty($validation))
	{
		if ($row)
			$stmt = $mysqli->prepare('UPDATE lost SET description=?');
		else
			$stmt = $mysqli->prepare('INSERT INTO lost VALUES (?)');
		$stmt->bind_param('s', $_POST['description']);
		$stmt->execute();
		$stmt->close();
		$result = $mysqli->query('SELECT * FROM lost');
		$row = $result->fetch_assoc();
		$result->free();
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
<form action="/private/updatelost.php" method="post">
<fieldset>
<h3>עדכון טקסט להצילו! אבד לי הכלב</h3>
<textarea class="tinymce" name="description" placeholder="טקסט לחלק העליון של הדף"><?php echo (empty($validation) ? ($row ? $row['description'] : '') : $_POST['description']); ?></textarea>
<input type="submit" value="עדכן טקסט">
</fieldset>
</form>
<p><a title="חזרה לעמוד הניהול" href="/private">חזרה לעמוד הניהול</a></p>
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
		echo 'swal("עדכנת טקסט", "הטקסט עודכן בהצלחה.", "success");';
	else
	{
		echo 'swal("העדכון נכשל", "';
		foreach ($validation as $p)
			echo $p . '\n';
		echo '", "error");';
	}
	echo '</script>';
}
?>
</body>
</html>