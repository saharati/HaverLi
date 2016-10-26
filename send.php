<?php
if (!isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] < 0)
{
	header('Location: /notfound');
	exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$result = $mysqli->query('SELECT name FROM album WHERE id=' . $_GET['page']);
$row = $result->fetch_assoc();
$result->free();
if (!$row)
{
	header('Location: /notfound');
	exit;
}
if (isset($_POST['myemail'], $_POST['friendemail'], $_POST['content']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['myemail']))
		$validation['emailempty'] = 'ייש להזין אימייל אישי.';
	elseif (!filter_var($_POST['myemail'], FILTER_VALIDATE_EMAIL))
		$validation ['emailwrong'] = 'האימייל האישי לא תקין.';
	if (empty($_POST['friendemail']))
		$validation['emailempty'] = 'ייש להזין אימייל של חבר.';
	elseif (!filter_var($_POST['friendemail'], FILTER_VALIDATE_EMAIL))
		$validation ['emailwrong'] = 'האימייל של חבר לא תקין.';
	if (empty($_POST['content']))
		$validation['contentempty'] = 'יש להזין תוכן להודעה.';
	if (empty($validation))
	{
		$mailtext = 'שלום רב,
ישנו חבר שחושב שחיית מחמד זו עשוייה להתאים עבורך והוא שלח לך את הפרטים הבאים:
קישור לבעל החיים: http://imutz.org/pet-' . $_GET['page'] . '
תוכן ההודעה: ' . $_POST ['content'] . '

לצפייה בפרטים המלאים של בעל החיים יש להיכנס לקישור המצורף לעיל.';
		if (!sendMail(initMailer(), 'חבר חושב שאימוץ חיית מחמד זו עשוייה להתאים עבורך', $mailtext, $_POST['myemail'], '', $_POST['friendemail'], ''))
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
<div id="content" class="contact fullwidth">
<div id="contentInner">
<form action="/send-<?php echo $_GET['page']; ?>" method="post">
<fieldset class="inline-form">
<h3>ספר לחבר על <?php echo $row['name']; ?></h3>
<div class="clearfix">
<div class="small"><input type="email" name="myemail" required placeholder="אימייל אישי" title="אנא מלא את האימייל שלך" <?php if (!empty($validation)) echo 'value="' . $_POST['myemail'] . '"'; ?>></div>
<div class="small nopadding"><input type="email" name="friendemail" required placeholder="אימייל של חבר" title="אנא מלא את האימייל של חבר" <?php if (!empty($validation)) echo 'value="' . $_POST['friendemail'] . '"'; ?>></div>
</div>
<textarea name="content" required placeholder="תוכן ההודעה" title="אנא הזן את תוכן ההודעה"><?php if (!empty($validation)) echo $_POST['content']; ?></textarea>
<input type="submit" value="שליחה">
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
		echo 'swal("שלחת הודעה", "ההודעה נשלחה בהצלחה.", "success");';
	else
	{
		echo 'swal("השליחה נכשלה", "';
		foreach ($validation as $p)
			echo $p . '\n';
		echo '", "error");';
	}
	echo '</script>';
}
?>
</body>
</html>