<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
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
		$stmt = $mysqli->prepare('INSERT INTO pet_breed (isDog, name) VALUES (?, ?)');
		$stmt->bind_param('is', $_POST['isDog'], $_POST['name']);
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
<form action="/private/addbreeds.php" method="post">
<fieldset>
<h3>הוספת גזעים</h3>
<select name="isDog" title="יש לבחור סוג מהרשימה" required>
<option value="">בחר סוג</option>
<option value="1" <?php if (!empty($validation) && $_POST['isDog'] == 1) echo 'selected'; ?>>כלב</option>
<option value="0" <?php if (!empty($validation) && $_POST['isDog'] == 0) echo 'selected'; ?>>חתול</option>
</select>
<input type="text" name="name" required maxlength="40" placeholder="שם" title="הזן שם כלשהו" <?php if (!empty($validation)) echo 'value="' . $_POST['name'] . '"'; ?>>
<input type="submit" value="הוסף גזע">
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
		echo 'swal("הוספת גזע", "הגזע נוסף בהצלחה.", "success");';
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