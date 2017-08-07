<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT pageHebrew, title, description, image, url FROM promote WHERE page="' . $mysqli->real_escape_string($_GET['page']) . '"');
$row2 = $result->fetch_assoc();
$result->free();
if (!$row2)
{
	header('Location: /private');
	exit;
}
if (isset($_POST['title'], $_POST['description'], $_POST['url'], $_FILES['image']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (mb_strlen($_POST['title']) > 45)
		$validation['namelong'] = 'הכותרת לא יכולה להכיל יותר מ-45 תווים.';
	if (mb_strlen($_POST['url']) > 255)
		$validation['urllong'] = 'הקישור לא יכול להכיל יותר מ-255 תווים.';
	if (!empty($_FILES['image']['name']))
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
		if (empty($_FILES['image']['name']))
			$image_name = $row2['image'];
		else
		{
			if (!empty($row2['image']))
				unlink($_SERVER['DOCUMENT_ROOT'] . '/images/og/' . $row2['image']);
			$image_name = mt_rand(1, time()) . time() . '.' . $file_type;
			$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/og/' . $image_name;
			move_uploaded_file($_FILES['image']['tmp_name'], $new_name);
		}
		$stmt = $mysqli->prepare('UPDATE promote SET title=?, description=?, image=?, url=? WHERE page=?');
		$stmt->bind_param('sssss', $_POST['title'], $_POST['description'], $image_name, $_POST['url'], $_GET['page']);
		$stmt->execute();
		$stmt->close();
		$result = $mysqli->query('SELECT pageHebrew, title, description, image, url FROM promote WHERE page="' . $mysqli->real_escape_string($_GET['page']) . '"');
		$row2 = $result->fetch_assoc();
		$result->free();
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
<form action="/private/promotepage.php?page=<?php echo $_GET['page']; ?>" method="post" enctype="multipart/form-data">
<fieldset>
<h3>קידום הדף "<?php echo $row2['pageHebrew']; ?>"</h3>
<input type="text" name="title" placeholder="כותרת" maxlength="45" value="<?php echo (empty($validation) ? $row2['title'] : $_POST['title']); ?>">
<?php
if (!empty($row2['image']))
{
	if (strpos($row2['image'], '.svg') !== false)
		$width = $height = 10000;
	else
		list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/og/' . $row2['image']);
	echo '<a href="/images/og/' . $row2['image'] . '" class="imageModal" title="הצג תמונה נוכחית" data-width="' . $width . '" data-height="' . $height . '">הצג תמונה נוכחית</a>';
}
?>
<input type="file" name="image" accept="image/*">
<input type="text" name="url" placeholder="קישור לדף" maxlength="255" value="<?php echo (empty($validation) ? $row2['url'] : $_POST['url']); ?>">
<textarea name="description" placeholder="תיאור הדף"><?php echo (empty($validation) ? $row2['description'] : $_POST['description']); ?></textarea>
<input type="submit" value="עדכן מידע">
</fieldset>
</form>
<p><a title="חזרה לקידום האתר" href="/private/promote.php">חזרה לקידום האתר</a></p>
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
		echo 'swal("עדכנת תוכן", "התוכן עודכן בהצלחה.", "success");';
	else
	{
		echo 'swal("העדכון נכשל", "';
		foreach ($validation as $p)
			echo $p . '\n';
		echo '", "error");';
	}
	echo '</script>';
}
?>
</body>
</html>