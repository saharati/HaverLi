<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
if (isset($_GET['logout'], $_SESSION['logged_in'], $_SESSION['signature']) && $_SESSION['logged_in'] && sanitize($_GET['logout']) === $_SESSION['signature'])
{
	session_destroy();
	session_unset();
	$_SESSION['logged_in'] = false;
}
elseif (isset($_GET['code']) && ctype_alnum($_GET['code']))
{
	$_GET['code'] = sanitize($_GET['code']);
	if (!empty($_GET['code']))
	{
		$result = $mysqli->query('SELECT id, username FROM user WHERE activateCode="' . $mysqli->real_escape_string($_GET['code']) . '"');
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
		{
			$activatecode = '';
			$base = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
			while (strlen($activatecode) < 10)
				$activatecode .= $base{mt_rand(0, 51)};
			$salt = bin2hex(mcrypt_create_iv(32, MCRYPT_RAND));
			$mysqli->query('UPDATE user SET activateCode="", password="' . $salt . hash('sha256', $salt . $activatecode) . '" WHERE id=' . $row['id']);
			$mailtext = 'ברכות, סיסמתכם שוחזרה בהצלחה!
סיסמתכם החדשה הינה: ' . $activatecode . '
אנא זכרו סיסמה זו או שנו אותה בפעם הבאה בה תכנסו למשתמש.
לעמוד הכניסה למשתמשים כנסו: http://v2.imutz.org/login

בברכה,
עמותת חבר לי.';
			sendMail(initMailer(), 'בקשה לשחזור סיסמה', $mailtext, 'support@imutz.org', 'עמותת חבר לי', $row['username'], '');
			$sent = true;
		}
	}
}
if (isset($_POST['username'], $_POST['password']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['username']))
		$validation['usernameempty'] = 'אנא הזן כתובת דוא\"ל.';
	if (empty($_POST['password']))
		$validation['passwordempty'] = 'אנא הזן את סיסמתך.';
	if (empty($validation))
	{
		$_SERVER['REMOTE_ADDR'] = $mysqli->real_escape_string(sanitize($_SERVER['REMOTE_ADDR']));
		$result = $mysqli->query('SELECT fails, UNIX_TIMESTAMP(timeSince) timeSince FROM ipcheck WHERE ip="' . $_SERVER['REMOTE_ADDR'] . '"');
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
		{
			if ((time() - $row['timeSince']) > 43200)
				$mysqli->query('DELETE FROM ipcheck WHERE ip="' . $_SERVER['REMOTE_ADDR'] . '"');
			elseif ($row['fails'] > 4)
				$validation['blocked'] = 'נחסמת מכיוון שניסית להיכנס למערכת מספר פעמים עם מידע שגוי.';
		}
		if (empty($validation))
		{
			$result = $mysqli->query('SELECT id, password FROM user WHERE username="' . $mysqli->real_escape_string($_POST['username']) . '"');
			$row = $result->fetch_assoc();
			$result->free();
			if ($row)
			{
				$salt = substr($row['password'], 0, 64);
				$correcthash = substr($row['password'], 64, 64);
				$userhash = hash('sha256', $salt . $_POST['password']);
			}
			if (!$row || $userhash != $correcthash)
			{
				$validation['incorrectdetails'] = 'כתובת הדוא\"ל או הסיסמה שהזנת שגויים.';
				$mysqli->query('INSERT INTO ipcheck (ip, fails) VALUES ("' . $_SERVER['REMOTE_ADDR'] . '", 1) ON DUPLICATE KEY UPDATE fails=fails+1');
			}
			if (empty($validation))
			{
				$mysqli->query('DELETE FROM ipcheck WHERE ip="' . $_SERVER['REMOTE_ADDR'] . '"');
				$characters = '0123456789abcdef';
				$salt = '';
				for ($i = 0;$i < 15;$i++)
					$salt .= $characters[mt_rand(0, 15)];
				$userhash = sha1($salt . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
				session_regenerate_id();
				$_SESSION['signature'] = $salt . $userhash;
				$_SESSION['logged_in'] = true;
				$_SESSION['LAST_ACTIVITY'] = time();
				$_SESSION['userId'] = $row['id'];
				header('Location: /private');
				exit;
			}
		}
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
<form action="/login" method="post">
<fieldset>
<h3>כניסה למערכת</h3>
<p>לשחזור סיסמה <a title="שחזור סיסמה" href="/forgot">לחץ כאן</a></p>
<input type="text" name="username" required placeholder="שם משתמש">
<input type="password" name="password" required placeholder="סיסמה">
<input type="submit" value="התחברות">
</fieldset>
</form>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
<?php
if (isset($sent) || isset($validation))
{
	echo '<script>';
	if (isset($sent))
		echo 'swal("שחזור סיסמה", "סיסמה חדשה נשלחה אליכם באימייל.", "success");';
	else
	{
		echo 'swal("החיבור נכשל", "';
		foreach ($validation as $p)
			echo $p . '\n';
		echo '", "error");';
	}
	echo '</script>';
}
?>
</body>
</html>