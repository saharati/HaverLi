<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
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
		$stmt = $mysqli->prepare('INSERT INTO album (name, description, isDog, isMale, size, breedId, bornDate) VALUES (?, ?, ?, ?, ?, ?, ?)');
		$stmt->bind_param('ssiiiis', $_POST['name'], $_POST['description'], $_POST['isDog'], $_POST['isMale'], $_POST['size'], $_POST['breedId'], $_POST['bornDate']);
		$stmt->execute();
		$_SESSION['albumId'] = $mysqli->insert_id;
		$stmt->close();
		mkdir($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $_SESSION['albumId']);
		header('Location: /private/addphotos.php');
		exit;
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
<form action="/private/addalbums.php" method="post">
<fieldset>
<h3>הוספת אלבומים</h3>
<input type="text" name="name" placeholder="שם" required maxlength="40" <?php if (!empty($validation)) echo 'value="' . $_POST['name'] . '"'; ?>>
<textarea class="tinymce" name="description" placeholder="תאור"><?php if (!empty($validation)) echo $_POST['description']; ?></textarea>
<select name="isDog" title="יש לבחור פריט מהרשימה" required onchange="showBreeds(this.value);">
<option value="">סוג</option>
<option value="1" <?php if (!empty($validation) && $_POST['isDog'] == 1) echo 'selected'; ?>>כלב</option>
<option value="0" <?php if (!empty($validation) && $_POST['isDog'] == 0) echo 'selected'; ?>>חתול</option>
</select>
<select name="isMale" title="יש לבחור פריט מהרשימה" required>
<option value="">מין</option>
<option value="1" <?php if (!empty($validation) && $_POST['isMale'] == 1) echo 'selected'; ?>>זכר</option>
<option value="0" <?php if (!empty($validation) && $_POST['isMale'] == 0) echo 'selected'; ?>>נקבה</option>
</select>
<select name="size">
<option value="0">גודל</option>
<option value="1" <?php if (!empty($validation) && $_POST['size'] == 1) echo 'selected'; ?>>קטן</option>
<option value="2" <?php if (!empty($validation) && $_POST['size'] == 2) echo 'selected'; ?>>בינוני</option>
<option value="3" <?php if (!empty($validation) && $_POST['size'] == 3) echo 'selected'; ?>>גדול</option>
</select>
<select name="breedId">
<option value="0">גזע (מעורב)</option>
</select>
תאריך לידה
<input type="date" name="bornDate" title="יש להזין תאריך לידה" max="<?php echo date('Y-m-d'); ?>" required <?php if (!empty($validation)) echo 'value="' . $_POST['bornDate'] . '"'; ?>>
<input type="submit" value="הוסף אלבום">
</fieldset>
</form>
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
	echo 'swal("ההוספה נכשלה", "';
	foreach ($validation as $p)
		echo $p . '\n';
	echo '", "error");';
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