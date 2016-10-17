<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
if (isset($_POST['email']))
{
	$validation = array();
	$_POST['email'] = sanitize($_POST['email']);
	if (empty($_POST['email']))
		$validation['emailempty'] = 'לא הזנתם כתובת דוא\"ל.';
	elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		$validation['emailwrong'] = 'כתובת הדוא\"ל שהזנתם שגוי.';
	else
	{
		$result = $mysqli->query('SELECT id FROM user WHERE username="' . $mysqli->real_escape_string($_POST['email']) . '"');
		$row = $result->fetch_assoc();
		$result->free();
		if (!$row)
			$validation['emailwrong'] = 'כתובת הדוא\"ל שהזנתם שגוי.';
	}
	if (empty($validation))
	{
		$base = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
		do
		{
			$activatecode = '';
			while (strlen($activatecode) < 20)
				$activatecode .= $base{mt_rand(0, 51)};
			$result = $mysqli->query('SELECT activateCode FROM user WHERE activateCode="' . $activatecode . '"');
			$row2 = $result->fetch_assoc();
			$result->free();
		} while ($row2);
		$mysqli->query('UPDATE user SET activateCode="' . $activatecode . '" WHERE id=' . $row['id']);
		$mailtext = 'שלום רב,
לאחרונה שלחתם טופס לשחזור סיסמתכם באתר פט פאל, אם אינכם ביצעתם בקשה זו אנא התעלמו ממנה.
ע"מ לשחזר את סיסמתכם אנא בקרו בעמוד הבא: http://v2.imutz.org/login?code=' . $activatecode . '
לאחר מכן תקבלו הודעת דוא"ל נוספת עם סיסמתכם החדשה.

בברכה,
עמותת חבר לי.';
		if (!sendMail(initMailer(), 'בקשה לשחזור סיסמה', $mailtext, 'support@imutz.org', 'עמותת חבר לי', $_POST['email'], ''))
			$validation['mailproblem'] = 'התרחשה תקלה בעט שליחת ההודעה, אנא נסו שוב במועד מאוחר יותר.';
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
<form action="/forgot" method="post">
<fieldset>
<h3>שחזור סיסמה</h3>
<input type="email" name="email" required placeholder="אימייל">
<input type="submit" value="שלח">
</fieldset>
</form>
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
		echo 'swal("שחזור סיסמה", "אנא בדקו את האימייל שלכם להוראות שחזור.", "success");';
	else
	{
		echo 'swal("השחזור נכשל", "';
		foreach ($validation as $p)
			echo $p . '\n';
		echo '", "error");';
	}
	echo '</script>';
}
?>
</body>
</html>