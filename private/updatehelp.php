<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT * FROM help');
$row = $result->fetch_assoc();
$result->free();
if (isset($_POST['donateText'], $_POST['adoptText'], $_POST['volunteerText'], $_POST['fosterText'], $_FILES['image']))
{
	$validation = array();
	if (empty($_POST['donateText']))
		$validation['donatetextempty'] = 'חובה להזין טקסט לתרומות.';
	if (empty($_POST['adoptText']))
		$validation['adopttextempty'] = 'חובה להזין טקסט לאימוץ.';
	if (empty($_POST['volunteerText']))
		$validation['volunteertextempty'] = 'חובה להזין טקסט למתנדבים.';
	if (empty($_POST['fosterText']))
		$validation['fostertextempty'] = 'חובה להזין טקסט לאומנות.';
	$file_types = array();
	for ($i = 0;$i < 4;$i++)
	{
		if (!$row && empty($_FILES['image']['name'][$i]))
			$validation['imageempty'] = 'חובה להוסיף את כל התמונות.';
		elseif (!empty($_FILES['image']['name'][$i]))
		{
			$file_type = strtolower(pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION));
			if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg' && $file_type != 'gif' && $file_type != 'svg')
				$validation['imagewrong'] = 'התמונה חייבת להיות אחת מהסוגים: JPG, JPEG, PNG, GIF, SVG.';
			elseif ($_FILES['image']['type'][$i] != 'image/jpeg' && $_FILES['image']['type'][$i] != 'image/pjpeg' && $_FILES['image']['type'][$i] != 'image/png' && $_FILES['image']['type'][$i] != 'image/gif' && $_FILES['image']['type'][$i] != 'image/svg+xml')
				$validation['imagewrong'] = 'התמונה חייבת להיות אחת מהסוגים: JPG, JPEG, PNG, GIF, SVG.';
			else
			{
				$image_size = getimagesize($_FILES['image']['tmp_name'][$i]);
				if ($image_size === false && $file_type != 'svg')
					$validation['imagewrong'] = 'הקובץ חייב להיות תמונה.';
				else
					$file_types[$_FILES['image']['name'][$i]] = $file_type;
			}
		}
	}
	if (empty($validation))
	{
		$image_names = array();
		for ($i = 0;$i < 4;$i++)
		{
			if (empty($_FILES['image']['name'][$i]))
				$image_names[] = $row['image' . $i];
			else
			{
				if ($row)
					unlink($_SERVER['DOCUMENT_ROOT'] . '/images/pages/' . $row['image' . $i]);
				$image_names[] = mt_rand(1, time()) . time() . '.' . $file_types[$_FILES['image']['name'][$i]];
				$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/pages/' . $image_names[$i];
				move_uploaded_file($_FILES['image']['tmp_name'][$i], $new_name);
			}
		}
		if ($row)
			$stmt = $mysqli->prepare('UPDATE help SET donateText=?, adoptText=?, volunteerText=?, fosterText=?, image1=?, image2=?, image3=?, image4=?');
		else
			$stmt = $mysqli->prepare('INSERT INTO help VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
		$stmt->bind_param('ssssssss', $_POST['donateText'], $_POST['adoptText'], $_POST['volunteerText'], $_POST['fosterText'], $image_names[0], $image_names[1], $image_names[2], $image_names[3]);
		$stmt->execute();
		$stmt->close();
		$result = $mysqli->query('SELECT * FROM help');
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
<form action="/private/updatehelp.php" method="post" enctype="multipart/form-data">
<fieldset>
<h3>עדכון תוכן לאיך ניתן לעזור</h3>
<textarea class="tinymce" name="donateText" placeholder="טקסט לתרומות"><?php echo (empty($validation) ? ($row ? $row['donateText'] : '') : $_POST['donateText']); ?></textarea>
<textarea class="tinymce" name="adoptText" placeholder="טקסט לאימוץ וירטואלי"><?php echo (empty($validation) ? ($row ? $row['adoptText'] : '') : $_POST['adoptText']); ?></textarea>
<textarea class="tinymce" name="volunteerText" placeholder="טקסט למתנדבים"><?php echo (empty($validation) ? ($row ? $row['volunteerText'] : '') : $_POST['volunteerText']); ?></textarea>
<textarea class="tinymce" name="fosterText" placeholder="טקסט לאומנות"><?php echo (empty($validation) ? ($row ? $row['fosterText'] : '') : $_POST['fosterText']); ?></textarea>
<?php
if ($row)
{
	for ($i = 1;$i <= 4;$i++)
	{
		switch ($i)
		{
			case 1:
				$text = 'ראשונה';
				break;
			case 2:
				$text = 'שנייה';
				break;
			case 3:
				$text = 'שלישית';
				break;
			case 4:
				$text = 'רביעית';
				break;
		}
		if (strpos($row['image' . $i], '.svg') !== false)
			$width = $height = 10000;
		else
			list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/pages/' . $row['image' . $i]);
		echo '<a href="/images/pages/' . $row['image' . $i] . '" class="imageModal" title="הצג תמונה נוכחית" data-width="' . $width . '" data-height="' . $height . '">הצג תמונה ' . $text . '</a>
<input type="file" name="image[]" accept="image/*">';
	}
}
else
{
	echo 'הוסף תמונה ראשונה
<input type="file" name="image[]" accept="image/*" required title="בחר תמונה ראשונה">
הוסף תמונה שנייה
<input type="file" name="image[]" accept="image/*" required title="בחר תמונה שנייה">
הוסף תמונה שלישית
<input type="file" name="image[]" accept="image/*" required title="בחר תמונה שלישית">
הוסף תמונה רביעית
<input type="file" name="image[]" accept="image/*" required title="בחר תמונה רביעית">';
}
?>
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