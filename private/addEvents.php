<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['hebrewTitle'], $_POST['englishTitle'], $_POST['russianTitle'], $_POST['hebrewDescription'], $_POST['englishDescription'], $_POST['russianDescription'], $_POST['eventDate'], $_POST['startHour'], $_POST['endHour'], $_FILES['image']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['hebrewTitle']))
		$validation['hebrewTitleEmpty'] = 'יש למלא כותרת בעברית.';
	elseif (mb_strlen($_POST['hebrewTitle']) > 30)
		$validation['hebrewTitleLong'] = 'מספר התווים בכותרת בעברית לא יכול לעלות על 30.';
	if (!empty($_POST['englishTitle']) && mb_strlen($_POST['englishTitle']) > 30)
		$validation['englishTitleLong'] = 'מספר התווים בכותרת באנגלית לא יכול לעלות על 30.';
	if (!empty($_POST['russianTitle']) && mb_strlen($_POST['russianTitle']) > 30)
		$validation['russianTitleLong'] = 'מספר התווים בכותרת ברוסית לא יכול לעלות על 30.';
	if (empty($_POST['hebrewDescription']))
		$validation['hebrewDescriptionEmpty'] = 'יש למלא תאור לאירוע בעברית.';
	if (empty($_POST['eventDate']))
		$_POST['eventDate'] = null;
	else
	{
		$eventDate = explode('-', $_POST['eventDate']);
		if (count($eventDate) < 3 || !checkdate($eventDate[1], $eventDate[2], $eventDate[0]))
			$validation['eventDateWrong'] = 'הפורמט של תאריך האירוע שגוי.';
	}
	if (empty($_POST['startHour']))
		$_POST['startHour'] = null;
	else
	{
		$startHour = explode(':', $_POST['startHour']);
		if (count($startHour) < 2 || strlen($startHour[0]) < 2 || strlen($startHour[1]) < 2)
			$validation['startHourWrong'] = 'הפורמט של שעת ההתחלה שגוי.';
		elseif (!is_numeric($startHour[0]) || !is_numeric($startHour[1]))
			$validation['startHourWrong'] = 'הפורמט של שעת ההתחלה שגוי.';
		elseif ($startHour[0] < 0 || $startHour[0] > 23 || $startHour[1] < 0 || $startHour[1] > 59)
			$validation['startHourWrong'] = 'הפורמט של שעת ההתחלה שגוי.';
	}
	if (empty($_POST['endHour']))
		$_POST['endHour'] = null;
	else
	{
		$endHour = explode(':', $_POST['endHour']);
		if (count($endHour) < 2 || strlen($endHour[0]) < 2 || strlen($endHour[1]) < 2)
			$validation['endHourWrong'] = 'הפורמט של שעת הסיום שגוי.';
		elseif (!is_numeric($endHour[0]) || !is_numeric($endHour[1]))
			$validation['endHourWrong'] = 'הפורמט של שעת הסיום שגוי.';
		elseif ($endHour[0] < 0 || $endHour[0] > 23 || $endHour[1] < 0 || $endHour[1] > 59)
			$validation['endHourWrong'] = 'הפורמט של שעת הסיום שגוי.';
	}
	if (empty($validation) && !empty($_POST['startHour']) && !empty($_POST['endHour']) && strtotime($_POST['startHour']) > strtotime($_POST['endHour']))
		$validation['hourWrong'] = 'שעת סיום לא יכולה להיות מוקדם משעת ההתחלה.';
	if (!empty($_FILES['image']['name']))
	{
		$file_type = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
		if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg' && $file_type != 'gif')
			$validation['imageWrong'] = 'התמונה חייבת להיות אחת מהסוגים: JPG, JPEG, PNG, GIF.';
		elseif ($_FILES['image']['type'] != 'image/jpeg' && $_FILES['image']['type'] != 'image/pjpeg' && $_FILES['image']['type'] != 'image/png' && $_FILES['image']['type'] != 'image/gif')
			$validation['imageWrong'] = 'התמונה חייבת להיות אחת מהסוגים: JPG, JPEG, PNG, GIF.';
		else
		{
			$image_size = getimagesize($_FILES['image']['tmp_name']);
			if ($image_size === false)
				$validation['imageWrong'] = 'הקובץ חייב להיות תמונה.';
		}
	}
	if (empty($validation))
	{
		if (empty($_POST['englishTitle']))
			$_POST['englishTitle'] = doTranslate($_POST['hebrewTitle'], 'en');
		if (empty($_POST['russianTitle']))
			$_POST['russianTitle'] = doTranslate($_POST['hebrewTitle'], 'ru');
		if (empty($_POST['englishDescription']))
			$_POST['englishDescription'] = str_replace('<br>', '', doTranslate($_POST['hebrewDescription'], 'en'));
		if (empty($_POST['russianDescription']))
			$_POST['russianDescription'] = str_replace('<br>', '', doTranslate($_POST['hebrewDescription'], 'ru'));
		if (empty($_FILES['image']['name']))
			$image_name = null;
		else
		{
			$image_name = mt_rand(1, time()) . time() . '.' . $file_type;
			$new_name = $_SERVER['DOCUMENT_ROOT'] . '/images/events/' . $image_name;
			if ($file_type == 'jpg' || $file_type == 'jpeg')
			{
				$src = imagecreatefromjpeg($_FILES['image']['tmp_name']);
				imagejpeg($src, $new_name, 100);
			}
			elseif ($file_type == 'png')
			{
				$src = imagecreatefrompng($_FILES['image']['tmp_name']);
				imagepng($src, $new_name, 9);
			}
			else
			{
				$src = imagecreatefromgif($_FILES['image']['tmp_name']);
				imagegif($src, $new_name);
			}
			imagedestroy($src);
		}
		$_POST['hebrewDescription'] = nl2br($_POST['hebrewDescription'], false);
		$_POST['englishDescription'] = nl2br($_POST['englishDescription'], false);
		$_POST['russianDescription'] = nl2br($_POST['russianDescription'], false);
		$stmt = $mysqli->prepare('INSERT INTO event (hebrewTitle, englishTitle, russianTitle, hebrewDescription, englishDescription, russianDescription, image, eventDate, startHour, endHour) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		$stmt->bind_param('ssssssssss', $_POST['hebrewTitle'], $_POST['englishTitle'], $_POST['russianTitle'], $_POST['hebrewDescription'], $_POST['englishDescription'], $_POST['russianDescription'], $image_name, $_POST['eventDate'], $_POST['startHour'], $_POST['endHour']);
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
<div id="content-wrap">
<div class="center-block">
<form action="/private/addEvents.php" method="post" enctype="multipart/form-data">
<fieldset class="customized-form">
<legend><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> הוספת אירועים</legend>
<p>לא חובה למלא את השדות באנגלית ורוסית, במידה והם ריקים, הם יתורגמו אוטומטית.</p>
<div>
<label for="hebrewTitle">כותרת בעברית</label>
<input type="text" id="hebrewTitle" name="hebrewTitle" required maxlength="30" <?php if (!empty($validation)) echo 'value="' . $_POST['hebrewTitle'] . '"'; ?>>
</div>
<div>
<label for="englishTitle">כותרת באנגלית</label>
<input type="text" id="englishTitle" name="englishTitle" maxlength="30" <?php if (!empty($validation)) echo 'value="' . $_POST['englishTitle'] . '"'; ?>>
</div>
<div>
<label for="russianTitle">כותרת ברוסית</label>
<input type="text" id="russianTitle" name="russianTitle" maxlength="30" <?php if (!empty($validation)) echo 'value="' . $_POST['russianTitle'] . '"'; ?>>
</div>
<div>
<label for="hebrewDescription">תאור בעברית</label>
<textarea id="hebrewDescription" name="hebrewDescription" required><?php if (!empty($validation)) echo $_POST['hebrewDescription']; ?></textarea>
</div>
<div>
<label for="englishDescription">תאור באנגלית</label>
<textarea id="englishDescription" name="englishDescription"><?php if (!empty($validation)) echo $_POST['englishDescription']; ?></textarea>
</div>
<div>
<label for="russianDescription">תאור ברוסית</label>
<textarea id="russianDescription" name="russianDescription"><?php if (!empty($validation)) echo $_POST['russianDescription']; ?></textarea>
</div>
<div>
<label for="eventDate">תאריך האירוע</label>
<input type="date" id="eventDate" name="eventDate" <?php if (!empty($validation)) echo 'value="' . $_POST['eventDate'] . '"'; ?>>
</div>
<div>
<label for="startHour">שעת התחלה</label>
<input type="time" id="startHour" name="startHour" <?php if (!empty($validation)) echo 'value="' . $_POST['startHour'] . '"'; ?>>
</div>
<div>
<label for="endHour">שעת סיום</label>
<input type="time" id="endHour" name="endHour" <?php if (!empty($validation)) echo 'value="' . $_POST['endHour'] . '"'; ?>>
</div>
<div>
<label for="image">תמונה</label>
<input type="file" id="image" name="image" accept="image/*">
<button id="button" class="button"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> הוסף איורע</button>
</div>
</fieldset>
</form>
<p><a href="/private" title="חזרה לעמוד הניהול"><i class="fa fa-undo" aria-hidden="true"></i> חזרה לעמוד הניהול</a></p>
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
		echo 'swal("הוספת אירוע", "האירוע נוסף בהצלחה.", "success");';
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