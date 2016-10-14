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
<div id="content-wrap">
<div class="center-block">
<form action="/private/change.php" method="post">
<fieldset>
<legend><i class="fa fa-key" aria-hidden="true"></i> שינוי סיסמה</legend>
<div>
<label for="newpassword">סיסמה חדשה</label>
<input type="password" id="newpassword" name="newpassword" pattern=".{6,}" onchange="form.repassword.pattern=this.value;" required title="חייב להכיל לפחות 6 תווים.">
<label for="repassword">אימות סיסמה</label>
<input type="password" id="repassword" name="repassword" required title="אנא חזור על הסיסמה שהזנת.">
<button class="button"><i class="fa fa-key" aria-hidden="true"></i> שנה סיסמה</button>
</div>
</fieldset>
</form>
<p><a href="/private" title="חזרה לעמוד הניהול"><i class="fa fa-undo" aria-hidden="true"></i> חזרה לעמוד הניהול</a></p>
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