<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['imageName'], $_FILES['image']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['imageName']))
		$validation['nameempty'] = 'יש להזין שם לתמונה.';
	elseif (strlen($_POST['imageName']) > 45)
		$validation['imagelong'] = 'מספר התווים בשם התמונה לא יכול לעלות על 45.';
	else
	{
		$result = $mysqli->query('SELECT imageName FROM image WHERE imageName="' . $mysqli->real_escape_string($_POST['imageName']) . '"');
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
			$validation['imageexist'] = 'השם שנבחר כבר קיים, אנא רשמו שם אחר.';
	}
	if (empty($_FILES['image']['name']))
		$validation['imageempty'] = 'חובה להוסיף תמונה.';
	else
	{
		$file_type = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
		if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg' && $file_type != 'gif' && $file_type != 'svg')
			$validation['imagewrong'] = 'התמונה חייבת להיות אחת מהסוגים: JPG, JPEG, PNG, GIF, SVG.';
		elseif ($_FILES['image']['type'] != 'image/jpeg' && $_FILES['image']['type'] != 'image/pjpeg' && $_FILES['image']['type'] != 'image/png' && $_FILES['image']['type'] != 'image/gif' && $_FILES['image']['type'] != 'image/svg+xml')
			$validation['imagewrong'] = 'התמונה חייבת להיות אחת מהסוגים: JPG, JPEG, PNG, GIF, SVG.';
		else
		{
			$image_size = getimagesize($_FILES['image']['tmp_name']);
			if ($image_size === false && $file_type != 'svg')
				$validation['imagewrong'] = 'הקובץ חייב להיות תמונה.';
		}
	}
	if (empty($validation))
	{
		$image_name = mt_rand(1, time()) . time() . '.' . $file_type;
		$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/data/' . $image_name;
		move_uploaded_file($_FILES['image']['tmp_name'], $new_name);
		$stmt = $mysqli->prepare('INSERT INTO image VALUES (?, ?)');
		$stmt->bind_param('ss', $_POST['imageName'], $image_name);
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
<form action="/private/adddatabase.php" method="post" enctype="multipart/form-data">
<fieldset>
<h3>הוספת תמונות למאגר</h3>
<input type="text" name="imageName" maxlength="45" placeholder="שם התמונה" <?php if (!empty($validation)) echo 'value="' . $_POST['imageName'] . '"'; ?>>
<input type="file" name="image" accept="image/*" required placeholder="בחר תמונה" title="חובה לבחור תמונה">
<input type="submit" value="הוסף תמונה">
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
		echo 'swal("הוספת תמונה", "התמונה נוסף בהצלחה.", "success");';
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