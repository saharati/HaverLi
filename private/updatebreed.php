<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT * FROM pet_breed WHERE id=' . $_GET['id']);
$row2 = $result->fetch_assoc();
$result->free();
if (!$row2)
{
	header('Location: /private');
	exit;
}
if (isset($_POST['isDog'], $_POST['name']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if ($_POST['isDog'] != 1 && $_POST['isDog'] != 0)
		$validation['dogempty'] = 'יש לבחור את סוג הגזע.';
	if (empty($_POST['name']))
		$validation['nameempty'] = 'יש להזין שם כלשהו.';
	elseif (mb_strlen($_POST['name']) > 40)
		$validation['namelong'] = 'השם לא יכול להכיל יותר מ-40 תווים.';
	if (empty($validation))
	{
		$stmt = $mysqli->prepare('UPDATE pet_breed SET isDog=?, name=? WHERE id=?');
		$stmt->bind_param('isi', $_POST['isDog'], $_POST['name'], $_GET['id']);
		$stmt->execute();
		$stmt->close();
		$result = $mysqli->query('SELECT * FROM pet_breed WHERE id=' . $_GET['id']);
		$row2 = $result->fetch_assoc();
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
<form action="/private/updatebreed.php?id=<?php echo $_GET['id']; ?>" method="post">
<fieldset>
<h3>עדכון גזעים</h3>
<select name="isDog" title="יש לבחור סוג מהרשימה" required>
<option value="">בחר סוג</option>
<option value="1" <?php echo (empty($validation) ? ($row2['isDog'] == 1 ? 'selected' : '') : ($_POST['isDog'] == 1 ? 'selected' : '')); ?>>כלב</option>
<option value="0" <?php echo (empty($validation) ? ($row2['isDog'] == 0 ? 'selected' : '') : ($_POST['isDog'] == 0 ? 'selected' : '')); ?>>חתול</option>
</select>
<input type="text" name="name" placeholder="שם" required maxlength="40" value="<?php echo (empty($validation) ? $row2['name'] : $_POST['name']); ?>">
<input type="submit" value="עדכן גזע">
</fieldset>
</form>
<p><a title="חזרה לעדכון גזעים" href="/private/updatebreeds.php">חזרה לעדכון גזעים</a></p>
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
		echo 'swal("עדכנת גזע", "הגזע עודכן בהצלחה.", "success");';
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