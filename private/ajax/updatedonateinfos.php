<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['position'], $_POST['newPosition']) && is_numeric($_POST['position']) && $_POST['position'] > 0 && $_POST['position'] < 100)
{
	$_POST = sanitize($_POST);
	if ($_POST['position'] == $_POST['newPosition'])
		exit;
	if (empty($_POST['newPosition']))
		echo 'יש למלא ערך כלשהו בשדה זה.';
	elseif (!is_numeric($_POST['newPosition']))
		echo 'המספר שהוזן לא תקין.';
	elseif ($_POST['newPosition'] < 1)
		echo 'המספר חייב להיות גדול מ-0.';
	elseif ($_POST['newPosition'] > 99)
		echo 'המספר חייב להיות קטן מ-100.';
	else
	{
		$result = $mysqli->query('SELECT position FROM donate_info WHERE position=' . $_POST['newPosition']);
		$row = $result->fetch_assoc();
		$result->free();
		if ($row)
			echo 'מספר זה כבר קיים.';
		else
			$mysqli->query('UPDATE donate_info SET position=' . $_POST['newPosition'] . ' WHERE position=' . $_POST['position']);
	}
}
?>