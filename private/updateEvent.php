<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT hebrewTitle, englishTitle, russianTitle, hebrewDescription, englishDescription, russianDescription, image, eventDate, startHour, endHour FROM event WHERE id=' . $_GET['id']);
$row2 = $result->fetch_assoc();
$result->free();
if (!$row2)
{
	header('Location: /private');
	exit;
}
if (isset($_POST['hebrewTitle'], $_POST['englishTitle'], $_POST['russianTitle'], $_POST['hebrewDescription'], $_POST['englishDescription'], $_POST['russianDescription'], $_POST['eventDate'], $_POST['startHour'], $_POST['endHour'], $_FILES['image']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	if (empty($_POST['hebrewTitle']))
		$validation['hebrewTitleEmpty'] = 'יש למלא כותרת בעברית.';
	elseif (mb_strlen($_POST['hebrewTitle']) > 45)
		$validation['hebrewTitleLong'] = 'מספר התווים בכותרת בעברית לא יכול לעלות על 45.';
	if (!empty($_POST['englishTitle']) && mb_strlen($_POST['englishTitle']) > 45)
		$validation['englishTitleLong'] = 'מספר התווים בכותרת באנגלית לא יכול לעלות על 45.';
	if (!empty($_POST['russianTitle']) && mb_strlen($_POST['russianTitle']) > 45)
		$validation['russianTitleLong'] = 'מספר התווים בכותרת ברוסית לא יכול לעלות על 45.';
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
		{
			$_POST['englishDescription'] = str_replace('<br>', '', doTranslate($_POST['hebrewDescription'], 'en'));
			$_POST['englishDescription'] .= "\r\n\r\nTranslated by Bing.";
		}
		if (empty($_POST['russianDescription']))
		{
			$_POST['russianDescription'] = str_replace('<br>', '', doTranslate($_POST['hebrewDescription'], 'ru'));
			$_POST['russianDescription'] .= "\r\n\r\nпереведёт Бинг.";
		}
		if (empty($_FILES['image']['name']))
		{
			if (isset($_POST['delete']) && $row2['image'] != null)
			{
				unlink($_SERVER['DOCUMENT_ROOT'] . '/images/events/' . $row2['image']);
				$image_name = null;
			}
			else
				$image_name = $row2['image'];
		}
		else
		{
			if ($row2['image'] != null)
				unlink($_SERVER['DOCUMENT_ROOT'] . '/images/events/' . $row2['image']);
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
		$stmt = $mysqli->prepare('UPDATE event SET hebrewTitle=?, englishTitle=?, russianTitle=?, hebrewDescription=?, englishDescription=?, russianDescription=?, image=?, eventDate=?, startHour=?, endHour=? WHERE id=?');
		$stmt->bind_param('ssssssssssi', $_POST['hebrewTitle'], $_POST['englishTitle'], $_POST['russianTitle'], $_POST['hebrewDescription'], $_POST['englishDescription'], $_POST['russianDescription'], $image_name, $_POST['eventDate'], $_POST['startHour'], $_POST['endHour'], $_GET['id']);
		$stmt->execute();
		$stmt->close();
		$result = $mysqli->query('SELECT hebrewTitle, englishTitle, russianTitle, hebrewDescription, englishDescription, russianDescription, image, eventDate, startHour, endHour FROM event WHERE id=' . $_GET['id']);
		$row2 = $result->fetch_assoc();
		$result->free();
	}
}
$row2['hebrewDescription'] = str_replace('<br>', '', $row2['hebrewDescription']);
$row2['englishDescription'] = str_replace('<br>', '', $row2['englishDescription']);
$row2['russianDescription'] = str_replace('<br>', '', $row2['russianDescription']);
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
<form action="/private/updateEvent.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
<fieldset class="customized-form">
<legend><i class="fa fa-pencil-square" aria-hidden="true"></i> עריכת אירועים</legend>
<p>אם אתם מעוניינים לתרגם מחדש ערך מסויים, מחקו את הערך שאותו תרצו לתרגם לפני שליחת הטופס.</p>
<div>
<label for="hebrewTitle">כותרת בעברית</label>
<input type="text" id="hebrewTitle" name="hebrewTitle" required maxlength="45" value="<?php echo (empty($validation) ? $row2['hebrewTitle'] : $_POST['hebrewTitle']); ?>">
</div>
<div>
<label for="englishTitle">כותרת באנגלית</label>
<input type="text" id="englishTitle" name="englishTitle" maxlength="45" value="<?php echo (empty($validation) ? $row2['englishTitle'] : $_POST['englishTitle']); ?>">
</div>
<div>
<label for="russianTitle">כותרת ברוסית</label>
<input type="text" id="russianTitle" name="russianTitle" maxlength="45" value="<?php echo (empty($validation) ? $row2['russianTitle'] : $_POST['russianTitle']); ?>">
</div>
<div>
<label for="hebrewDescription">תאור בעברית</label>
<textarea id="hebrewDescription" name="hebrewDescription" required><?php echo (empty($validation) ? $row2['hebrewDescription'] : $_POST['hebrewDescription']); ?></textarea>
</div>
<div>
<label for="englishDescription">תאור באנגלית</label>
<textarea id="englishDescription" name="englishDescription"><?php echo (empty($validation) ? $row2['englishDescription'] : $_POST['englishDescription']); ?></textarea>
</div>
<div>
<label for="russianDescription">תאור ברוסית</label>
<textarea id="russianDescription" name="russianDescription"><?php echo (empty($validation) ? $row2['russianDescription'] : $_POST['russianDescription']); ?></textarea>
</div>
<div>
<label for="eventDate">תאריך האירוע</label>
<input type="date" id="eventDate" name="eventDate" value="<?php echo (empty($validation) ? $row2['eventDate'] : $_POST['eventDate']); ?>">
</div>
<div>
<label for="startHour">שעת התחלה</label>
<input type="time" id="startHour" name="startHour" value="<?php echo (empty($validation) ? $row2['startHour'] : $_POST['startHour']); ?>">
</div>
<div>
<label for="endHour">שעת סיום</label>
<input type="time" id="endHour" name="endHour" value="<?php echo (empty($validation) ? $row2['endHour'] : $_POST['endHour']); ?>">
</div>
<div>
<?php
if ($row2['image'] != null)
{
	list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/events/' . $row2['image']);
	echo '<label for="delete"><a href="/images/events/' . $row2['image'] . '" class="imageModal" title="הצג תמונה נוכחית" data-width="' . $width . '" data-height="' . $height . '">תמונה</a> <input type="checkbox" id="delete" name="delete"> סמן למחיקה</label>';
}
else
	echo '<label for="image">תמונה</label>';
?>
<input type="file" id="image" name="image" accept="image/*">
<button id="button" class="button"><i class="fa fa-pencil-square" aria-hidden="true"></i> עדכן איורע</button>
</div>
</fieldset>
</form>
<p><a href="/private/updateEvents.php" title="חזרה לעריכת אירועים"><i class="fa fa-undo" aria-hidden="true"></i> חזרה לעריכת אירועים</a></p>
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
		echo 'swal("עדכנת אירוע", "האירוע עודכן בהצלחה.", "success");';
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