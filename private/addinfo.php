<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['title'], $_POST['text'], $_FILES['image']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	$_POST['text'] = htmlspecialchars_decode($_POST['text'], ENT_QUOTES);
	if (empty($_POST['title']))
		$validation['titleempty'] = 'יש להזין כותרת.';
	elseif (mb_strlen($_POST['title']) > 45)
		$validation['titlelong'] = 'הכותרת לא יכולה להכיל יותר מ-45 תווים.';
	if (empty($_POST['text']))
		$validation['textempty'] = 'חובה להוסיף תוכן.';
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
		$result = $mysqli->query('SELECT MAX(position) position FROM info');
		$row = $result->fetch_assoc();
		$result->free();
		if ($row['position'] == 99)
			$validation['full'] = 'אין מקומות פנויים, נא לפנות.';
		else
		{
			$row['position']++;
			$image_name = mt_rand(1, time()) . time() . '.' . $file_type;
			$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/pages/' . $image_name;
			move_uploaded_file($_FILES['image']['tmp_name'], $new_name);
			$stmt = $mysqli->prepare('INSERT INTO info VALUES (?, ?, ?, ?)');
			$stmt->bind_param('isss', $row['position'], $_POST['title'], $_POST['text'], $image_name);
			$stmt->execute();
			$stmt->close();
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
<form action="/private/addinfo.php" method="post" enctype="multipart/form-data">
<fieldset>
<h3>הוספת תוכן למידע למאמץ</h3>
<input type="text" name="title" placeholder="כותרת" required maxlength="45" <?php if (!empty($validation)) echo 'value="' . $_POST['title'] . '"'; ?>>
<input type="file" name="image" accept="image/*" required title="בחר תמונה">
<textarea class="tinymce" name="text" placeholder="מלל"><?php if (!empty($validation)) echo $_POST['text']; ?></textarea>
<input type="submit" value="הוסף תוכן">
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
		echo 'swal("הוספת תוכן", "התוכן נוסף בהצלחה.", "success");';
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