<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['text'], $_FILES['image']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	$_POST['text'] = htmlspecialchars_decode($_POST['text'], ENT_QUOTES);
	if (empty($_POST['text']))
		$validation['textempty'] = 'יש להזין טקסט.';
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
		$result = $mysqli->query('SELECT MAX(imageOrder) imageOrder FROM family');
		$row = $result->fetch_assoc();
		$result->free();
		if ($row['imageOrder'] == 99)
			$validation['full'] = 'אין מקומות פנויים, נא לפנות.';
		else
		{
			$row['imageOrder']++;
			$image_name = mt_rand(1, time()) . time() . '.' . $file_type;
			$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/families/' . $image_name;
			move_uploaded_file($_FILES['image']['tmp_name'], $new_name);
			$stmt = $mysqli->prepare('INSERT INTO family VALUES (?, ?, ?)');
			$stmt->bind_param('iss', $row['imageOrder'], $image_name, $_POST['text']);
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
<form action="/private/addfamilies.php" method="post" enctype="multipart/form-data">
<fieldset>
<h3>הוספת משפחות מאושרות</h3>
<input type="file" name="image" accept="image/*" required title="בחר תמונה">
<textarea class="tinymce" name="text" placeholder="טקסט נילווה לתמונה"><?php if (!empty($validation)) echo $_POST['text']; ?></textarea>
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