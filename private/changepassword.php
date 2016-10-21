<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['newpassword'], $_POST['repassword']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['newpassword']))
		$validation['passempty'] = 'יש להזין את הסיסמה החדשה.';
	elseif (strlen($_POST['newpassword']) < 6)
		$validation['passwrong'] = 'הסיסמה החדשה חייבת להיות בת לפחות 6 תווים.';
	elseif (empty($_POST['repassword']) || $_POST['newpassword'] != $_POST['repassword'])
		$validation['passnotmatch'] = 'הסיסמאות אינן תואמות.';
	if (empty($validation))
	{
		$salt = bin2hex(mcrypt_create_iv(32, MCRYPT_RAND));
		$mysqli->query('UPDATE user SET password="' . $salt . hash('sha256', $salt . $_POST['newpassword']) . '" WHERE id=' . $_SESSION['userId']);
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
<form action="/private/changepassword.php" method="post">
<fieldset>
<h3>שינוי סיסמה</h3>
<div>
<input type="password" name="newpassword" placeholder="סיסמה חדשה" pattern=".{6,}" onchange="form.repassword.pattern=this.value;" required title="חייב להכיל לפחות 6 תווים.">
<input type="password" name="repassword" placeholder="חזור על סיסמה" required title="אנא חזור על הסיסמה שהזנת.">
<input type="submit" value="שנה סיסמה">
</div>
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
		echo 'swal("שינוי סיסמה", "סיסמתכם שונתה בהצלחה.", "success");';
	else
	{
		echo 'swal("השינוי נכשל", "';
		foreach ($validation as $p)
			echo $p . '\n';
		echo '", "error");';
	}
	echo '</script>';
}
?>
</body>
</html>