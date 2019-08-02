<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
$result = $mysqli->query('SELECT * FROM donate_info WHERE position=' . $_GET['id']);
$row2 = $result->fetch_assoc();
$result->free();
if (!$row2)
{
    header('Location: /private');
    exit;
}
if (isset($_POST['position'], $_POST['caption']))
{
    $validation = array();
    $_POST = sanitize($_POST);
    $_POST['caption'] = htmlspecialchars_decode($_POST['caption'], ENT_QUOTES);
    if (empty($_POST['position']) || !is_numeric($_POST['position']) || $_POST['position'] < 1 || $_POST['position'] > 99)
        $validation['orderEmpty'] = 'המיקום חייב להיות מספר בין 1 ל-99.';
    elseif ($_POST['position'] != $_GET['id'])
    {
        $result = $mysqli->query('SELECT position FROM donate_info WHERE position=' . $_POST['position']);
        $row = $result->fetch_assoc();
        $result->free();
        if ($row)
            $validation['positionEmpty'] = 'המיקום שהוזן כבר קיים, יש לבחור מיקום אחר.';
    }
    if (empty($_POST['caption']))
        $validation['captionEmpty'] = 'יש להזין תוכן.';
    if (empty($validation))
    {
        $stmt = $mysqli->prepare('UPDATE donate_info SET position=?, caption=? WHERE position=?');
        $stmt->bind_param('isi', $_POST['position'], $_POST['caption'], $_GET['id']);
        $stmt->execute();
        $stmt->close();
        $_GET['id'] = $_POST['position'];
        $result = $mysqli->query('SELECT * FROM donate_info WHERE position=' . $_GET['id']);
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
<form action="/private/updatedonateinfo.php?id=<?php echo $_GET['id']; ?>" method="post">
<fieldset>
<h3>עדכון תוכן בתרומות</h3>
<input type="number" name="position" min="1" max="99" required placeholder="מיקום (בין 1 ל-99)" value="<?php echo (empty($validation) ? $row2['position'] : $_POST['position']); ?>">
<textarea class="tinymce" name="caption" placeholder="מלל"><?php echo (empty($validation) ? $row2['caption'] : $_POST['caption']); ?></textarea>
<input type="submit" value="עדכן תוכן">
</fieldset>
</form>
<p><a title="חזרה לעדכון תוכן" href="/private/updatedonateinfos.php">חזרה לעדכון תוכן</a></p>
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