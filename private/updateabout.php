<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT * FROM about');
$row = $result->fetch_assoc();
$result->free();
if (isset($_POST['aboutdescription'], $_POST['volunteersdescription'], $_POST['link'], $_FILES['image']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	$_POST['aboutdescription'] = htmlspecialchars_decode($_POST['aboutdescription'], ENT_QUOTES);
	$_POST['volunteersdescription'] = htmlspecialchars_decode($_POST['volunteersdescription'], ENT_QUOTES);
	if (empty($_POST['aboutdescription']))
		$validation['descriptionempty'] = 'חובה להזין טקסט.';
	elseif (empty($_POST['volunteersdescription']))
		$validation['descriptionempty'] = 'חובה להזין טקסט.';
	if (!$row && empty($_FILES['image']['name']))
		$validation['imageempty'] = 'חובה להוסיף תמונה.';
	elseif (!empty($_FILES['image']['name']))
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
			$image_name = $row['image'];
		else
		{
			if ($row)
				unlink($_SERVER['DOCUMENT_ROOT'] . '/images/pages/' . $row['image']);
			$image_name = mt_rand(1, time()) . time() . '.' . $file_type;
			$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/pages/' . $image_name;
			move_uploaded_file($_FILES['image']['tmp_name'], $new_name);
		}
		if ($row)
			$stmt = $mysqli->prepare('UPDATE about SET aboutdescription=?, volunteersdescription=?, image=?, imageLink=?');
		else
			$stmt = $mysqli->prepare('INSERT INTO about VALUES (?, ?, ?, ?)');
		$stmt->bind_param('ssss', $_POST['aboutdescription'], $_POST['volunteersdescription'], $image_name, $_POST['link']);
		$stmt->execute();
		$stmt->close();
		$result = $mysqli->query('SELECT * FROM about');
		$row = $result->fetch_assoc();
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
<form action="/private/updateabout.php" method="post" enctype="multipart/form-data">
<fieldset>
<h3>עדכון תוכן לדף אודות</h3>
<?php
if ($row)
{
	if (strpos($row['image'], '.svg') !== false)
		$width = $height = 10000;
	else
		list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/pages/' . $row['image']);
	echo '<a href="/images/pages/' . $row['image'] . '" class="imageModal" title="הצג תמונה נוכחית" data-width="' . $width . '" data-height="' . $height . '">הצג תמונה נוכחית</a>
<input type="file" name="image" accept="image/*">';
}
else
{
	echo 'הוסף תמונה חדשה
<input type="file" name="image" accept="image/*" required title="בחר תמונה">';
}
?>
<input type="url" name="link" placeholder="קישור (אם יש)" value="<?php echo (empty($validation) ? ($row ? $row['imageLink'] : '') : $_POST['link']); ?>">
<textarea class="tinymce" name="aboutdescription" placeholder="טקסט לחלק העליון של דף האודות"><?php echo (empty($validation) ? ($row ? $row['aboutdescription'] : '') : $_POST['aboutdescription']); ?></textarea>
<textarea class="tinymce" name="volunteersdescription" placeholder="טקסט למתנדבים"><?php echo (empty($validation) ? ($row ? $row['volunteersdescription'] : '') : $_POST['volunteersdescription']); ?></textarea>
<input type="submit" value="עדכן תוכן">
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