<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT * FROM volunteer WHERE imageOrder=' . $_GET['id']);
$row2 = $result->fetch_assoc();
$result->free();
if (!$row2)
{
	header('Location: /private');
	exit;
}
if (isset($_POST['order'], $_POST['name'], $_POST['text'], $_FILES['image']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	$_POST['text'] = htmlspecialchars_decode($_POST['text'], ENT_QUOTES);
	if (empty($_POST['order']) || !is_numeric($_POST['order']) || $_POST['order'] < 1 || $_POST['order'] > 99)
		$validation['orderEmpty'] = 'המיקום חייב להיות מספר בין 1 ל-99.';
	elseif ($_POST['order'] != $_GET['id'])
	{
		$result = $mysqli->query('SELECT imageOrder FROM volunteer WHERE imageOrder=' . $_POST['order']);
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
			$validation['orderEmpty'] = 'המיקום שהוזן כבר קיים, יש לבחור מיקום אחר.';
	}
	if (empty($_POST['name']))
		$validation['nameempty'] = 'יש להזין את שם המתנדב.';
	elseif (mb_strlen($_POST['name']) > 45)
		$validation['namelong'] = 'שם המתנדב לא יכול להכיל יותר מ-45 תווים.';
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
			unlink($_SERVER['DOCUMENT_ROOT'] . '/images/volunteers/' . $row2['image']);
			$image_name = mt_rand(1, time()) . time() . '.' . $file_type;
			$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/volunteers/' . $image_name;
			move_uploaded_file($_FILES['image']['tmp_name'], $new_name);
		}
		$stmt = $mysqli->prepare('UPDATE volunteer SET imageOrder=?, image=?, imageName=?, imageCaption=? WHERE imageOrder=?');
		$stmt->bind_param('isssi', $_POST['order'], $image_name, $_POST['name'], $_POST['text'], $_GET['id']);
		$stmt->execute();
		$stmt->close();
		$_GET['id'] = $_POST['order'];
		$result = $mysqli->query('SELECT * FROM volunteer WHERE imageOrder=' . $_GET['id']);
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
<form action="/private/updatevolunteer.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
<fieldset>
<h3>עדכון מתנדבים</h3>
<input type="number" name="order" min="1" max="99" placeholder="מיקום (בין 1 ל-99)" value="<?php echo (empty($validation) ? $row2['imageOrder'] : $_POST['order']); ?>">
<input type="text" name="name" placeholder="שם המתנדב" required maxlength="45" value="<?php echo (empty($validation) ? $row2['imageName'] : $_POST['name']); ?>">
<?php
if (strpos($row2['image'], '.svg') !== false)
	$width = $height = 10000;
else
	list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/volunteers/' . $row2['image']);
echo '<a href="/images/volunteers/' . $row2['image'] . '" class="imageModal" title="הצג תמונה נוכחית" data-width="' . $width . '" data-height="' . $height . '">הצג תמונה נוכחית</a>';
?>
<input type="file" name="image" accept="image/*">
<textarea class="tinymce" name="text" placeholder="טקסט חופשי למתנדב"><?php echo (empty($validation) ? $row2['imageCaption'] : $_POST['text']); ?></textarea>
<input type="submit" value="עדכן תמונה">
</fieldset>
</form>
<p><a title="חזרה לעדכון מתנדבים" href="/private/updatevolunteers.php">חזרה לעדכון מתנדבים</a></p>
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
		echo 'swal("עדכנת מתנדב", "המתנדב עודכן בהצלחה.", "success");';
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