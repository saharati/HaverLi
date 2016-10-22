<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['order'], $_POST['name'], $_POST['value']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['order']) || !is_numeric($_POST['order']) || $_POST['order'] < 1 || $_POST['order'] > 99)
		$validation['orderEmpty'] = 'המיקום חייב להיות מספר בין 1 ל-99.';
	else
	{
		$result = $mysqli->query('SELECT name FROM contact WHERE viewOrder=' . $_POST['order']);
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
			$validation['orderEmpty'] = 'המיקום שהוזן כבר קיים, יש לבחור מיקום אחר.';
	}
	if (empty($_POST['name']))
		$validation['nameempty'] = 'יש להזין שם כלשהו.';
	elseif (mb_strlen($_POST['name']) > 45)
		$validation['namelong'] = 'השם לא יכול להכיל יותר מ-45 תווים.';
	if (empty($_POST['value']))
		$validation['valueempty'] = 'יש להזין ערך כלשהו.';
	elseif (mb_strlen($_POST['value']) > 45)
		$validation['valuelong'] = 'הערך לא יכול להכיל יותר מ-45 תווים.';
	if (empty($validation))
	{
		$stmt = $mysqli->prepare('INSERT INTO contact VALUES (?, ?, ?)');
		$stmt->bind_param('iss', $_POST['order'], $_POST['name'], $_POST['value']);
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
<form action="/private/addcontact.php" method="post">
<fieldset>
<h3>הוספת דרכים ליצירת קשר</h3>
<input type="number" name="order" min="1" max="99" placeholder="מיקום (בין 1 ל-99)" <?php if (!empty($validation)) echo 'value="' . $_POST['order'] . '"'; ?>>
<input type="text" name="name" required maxlength="45" placeholder="שם" title="הזן שם כלשהו" <?php if (!empty($validation)) echo 'value="' . $_POST['name'] . '"'; ?>>
<input type="text" name="value" required maxlength="45" placeholder="ערך" title="הזן ערך כלשהו" <?php if (!empty($validation)) echo 'value="' . $_POST['value'] . '"'; ?>>
<input type="submit" value="הוסף דרך התקשרות">
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
		echo 'swal("הוספת דרך התקשרות", "דרך התקשרות נוסף בהצלחה.", "success");';
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