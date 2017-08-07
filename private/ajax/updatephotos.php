<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (isset($_POST['id'], $_POST['value']) && is_numeric($_POST['id']) && $_POST['id'] > 0 && ($_POST['value'] == 0 || $_POST['value'] == 1 || $_POST['value'] == 2))
	$mysqli->query('UPDATE album_photo SET cover=' . $_POST['value'] . ' WHERE id=' . $_POST['id']);
?>