<?php
if (!isset($mysqli))
	exit;
if (isset($_POST['name'], $_POST['phone'], $_POST['city'], $_POST['email'], $_POST['content']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['name']))
		$validation['nameempty'] = 'יש להזין שם.';
	if (empty($_POST['phone']))
		$validation['phoneempty'] = 'יש להזין מספר טלפון.';
	if (empty($_POST['city']))
		$validation['phoneempty'] = 'יש להזין עיר מגורים.';
	if (empty($_POST['email']))
		$validation['emailempty'] = 'יש להזין אימייל.';
	elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		$validation['emailwrong'] = 'האימייל שהוזן לא תקין.';
	if (empty($_POST['content']))
		$validation['contentempty'] = 'יש להזין תוכן להודעה.';
	if (empty($validation))
	{
		$mailtext = 'התקבלה הודעה חדשה מאתר חבר לי, להלן פרטי ההודעה:
שם השולח: ' . $_POST['name'] . '
מספר טלפון: ' . $_POST['phone'] . '
עיר מגורים: ' . $_POST['city'] . '
אימייל: ' . $_POST['email'] . '
תוכן: ' . $_POST['content'];
		if (!sendMail(initMailer(), 'הודעה חדשה מאתר חבר לי', $mailtext, $_POST['email'], $_POST['name'], 'support@imutz.org', 'עמותת חבר לי'))
			$validation['mailproblem'] = 'התרחשה תקלה בעט שליחת ההודעה, אנא נסו שוב במועד מאוחר יותר.';
	}
}
?>
<form action="<?php echo strlen($_SERVER['QUERY_STRING']) ? basename($_SERVER['PHP_SELF']) . '?' . $_SERVER['QUERY_STRING'] : basename($_SERVER['PHP_SELF']); ?>" method="post">
<fieldset class="inline-form">
<h3>צור קשר</h3>
<div class="clearfix">
<div class="small"><input type="text" name="name" required placeholder="שם" title="אנא מלא את שמך" <?php if (!empty($validation)) echo 'value="' . $_POST['name'] . '"'; ?>></div>
<div class="small"><input type="tel" name="phone" required placeholder="טלפון" title="אנא מלא מספר טלפון" <?php if (!empty($validation)) echo 'value="' . $_POST['phone'] . '"'; ?>></div>
<div class="small"><input type="text" name="city" required placeholder="עיר" title="אנא מלא את עיר מגורך" <?php if (!empty($validation)) echo 'value="' . $_POST['city'] . '"'; ?>></div>
<div class="small nopadding"><input type="email" name="email" required placeholder="אימייל" title="אנא הזן אימייל" <?php if (!empty($validation)) echo 'value="' . $_POST['email'] . '"'; ?>></div>
</div>
<textarea name="content" required placeholder="תוכן" title="אנא הזן את תוכן ההודעה"><?php if (!empty($validation)) echo $_POST['content']; ?></textarea>
<input type="submit" value="שליחה">
</fieldset>
</form>
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