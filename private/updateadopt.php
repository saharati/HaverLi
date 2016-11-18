<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT * FROM adopt');
$row = $result->fetch_assoc();
$result->free();
if (isset($_POST['dogdescription'], $_POST['catdescription']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	$_POST['dogdescription'] = htmlspecialchars_decode($_POST['dogdescription'], ENT_QUOTES);
	$_POST['catdescription'] = htmlspecialchars_decode($_POST['catdescription'], ENT_QUOTES);
	if (empty($_POST['dogdescription']))
		$validation['descriptionempty'] = 'חובה להזין טקסט.';
	elseif (empty($_POST['catdescription']))
		$validation['descriptionempty'] = 'חובה להזין טקסט.';
	if (empty($validation))
	{
		if ($row)
			$stmt = $mysqli->prepare('UPDATE adopt SET dogdescription=?, catdescription=?, image=?, imageLink=?');
		else
			$stmt = $mysqli->prepare('INSERT INTO adopt VALUES (?, ?)');
		$stmt->bind_param('ss', $_POST['dogdescription'], $_POST['catdescription']);
		$stmt->execute();
		$stmt->close();
		$result = $mysqli->query('SELECT * FROM adopt');
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
<form action="/private/updateadopt.php" method="post">
<fieldset>
<h3>עדכון תוכן לדפי אימוץ</h3>
<textarea class="tinymce" name="dogdescription" placeholder="טקסט לפינת אימוץ כלבים"><?php echo (empty($validation) ? ($row ? $row['dogdescription'] : '') : $_POST['dogdescription']); ?></textarea>
<textarea class="tinymce" name="catdescription" placeholder="טקסט לפינת אימוץ חתולים"><?php echo (empty($validation) ? ($row ? $row['catdescription'] : '') : $_POST['catdescription']); ?></textarea>
<input type="submit" value="עדכן תוכן">
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
		echo 'swal("עדכנת תוכן", "התוכן עודכן בהצלחה.", "success");';
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