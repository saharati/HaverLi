<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT * FROM help_image WHERE imageOrder=' . $_GET['id']);
$row2 = $result->fetch_assoc();
$result->free();
if (!$row2)
{
	header('Location: /private');
	exit;
}
if (isset($_POST['order'], $_FILES['image'], $_FILES['image2']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['order']) || !is_numeric($_POST['order']) || $_POST['order'] < 1 || $_POST['order'] > 99)
		$validation['orderEmpty'] = 'המיקום חייב להיות מספר בין 1 ל-99.';
	elseif ($_POST['order'] != $_GET['id'])
	{
		$result = $mysqli->query('SELECT imageOrder FROM help_image WHERE imageOrder=' . $_POST['order']);
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
			$validation['orderEmpty'] = 'המיקום שהוזן כבר קיים, יש לבחור מיקום אחר.';
	}
	if (!empty($_FILES['image']['name']))
	{
		$file_type = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
		if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg' && $file_type != 'gif' && $file_type != 'svg')
			$validation['imagewrong'] = 'התמונה הראשית חייבת להיות אחת מהסוגים: JPG, JPEG, PNG, GIF, SVG.';
		elseif ($_FILES['image']['type'] != 'image/jpeg' && $_FILES['image']['type'] != 'image/pjpeg' && $_FILES['image']['type'] != 'image/png' && $_FILES['image']['type'] != 'image/gif' && $_FILES['image']['type'] != 'image/svg+xml')
			$validation['imagewrong'] = 'התמונה הראשית חייבת להיות אחת מהסוגים: JPG, JPEG, PNG, GIF, SVG.';
		else
		{
			$image_size = getimagesize($_FILES['image']['tmp_name']);
			if ($image_size === false && $file_type != 'svg')
				$validation['imagewrong'] = 'הקובץ הראשי חייב להיות תמונה.';
		}
	}
	if (!empty($_FILES['image2']['name']))
	{
		$file_type2 = strtolower(pathinfo($_FILES['image2']['name'], PATHINFO_EXTENSION));
		if ($file_type2 != 'jpg' && $file_type2 != 'png' && $file_type2 != 'jpeg' && $file_type2 != 'gif' && $file_type2 != 'svg')
			$validation['imagewrong2'] = 'התמונה הראשית חייבת להיות אחת מהסוגים: JPG, JPEG, PNG, GIF, SVG.';
		elseif ($_FILES['image2']['type'] != 'image/jpeg' && $_FILES['image2']['type'] != 'image/pjpeg' && $_FILES['image2']['type'] != 'image/png' && $_FILES['image2']['type'] != 'image/gif' && $_FILES['image2']['type'] != 'image/svg+xml')
			$validation['imagewrong2'] = 'התמונה הראשית חייבת להיות אחת מהסוגים: JPG, JPEG, PNG, GIF, SVG.';
		else
		{
			$image_size = getimagesize($_FILES['image2']['tmp_name']);
			if ($image_size === false && $file_type2 != 'svg')
				$validation['imagewrong2'] = 'הקובץ הראשי חייב להיות תמונה.';
		}
	}
	if (empty($validation))
	{
		if (empty($_FILES['image']['name']))
			$image_name = $row2['image'];
		else
		{
			unlink($_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $row2['image']);
			$image_name = mt_rand(1, time()) . time() . '.' . $file_type;
			$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $image_name;
			move_uploaded_file($_FILES['image']['tmp_name'], $new_name);
		}
		if (empty($_FILES['image2']['name']))
			$image_name2 = $row2['image2'];
		else
		{
			unlink($_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $row2['image2']);
			$image_name2 = mt_rand(1, time()) . time() . '.' . $file_type2;
			$new_name2 = $_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $image_name2;
			move_uploaded_file($_FILES['image2']['tmp_name'], $new_name2);
		}
		$stmt = $mysqli->prepare('UPDATE help_image SET imageOrder=?, image=?, image2=? WHERE imageOrder=?');
		$stmt->bind_param('issi', $_POST['order'], $image_name, $image_name2, $_GET['id']);
		$stmt->execute();
		$stmt->close();
		$_GET['id'] = $_POST['order'];
		$result = $mysqli->query('SELECT * FROM help_image WHERE imageOrder=' . $_GET['id']);
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
<form action="/private/updatehelpimage.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
<fieldset>
<h3>עדכון תמונה באיך ניתן לעזור</h3>
<input type="number" name="order" min="1" max="99" placeholder="מיקום (בין 1 ל-99)" value="<?php echo (empty($validation) ? $row2['imageOrder'] : $_POST['order']); ?>">
<?php
if (strpos($row2['image'], '.svg') !== false)
	$width = $height = 10000;
else
	list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $row2['image']);
echo '<a href="/images/help/' . $row2['image'] . '" class="imageModal" title="הצג תמונה נוכחית" data-width="' . $width . '" data-height="' . $height . '">הצג תמונה ראשית</a>';
?>
<input type="file" name="image" accept="image/*">
<?php
if (strpos($row2['image2'], '.svg') !== false)
	$width = $height = 10000;
else
	list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $row2['image2']);
echo '<a href="/images/help/' . $row2['image2'] . '" class="imageModal" title="הצג תמונה נוכחית" data-width="' . $width . '" data-height="' . $height . '">הצג תמונה משנית</a>';
?>
<input type="file" name="image2" accept="image/*">
<input type="submit" value="עדכן תמונה">
</fieldset>
</form>
<p><a title="חזרה לעדכון תמונות" href="/private/updatehelpimages.php">חזרה לעדכון תמונות</a></p>
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
		echo 'swal("עדכנת תמונה", "התמונה עודכנה בהצלחה.", "success");';
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