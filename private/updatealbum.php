<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT * FROM album WHERE id=' . $_GET['id']);
$row2 = $result->fetch_assoc();
$result->free();
if (!$row2)
{
	header('Location: /private');
	exit;
}
if (isset($_POST['name'], $_POST['description'], $_POST['isDog'], $_POST['isMale'], $_POST['size'], $_POST['breedId'], $_POST['bornDate']))
{
	$validation = array();
	$_POST = sanitize($_POST);
	$_POST['description'] = htmlspecialchars_decode($_POST['description'], ENT_QUOTES);
	if (empty($_POST['name']))
		$validation['nameempty'] = 'יש להזין שם כלשהו.';
	elseif (mb_strlen($_POST['name']) > 40)
		$validation['namelong'] = 'השם לא יכול להכיל יותר מ-40 תווים.';
	if (empty($_POST['description']))
		$validation['descriptionempty'] = 'יש להזין תאור כלשהו.';
	if ($_POST['isDog'] != 1 && $_POST['isDog'] != 0)
		$validation['dogempty'] = 'יש לבחור את סוג בעל חיים.';
	if ($_POST['isMale'] != 1 && $_POST['isMale'] != 0)
		$validation['maleempty'] = 'יש לבחור את מין בעל חיים.';
	if ($_POST['size'] < 0 || $_POST['size'] > 3)
		$validation['sizewrong'] = 'הגודל שנבחר לא תקין.';
	if (!is_numeric($_POST['breedId']))
		$validation['breedwrong'] = 'הגזע שנבחר לא תקין.';
	if (empty($_POST['bornDate']))
		$validation['borndateempty'] = 'יש להזין תאריך לידה.';
	else
	{
		$bornDate = explode('-', $_POST['bornDate']);
		if (count($bornDate) < 3 || !checkdate($bornDate[1], $bornDate[2], $bornDate[0]))
			$validation['borndatewrong'] = 'הפורמט של תאריך הלידה שגוי.';
	}
	if (empty($validation))
	{
		$stmt = $mysqli->prepare('UPDATE album SET name=?, description=?, isDog=?, isMale=?, size=?, breedId=?, bornDate=? WHERE id=?');
		$stmt->bind_param('ssiiiisi', $_POST['name'], $_POST['description'], $_POST['isDog'], $_POST['isMale'], $_POST['size'], $_POST['breedId'], $_POST['bornDate'], $_GET['id']);
		$stmt->execute();
		$stmt->close();
		echo $mysqli->error;
		$result = $mysqli->query('SELECT * FROM album WHERE id=' . $_GET['id']);
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
<form action="/private/updatealbum.php?id=<?php echo $_GET['id']; ?>" method="post">
<fieldset>
<h3>עדכון אלבומים</h3>
<input type="text" name="name" placeholder="שם" required maxlength="40" value="<?php echo (empty($validation) ? $row2['name'] : $_POST['name']); ?>">
<textarea class="tinymce" name="description" placeholder="תאור"><?php echo (empty($validation) ? $row2['description'] : $_POST['description']); ?></textarea>
<select name="isDog" title="יש לבחור פריט מהרשימה" required onchange="showBreeds(this.value);">
<option value="">סוג</option>
<option value="1" <?php if ($row2['isDog'] == 1) echo 'selected'; ?>>כלב</option>
<option value="0" <?php if ($row2['isDog'] == 0) echo 'selected'; ?>>חתול</option>
</select>
<select name="isMale" title="יש לבחור פריט מהרשימה" required>
<option value="">מין</option>
<option value="1" <?php if ($row2['isMale'] == 1) echo 'selected'; ?>>זכר</option>
<option value="0" <?php if ($row2['isMale'] == 0) echo 'selected'; ?>>נקבה</option>
</select>
<select name="size">
<option value="0">גודל</option>
<option value="1" <?php if ($row2['size'] == 1) echo 'selected'; ?>>קטן</option>
<option value="2" <?php if ($row2['size'] == 2) echo 'selected'; ?>>בינוני</option>
<option value="3" <?php if ($row2['size'] == 3) echo 'selected'; ?>>גדול</option>
</select>
<select name="breedId">
<option value="0">גזע (מעורב)</option>
<?php
$result = $mysqli->query('SELECT id, name FROM pet_breed WHERE isDog=' . $row2['isDog']);
while ($row3 = $result->fetch_assoc())
	echo '<option value="' . $row3['id'] . '" ' . ($row2['breedId'] == $row3['id'] ? 'selected' : '') . '>' . $row3['name'] . '</option>';
$result->free();
?>
</select>
תאריך לידה
<input type="date" name="bornDate" title="יש להזין תאריך לידה" max="<?php echo date('Y-m-d'); ?>" required value="<?php echo (empty($validation) ? $row2['bornDate'] : $_POST['bornDate']); ?>">
<input type="submit" value="עדכן אלבום">
</fieldset>
</form>
<p><a title="עדכון תמונות לאלבום זה" href="/private/updatephotos.php?id=<?php echo $_GET['id']; ?>">עדכון תמונות לאלבום זה</a></p>
<br>
<p><a title="חזרה לעדכון אלבומים" href="/private/updatealbums.php">חזרה לעדכון אלבומים</a></p>
<p><a title="חזרה לעמוד הניהול" href="/private">חזרה לעמוד הניהול</a></p>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
<script>
<?php
if (isset($validation))
{
	if (empty($validation))
		echo 'swal("עדכנת אלבום", "האלבום עודכן בהצלחה.", "success");';
	else
	{
		echo 'swal("העדכון נכשל", "';
		foreach ($validation as $p)
			echo $p . '\n';
		echo '", "error");';
	}
}
$dogs = $cats = '<option value="0">גזע (מעורב)</option>';
$result = $mysqli->query('SELECT * FROM pet_breed ORDER BY name');
while ($row = $result->fetch_assoc())
{
	if ($row['isDog'] == 1)
		$dogs .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
	else
		$cats .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
$result->free();
echo 'var dogBreeds = \'' . $dogs . '\', catBreeds = \'' . $cats . '\';';
?>
function showBreeds(isDog)
{
	if (isDog == '1')
		document.getElementsByName('breedId')[0].innerHTML = dogBreeds;
	else if (isDog == '0')
		document.getElementsByName('breedId')[0].innerHTML = catBreeds;
	else
		document.getElementsByName('breedId')[0].innerHTML = '<option value="0">גזע (מעורב)</option>';
}
</script>
</body>
</html>