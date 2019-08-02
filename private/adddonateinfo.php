<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['text']))
{
    $validation = array();
    $_POST = sanitize($_POST);
    $_POST['text'] = htmlspecialchars_decode($_POST['text'], ENT_QUOTES);
    if (empty($_POST['text']))
        $validation['textempty'] = 'חובה להוסיף תוכן.';
    if (empty($validation))
    {
        $result = $mysqli->query('SELECT MAX(position) position FROM donate_info');
        $row = $result->fetch_assoc();
        $result->free();
        if ($row['position'] == 99)
            $validation['full'] = 'אין מקומות פנויים, נא לפנות.';
        else
        {
            $row['position']++;
            $stmt = $mysqli->prepare('INSERT INTO donate_info VALUES (?, ?)');
            $stmt->bind_param('is', $row['position'], $_POST['text']);
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
<form action="/private/adddonateinfo.php" method="post">
<fieldset>
<h3>הוספת תוכן לתרומות</h3>
<textarea class="tinymce" name="text" placeholder="מלל"><?php if (!empty($validation)) echo $_POST['text']; ?></textarea>
<input type="submit" value="הוסף תוכן">
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
		echo 'swal("הוספת תוכן", "התוכן נוסף בהצלחה.", "success");';
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