<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_FILES['image'], $_FILES['image2']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_FILES['image']['name']))
		$validation['imageempty'] = 'חובה להוסיף תמונה ראשית.';
	else
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
	if (empty($_FILES['image2']['name']))
		$validation['imageempty2'] = 'חובה להוסיף תמונה משנית.';
	else
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
		$result = $mysqli->query('SELECT MAX(imageOrder) imageOrder FROM help_image');
		$row = $result->fetch_assoc();
		$result->free();
		if ($row['imageOrder'] == 99)
			$validation['full'] = 'אין מקומות פנויים, נא לפנות.';
		else
		{
			$row['imageOrder']++;
			$image_name = mt_rand(1, time()) . time() . '.' . $file_type;
			$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $image_name;
			move_uploaded_file($_FILES['image']['tmp_name'], $new_name);
			$image_name2 = mt_rand(1, time()) . time() . '.' . $file_type2;
			$new_name2 = $_SERVER['DOCUMENT_ROOT'] . '/images/help/' . $image_name2;
			move_uploaded_file($_FILES['image2']['tmp_name'], $new_name2);
			$stmt = $mysqli->prepare('INSERT INTO help_image VALUES (?, ?, ?)');
			$stmt->bind_param('iss', $row['imageOrder'], $image_name, $image_name2);
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
<form action="/private/addhelpimages.php" method="post" enctype="multipart/form-data">
<fieldset>
<h3>הוספת תמונות לתרומות</h3>
תמונה ראשית
<input type="file" name="image" accept="image/*" required title="בחר תמונה ראשית">
תמונה משנית
<input type="file" name="image2" accept="image/*" required title="בחר תמונה משנית">
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