<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['imageOrder'], $_POST['newImageOrder']) && is_numeric($_POST['imageOrder']) && $_POST['imageOrder'] > 0 && $_POST['imageOrder'] < 100)
{
	$_POST = sanitize($_POST);
	if ($_POST['imageOrder'] == $_POST['newImageOrder'])
		exit;
	if (empty($_POST['newImageOrder']))
		echo 'יש למלא ערך כלשהו בשדה זה.';
	elseif (!is_numeric($_POST['newImageOrder']))
		echo 'המספר שהוזן לא תקין.';
	elseif ($_POST['newImageOrder'] < 1)
		echo 'המספר חייב להיות גדול מ-0.';
	elseif ($_POST['newImageOrder'] > 99)
		echo 'המספר חייב להיות קטן מ-100.';
	else
	{
		$result = $mysqli->query('SELECT position FROM help WHERE position=' . $_POST['newImageOrder']);
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
			echo 'מספר זה כבר קיים.';
		else
			$mysqli->query('UPDATE help SET position=' . $_POST['newImageOrder'] . ' WHERE position=' . $_POST['imageOrder']);
	}
}
?>