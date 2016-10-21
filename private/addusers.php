<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['username'], $_POST['password']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['username']))
		$validation['usernameempty'] = 'יש להזין שם משתמש.';
	elseif (strlen($_POST['username']) > 50)
		$validation['usernamelong'] = 'שם המשתמש לא יכול להכיל יותר מ-50 תווים.';
	elseif (!filter_var($_POST['username'], FILTER_VALIDATE_EMAIL))
		$validation['usernamewrong'] = 'שם משתמש חייב להיות אימייל.';
	if (empty($_POST['password']))
		$validation['passwordempty'] = 'יש להזין סיסמה.';
	if (empty($validation))
	{
		$salt = bin2hex(mcrypt_create_iv(32, MCRYPT_RAND));
		$_POST['password'] = $salt . hash('sha256', $salt . $_POST['password']);
		$stmt = $mysqli->prepare('INSERT INTO user (username, password) VALUES (?, ?)');
		$stmt->bind_param('ss', $_POST['username'], $_POST['password']);
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
<form action="/private/addusers.php" method="post">
<fieldset>
<h3>הוספת משתמשים</h3>
<input type="email" name="username" placeholder="כתובת אימייל" required maxlength="50" title="חובה למלא שדה זה." <?php if (!empty($validation)) echo 'value="' . $_POST['username'] . '"'; ?>>
<input type="password" name="password" placeholder="סיסמה" pattern=".{6,}" required title="חייב להכיל לפחות 6 תווים.">
<input type="submit" value="הוסף משתמש">
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
		echo 'swal("הוספת משתמש", "המשתמש נוסף בהצלחה.", "success");';
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