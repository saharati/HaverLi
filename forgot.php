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
<head>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<script src="/js/sweetalert.min.js"></script>
</head>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<div id="content-wrap">
<div class="center-block">
<form action="" method="post">
<fieldset>
<legend><i class="fa fa-info-circle" aria-hidden="true"></i> שחזור סיסמה</legend>
<p>
אם איבדתם את סיסמתכם אנא מלאו את הטופס הבא.<br>
הודעת אימייל תשלח אל הכתובת שציינתם עם הוראות לשחזור הסיסמה.
</p>
<div>
<label for="email">כתובת דוא"ל</label>
<input type="email" id="email" name="email" required>
<button class="button"><i class="fa fa-info-circle" aria-hidden="true"></i> שחזר סיסמה</button>
</div>
</fieldset>
</form>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
<script>
swal.setDefaults({confirmButtonText:'אישור'});
<?php
if (isset($validation))
{
	if (empty($validation))
		echo 'swal("שחזור סיסמה", "אנא בדקו את האימייל שלהם להוראות שחזור.", "success");';
	else
	{
		echo 'swal("השחזור נכשל", "';
		foreach ($validation as $p)
			echo $p . '\n';
		echo '", "error");';
	}
}
?>
</script>
</body>
</html>