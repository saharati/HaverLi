<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['order'], $_POST['link'], $_FILES['image']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['order']) || !is_numeric($_POST['order']) || $_POST['order'] < 1 || $_POST['order'] > 99)
		$validation['orderEmpty'] = 'המיקום חייב להיות מספר בין 1 ל-99.';
	else
	{
		$result = $mysqli->query('SELECT imageOrder FROM board WHERE imageOrder=' . $_POST['order']);
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
			$validation['orderEmpty'] = 'המיקום שהוזן כבר קיים, יש לבחור מיקום אחר.';
	}
	if (!empty($_POST['link']) && !filter_var($_POST['link'], FILTER_VALIDATE_URL))
		$validation['linkwrong'] = 'הקישור שהוזן לא תקין.';
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
		$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/board/' . $image_name;
		move_uploaded_file($_FILES['image']['tmp_name'], $new_name);
		$stmt = $mysqli->prepare('INSERT INTO board VALUES (?, ?, ?)');
		$stmt->bind_param('iss', $_POST['order'], $image_name, $_POST['link']);
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
<form action="/private/addboard.php" method="post" enctype="multipart/form-data">
<fieldset>
<h3>הוספת תמונות ללוח מודעות</h3>
<input type="number" name="order" min="1" max="99" placeholder="מיקום (בין 1 ל-99)" <?php if (!empty($validation)) echo 'value="' . $_POST['order'] . '"'; ?>>
<input type="file" name="image" accept="image/*" required placeholder="בחר תמונה" title="חובה לבחור תמונה">
<input type="url" name="link" placeholder="קישור (אם יש)" <?php if (!empty($validation)) echo 'value="' . $_POST['link'] . '"'; ?>>
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